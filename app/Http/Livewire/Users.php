<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Settings;
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
    public $per_page    = [10,25,50,100];
    public $perPage     = 10;
    public $orderBy     = 'id';
    public $orderAsc    = true;
    public $arrow       = '<i class="bi bi-arrow-down-circle-fill"></i>';

    public $sso_host;
    public $sso_validation;
    public $sso_version;
    public $sso_logout;
    public $sso;


    public function mount()
    {
        $this->sso = Settings::where('id', 1)->first()->sso;
        $this->sso_host         = env('CAS_HOSTNAME');
        $this->sso_validation   = env('CAS_VALIDATION');
        $this->sso_version      = env('CAS_VERSION');
        $this->sso_logout       = env('CAS_LOGOUT_URL');
    }
    public function render()
    {
        $users = new User();
        return view('livewire.users',[
            'users'         => $users->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->simplePaginate($this->perPage)
        ]);
    }
    
    public function changeOrder($newOrderBy)
    {
        if ($newOrderBy == $this->orderBy) $this->orderAsc = !$this->orderAsc;
        else $this->orderAsc = true;
        $this->orderBy  = $newOrderBy;
        $this->orderAsc ? $this->arrow = '<i class="bi bi-arrow-down-circle-fill"></i>' : $this->arrow = '<i class="bi bi-arrow-up-circle-fill"></i>';
    }

    public function saveSso(){
        config(['CAS_HOSTNAME' => $this->sso_host]);
        config(['CAS_VALIDATION' => $this->sso_validation]);
        config(['CAS_VERSION' => $this->sso_version]);
        config(['CAS_LOGOUT_URL' => $this->sso_logout]);
    }
    public function updatedSso()
    {
        Settings::where('id', 1)->update(['sso' => $this->sso]);
    }
}
