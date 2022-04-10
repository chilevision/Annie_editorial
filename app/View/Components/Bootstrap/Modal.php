<?php

namespace App\View\Components\Bootstrap;

use Illuminate\View\Component;

class Modal extends Component
{

    public $id;
    public $size;
    public $saveBtn;
    public $saveClick;
    public $title;
    public $footer;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id, $saveBtn = '', $saveClick = '', $size = '', $title = '', $footer = '')
    {
        $this->id           = $id;
        $this->size         = $size;
        $this->saveBtn      = $saveBtn;
        $this->saveClick    = $saveClick;
        $this->title        = $title;
        $this->footer       = $footer;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.bootstrap.modal');
    }
}
