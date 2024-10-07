<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShiftModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $shifts;
    public $title;
    public $date;

    public function __construct($shifts, $title, $date)
    {
        $this->shifts = $shifts;
        $this->title = $title;
        $this->date = $date;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shift-modal');
    }
}
