<div class="card-body">           
    <form method="POST" wire:submit.prevent="submit">
        <div class="form-row">
            <x-Forms.input type="text" name="story" value="" wrapClass="col-2" wire="story" label="rundown.story" inputClass="form-control-sm" />
            <x-Forms.Select name="type" wrapClass="col-1" selectClass="form-control-sm" :wire="[['type' => 'model', 'target' => 'type'],['type' => 'change', 'target' => 'typeChange']]" label="rundown.type" :options="$typeOptions" />
@switch($type)

@case('MIXER')
            <x-Forms.input type="text" name="talent" value="" wrapClass="col" wire="talent" label="rundown.talent" inputClass="form-control-sm" />
            <x-Forms.input type="text" name="cue" value="" wrapClass="col-2" wire="cue" label="rundown.cue" inputClass="form-control-sm" />
            <x-Forms.Select name="source" wrapClass="col" selectClass="form-control-sm" wire="source" label="rundown.source" :options="$sourceOptions" />
            <x-Forms.input type="text" name="audio" value="" wrapClass="col" wire="audio" label="rundown.audio" inputClass="form-control-sm" />
            <x-Forms.input type="time" name="duration" value="" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" />
            <x-Forms.input type="checkbox" name="autotrigg" value="" wrapClass="col" wire="autotrigg" label="rundown.triggering" inputClass="form-control-sm" />
    @break

@case('VB')
            <x-Forms.input type="text" name="talent" value="" wrapClass="col" wire="talent" label="rundown.talent" inputClass="form-control-sm" />
            <x-Forms.input type="text" name="cue" value="" wrapClass="col-2" wire="cue" label="rundown.cue" inputClass="form-control-sm" />
            <x-Forms.source type="text" name="source" value="" wrapClass="col" wire="source" label="rundown.source" inputClass="form-control-sm" />
            <x-Forms.input type="text" name="audio" value="" wrapClass="col" wire="audio" label="rundown.audio" inputClass="form-control-sm" />
            <x-Forms.input type="time" name="duration" value="" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" />
            <x-Forms.input type="checkbox" name="autotrigg" value="" wrapClass="col" wire="autotrigg" label="rundown.triggering" inputClass="form-control-sm" />
    @break

@case('PRE')
            <input type="hidden" wire:model="duration" value="0" >
    @break

@case('BREAK')
            <x-Forms.input type="time" name="duration" value="" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" />
    @break
            
@endswitch
            <x-Forms.input type="submit" name="submit" value="" wrapClass="col" wire="" label="rundown.create" inputClass="btn-dark btn-sm mt-4 float-right" />
        </div>
    </form>   
</div>