<?php

namespace App\Http\Requests;

use App\Models\MusicStyle;
use App\Http\Resources\MusicStyleResource;
use App\Http\Requests\Api\FormRequest;

class BandContactRequest extends FormRequest
{
    protected $model;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function fields() {
        return [
            'music_style_id'    => [
                'type'      => 'select',
                'label'     => 'Musik Richtung',
                'options'   => MusicStyleResource::collection(MusicStyle::all()->sortBy('name')),
                'rules'     => $this->rules()['music_style_id'],
                'messages'  => array_filter($this->messages(), function($item, $key) {
                    $name = substr($key, 0, strpos($key, '.'));
                    if($name === 'music_style_id') {
                        return $item;
                    }
                },ARRAY_FILTER_USE_BOTH),
            ],
            'name'              => [
                'type'      => 'text',
                'label'     => 'Name',
                'rules'     => $this->rules()['name'],
                'messages'  => array_filter($this->messages(), function($item, $key) {
                    $name = substr($key, 0, strpos($key, '.'));
                    if($name === 'name') {
                        return $item;
                    }
                },ARRAY_FILTER_USE_BOTH),
            ],
            'email'             => [
                'type'      => 'email',
                'label'     => 'Email',
                'rules'     => $this->rules()['email'],
                'messages'  => array_filter($this->messages(), function($item, $key) {
                    $name = substr($key, 0, strpos($key, '.'));
                    if($name === 'email') {
                        return $item;
                    }
                }, ARRAY_FILTER_USE_BOTH),
            ],
            'message'           => [
                'type'      => 'textarea',
                'label'     => 'Nachricht',
                'rules'     => $this->rules()['message'],
                'messages'  => array_filter($this->messages(), function($item, $key) {
                    $name = substr($key, 0, strpos($key, '.'));
                    if($name === 'message') {
                        return $item;
                    }
                }, ARRAY_FILTER_USE_BOTH),
            ],
            'recaptcha'           => [
                'type'      => 'textarea',
                'rules'     => $this->rules()['recaptcha'],
                'messages'  => array_filter($this->messages(), function($item, $key) {
                    $name = substr($key, 0, strpos($key, '.'));
                    if($name === 'recaptcha') {
                        return $item;
                    }
                }, ARRAY_FILTER_USE_BOTH),
            ],
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'music_style_id'    => 'required',
            'name'              => 'required',
            'email'             => 'required|email',
            'message'           => 'required',
            'recaptcha'         => 'required',
        ];
    }

    public function messages()
    {
        return [
            'music_style_id.required'   => 'Bitte eine Musik-Richtung angeben!',
            'name.required'         => 'Bitte einen Name angeben!',
            'email.required'        => 'Bitte eine Email Adresse angeben!',
            'email.eamil'           => 'Das ist keine korrekte Email-Adresse!.',
            'message.required'      => 'Bitte ein Nachricht eingeben!',
            'recaptcha.required'    => 'Bitte das Captcha bedienen!',
        ];
    }
}
