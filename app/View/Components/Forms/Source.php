<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Source extends Component
{
    public $name;
    public $type;
    public $value;
    public $wrapClass;
    public $inputClass;
    public $wire;
    public $wires = '';
    public $label;
    public $template;
    public $sourceQuery;
    public $modalTarget;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $name, $value, $wrapClass, $wire, $label, $inputClass, $sourceQuery, $modalTarget)
    {
        $this->type         = $type;
        $this->name         = $name;
        $this->value        = $value;
        $this->wrapClass    = $wrapClass;
        $this->inputClass   = $inputClass;
        $this->label        = $label;
        $this->sourceQuery  = $sourceQuery;
        $this->modalTarget  = $modalTarget;

        if ($wire != '') $this->wires = $wire;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.source');
    }
}
