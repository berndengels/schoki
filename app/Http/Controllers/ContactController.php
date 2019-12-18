<?php

namespace App\Http\Controllers;

use App\Forms\NewsletterSubscribeForm;
use App\Mail\NotifyBooker;
use App\Models\Address;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Forms\NewsletterForm;
use App\Forms\BandsForm;
use Illuminate\Support\Facades\Mail;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ContactController extends BaseController
{
    use FormBuilderTrait, DispatchesJobs, ValidatesRequests;

    public function formBands( FormBuilder $formBuilder ) {
        $form   = $formBuilder->create(BandsForm::class);
		return view('public.form.bands', compact('form'));
    }

    public function sendBands( Request $request )
    {
        $this->validate(request(), [
            'g-recaptcha-response' => 'required|captcha',
        ],
		[
			'captcha.required' => 'Bitte das Captcha bedienen.'
		]);
		$data			= $request->only(['music_style_id','email', 'name', 'message']);
		$message		= Message::create($data);
		$musicStyleId	= $data['music_style_id'];

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

		return view('public.contact', compact('content'));
    }

    public function formNewsletterSubscribe( FormBuilder $formBuilder ) {
        $form   = $formBuilder->create(NewsletterSubscribeForm::class);
        return view('public.form.newsletter-subscribe', compact('form'));
    }

    public function sendNewsletterSubscribe( Request $request )
    {
        $form = $this->form(NewsletterSubscribeForm::class);
		$this->validate($request, [
			'g-recaptcha-response' => 'required|captcha'
		]);
    }

	public function removeAddressShow($token)
	{
		$address = Address::where('token', $token)->first();
		return view('public.address-remove', compact('address'));
	}

	public function removeAddressHard($token)
	{
		$address = Address::where('token', $token)->first();
		return view('public.address-remove', compact('address'));
	}
}
