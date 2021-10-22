<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;
use App\Events\RundownEvent;


class RundownrowForm extends Component
{
    public $rundown;
    public $rundown_row_id;
    public $story;
    public $talent;
    public $cue;
    public $type = 'MIXER';
    public $source = 'CAM1';
    public $audio;
    public $duration = '00:00:00';
    public $autotrigg = 1;

    public $header = 'rundown.new_row';
    public $submit_btn_label = 'rundown.create';
    public $formAction = 'submit';
    public $type_disabled;

    protected $typeOptions = [
        ['value' => 'MIXER', 'title' => 'MIXER'],
        ['value' => 'VB', 'title' => 'VB'],
        ['value' => 'PRE', 'title' => 'PRE'],
        ['value' => 'BREAK', 'title' => 'BREAK']
    ];
    protected $sourceOptions = [
        ['value' => 'CAM1', 'title' => 'CAM1'],
        ['value' => 'CAM2', 'title' => 'CAM2'],
        ['value' => 'CAM3', 'title' => 'CAM3'],
        ['value' => 'CAM4', 'title' => 'CAM4'],
        ['value' => 'CAM5', 'title' => 'CAM5'],
        ['value' => 'CAM6', 'title' => 'CAM6'],
        ['value' => 'CAM7', 'title' => 'CAM7'],
        ['value' => 'CAM8', 'title' => 'CAM8'],
        ['value' => 'CAM9', 'title' => 'CAM9'],
        ['value' => 'CAM10', 'title' => 'CAM10'],
        ['value' => 'BLK', 'title' => 'BLK'],
        ['value' => 'BARS', 'title' => 'BARS'],
        ['value' => 'SSRC', 'title' => 'SSRC']  
    ];
    protected $colors = ['930000', 'e05500', 'da8f00', '897800', '39000d', '004334', '003a48', '007792', '47295e'];
    protected $rules = [
        'name' => 'required|min:6',
        'email' => 'required|email',
    ];
    protected $listeners = [
        'editRow'       => 'editRow',
        'cancel_edit'   => 'cancel_edit',
    ];

    public function render()
    {
        return view('livewire.rundownrow-form')->with([
            'rundown'       => $this->rundown,
            'typeOptions'   => $this->typeOptions,
            'sourceOptions' => $this->sourceOptions
        ]);
    }

    /* Function to create a new rundown row.
    |
    |
    */
    public function submit()
    {
        $duration           = to_seconds($this->duration);
        $rows               = Rundown_rows::where('rundown_id', $this->rundown->id)->get();
        $before_in_table    = sort_rows($rows)[1];
        $color              = array_search( $rows->where('id', $before_in_table)->first()->color, $this->colors ) + 1;
        $color              = $this->colors[$color%count($this->colors)];
        //$this->validate();

        // Execution doesn't reach here if validation fails.

        $row = Rundown_rows::create([
            'rundown_id'        => $this->rundown->id,
            'before_in_table'   => $before_in_table,
            'color'             => $color,
            'story'             => $this->story,
            'talent'            => $this->talent,
            'cue'               => $this->cue,
            'type'              => $this->type,
            'source'            => $this->source,
            'audio'             => $this->audio,
            'duration'          => $duration,
            'autotrigg'         => $this->autotrigg
        ]);
        $this->resetForm();
        event(new RundownEvent(['type'=> 'render', 'id' => $row->id], $this->rundown->id));
    }


    /* Function to edit a  rundown row.
    |
    |
    */
    public function editRow($id){
        $row = Rundown_rows::find($id);
        if($row) {
            $row->locked = 1;
            $row->save();
            $this->rundown_row_id   = $id;
            $this->story            = $row->story;
            $this->talent           = $row->talent;
            $this->cue              = $row->cue;
            $this->type             = $row->type;
            $this->source           = $row->source;
            $this->audio            = $row->audio;
            $this->duration         = gmdate('H:i:s', $row->duration);
            $this->autotrigg        = $row->autotrigg;

            $this->header           = 'rundown.edit_row';
            $this->submit_btn_label = 'rundown.update';
            $this->formAction       = 'update';
            $this->type_disabled    = 'disabled';
        
            event(new RundownEvent(['type' => 'edit', 'id' => $id], $this->rundown->id));
            $this->emit('in_edit_mode', true);
        }
    }

    public function update(){
        $row = Rundown_rows::find($this->rundown_row_id);
        if($row){
            $row->story            = $this->story;
            $row->talent           = $this->talent;
            $row->cue              = $this->cue;
            $row->source           = $this->source;
            $row->audio            = $this->audio;
            $row->duration         = to_seconds($this->duration);
            $row->autotrigg        = $this->autotrigg;
            $row->locked = 0;
            $row->save();
        }
        event(new RundownEvent(['type' => 'row_updated', 'id' => $this->rundown_row_id], $this->rundown->id));
        $this->resetForm();
        $this->emit('in_edit_mode', false);
    }

    public function cancel_edit(){
        $row = Rundown_rows::find($this->rundown_row_id);
        if($row) {
            $row->locked = 0;
            $row->save();
        }
        event(new RundownEvent(['type' => 'cancel_edit', 'id' => $this->rundown_row_id], $this->rundown->id));
        $this->resetForm();
        $this->emit('in_edit_mode', false);
    }


    public function typeChange() {
        $this->dispatchBrowserEvent('typeHasChanged', ['newTime' => '']);
        switch($this->type){
            case 'MIXER':
                $this->source = 'CAM1';
            break;
            case 'VB': 
                $this->source = '';
            break;
        }
    }

    private function resetForm(){
        $this->reset(['story', 'talent', 'cue', 'source', 'audio', 'duration']);
        $this->type             = 'MIXER';
        $this->autotrigg        = 1;
        $this->header           = 'rundown.new_row';
        $this->submit_btn_label = 'rundown.create';
        $this->formAction       = 'submit';
        $this->type_disabled    = '';
        $this->dispatchBrowserEvent('typeHasChanged', ['newTime' => '']);
    }
}
