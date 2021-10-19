<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;
use App\Events\RundownEvent;

class Rundown extends Component
{
    public $rundown;
    public $rundownrows;

    protected $listeners = [
        'render' => 'add_rows',
        'orderChanged' => 'updateOrder'
    ];

    public function render()
    {
        $this->add_rows();
        return view('livewire.rundown')->with(['rundownrows' => $this->rundownrows, 'rundown' => $this->rundown]);
    }

    public function add_rows()
    {
        $rows = Rundown_rows::where('rundown_id', $this->rundown->id)->get();
        $this->rundownrows = sort_rows($rows)[0];
    }

    public function deleteRow($id){
        $position = $this->rundownrows->where('id', $id)->first()['before_in_table'];
        $next_row = $this->rundownrows->where('before_in_table', $id)->first()['id'];
        
        Rundown_rows::where('id', $next_row)->update(array('before_in_table' => $position));
        Rundown_rows::findOrFail($id)->delete();
        event(new RundownEvent('render', $this->rundown->id));
    }
    public function updateOrder($oldIndex, $newIndex)
    {
        dd($this->rundown->id);
    }
}
