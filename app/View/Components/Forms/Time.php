<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Time extends Component
{
    public $name;
    public $value;
    public $wrapClass;
    public $inputClass;
    public $wires;
    public $label;
    public $template;
    public $step;
    public $snappy;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $step, $value = '', $wrapClass = '', $wire = '', $inputClass = '', $snappy = '')
    {
        $this->name         = $name;
        $this->value        = $value;
        $this->wrapClass    = $wrapClass;
        $this->inputClass   = $inputClass;
        $this->label        = $label;
        $this->step         = $step;
        $this->snappy       = $snappy;

        if ($wire != '') $this->wires = $wire;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.time');
    }
}
