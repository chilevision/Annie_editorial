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

    public $per_page    = [10,25,50,100];
    public $perPage     = 10;
    public $orderBy     = 'title';
    public $orderAsc    = true;
    public $arrow       = '<i class="bi bi-arrow-down-circle-fill"></i>';

    public function render()
    {
        return view('livewire.rundown-index', [
            'rundowns' => Rundowns::where('user_id', Auth::user()->id)->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->simplePaginate($this->perPage)
        ]);
    }

    public function changeOrder($newOrderBy)
    {
        if ($newOrderBy == $this->orderBy) $this->orderAsc = !$this->orderAsc;
        else $this->orderAsc = true;
        $this->orderBy  = $newOrderBy;
        $this->orderAsc ? $this->arrow = '<i class="bi bi-arrow-down-circle-fill"></i>' : $this->arrow = '<i class="bi bi-arrow-up-circle-fill"></i>';
    }
}
