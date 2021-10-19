<?php

namespace App\View\Components\Tables;

use Illuminate\View\Component;

class table extends Component
{
    public array $headers;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(array $headers)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.tables.table');
    }
}
