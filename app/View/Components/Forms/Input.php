<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Input extends Component
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
    public $snappy;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type, $name, $label, $value = '', $wrapClass = '', $wire = '', $inputClass = '', $snappy = '')
    {
        $this->type         = $type;
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
        switch ($this->type){
            case 'submit':  $this->template = 'components.forms.submit';    break;
            case 'button':  $this->template = 'components.forms.button';    break;
            case 'file':    $this->template = 'components.forms.file';      break;
            default:        $this->template = 'components.forms.input';
        }
        return view($this->template);
    }
}
