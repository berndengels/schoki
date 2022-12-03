<?php

namespace App\View\Components;

use App\Entities\EventEntity;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EventView extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public EventEntity $item,
        public int|string $index
    )
    {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.event-view', [
//            'item'  => $this->item,
        ]);
    }
}
