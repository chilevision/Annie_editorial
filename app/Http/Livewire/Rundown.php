<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;
use App\Models\Rundowns;
use App\Events\RundownEvent;

class Rundown extends Component
{
    public $rundown;
    public $rundownrows;

    protected $listeners = [
        'render'            => 'add_rows',
        'orderChanged'      => 'updateOrder',
        'sortingStarted'    => 'lockSorting',
        'sortingEnded'      => 'unlockSorting',
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
        $this->pick_out_row($id);
        Rundown_rows::findOrFail($id)->delete();
        event(new RundownEvent(['type' => 'render', 'id' => $id], $this->rundown->id));
    }

    public function lockSorting($code){
        $rundown = Rundowns::find($this->rundown->id);
        if($rundown) {
            $rundown->sortable = 0;
            $rundown->save();
        }
        event(new RundownEvent(['type' => 'lockSorting', 'code' => $code], $this->rundown->id));
    }

    public function unlockSorting(){
        $rundown = Rundowns::find($this->rundown->id);
        if($rundown) {
            $rundown->sortable = 1;
            $rundown->save();
        }
        event(new RundownEvent(['type' => 'unlockSorting'], $this->rundown->id));
    }

    public function updateOrder($moved_row, $before_in_table, $after_in_table)
    {        
        $this->pick_out_row($moved_row);
        Rundown_rows::where('id', $moved_row)->update(['before_in_table' => $before_in_table]);
        Rundown_rows::where('id', $after_in_table)->update(['before_in_table' => $moved_row]);
        event(new RundownEvent(['type' => 'render'], $this->rundown->id));
    }

    private function pick_out_row($id){
        $row_before_this = $this->rundownrows->where('id', $id)->first()['before_in_table'];
        //if this isn't the last row: find the next row and update it's "before_in_table" value
        if (!$this->rundownrows->where('before_in_table', '=', $id)->isEmpty()) {
            $next_row = $this->rundownrows->where('before_in_table', $id)->first()['id'];
            Rundown_rows::where('id', $next_row)->update(['before_in_table' => $row_before_this]);
         }
         return;
    }
}
