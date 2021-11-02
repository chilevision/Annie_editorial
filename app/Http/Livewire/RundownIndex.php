<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundowns;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use stdClass;

class RundownIndex extends Component
{
    use WithPagination;

    public $perPage     = 10;
    public $orderBy     = 'title';
    public $orderAsc    = true;

    public function render()
    {
        $properties = new stdClass();
        $properties->orderBy = $this->orderBy;
        $properties->orderAsc = $this->orderAsc;
        $properties->perPage = $this->perPage;

        return view('livewire.rundown-index', [
            'rundowns' => Rundowns::where('user_id', Auth::user()->id)->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->simplePaginate($this->perPage),
            'properties' => $properties,
        ]);
    }

    public function changeOrder($newOrderBy, $order){
        if ($order == '') $order = false;
        $this->orderBy = $newOrderBy;
        $this->orderAsc = $order;
    }
}
