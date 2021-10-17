<div class="card-body">           
    <form method="POST" wire:submit.prevent="{{ $formAction }}">
        <div class="form-row">
            <x-Forms.Input type="text" name="story" value="{{ $story }}" wrapClass="col-2" label="rundown.story" wire="story" inputClass=""/>
            <x-Forms.Select name="story" wrapClass="col-1" label="rundown.type" :wire="[['type' => 'model', 'target' => 'type'], ['type' => 'change', 'target' => 'typeChange']]" selectClass="" :options="$typeOptions"/>
@switch($type)
    @case('MIXER')
            <x-Forms.Input type="text" name="talent" value="{{ $talent }}" wrapClass="col" label="rundown.talent" wire="talent" inputClass=""/>
            <x-Forms.Input type="text" name="cue" value="{{ $cue }}" wrapClass="col-2" label="rundown.cue" wire="cue" inputClass=""/>
            <x-Forms.Select name="source" wrapClass="col" label="rundown.source" wire="source" selectClass="" :options="$sourceOptions"/>
            <x-Forms.Input type="text" name="audio" value="{{ $audio }}" wrapClass="col" label="rundown.audio" wire="audio" inputClass=""/>
            <x-Forms.Input type="time" name="duration" value="{{ $audio }}" wrapClass="col" label="rundown.duration" wire="duration" inputClass=""/>
            <x-Forms.Input type="checkbox" name="autotrigg" value="" wrapClass="col" label="rundown.triggering" wire="" inputClass=""/>
    @break
    @case('VB')
            <x-Forms.Input type="text" name="talent" value="{{ $talent }}" wrapClass="col" label="rundown.talent" wire="talent" inputClass=""/>
            <div class="form-group col">
                <label for="inputSource">{{ __('rundown.source') }}</label>
                <div class="input-group">
                    <input type="text" wire:model="source" class="form-control form-control-sm shadow-none" placeholder="mediefil" aria-describedby="source-search">
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-dark" type="button" id="source-search"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
            <x-Forms.Input type="time" name="duration" value="" wrapClass="col" label="rundown.duration" wire="duration" inputClass=""/>
            <x-Forms.Input type="checkbox" name="autotrigg" value="" wrapClass="col" label="rundown.triggering" wire="" inputClass=""/>
    @break
    @case('PRE')
            <input type="hidden" wire:model="duration" value="0" >
    @break
    @case('BREAK')
            <x-Forms.Input type="time" name="duration" value="" wrapClass="col" label="rundown.duration" wire="duration" inputClass=""/>
    @break   
@endswitch
            <x-Forms.Input type="submit" name="submit" value="" wrapClass="col" label="rundown.create" wire="" inputClass="btn-dark btn-sm mt-4 float-right"/>
        </div>
    </form>   
</div>