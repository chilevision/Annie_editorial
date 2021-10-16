<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;
use App\Events\RundownEvent;

class Rundown extends Component
{
    public $rundown;
    public $rundownrows;

    protected $listeners = ['render' => 'add_rows'];

    public function render()
    {
        $this->add_rows();
        return view('livewire.rundown')->with(['rundownrows' => $this->rundownrows, 'rundown' => $this->rundown]);
    }

    public function add_rows()
    {
        $this->rundownrows = Rundown_rows::where('rundown_id', $this->rundown->id)->orderBy('position', 'asc')->get();
    }

    public function deleteRow($id){
        Rundown_rows::findOrFail($id)->delete();
        event(new RundownEvent('render', $this->rundown->id));
    }
}
