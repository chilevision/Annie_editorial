<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;

class Teleprompter extends Component
{
    public $rundown;

    protected $listeners = [
        'render'            => 'render',
    ];

    public function render()
    {
        $rundownrows = [];
        if ($this->rundown != null){
            $rows           = Rundown_rows::where('rundown_id', $this->rundown->id)->get();
            $rundownrows    = sort_rows($rows)[0];
        }
        return view('livewire.teleprompter')->with(['rundownrows' => $rundownrows]);
    }
}
