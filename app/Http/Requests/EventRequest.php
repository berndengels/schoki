<?php

namespace App\Http\Requests;

class EventRequest extends AdminRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function validationData()
    {
        return array_merge([
            'is_published'  => false,
            'is_periodic'   => 0,
        ], $this->all());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => 'required|string|min:3|max:160',
            'subtitle'      => 'max:100',
            'event_date'    => 'required|date',
            'event_time'    => 'required',
            'category_id'   => 'required',
			'ticketlink'    => 'nullable|url',
			'is_published'  => 'boolean',
            'theme_id'      => '',
            'description'   => '',
            'images'        => '',
            'links'         => '',
            'is_periodic'   => '',
        ];
    }

    public function messages()
    {
        return [
            'category_id.required'  => 'Bitte eine Kategorie auswählen!',
            'event_date.required'   => 'Bitte ein Veranstaltungs-Datum angeben!',
            'event_time.required'   => 'Bitte die Uhrzeit der Veranstaltungs angeben!',
            'title.required'        => 'Bitte den Titel eintragen!',
            'title.min'             => 'Der Titel muß mindestens 3 Zeichen lang sein.',
            'title.max'             => 'Der Titel darf maximal 160 Zeichen enhalten.',
            'subtitle.max'          => 'Der Untertitel darf maximal 100 Zeichen lang sein.',
        ];
    }
}
