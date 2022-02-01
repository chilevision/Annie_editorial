<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class box extends Component
{
    public $name;
    public $value;
    public $wrapClass;
    public $inputClass;
    public $wire;
    public $wires = '';
    public $label;
    public $template;
    public $snappy;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $value = '', $wrapClass = '', $wire = '', $inputClass = '', $snappy = '')
    {
        $this->name         = $name;
        $this->value        = $value;
        $this->wrapClass    = $wrapClass;
        $this->inputClass   = $inputClass;
        $this->label        = $label;
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
        return view('components.forms.box');
    }
}
