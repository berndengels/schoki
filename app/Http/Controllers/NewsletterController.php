<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\AddressCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AddressRequest;
use App\Repositories\NewsletterRepository;

class NewsletterController extends Controller
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
        $addressCategories = collect(AddressCategory::all()->keyBy('id')->map->name)->prepend('Bitte wählen', null);
        return view('public.form.newsletter-subscribe', compact('addressCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddressRequest  $request
     * @return Response
     */
    public function store(AddressRequest $request)
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
     * Display the specified resource.
     *
     * @param Address $address
     * @return Response
     */
//    public function show(Address $address) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param Address $address
     * @return Response
     */
//    public function edit(Address $address) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param Address $address
     * @return Response
     */
//    public function update(Request $request, Address $address) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param Address $address
     * @return Response
     */
//    public function destroy(Address $address) {}
}
