<div class="card-body">           
    <form method="POST" wire:submit.prevent="submit">
        <div class="form-row">
            <x-Forms.Input name='story' wrapClass='col-2' label="{{ __('rundown.story') }}" wire="story" inputClass=""/>
            <div class="form-group col-1">
                <label for="inputType">{{ __('rundown.type') }}</label>
                <select wire:model="type" wire:change="typeChange" class="form-control form-control-sm shadow-none" id="inputType">
                    <option value="MIXER" selected>MIXER</option>
                    <option value="VB">VB</option>
                    <option value="PRE">PRE BLOCK</option>
                    <option value="BREAK">BREAK</option>
                </select>
            </div>
@switch($type)
    @case('MIXER')
            
            <div class="form-group col">
                <label for="inputTalent">{{ __('rundown.talent') }}</label>
                <input type="text" wire:model="talent" class="form-control form-control-sm shadow-none" id="inputTalent">
            </div>
            <div class="form-group col-2">
                <label for="inputCue">{{ __('rundown.cue') }}</label>
                <input type="text" wire:model="cue" class="form-control form-control-sm shadow-none" id="inputCue">
            </div>
            <div class="form-group col">
                <label for="inputSource">{{ __('rundown.source') }}</label>
                <select wire:model="source" id="inputSource" class="form-control form-control-sm shadow-none" >
                    <option value="CAM1">CAM1</option>
                    <option value="CAM2">CAM2</option>
                    <option value="CAM3">CAM3</option>
                    <option value="CAM4">CAM4</option>
                    <option value="CAM5">CAM5</option>
                    <option value="CAM6">CAM6</option>
                    <option value="CAM7">CAM7</option>
                    <option value="CAM8">CAM8</option>
                    <option value="CAM9">CAM9</option>
                    <option value="CAM10">CAM10</option>
                    <option value="BLK">BLK</option>
                    <option value="BARS">BARS</option>
                    <option value="SSRC">SSRC</option>
                </select>
            </div>
            <div class="form-group col">
                <label for="inputAudio">{{ __('rundown.audio') }}</label>
                <input type="text" wire:model="audio" class="form-control form-control-sm shadow-none" id="inputAudio">
            </div>
            <div class="form-group col">
                <label for="inputDuration">{{ __('rundown.duration') }}</label>
                <input type="time" wire:model="duration" step="1" class="form-control form-control-sm shadow-none" id="inputDuration">
            </div>
            <div class="form-group col">
                <label for="inputAutotrigg">{{ __('rundown.triggering') }}</label>
                <input type="checkbox" wire:model="autotrigg" class="form-control form-control-sm shadow-none" id="inputAutotrigg">
            </div>
    @break
    @case('VB')
            <div class="form-group col">
                <label for="inputTalent">{{ __('rundown.talent') }}</label>
                <input type="text" wire:model="talent" class="form-control form-control-sm shadow-none" id="inputTalent">
            </div>
            <div class="form-group col">
                <label for="inputSource">{{ __('rundown.source') }}</label>
                <div class="input-group">
                    <input type="text" wire:model="source" class="form-control form-control-sm shadow-none" placeholder="mediefil" aria-describedby="source-search">
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-dark" type="button" id="source-search"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="form-group col">
                <label for="inputAudio">{{ __('rundown.audio') }}</label>
                <input type="text" wire:model="audio" class="form-control form-control-sm shadow-none" id="inputAudio">
            </div>
            <div class="form-group col">
                <label for="inputDuration">{{ __('rundown.duration') }}</label>
                <input type="time" wire:model="duration" step="1" class="form-control form-control-sm shadow-none" id="inputDuration">
            </div>
            <div class="form-group col">
                <label for="inputAutotrigg">{{ __('rundown.triggering') }}</label>
                <input type="checkbox" wire:model="autotrigg" class="form-control form-control-sm shadow-none" id="inputAutotrigg">
            </div>
    @break
    @case('PRE')
            <input type="hidden" wire:model="duration" value="0" >
    @break
    @case('BREAK')
            <div class="form-group col">
                <label for="inputDuration">{{ __('rundown.duration') }}</label>
                <input type="time" wire:model="duration" step="1" class="form-control form-control-sm shadow-none" id="inputDuration">
            </div>
    @break

    @default
        
@endswitch
            <div class="form-group col">
                <button type="submit" name="submit" class="btn btn-dark btn-sm mt-4 float-right">{{ __('rundown.create') }}</button>
            </div>
        </div>
    </form>   
</div>