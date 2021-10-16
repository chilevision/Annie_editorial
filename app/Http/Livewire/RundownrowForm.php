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
    public $type = 'MIXER';
    public $source = 'CAM1';
    public $audio;
    public $duration;
    public $autotrigg = 0;

    protected $colors = ['930000', 'e05500', 'da8f00', '897800', '39000d', '004334', '003a48', '007792', '47295e'];

    public function render()
    {
        return view('livewire.rundownrow-form')->with('rundown', $this->rundown);
    }

    protected $rules = [
        'name' => 'required|min:6',
        'email' => 'required|email',
    ];

    public function submit()
    {
        $duration = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $this->duration);
        sscanf($duration, "%d:%d:%d", $hours, $minutes, $seconds);
        $duration = $hours * 3600 + $minutes * 60 + $seconds;

        $position = Rundown_rows::where('rundown_id', $this->rundown->id)->count();
        $color = $this->colors[$position%9];
        //$this->validate();

        // Execution doesn't reach here if validation fails.

        Rundown_rows::create([
            'rundown_id'    => $this->rundown->id,
            'position'      => $position,
            'color'         => $color,
            'story'         => $this->story,
            'talent'        => $this->talent,
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
