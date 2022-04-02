<?php

namespace App\View\Components\Table;

use Illuminate\View\Component;

class Table extends Component
{

    public $class;
    public $id;
    public $headClass;
    public $headId;
    public $headRowClass;
    public $th;
    public $bodyClass;
    public $bodyId;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($class, $id, $headClass, $headId, $headRowClass, $th, $bodyClass, $bodyId)
    {
        $this->class            = $class;
        $this->id               = $id;
        $this->headClass        = $headClass;
        $this->headId           = $headId;
        $this->headRowClass     = $headRowClass;
        $this->th               = $th;
        $this->bodyClass        = $bodyClass;
        $this->bodyId           = $bodyId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table.table');
    }
}
