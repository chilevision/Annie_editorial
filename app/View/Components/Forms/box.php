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
        $this->label        = $label;

        isset($checked)     ? $this->checked    = $checked      : $this->checked    = null;
        isset($value)       ? $this->value      = $value        : $this->value      = null;
        isset($wrapClass)   ? $this->wrapClass  = $wrapClass    : $this->wrapClass  = null;
        isset($wire)        ? $this->wires      = $wire         : $this->wires      = null;
        isset($inputClass)  ? $this->inputClass = $inputClass   : $this->inputClass = null;
        isset($snappy)      ? $this->snappy     = $snappy       : $this->$snappy    = null;

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
