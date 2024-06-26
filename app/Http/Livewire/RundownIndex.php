<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\Rundowns;

class RundownIndex extends Component
{
    use WithPagination;

    public $per_page    = [10,25,50,100];
    public $perPage     = 10;
    public $orderBy     = 'title';
    public $orderAsc    = true;
    public $arrow       = '<i class="bi bi-arrow-down-circle-fill"></i>';
    public $search;
    public $filter      = 'my';

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        if ($this->filter == 'all'){
            $rundowns = Rundowns::when($this->search, function($query, $search){
                return $query->where('title', 'LIKE', "%$search%")->orWhere('starttime', 'LIKE', "%$search%");
            })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);
        }
        else{
            $this->filter == 'my' ? $where = '=' : $where = '!=';
            $rundowns = Auth::user()->rundowns()->where('owner', $where, Auth::user()->id)
                ->when($this->search, function($query, $search){
                    return $query->where('title', 'LIKE', "%$search%")->orWhere('starttime', 'LIKE', "%$search%");
                })
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
                ->paginate($this->perPage);
        }
        return view('livewire.rundown-index', [
            'rundowns' => $rundowns
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
