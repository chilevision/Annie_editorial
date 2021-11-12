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
    public $ttl;

    public $ttlOptions = [
        ['value' => '0', 'title' => 'Never'],
        ['value' => '6', 'title' => '6 months'],
        ['value' => '12', 'title' => '12 months'],
        ['value' => '24', 'title' => '24 months'],
        ['value' => '36', 'title' => '36 months']
    ];

    public function mount()
    {
        $this->sso              = Settings::where('id', 1)->first()->sso;
        $this->sso_host         = env('CAS_HOSTNAME');
        $this->sso_validation   = env('CAS_VALIDATION');
        $this->sso_version      = env('CAS_VERSION');
        $this->sso_logout       = env('CAS_LOGOUT_URL');
        $this->ttl              = Settings::where('id', 1)->first()->user_ttl;
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
        Settings::where('id', 1)->update(['user_ttl' => $this->ttl]);
        $this->emit('settings_saved');
    }
    public function updatedSso()
    {
        Settings::where('id', 1)->update(['sso' => $this->sso]);
    }
}
