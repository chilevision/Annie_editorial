<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;
use App\Models\Rundowns;
use App\Models\Settings;
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
    public $file_fps;
    public $delay = '00:00:00';
    public $autotrigg = 0;
    public $metaData = '';
    public $mediabowser = 'MOVIE';

    public $header = 'rundown.new_row';
    public $submit_btn_label = 'rundown.create';
    public $formAction = 'submit';
    public $type_disabled;
    public $formType = 'standard';
    public $edit_mode;
    public $sourceOptions;
    public $mixerKeys;

    protected $typeOptions = [
        ['value' => 'MIXER', 'title' => 'MIXER'],
        ['value' => 'VB', 'title' => 'VB'],
        ['value' => 'PRE', 'title' => 'PRE'],
        ['value' => 'BREAK', 'title' => 'BREAK']
    ];
    protected $MetaTypeOptions = [
        ['value' => 'GFX', 'title' => 'GFX'],
        ['value' => 'MIXER', 'title' => 'MIXER'],
        ['value' => 'KEY', 'title' => 'KEY'],
        ['value' => 'BG', 'title' => 'BG'],
        ['value' => 'AUDIO', 'title' => 'AUDIO']
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
        'sortingEnded'      => 'unlockSorting',
        'updateSource'      => 'updateSource'
    ];

    public function mount()   
    {
        $settings = Settings::where('id', 1)->first();
        $sourceOptions    = unserialize($settings->mixer_inputs);
        $mixerKeys        = unserialize($settings->mixer_keys);
        $this->sourceOptions = [];
        if (is_array($sourceOptions) && !empty($sourceOptions)){
            foreach ($sourceOptions as $option){
                array_push($this->sourceOptions, ['value' => $option, 'title' => $option]);
            }
        }
        $this->mixerKeys = [];
        if (is_array($mixerKeys) && !empty($mixerKeys)){
            foreach ($mixerKeys as $option){
                array_push($this->mixerKeys, ['value' => $option, 'title' => $option]);
            }
        }
    }

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
        $rules = [
            'story'      => 'required',
        ];
        $this->validate($rules);
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
            'file_fps'          => $this->file_fps,
            'autotrigg'         => $this->autotrigg
        ]);
        $this->resetForm();
        event(new RundownEvent(['type'=> 'render', 'id' => $row->id], $this->rundown->id));
    }
    /* Function to edit a rundown row.
    |
    |
    */
    public function editRow($id){
        $this->formType = 'standard';
        $row = Rundown_rows::find($id);
        if($row !== NULL) {
            if ($this->edit_mode == 'row') $this->cancel_edit();
            if ($this->edit_mode == 'meta') $this->cancel_meta();
            $this->rundown_row_id   = $id;
            $this->story            = $row->story;
            $this->talent           = $row->talent;
            $this->cue              = $row->cue;
            $this->type             = $row->type;
            $this->source           = $row->source;
            $this->audio            = $row->audio;
            $this->duration         = gmdate('H:i:s', $row->duration);
            $this->file_fps         = $row->file_fps;
            $this->autotrigg        = $row->autotrigg;

            $this->header           = 'rundown.edit_row';
            $this->submit_btn_label = 'rundown.update';
            $this->formAction       = 'update';
            $this->type_disabled    = 'disabled';
        
            $this->edit_mode = 'row';
            $this->emit('lock', 'row', $id, 1);
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
            $row->file_fps         = $this->file_fps;
            $row->autotrigg        = $this->autotrigg;
            $row->locked_by        = NULL;
            $row->locked_at        = NULL;
            $row->save();
        }
        $this->emit('lock', 'row', $this->rundown_row_id);
        $this->resetForm();
        $this->emit('in_edit_mode', false);
    }

    public function cancel_edit(){    
        $this->emit('lock', 'row', $this->rundown_row_id);    
        $this->emit('in_edit_mode', false);
        $this->resetForm();
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

            switch ($this->type){
                case 'AUDIO'    : $this->mediabowser = 'AUDIO';     break;
                case 'BG'       : $this->mediabowser = 'BG';        break;
                case 'GFX'      : $this->mediabowser = 'TEMPLATE';  break;
                default         : $this->mediabowser = 'MOVIE';     break;
            }
        
            $this->emit('lock', 'meta_row', $id, 1);
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
        $this->emit('lock', 'meta_row', $this->rundown_meta_row_id);
        $this->resetForm();
        $this->emit('in_edit_mode', false); 
    }

    /* Resets form to default */
    public function cancel_meta(){
        $this->emit('lock', 'meta_row', $this->rundown_meta_row_id);
        $this->emit('in_edit_mode', false);
        $this->resetForm();
    }

    /*
    |
    |
    |  Form handlers
    |
    |
    /* Resets form to default */ 
    private function resetForm(){
        $this->reset(['story', 'talent', 'cue', 'source', 'audio', 'duration', 'file_fps', 'rundown_meta_row_id', 'rundown_row_id', 'metaData', 'type_disabled']);
        $this->type                     = 'MIXER';
        $this->autotrigg                = 1;
        $this->header                   = 'rundown.new_row';
        $this->submit_btn_label         = 'rundown.create';
        $this->formAction               = 'submit';
        $this->formType                 = 'standard';
        $this->delay                    = '00:00:00';
        $this->mediabowser              = 'MOVIE';
        $this->dispatchBrowserEvent('set_duration_input', ['newTime' => '']);
        $this->edit_mode = NULL;
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
        $this->source = '';
        switch($this->type){
            case 'MIXER'    : $this->source = 'CAM1';           break;
            case 'VB'       : $this->source = '';               break;
            case 'AUDIO'    : $this->mediabowser = 'AUDIO';     break;
            case 'BG'       : $this->mediabowser = 'BG';        break;
            case 'GFX'      : $this->mediabowser = 'TEMPLATE';  break;
        }
    }

    /* Updates source value when a file is selected in form*/
    public function updateSource($value, $duration, $fps)
    {
        $this->source       = $value;
        $this->file_fps     = $fps;
        if($duration != null){
            $this->duration = $duration;
        }
    }
}
