<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Mail\NotifyBooker;
use App\Models\MusicStyle;
use App\Rules\ReCaptcha;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\BandMessageRequest;
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
        $musicStyles = collect(MusicStyle::all()->keyBy('id')->map->name)->prepend('Bitte wählen', null);
        return view('public.form.bands', compact('musicStyles', 'errors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BandMessageRequest $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'music_style_id'    => 'required',
            'name'              => 'required|max:50',
            'email'             => 'required|email',
            'msg'               => 'required',
//            'g-recaptcha-response' => ['required', new ReCaptcha()]
            'g-recaptcha-response' => 'recaptcha',
        ], [
            'music_style_id.required'   => 'Bitte eine Musik-Richtung angeben!',
            'name.required'         => 'Bitte einen Name angeben!',
            'name.max'              => 'Der Name darf max. 50 Zeichen enthalten!',
            'email.required'        => 'Bitte eine Email Adresse angeben!',
            'email.email'           => 'Das ist keine korrekte Email-Adresse!.',
            'msg.required'      => 'Bitte ein Nachricht eingeben!',
//            'g-recaptcha-response.required'      => 'Bitte den Captcha-Feld bedienen!',
//            'g-recaptcha-response.captcha'       => 'Der Captcha-Text ":input" stimmt nicht!',
        ]);

        if($validator->fails()) {
            return $this->create($validator->errors()->getMessages());
        }

        $message		= Message::create($request->all());
        $musicStyleId   = $request->post('music_style_id');

        if('prod' === config('app.env')) {
            $users = User::whereHas('musicStyles', function ($query) use ($musicStyleId) {
                $query->where('music_style.id', $musicStyleId);
            })->pluck('email');
        } else {
            $users = ['engels@goldenacker.de'];
        }

        $content = 'Anfrage wurde erfolgreich gespeichert!<br>';

        if($users && count($users) > 0) {
            $notifyBooker = new NotifyBooker($message);
            try {
                Mail::to($users)->send($notifyBooker);
                $content = $notifyBooker->render();
            } catch (Exception $e ) {
                $content = 'Error: Mail konnte nicht versand werden!<br>'. $e->getMessage();
            }
        }

        return view('public.contact', compact('content'));    }
}
