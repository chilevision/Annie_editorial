<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;
use App\Events\RundownEvent;
use App\Models\Rundown_meta_rows;

class Rundown extends Component
{
    public $rundown;
    public $rundownrows;
    public $page            = 'A';
    public $page_number     = 1;
    public $rundown_timer   = 0;

    protected $listeners = [
        'render'            => 'add_rows',
        'orderChanged'      => 'updateOrder'
    ];

    public $cells = [
        ['style' => 'width: 60px;', 'text' => 'rundown.page'],
        ['style' => 'padding: 0;', 'text' => ''],
        ['style' => '', 'text' => 'rundown.story'],
        ['style' => 'width: 60px;', 'text' => 'rundown.type'],
        ['style' => 'width: 150px;', 'text' => 'rundown.talent'],
        ['style' => 'width: 200px;', 'text' => 'rundown.cue'],
        ['style' => 'width: 80px;', 'text' => 'rundown.source'],
        ['style' => 'width: 80px;', 'text' => 'rundown.audio'],
        ['style' => 'width: 90px;', 'text' => 'rundown.duration'],
        ['style' => 'width: 90px;', 'text' => 'rundown.start'],
        ['style' => 'width: 90px;', 'text' => 'rundown.stop']
    ];

    public $meta_cells = [
        ['style' => 'width: 60px;', 'text' => 'rundown.page'],
        ['style' => 'padding: 0; width: 10px', 'text' => ''],
        ['style' => '', 'text' => 'rundown.title'],
        ['style' => 'width: 100px;', 'text' => 'rundown.type'],
        ['style' => 'width: 80px;', 'text' => 'rundown.source'],
        ['style' => 'width: 400px;', 'text' => 'rundown.data'],
        ['style' => 'width: 90px;', 'text' => 'rundown.delay'],
        ['style' => 'width: 90px;', 'text' => 'rundown.duration']
    ];

    public function render()
    {
        $timer = strtotime($this->rundown->starttime);
        $this->page            = 'A';
        $this->page_number     = 1;
        $this->add_rows();
        return view('livewire.rundown')->with(['rundownrows' => $this->rundownrows, 'rundown' => $this->rundown, 'timer' => $timer]);
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
    
    public function deleteMeta($id){
        Rundown_meta_rows::findOrFail($id)->delete();
        event(new RundownEvent(['type' => 'render', 'id' => $id], $this->rundown->id));
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
