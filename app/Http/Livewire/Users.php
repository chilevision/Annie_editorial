<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    public $users_cells = [
        ['style' => 'width: 60px;', 'text' => 'settings.id'],
        ['style' => 'width: 300px', 'text' => 'rundown.title'],
        ['style' => 'width: 100px;', 'text' => 'rundown.type'],
        ['style' => 'width: 250px;', 'text' => 'rundown.source'],
        ['style' => '', 'text' => 'rundown.data'],
        ['style' => 'width: 90px;', 'text' => 'rundown.delay'],
        ['style' => 'width: 90px;', 'text' => 'rundown.duration']
    ];
    public $per_page    = [10,25,50,100];
    public $perPage     = 10;
    public $orderBy     = 'id';
    public $orderAsc    = true;
    public $arrow       = '<i class="bi bi-arrow-down-circle-fill"></i>';


    public function render()
    {
        $users = new User();
        return view('livewire.users',[ 'users' => $users->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->simplePaginate($this->perPage)]);
    }
    
    public function changeOrder($newOrderBy)
    {
        if ($newOrderBy == $this->orderBy) $this->orderAsc = !$this->orderAsc;
        else $this->orderAsc = true;
        $this->orderBy  = $newOrderBy;
        $this->orderAsc ? $this->arrow = '<i class="bi bi-arrow-down-circle-fill"></i>' : $this->arrow = '<i class="bi bi-arrow-up-circle-fill"></i>';
    }
    public function editUser($id){
        $this->emit('editUser', $id);
    }
}
