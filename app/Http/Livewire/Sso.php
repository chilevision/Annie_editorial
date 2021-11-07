<?php

namespace App\Http\Livewire;

use App\Models\Settings;
use Livewire\Component;

class Sso extends Component
{
    public $sso_host;
    public $sso_validation;
    public $sso_version;
    public $sso_logout;
    public $sso;

    public function mount()
    {
        $this->sso              = Settings::where('id', 1)->first()->sso;
        $this->sso_host         = env('CAS_HOSTNAME');
        $this->sso_validation   = env('CAS_VALIDATION');
        $this->sso_version      = env('CAS_VERSION');
        $this->sso_logout       = env('CAS_LOGOUT_URL');
    }

    public function render()
    {
        return view('livewire.sso');
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
