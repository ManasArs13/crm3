<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShipmentModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $shipments;
    public $title;
    public $date;

    public function __construct($shipments, $title, $date)
    {
        $this->shipments = $shipments;
        $this->title = $title;
        $this->date = $date;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shipment-modal');
    }
}
