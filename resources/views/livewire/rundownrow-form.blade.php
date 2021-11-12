<div class="card text-white bg-custom mb-3 mt-5">
    <div class="card-header">{{ __( $header ) }}</div>
    <div class="card-body">

@if ($formType == 'standard')


        <form method="POST" wire:submit.prevent="{{ $formAction }}">
            <div class="form-row">
                <x-Forms.input type="text" name="story" value="{{ $story }}" wrapClass="col-2" wire="story" label="rundown.story" inputClass="form-control-sm" />
                <x-Forms.Select name="type" wrapClass="col-1" selectClass="form-control-sm" disabled="{{ $type_disabled }}" :wire="[['type' => 'model', 'target' => 'type'],['type' => 'change', 'target' => 'typeChange']]" label="rundown.type" :options="$typeOptions" />
@switch($type)

    @case('MIXER')
                <x-Forms.input type="text" name="talent" value="{{ $talent }}" wrapClass="col" wire="talent" label="rundown.talent" inputClass="form-control-sm" />
                <x-Forms.input type="text" name="cue" value="{{ $cue }}" wrapClass="col-2" wire="cue" label="rundown.cue" inputClass="form-control-sm" />
                <x-Forms.Select name="source" wrapClass="col" selectClass="form-control-sm" wire="source" label="rundown.source" :options="$sourceOptions" />
                <x-Forms.input type="text" name="audio" value="{{ $audio }}" wrapClass="col" wire="audio" label="rundown.audio" inputClass="form-control-sm" />
                <x-Forms.time name="duration" value="{{ $duration }}" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" step="1" />
                <x-Forms.input type="checkbox" name="autotrigg" value="{{ $autotrigg }}" wrapClass="mr-5 ml-2" wire="autotrigg" label="rundown.triggering" inputClass="rundown-checkbox" />
        @break

    @case('VB')
                <x-Forms.input type="text" name="talent" value="{{ $talent }}" wrapClass="col" wire="talent" label="rundown.talent" inputClass="form-control-sm" />
                <x-Forms.input type="text" name="cue" value="{{ $cue }}" wrapClass="col-2" wire="cue" label="rundown.cue" inputClass="form-control-sm" />
                <x-Forms.source type="text" name="source" value="{{ $source }}" wrapClass="col" wire="source" sourceQuery="{{ $mediabowser }}" label="rundown.source" inputClass="form-control-sm" />
                <x-Forms.input type="text" name="audio" value="{{ $audio }}" wrapClass="col" wire="audio" label="rundown.audio" inputClass="form-control-sm" />
                <x-Forms.time name="duration" value="{{ $duration }}" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" step="1" />
                <x-Forms.input type="checkbox" name="autotrigg" wrapClass="mr-5 ml-2" wire="autotrigg" label="rundown.triggering" inputClass="rundown-checkbox" />
        @break

    @case('PRE')
                <input type="hidden" wire:model="duration" value="0" >
        @break

    @case('BREAK')
                <x-Forms.time name="duration" value="{{ $duration }}" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" step="1" />
        @break
                
@endswitch
@if ($formAction == 'update')
                <x-Forms.input type="button" name="cancel" value="cancel" wrapClass="mr-2 rundown-form-buttons" wire="cancel_edit" label="{{ __('rundown.cancel') }}" inputClass="btn-secondary btn-sm mt-4 float-right" />
@endif
                <x-Forms.input type="submit" name="submit" wrapClass="rundown-form-buttons" label="{{ __($submit_btn_label) }}" inputClass="btn-dark btn-sm mt-4 float-right" />
            </div>
        </form>


@elseif ($formType == 'meta')
     

        <form method="POST" wire:submit.prevent="{{ $formAction }}">
            <div class="form-row">
                <x-Forms.input type="text" name="title" value="{{ $story }}" wrapClass="col-2" wire="story" label="rundown.title" inputClass="form-control-sm" />
                <x-Forms.Select name="type" wrapClass="col-1" selectClass="form-control-sm" disabled="{{ $type_disabled }}" :wire="[['type' => 'model', 'target' => 'type'],['type' => 'change', 'target' => 'typeChange']]" label="rundown.type" :options="$MetaTypeOptions" />
@if ($type == 'KEY')
                <x-Forms.Select name="source" wrapClass="col-1" selectClass="form-control-sm" wire="source" label="rundown.source" :options="$mixerKeys" />
@else
                <x-Forms.source type="text" name="source" value="{{ $source }}" wrapClass="col" wire="source" sourceQuery="{{ $mediabowser }}" label="rundown.source" inputClass="form-control-sm" />
@endif
                <div class="form-group">
                    <label for="comment">Data</label>
                    <textarea class="form-control" rows="2" id="comment" wire:model="metaData"></textarea>
                </div>
                <x-Forms.time name="delay" value="{{ $delay }}" wrapClass="col" wire="delay" label="rundown.delay" inputClass="form-control-sm" step="1" />
                <x-Forms.time name="duration" value="{{ $duration }}" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" step="1" />
                <x-Forms.input type="button" name="cancel" value="cancel" wrapClass="mr-2 rundown-form-buttons" wire="cancel_meta" label="{{ __('rundown.cancel') }}" inputClass="btn-secondary btn-sm mt-4 float-right" />
                <x-Forms.input type="submit" name="submit" wrapClass="rundown-form-buttons" label="{{ __($submit_btn_label) }}" inputClass="btn-dark btn-sm mt-4 float-right" />
            </div>
        </form>

@endif


    </div>
</div>