<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;
use App\Events\RundownEvent;
use App\Models\Rundown_meta_rows;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Rundown extends Component
{
    public $rundown;
    public $rundownrows;
    public $page            = 'A';
    public $page_number     = 1;
    public $rundown_timer   = 0;
    public $row;
    public $show_meta       = false;

    protected $listeners = [
        'render'            => 'add_rows',
        'orderChanged'      => 'updateOrder',
        'textEditor'        => 'init_textEditor',
        'saveText'          => 'saveText',
        'lock'              => 'lock',
        'update_lock'       => 'update_lock'
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
        ['style' => 'width: 300px', 'text' => 'rundown.title'],
        ['style' => 'width: 100px;', 'text' => 'rundown.type'],
        ['style' => 'width: 250px;', 'text' => 'rundown.source'],
        ['style' => '', 'text' => 'rundown.data'],
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
    /*~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~
                            TEXT EDITOR
    ~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~*/

    public function init_textEditor($input)
    {
        $this->row      = $input[0];
        $type           = $input[1];
        if ($this->row != '' || $this->row != null){
            $data = Rundown_rows::where('id', $this->row)->first()->$type;
            if ($type == 'cam_notes') $title = __('rundown.edit_camera_notes');
            else $title = __('rundown.edit_script');
            $this->lock($type, $this->row, 1);
            if ($data == null) $data = '';
            $this->emit('loadEditor', [$data, $type, $title, $this->row]);
        }
    }
    public function saveText($data){
        $type = $data[0];
        $text = $data[1];
        Rundown_rows::where('id', $this->row)->update([
            $type   => $text
        ]);
        $this->lock($type, $this->row);
    }

    /*~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~
                        MENU LOCKING
    ~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~^~*/
    public function update_lock($data)
    {
        $this->lock($data['type'], $data['id'], 1, 0);
    }

    public function lock($type, $id, $lock = 0, $emit = 1)
    {
        $this->show_meta = false;
        $fields     = $this->get_fields($type);
        if ($fields != null){ 
            $user       = null;
            $time       = null;
            if ($lock){
                $user = Auth::user()->id;
                $time = Carbon::now()->toDateTimeString();
            }

            if ($type == 'meta_row'){
                Rundown_meta_rows::where('id', $id)->update([
                    $fields[0]  => $user,
                    $fields[1]  => $time
                ]);
            }
            else{
                Rundown_rows::where('id', $id)->update([
                    $fields[0]  => $user,
                    $fields[1]  => $time
                ]);
            }
            if ($emit){ 
                $this->emit('keepLocked', ['type' => $type, 'id' => $id, 'lock' => $lock]);
                event(new RundownEvent(['type' => $type, 'id' => $id, 'lock' => $lock], $this->rundown->id));
            }
        }

        if ($type == 'meta_row') $this->show_meta = Rundown_meta_rows::where('id', $id)->first()->rundown_rows_id;
    }

    protected function get_fields($type)
    {
        switch ($type){
            case 'row' : 
            case 'meta_row' : 
                return ['locked_by', 'locked_at'];
                break;
            case 'script' : 
                return ['script_locked_by', 'script_locked_at'];
                break;
            case 'cam_notes' : 
                return ['notes_locked_by', 'notes_locked_at'];
                break;
            default : return null;
        }
    }
}
