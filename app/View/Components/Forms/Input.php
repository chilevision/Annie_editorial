<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Input extends Component
{

    public $name;
    public $wrapClass;
    public $label;
    public $wire;
    public $inputClass;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $wrapClass, $label, $wire, $inputClass)
    {
        $this->name = $name;
        $this->wrapClass = $wrapClass;
        $this->label =$label;
        $this->wire = $wire;
        $this->inputClass = $inputClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.input');
    }
}
