<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Rundown_rows;
use App\Events\RundownEvent;


class RundownrowForm extends Component
{
    public $rundown;
    public $story;
    public $talent;
    public $cue;
    public $type = 'MIXER';
    public $source = 'CAM1';
    public $audio;
    public $duration;
    public $autotrigg = 0;
    public $formAction = 'submit';

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

    public function render()
    {
        return view('livewire.rundownrow-form')->with([
            'rundown'       => $this->rundown,
            'typeOptions'   => $this->typeOptions,
            'sourceOptions' => $this->sourceOptions
        ]);
    }

    public function submit()
    {
        $duration = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $this->duration);
        sscanf($duration, "%d:%d:%d", $hours, $minutes, $seconds);
        $duration = $hours * 3600 + $minutes * 60 + $seconds;
        $position = Rundown_rows::where('rundown_id', $this->rundown->id)->count();
        $color = $this->colors[$position%count($this->colors)];
        //$this->validate();

        // Execution doesn't reach here if validation fails.

        Rundown_rows::create([
            'rundown_id'    => $this->rundown->id,
            'position'      => $position,
            'color'         => $color,
            'story'         => $this->story,
            'talent'        => $this->talent,
            'cue'           => $this->cue,
            'type'          => $this->type,
            'source'        => $this->source,
            'audio'         => $this->audio,
            'duration'      => $duration,
            'autotrigg'     => $this->autotrigg
        ]);
        $this->reset(['story', 'talent', 'source', 'audio', 'duration']);
        $this->type = 'MIXER';
        $this->autotrigg = 0;
        event(new RundownEvent('render', $this->rundown->id));
    }

    public function update(){
        dd('update');
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
}
