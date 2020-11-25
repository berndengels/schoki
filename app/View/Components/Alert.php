<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Alert extends Component
{
    public $type;
    public $message;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type = null, $message = null)
    {
        $this->type    = $type;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('components.alert');
    }
}
