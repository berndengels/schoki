<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\BandContactRequest;
use App\Mail\NotifyBooker;
use App\Models\Address;
use App\Models\AddressCategory;
use App\Models\Message;
use App\Models\MusicStyle;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Repositories\NewsletterRepository;

/**
 *
 */
class ContactController extends BaseController
{
    use FormBuilderTrait, DispatchesJobs, ValidatesRequests;

    /**
     * @param FormBuilder $formBuilder
     * @return Application|Factory|View
     */
    public function formBands() {
        $musicStyles = collect(MusicStyle::all()->keyBy('id')->map->name)->prepend('Bitte wählen', null);
		return view('public.form.bands', compact('musicStyles'));
    }

    /**
     * @param BandContactRequest $request
     * @return Application|Factory|View
     * @throws ValidationException
     */
    public function sendBands(BandContactRequest $request )
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

		return view('public.contact', compact('content'));
    }

    /**
     * @return Application|Factory|View
     */
    public function formNewsletterSubscribe() {
        $addressCategories = collect(AddressCategory::all()->keyBy('id')->map->name)->prepend('Bitte wählen', null);
        return view('public.form.newsletter-subscribe', compact('addressCategories'));
    }

    /**
     * @param AddressRequest $request
     * @return void
     * @throws ValidationException
     */
    public function sendNewsletterSubscribe(AddressRequest $request )
    {
        $validated  = $request->validated();
        $email      = $validated['email'];
        $remove     = (boolean) $validated['remove'];
        $addressCategoryId = $validated['address_category_id'];
        $validated['token'] = Hash::make($addressCategoryId.$email);

        $repo = new NewsletterRepository();
        $address = Address::whereEmail($email)->first();
        $message = 'Unbekannter Fehler';

        if($remove) {
            if($address) {
                $response = $repo->removeMember($address);
                if('unsubscribed' === $response['status']) {
                    $address->delete();
                    $message = "Email-Adresse $email erfolgreich gelöscht";
                }
            } else {
                $message = "Email-Adresse $email ist nicht für Newsletter registriert";
            }
        } else {
            if(!$address) {
                $address = Address::create($validated);
            }
            $response = $repo->addMember($address);
            if(isset($response['lastError']) && 'isSubscribed' === $response['lastError']) {
                $message = "Email-Adresse $email ist bereits für Newsletter registriert";
            } else {
                $message = "Email-Adresse $email erfolgreich für Newsletter hinzugefügt";
            }
        }

        return view('public.newsletter-subscribe', compact('response', 'message'));
    }

    /**
     * @param $token
     * @return Application|Factory|View
     */
    public function removeAddressShow($token)
	{
		$address = Address::where('token', $token)->first();
		return view('public.address-remove', compact('address'));
	}

    /**
     * @param $token
     * @return Application|Factory|View
     */
    public function removeAddressHard($token)
	{
		$address = Address::where('token', $token)->first();
		return view('public.address-remove', compact('address'));
	}
}
