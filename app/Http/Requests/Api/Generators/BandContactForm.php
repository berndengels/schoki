<?php

namespace App\Http\Requests\Api\Generators;

use App\Entities\VueFormGenerator\Fields;
use App\Entities\VueFormGenerator\FormOptions;
use App\Entities\VueFormGenerator\Model;
use App\Entities\VueFormGenerator\Schema;
use App\Models\MusicStyle;
use App\Http\Resources\MusicStyleResource;

class BandContactForm
{

    /**
     * @var Model
     */
    public $model;
    /**
     * @var Schema
     */
    public $schema;
    /**
     * @var Fields
     */
    public $fields;
    /**
     * @var FormOptions
     */
    public $formOptions;

    protected $modelData = [
        'music_style_id'    => null,
        'name'              => '',
        'email'             => '',
        'message'           => '',
    ];

    protected $schemaData = [
        'fields'    => [
            [
                'type'          => 'input',
                'inputType'     => 'text',
                'label'         => 'Name',
                'model'         => 'name',
                'readonly'      => false,
                'disabled'      => false,
                'placeholder'   => 'Dein Name',
                'featured'      => true,
                'required'      => true,
                'validator'     => 'required',
//                'hint'          => 'Bitte Deinen Namen eintragen!',
            ],
            [
                'type'          => 'input',
                'inputType'     => 'email',
                'label'         => 'Email',
                'model'         => 'email',
                'readonly'      => false,
                'disabled'      => false,
                'placeholder'   => 'Deine Email-Adresse',
                'featured'      => true,
                'required'      => true,
                'validator'     => 'required',
//                'hint'          => 'Bitte Deine Email Adresse eintragen!',
            ],
            [
                'type'          => 'textArea',
                'label'         => 'Nachricht',
                'model'         => 'message',
                'readonly'      => false,
                'disabled'      => false,
                'placeholder'   => 'Deine Nachricht an uns',
                'featured'      => true,
                'required'      => true,
                'rows'          => 4,
                'validator'     => 'required',
//                'hint'          => 'Bitte eine Nachricht eintragen!',
            ],
        ],
    ];

    protected $formOptionsData = [
        'validateAfterLoad'     => false,
        'validateAfterChanged'  => true,
        'validateAsync'         => true,
    ];

    /**
     * BandContactForm constructor.
     */
    public function __construct()
    {
        $fields  = $this->addSelectValues();

        $this->model        = new Model($this->modelData);
        $this->schema       = new Schema($fields);
        $this->formOptions  = new FormOptions($this->formOptionsData);
    }

    public function data() {
        return [
            'model'     => $this->model,
            'schema'    => $this->schema,
            'formOptions'   => $this->formOptions,
        ];
    }

    public function addSelectValues()
    {
        $extraField = [
            'type'          => 'select',
            'label'         => 'Musik Richtung',
            'model'         => 'music_style_id',
            'required'      => true,
            'default'       => null,
            'value'         => null,
            'values'        => MusicStyleResource::collection( MusicStyle::all()->sortBy('name') ),
            'validator'     => 'required',
            'selectOptions' => [
                'noneSelectedText'      => 'bitte wählen',
                'hideNoneSelectedText'  =>  false,
            ],
        ];

        $fields = $this->schemaData['fields'];
        array_unshift($fields, $extraField);

        return $fields;
    }
}
