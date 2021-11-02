<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use stdClass;

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
    public $sso         = 1;
    public $perPage     = 10;
    public $orderBy     = 'name';
    public $orderAsc    = true;

    public function render()
    {
        $properties = new stdClass();
        $properties->orderBy = $this->orderBy;
        $properties->orderAsc = $this->orderAsc;
        $properties->perPage = $this->perPage;
        $users = new User();
        return view('livewire.users',[
            'users' => $users->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->simplePaginate($this->perPage),
            'properties' => $properties,
        ]);
    }
}
