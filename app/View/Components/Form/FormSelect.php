<?php

namespace App\View\Components\Form;

use ProtoneMedia\LaravelFormComponents\Components\FormSelect as BaseFormSelect;

class FormSelect extends BaseFormSelect
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $name,
        string $label = '',
               $options = [],
               $bind = null,
               $default = null,
        bool $multiple = false,
        bool $showErrors = true,
        bool $manyRelation = false,
        bool $floating = false,
        string $placeholder = '',
        public $inline = null,
        public $class = null,
        public $help = null
    )
    {
        parent::__construct(
            $name,
            $label,
            $options,
            $bind,
            $default,
            $multiple,
            $showErrors,
            $manyRelation,
            $floating,
            $placeholder
        );
    }
}
