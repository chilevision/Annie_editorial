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
    public $checked;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $checked = null, $value = null, $wrapClass = null, $wire = null, $inputClass = null, $snappy = null)
    {
        $this->name         = $name;
        $this->value        = $value;
        $this->wrapClass    = $wrapClass;
        $this->inputClass   = $inputClass;
        $this->label        = $label;
        $this->snappy       = $snappy;
        $this->checked      = $checked;

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
