<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Input extends Component
{
    public $type;
    public $name;
    public $value;
    public $wrapClass;
    public $inputClass;
    public $wire;
    public $wires = '';
    public $label;
    protected $template;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $name, $value, $wrapClass, $inputClass, $wire, $label)
    {
        $this->type         = $type;
        $this->name         = $name;
        $this->value        = $value;
        $this->wrapClass    = $wrapClass;
        $this->inputClass   = $inputClass;
        $this->label        = $label;
        
        if ($wire != '') $this->wires = $wire;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        switch($this->type){
            case('text'): $this->template = 'components.forms.input'; break;
            case('time'): $this->template = 'components.forms.time'; break;
            case('checkbox'): $this->template = 'components.forms.checkbox'; break;
            case('submit'): $this->template = 'components.forms.submit'; break;
        }
        return view($this->template);
    }
}
