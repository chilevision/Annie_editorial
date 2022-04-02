<?php

namespace App\View\Components\Table;

use Illuminate\View\Component;

class Row extends Component
{
    public $class;
    public $id;
    public $cells;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($class, $id, $cells)
    {
        $this->class            = $class;
        $this->id               = $id;
        $this->cells            = $cells;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table.row');
    }
}
