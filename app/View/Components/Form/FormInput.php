<?php

namespace App\View\Components\Form;

use ProtoneMedia\LaravelFormComponents\Components\FormInput as BaseFormInput;
use ProtoneMedia\LaravelFormComponents\Components\HandlesDefaultAndOldValue;
use ProtoneMedia\LaravelFormComponents\Components\HandlesValidationErrors;

class FormInput extends BaseFormInput
{
    use HandlesValidationErrors;
    use HandlesDefaultAndOldValue;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $name,
        string $label = '',
        string $type = 'text',
               $bind = null,
               $default = null,
               $language = null,
        bool $showErrors = true,
        bool $floating = false,
        $inline = null,
        $class = null,
        $help = null
    )
    {
        parent::__construct(
            $name,
            $label,
            $type,
            $bind,
            $default,
            $language,
            $showErrors,
            $floating,
        );
    }
}
