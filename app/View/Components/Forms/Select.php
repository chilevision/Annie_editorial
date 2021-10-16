<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Select extends Component
{
    public $name;
    public $wrapClass;
    public $selectClass;
    public $wire;
    public $wires = '';
    public $label;
    public $options;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $wrapClass, $selectClass, $wire, $label, $options)
    {
        if ($wire != '') $this->wires = $wire;
        $this->name         = $name;
        $this->wrapClass    = $wrapClass;
        $this->selectClass  = $selectClass;
        $this->label        = $label;
        $this->options      = $options;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.select');
    }
}
