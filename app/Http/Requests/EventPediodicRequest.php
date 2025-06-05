<?php

namespace App\Http\Requests;

class EventPediodicRequest extends AdminRequest
{

    public function validationData()
    {
        return array_merge(['is_published' => false], $this->all());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'             => 'required|string|min:3|max:160',
            'subtitle'          => 'max:100',
			'promoter'      	=> 'nullable|max:100',
			'dj'      			=> 'nullable|max:100',
            'event_time'        => 'required',
            'category_id'       => 'required',
            'periodic_position' => 'required',
            'periodic_weekday'  => 'required',
            'theme_id'          => '',
            'description'       => '',
            'images'            => '',
            'links'             => '',
            'is_published'      => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'category_id.required'  => 'Bitte eine Kategorie auswählen!',
            'event_time.required'   => 'Bitte die Uhrzeit der Veranstaltungs angeben!',
            'title.required'        => 'Bitte den Titel eintragen!',
            'title.min'             => 'Der Titel muß mindestens 3 Zeichen lang sein.',
            'title.max'             => 'Der Titel darf maximal 160 Zeichen enhalten.',
            'subtitle.max'          => 'Der Untertitel darf maximal 100 Zeichen lang sein.',
            'periodic_position.required'   => 'Bitte die Position das Datums angeben!',
            'periodic_weekday.required'    => 'Bitte den Wochentag das Datums angeben!',
        ];
    }
}
