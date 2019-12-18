<?php

namespace App\Http\Controllers\Api\SPA;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Generators\BandContactForm;
use App\Http\Requests\BandContactRequest;
use App\Mail\NotifyBooker;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Mail;

class SpaContactController extends Controller
{
    public function fields() {
        $form = new BandContactForm();
        $data = $form->data();
        return response()->json($data);
    }

    public function send( BandContactRequest $request )
    {
        $validated      = $request->validated();
        $data			= collect($validated)->only(['music_style_id','email', 'name', 'message'])->toArray();
        $message		= Message::create($data);
        $musicStyleId	= $data['music_style_id'];

        if('prod' === env('APP_ENV')) {
            $users = User::whereHas('musicStyles', function ($query) use ($musicStyleId) {
                $query->where('music_style.id', $musicStyleId);
            })->pluck('email');
        } else {
            $users = ['engels@goldenacker.de'];
        }
        $notifyBooker = new NotifyBooker($message);

        try {
            Mail::to($users)->send($notifyBooker);
            $content = $notifyBooker->render();
            $response = [
                'recipients'    => $users,
                'data'          => $data,
                'content'       => $content,
                'success'       => true,
                'error'         => null,
            ];
        } catch (Exception $e ) {
            $content = 'Error: Mail konnte nicht versand werden!<br>'. $e->getMessage();
            $response = [
                'recipients'    => $users,
                'data'          => $data,
                'content'       => $content,
                'success'       => false,
                'error'         => $e->getMessage(),
            ];
        }
        return response()->json($response);
    }

}
