<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;
use App\Models\Rundowns;
use App\Models\Settings;
use App\Events\RundownEvent;
use App\Models\Rundown_meta_rows;
use Livewire\WithFileUploads;

class RundownrowForm extends Component
{
    use WithFileUploads;

    public $rundown;
    public $rundown_row_id;
    public $rundown_meta_row_id;
    public $story;
    public $talent;
    public $cue;
    public $type = 'MIXER';
    public $file_type; 
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
    public $dataBtn;

    public $xml;
    public $pane = 'editor';
    public $unit = 'time';

    protected $typeOptions = [
        ['value' => 'MIXER', 'title' => 'MIXER'],
        ['value' => 'VB', 'title' => 'VB'],
        ['value' => 'GFX', 'title' => 'GFX'],
        ['value' => 'PRE', 'title' => 'PRE'],
        ['value' => 'BREAK', 'title' => 'BREAK']
    ];
    public $metaTypeOptions = [
        ['value' => 'GFX', 'title' => 'GFX'],
        ['value' => 'MIXER', 'title' => 'MIXER'],
        ['value' => 'KEY', 'title' => 'KEY'],
        ['value' => 'BG', 'title' => 'BG'],
        ['value' => 'MEDIA', 'title' => 'MEDIA']
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
            'metaTypeOptions'   => $this->metaTypeOptions,
            'sourceOptions'     => $this->sourceOptions,
            'mixerKeys'         => $this->mixerKeys,
        ]);
        $this->emit('contentUpdated');
    }

    /* Function to create a new rundown row.
    |
    |
    */
    public function submit()
    {
        $rules = [
            'story'         => 'required|min:3|max:40',
            'duration'      => 'nullable|date_format:H:i:s',
            'talent'        => 'nullable|max:30',
            'cue'           => 'nullable|max:40',
            'audio'         => 'nullable|max:30'
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
        $notes = '';
        if ($this->type == 'GFX'){
            if ($this->metaData){
                $notes = $this->metaData;
            }
            switch ($this->file_type){
                case 'MOVIE' : $this->type .= '/M'; 
                break;
                case 'STILL' : $this->type .= '/S';
                break;
            }
        }
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
            'autotrigg'         => $this->autotrigg,
            'cam_notes'         => $notes
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
            $this->source           = $row->source;
            $this->audio            = $row->audio;
            $this->duration         = gmdate('H:i:s', $row->duration);
            $this->file_fps         = $row->file_fps;
            $this->autotrigg        = $row->autotrigg;

            $this->header           = 'rundown.edit_row';
            $this->submit_btn_label = 'rundown.update';
            $this->formAction       = 'update';
            $this->type_disabled    = 'disabled';

            if(strpos($row->type, "/")){
                $type = preg_split("#/#", $row->type);
                $this->type = $type[0];
                $this->file_type = getFileType($type[1]);
            }else{
                $this->type = $row->type;
            }
            if ($this->type == 'GFX') $this->mediabowser = 'TEMPLATE';
            $this->edit_mode = 'row';
            $this->emit('lock', 'row', $id, 1);
            $this->emit('in_edit_mode', true);
        }
    }

    public function update(){
        $rules = [
            'story'         => 'required|min:3|max:40',
            'duration'      => 'nullable|date_format:H:i:s',
            'talent'        => 'nullable|max:30',
            'cue'           => 'nullable|max:40',
            'audio'         => 'nullable|max:30'
        ];
        $this->validate($rules);
        if ($this->type == 'GFX'){
            switch ($this->file_type){
                case 'MOVIE' : $this->type .= '/M'; 
                break;
                case 'STILL' : $this->type .= '/S';
                break;
            }
        }
        $row = Rundown_rows::find($this->rundown_row_id);
        if($row !== NULL){
            $row->story            = $this->story;
            $row->type             = $this->type;
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
        if ($this->edit_mode == 'meta'){
            $this->cancel_meta();
            return;
        }
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
    public function createMetaRow($id, $type)
    {
        if ($type == 'VB'){
            unset($this->metaTypeOptions[1]);
        }
        else{
            $this->metaTypeOptions[1] = ['value' => 'MIXER', 'title' => 'MIXER'];
        }
        $this->rundown_row_id   = $id;
        $this->formType         = 'meta';
        $this->type             = 'GFX';
        $this->source           = '';
        $this->formAction       = 'submit_meta';
        $this->mediabowser      = 'TEMPLATE';
        $this->dataBtn          = 'gfx';
    }

    /* Stores a newly created rundown_meta_row in storage. */
    public function submit_meta()
    {
        $rules = [
            'story'         => 'required|min:3|max:40',
            'duration'      => 'nullable|date_format:H:i:s',
        ];
        $this->validate($rules);
        $duration   = to_seconds($this->duration);
        $delay      = to_seconds($this->delay);
        if ($this->type == 'GFX' || $this->type == 'MEDIA' || $this->type == 'BG'){
            switch ($this->file_type){
                case 'MOVIE' : 
                    $this->type .= '/M'; 
                    break;
                case 'STILL' : 
                    $this->type .= '/S';
                    break;
                case 'AUDIO' :
                    $this->type .= '/A';
                    break;
            }
        }
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
            if(strpos($row->type, "/")){
                $type = preg_split("#/#", $row->type);
                $this->type = $type[0];
                $this->file_type = getFileType($type[1]);
            }else{
                $this->type = $row->type;
            }
            $this->typeChange();
            $this->source               = $row->source;
            $this->duration             = gmdate('H:i:s', $row->duration);
            if (strpos($row->delay, '.') !== false){
                $this->delay            = $row->delay*1000;
                $this->unit             = 'number';
            }
            else{
                $this->delay            = gmdate('H:i:s', $row->delay);
                $this->unit             = 'time';
            }
            $this->metaData             = $row->data;

            $this->formType             = 'meta';
            $this->header               = 'rundown.edit_meta';
            $this->submit_btn_label     = 'rundown.update';
            $this->formAction           = 'update_meta';
            $this->type_disabled        = 'disabled';
            $this->edit_mode            = 'meta';

        
            $this->emit('lock', 'meta_row', $id, 1);
            $this->emit('in_edit_mode', true);
            $this->dispatchBrowserEvent('set_duration_input', ['newTime' => $this->duration]);
        }
    }

    /* Updates a rundown_meta_row in DB */ 
    public function update_meta()
    {
        $rules = [
            'story'         => 'required|min:3|max:40',
            'duration'      => 'nullable|date_format:H:i:s',
        ];
        $this->validate($rules);
        $row = Rundown_meta_rows::find($this->rundown_meta_row_id);
        if($row !== NULL){
            if ($this->type == 'GFX' || $this->type == 'MEDIA' || $this->type == 'BG'){
                switch ($this->file_type){
                    case 'MOVIE' : 
                        $this->type .= '/M'; 
                        break;
                    case 'STILL' : 
                        $this->type .= '/S';
                        break;
                    case 'AUDIO' :
                        $this->type .= '/A';
                        break;
                }
            }
            $row->title            = $this->story;
            $row->type             = $this->type;
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
        $this->autotrigg                = 0;
        $this->header                   = 'rundown.new_row';
        $this->submit_btn_label         = 'rundown.create';
        $this->formAction               = 'submit';
        $this->formType                 = 'standard';
        $this->delay                    = '00:00:00';
        $this->mediabowser              = 'MOVIE';
        $this->edit_mode                = NULL;
        $this->dataBtn                  = 'gfx';
        $this->unit                     = 'time';
        $this->file_type                = '';

        $this->dispatchBrowserEvent('set_duration_input', ['newTime' => '']);
        $this->resetErrorBag();
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

    /* Sets values in form depending on type selected
    Triggers when type select is changed */
    public function typeChange() {
        $this->source = '';
        switch($this->type){
            case 'MIXER': 
                $this->source = 'CAM1';
                $this->dataBtn = 'notes';
                break;
            case 'VB':
                $this->source = '';
                $this->dataBtn = null;
                $this->mediabowser = 'MOVIE';
                break;
            case 'MEDIA': 
                $this->mediabowser = 'MEDIA';
                $this->dataBtn = null;
                break;
            case 'BG':
                $this->mediabowser = 'BG';
                $this->dataBtn = null;
                break;
            case 'GFX':
                $this->mediabowser = 'TEMPLATE';
                $this->dataBtn = 'gfx';
                break;
            case 'KEY':
                $this->dataBtn = null;
                break;
        }
    }

    /* Updates source value when a file is selected in form*/
    public function updateSource($value, $duration, $fps, $type)
    {
        $this->source       = $value;
        $this->file_fps     = $fps;
        $this->file_type    = $type;
        if($duration != null){
            $this->duration = $duration;
        }
    }

    public function changePane($pane)
    {
        $this->pane = $pane;
    }

    public function save()
    {
        $this->validate([
            'xml' => 'mimes:application/xml,xml|max:10000'
        ]);
        $this->xml->store('presets');
    }

    public function unit($unit)
    {
        $this->unit = $unit;
    }
}
