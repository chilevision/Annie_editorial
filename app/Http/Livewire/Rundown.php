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
        'render'        => 'add_rows',
        'orderChanged'  => 'updateOrder'
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
        $row_before_this = $this->rundownrows->where('id', $id)->first()['before_in_table'];
        //if this isn't the last row: find the next row and update it's "before_in_table" value
        if (!$this->rundownrows->where('before_in_table', '=', $id)->isEmpty()) {
            $next_row = $this->rundownrows->where('before_in_table', $id)->first()['id'];
            Rundown_rows::where('id', $next_row)->update(array('before_in_table' => $row_before_this));
         }
        //else just delete the last row
        Rundown_rows::findOrFail($id)->delete();
        event(new RundownEvent(['type' => 'render', 'id' => $id], $this->rundown->id));
    }

    public function updateOrder($old_position, $new_position)
    {
        $moved_row = $this->rundownrows[$old_position];
        ($new_position-1>0) ? $row_abowe = $this->rundownrows[$new_position-1]['id'] : $row_abowe = NULL;
        
        dd($moved_row);
    }
}
