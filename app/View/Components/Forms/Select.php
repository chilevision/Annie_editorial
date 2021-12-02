<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Select extends Component
{
    public $name;
    public $wrapClass;
    public $selectClass;
    public $disabled;
    public $wire;
    public $wires = '';
    public $label;
    public $options;
    public $selected;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $wire, $label, $options, $wrapClass = '', $selectClass = '', $disabled = '', $selected = '')
    {
        if ($wire != '') $this->wires = $wire;
        $this->name         = $name;
        $this->wrapClass    = $wrapClass;
        $this->selectClass  = $selectClass;
        $this->disabled     = $disabled;
        $this->label        = $label;
        $this->options      = $options;
        $this->selected     = $selected;
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
