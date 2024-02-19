<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Mail\NotifyBooker;
use App\Models\MusicStyle;
use App\Rules\ReCaptcha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\BandMessageRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BandMessageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($errors = null)
    {
        $musicStyles = collect(MusicStyle::orderBy('name')->get()->keyBy('id')->map->name)->prepend('Bitte wählen', null);
        return view('public.form.bands', compact('musicStyles', 'errors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BandMessageRequest $request
     * @return Response
     */
    public function store(Request $httpRequest, BandMessageRequest $request)
    {
		$validated = $request->validated();
		$now = Carbon::now(config('app.timezone'));
		$diff = $now->subSeconds(60)->format('Y-m-d H:i:s');
		$musicStyleId   = $validated['music_style_id'];

		$users = User::whereHas('musicStyles', function ($query) use ($musicStyleId) {
			$query->where('music_style.id', $musicStyleId);
		})->pluck('email')->unique()->toArray();

		$message = Message::select()
			->whereRaw('created_at >= ?', [$diff])
			->whereName($validated['name'])
			->whereEmail($validated['email'])
//			->whereMsg($validated['msg'])
			->get()
		;

		if($message->count() > 0) {
			$notifyBooker = new NotifyBooker($message->first());
		} else {
			$message = Message::create($validated);
			$notifyBooker = new NotifyBooker($message);
		}

		$content = $notifyBooker->render();

		if($users && count($users) > 0 && !session()->get('stored')) {
			try {
				Mail::to($users)->send($notifyBooker);
			} catch (Exception $e ) {
				$content = 'Error: Mail konnte nicht versand werden!<br>'. $e->getMessage();
			}
		}

		session()->flash('stored', 1);

		return view('public.contact', compact('content'));
	}
}
