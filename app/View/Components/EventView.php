<?php

namespace App\View\Components;

use Closure;
use App\Entities\EventEntity;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EventView extends Component
{
    /**
     * @var EventEntity
     */
    public $item;
    public $index;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        EventEntity $item,
        string $index
    )
    {
        $this->item = $item;
        $this->index = $index;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.event-view');
    }
}
