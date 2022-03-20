<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Mail\NotifyBooker;
use App\Models\MusicStyle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\BandMessageRequest;

class BandMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
//    public function index() {}

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $musicStyles = collect(MusicStyle::all()->keyBy('id')->map->name)->prepend('Bitte wählen', null);
        return view('public.form.bands', compact('musicStyles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BandMessageRequest $request
     * @return Response
     */
    public function store(BandMessageRequest $request)
    {
        $validated      = $request->validated();
        $message		= Message::create($validated);
        $musicStyleId   = $validated['music_style_id'];

        if('prod' === env('APP_ENV')) {
            $users = User::whereHas('musicStyles', function ($query) use ($musicStyleId) {
                $query->where('music_style.id', $musicStyleId);
            })->pluck('email');
        } else {
            $users = ['engels@goldenacker.de'];
        }
        /*
                $users = User::whereHas('musicStyles', function ($query) use ($musicStyleId) {
                    $query->where('music_style.id', $musicStyleId);
                })->pluck('email');
        */
        $notifyBooker = new NotifyBooker($message);

        try {
            Mail::to($users)->send($notifyBooker);
            $content = $notifyBooker->render();
        } catch (Exception $e ) {
            $content = 'Error: Mail konnte nicht versand werden!<br>'. $e->getMessage();
        }

        return view('public.contact', compact('content'));    }

    /**
     * Display the specified resource.
     *
     * @param Message $message
     * @return Response
     */
//    public function show(Message $message) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param Message $message
     * @return Response
     */
//    public function edit(Message $message) {}

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Message $message
     * @return Response
     */
//    public function update(Request $request, Message $message) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param Message $message
     * @return Response
     */
//    public function destroy(Message $message) {}
}
