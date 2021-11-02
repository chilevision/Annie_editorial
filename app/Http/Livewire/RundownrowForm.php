<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;
use App\Models\Rundowns;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\RundownEvent;
use App\Models\Rundown_meta_rows;

class RundownrowForm extends Component
{
    public $rundown;
    public $rundown_row_id;
    public $rundown_meta_row_id;
    public $story;
    public $talent;
    public $cue;
    public $type = 'MIXER';
    public $source = 'CAM1';
    public $audio;
    public $duration = '00:00:00';
    public $delay = '00:00:00';
    public $autotrigg = 1;
    public $metaData = '';
    public $mediabowser = 'media';

    public $header = 'rundown.new_row';
    public $submit_btn_label = 'rundown.create';
    public $formAction = 'submit';
    public $type_disabled;
    public $formType = 'standard';
    public $edit_mode;

    protected $typeOptions = [
        ['value' => 'MIXER', 'title' => 'MIXER'],
        ['value' => 'VB', 'title' => 'VB'],
        ['value' => 'PRE', 'title' => 'PRE'],
        ['value' => 'BREAK', 'title' => 'BREAK']
    ];
    protected $MetaTypeOptions = [
        ['value' => 'GFX', 'title' => 'GFX'],
        ['value' => 'KEY', 'title' => 'KEY'],
        ['value' => 'BG', 'title' => 'BG'],
        ['value' => 'AUDIO', 'title' => 'AUDIO']
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
    protected $mixerKeys = [
        ['value' => 'KEY1', 'title' => 'KEY1'],
        ['value' => 'KEY2', 'title' => 'KEY2'],
        ['value' => 'KEY3', 'title' => 'KEY3'],
        ['value' => 'KEY4', 'title' => 'KEY4'],
    ];
    protected $rules = [
        'name' => 'required|min:6',
        'email' => 'required|email',
    ];
    protected $listeners = [
        'createMetaRow'     => 'createMetaRow',
        'editRow'           => 'editRow',
        'editMeta'          => 'editMeta',
        'cancel_edit'       => 'cancel_edit',
        'sortingStarted'    => 'lockSorting',
        'sortingEnded'      => 'unlockSorting'
    ];

    public function render()
    {
        return view('livewire.rundownrow-form')->with([
            'rundown'           => $this->rundown,
            'typeOptions'       => $this->typeOptions,
            'MetaTypeOptions'   => $this->MetaTypeOptions,
            'sourceOptions'     => $this->sourceOptions,
            'mixerKeys'         => $this->mixerKeys,
        ]);
    }

    /* Function to create a new rundown row.
    |
    |
    */
    public function submit()
    {
        $duration = to_seconds($this->duration);
        $rows = Rundown_rows::where('rundown_id', $this->rundown->id)->get();
        $colors = unserialize(Settings::where('id', 1)->first()->colors);
        if ($rows->isEmpty()){
            $before_in_table = NULL;
            $color = $colors[0];
        }
        else {
            $before_in_table    = sort_rows($rows)[1];
            $color              = array_search( $rows->where('id', $before_in_table)->first()->color, $colors ) + 1;
            $color              = $colors[$color%count($colors)];
        }
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
        $this->formType = 'standard';
        $row = Rundown_rows::find($id);
        if($row !== NULL) {
            if ($this->edit_mode == 'row') $this->cancel_edit();
            if ($this->edit_mode == 'meta') $this->cancel_meta();
            $row->locked_by = Auth::user()->name;
            $row->locked_at = Carbon::now()->toDateTimeString();
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
            $this->edit_mode = 'row';
            $this->emit('in_edit_mode', true);
        }
    }

    public function update(){
        $row = Rundown_rows::find($this->rundown_row_id);
        if($row !== NULL){
            $row->story            = $this->story;
            $row->talent           = $this->talent;
            $row->cue              = $this->cue;
            $row->source           = $this->source;
            $row->audio            = $this->audio;
            $row->duration         = to_seconds($this->duration);
            $row->autotrigg        = $this->autotrigg;
            $row->locked_by = NULL;
            $row->locked_at = NULL;
            $row->save();
        }
        event(new RundownEvent(['type' => 'row_updated', 'id' => $this->rundown_row_id], $this->rundown->id));
        $this->resetForm();
        $this->edit_mode = NULL;
        $this->emit('in_edit_mode', false);
    }

    public function cancel_edit(){
        $row = Rundown_rows::find($this->rundown_row_id);
        if($row) {
            $row->locked_by = NULL;
            $row->locked_at = NULL;
            $row->save();
        }
        event(new RundownEvent(['type' => 'cancel_edit', 'id' => $this->rundown_row_id], $this->rundown->id));
        $this->resetForm();
        $this->edit_mode = NULL;
        $this->emit('in_edit_mode', false);
    }

    /*
    |
    |
    |  Meta row functions
    |
    |
    /* Displays the form to create a new rundown_meta_row model. */
    public function createMetaRow($id)
    {
        $this->rundown_row_id   = $id;
        $this->formType         = 'meta';
        $this->type             = 'GFX';
        $this->source           = '';
        $this->formAction       = 'submit_meta';
    }

    /* Stores a newly created rundown_meta_row in storage. */
    public function submit_meta()
    {  
        $duration   = to_seconds($this->duration);
        $delay      = to_seconds($this->delay);
        Rundown_meta_rows::create([
            'rundown_rows_id'   => $this->rundown_row_id,
            'title'             => $this->story,
            'type'              => $this->type,
            'source'            => $this->source,
            'delay'             => $delay,
            'duration'          => $duration,
            'data'              => $this->metaData,        
        ]);
        $this->resetForm();
        event(new RundownEvent(['type'=> 'render', 'id' => $this->rundown_row_id], $this->rundown->id));
    }

    /* Displays the form to edit a rundown_meta_row. */
    public function editMeta($id){
        
        $row = Rundown_meta_rows::find($id);
        if($row !== NULL) {
            if ($this->edit_mode == 'row') $this->cancel_edit();
            if ($this->edit_mode == 'meta') $this->cancel_meta();
            $row->locked_by = Auth::user()->name;
            $row->locked_at = Carbon::now()->toDateTimeString();
            $row->save();
            $this->rundown_meta_row_id  = $id;
            $this->story                = $row->title;
            $this->type                 = $row->type;
            $this->source               = $row->source;
            $this->duration             = gmdate('H:i:s', $row->duration);
            $this->delay                = gmdate('H:i:s', $row->delay);
            $this->metaData             = $row->data;

            $this->formType             = 'meta';
            $this->header               = 'rundown.edit_meta';
            $this->submit_btn_label     = 'rundown.update';
            $this->formAction           = 'update_meta';
            $this->type_disabled        = 'disabled';
            $this->edit_mode            = 'meta';
        
            event(new RundownEvent(['type' => 'edit_meta', 'id' => $id], $this->rundown->id));
            $this->emit('in_edit_mode', true);
            $this->dispatchBrowserEvent('set_duration_input', ['newTime' => $this->duration]);
        }
    }

    /* Updates a rundown_meta_row in DB */ 
    public function update_meta()
    {
        $row = Rundown_meta_rows::find($this->rundown_meta_row_id);
        if($row !== NULL){
            $row->title            = $this->story;
            $row->source           = $this->source;
            $row->duration         = to_seconds($this->duration);
            $row->delay            = to_seconds($this->delay);
            $row->data             = $this->metaData;
            $row->locked_by = NULL;
            $row->locked_at = NULL;
            $row->save();
        }
        event(new RundownEvent(['type' => 'row_updated', 'id' => $this->rundown_meta_row_id], $this->rundown->id));
        $this->resetForm();
        $this->edit_mode = NULL;
        $this->emit('in_edit_mode', false); 
    }

    /* Resets form to default */
    public function cancel_meta(){
        $row = Rundown_meta_rows::find($this->rundown_meta_row_id);
        if($row !== NULL){
            $row->locked_by = NULL;
            $row->locked_at = NULL;
            $row->save();
        }
        event(new RundownEvent(['type' => 'cancel_meta_edit', 'id' => $this->rundown_meta_row_id], $this->rundown->id));
        $this->resetForm();
        $this->edit_mode = NULL;
        $this->emit('in_edit_mode', false);
    }

    /*
    |
    |
    |  Form handlers
    |
    |
    /* Resets form to default */ 
    private function resetForm(){
        $this->reset(['story', 'talent', 'cue', 'source', 'audio', 'duration', 'rundown_meta_row_id', 'rundown_row_id', 'metaData', 'type_disabled']);
        $this->type                     = 'MIXER';
        $this->autotrigg                = 1;
        $this->header                   = 'rundown.new_row';
        $this->submit_btn_label         = 'rundown.create';
        $this->formAction               = 'submit';
        $this->formType                 = 'standard';
        $this->delay                    = '00:00:00';
        $this->mediabowser              = 'media';
        $this->dispatchBrowserEvent('set_duration_input', ['newTime' => '']);
    }

    /* Disable sorting functionality in rundown table */
    public function lockSorting($code){
        $rundown = Rundowns::find($this->rundown->id);
        if($rundown !== NULL) {
            $rundown->sortable = 0;
            $rundown->save();
        }
        event(new RundownEvent(['type' => 'lockSorting', 'code' => $code], $this->rundown->id));
    }

    /* Enables sorting functionality in rundown table */
    public function unlockSorting(){
        $rundown = Rundowns::find($this->rundown->id);
        if($rundown !== NULL) {
            $rundown->sortable = 1;
            $rundown->save();
        }
        event(new RundownEvent(['type' => 'unlockSorting'], $this->rundown->id));
    }

    /* Sets values in form depending on type selected
    Triggers when type select is changed */
    public function typeChange() {
        switch($this->type){
            case 'MIXER':
                $this->source = 'CAM1';
            break;
            case 'VB': 
                $this->source = '';
            break;
        }
    }
}
