<div class="card text-white bg-custom mb-3 mt-5">
    <div class="card-header">
        <ul class="nav nav-pills" id="editor-menu">
            <li class="nav-item">
                <a class="nav-link btn-custom @if($pane == 'editor')active @endif" data-toggle="list" wire:click="changePane('editor')" href="#editor">{{ __('rundown.editor') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-custom @if($pane == 'presets')active @endif" data-toggle="list" wire:click="changePane('presets')" href="#presets">{{ __('rundown.presets') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn-custom @if($pane == 'upload')active @endif" data-toggle="list" wire:click="changePane('upload')" href="#upload">{{ __('rundown.upload') }}</a>
            </li>
        </ul>
    </div>
    <div class="card-body tab-content" id="rundown-editor">
        <div class="tab-pane fade @if($pane == 'editor')active show @endif" id="editor">
        @if ($formType == 'standard')
            <form method="POST" wire:submit.prevent="{{ $formAction }}">
                <div class="form-row">
                    <x-forms.input type="text" name="story" value="{{ $story }}" wrapClass="col-2" wire="story" label="rundown.story" inputClass="form-control-sm" />
                    <x-forms.Select name="type" wrapClass="col-1" selectClass="form-control-sm" disabled="{{ $type_disabled }}" :wire="[['type' => 'model', 'target' => 'type'],['type' => 'change', 'target' => 'typeChange']]" label="rundown.type" :options="$typeOptions" />
                @switch($type)

                @case('MIXER')
                    <x-forms.input type="text" name="talent" value="{{ $talent }}" wrapClass="col" wire="talent" label="rundown.talent" inputClass="form-control-sm" />
                    <x-forms.input type="text" name="cue" value="{{ $cue }}" wrapClass="col-2" wire="cue" label="rundown.cue" inputClass="form-control-sm" />
                    <x-forms.Select name="source" wrapClass="col" selectClass="form-control-sm" wire="source" label="rundown.source" :options="$sourceOptions" />
                    <x-forms.input type="text" name="audio" value="{{ $audio }}" wrapClass="col" wire="audio" label="rundown.audio" inputClass="form-control-sm" />
                    <x-forms.time name="duration" value="{{ $duration }}" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" step="1" />
                    <x-forms.box name="autotrigg" value="1" wrapClass="mr-5 ml-2" wire="autotrigg" label="rundown.triggering" inputClass="rundown-checkbox" />
                @break

                @case('VB')
                    <x-forms.input type="text" name="talent" value="{{ $talent }}" wrapClass="col" wire="talent" label="rundown.talent" inputClass="form-control-sm" />
                    <x-forms.input type="text" name="cue" value="{{ $cue }}" wrapClass="col-2" wire="cue" label="rundown.cue" inputClass="form-control-sm" />
                    <x-forms.source type="text" name="source" value="{{ $source }}" wrapClass="col" wire="source" sourceQuery="{{ $mediabowser }}" label="rundown.source" source="{{ __('rundown.media') }}" inputClass="form-control-sm" modalTarget="casparModal"/>
                    <x-forms.input type="text" name="audio" value="{{ $audio }}" wrapClass="col" wire="audio" label="rundown.audio" inputClass="form-control-sm" />
                    <x-forms.time name="duration" value="{{ $duration }}" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" step="1" />
                    <x-forms.box name="autotrigg" wrapClass="mr-5 ml-2" wire="autotrigg" label="rundown.triggering" inputClass="rundown-checkbox" />
                    <input type="hidden" name="file_fps" wire="file_fps" />
                @break
                @case('GFX')
                    <x-Forms.source type="text" name="source" value="{{ $source }}" wrapClass="col" wire="source" sourceQuery="{{ $mediabowser }}" label="rundown.source" source="{{ __('rundown.template') }}" inputClass="form-control-sm" modalTarget="casparModal"/>
                    <x-Forms.input value="{{ $dataBtn }}" type="button" name="edit_data" wrapClass="rundown-form-buttons" label="{{ __('rundown.edit_data') }}" inputClass="btn-dark btn-sm mt-4 float-right" />
                    <input type="hidden" name="metaData" id="metaData" wire:model="metaData"/>
                    <x-forms.input type="text" name="audio" value="{{ $audio }}" wrapClass="col" wire="audio" label="rundown.audio" inputClass="form-control-sm" />
                    <x-forms.time name="duration" value="{{ $duration }}" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" step="1" />
                    <x-forms.box name="autotrigg" wrapClass="mr-5 ml-2" wire="autotrigg" label="rundown.triggering" inputClass="rundown-checkbox" />
                    <input type="hidden" name="file_fps" wire="file_fps" />
                @case('PRE')
                    <input type="hidden" wire:model="duration" value="0" >
                @break

                @case('BREAK')
                    <x-forms.time name="duration" value="{{ $duration }}" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" step="1" />
                @break
                    
                @endswitch
                @if ($formAction == 'update')
                    <x-forms.input type="button" name="cancel" value="cancel" wrapClass="mr-2 rundown-form-buttons" wire="cancel_edit" label="{{ __('rundown.cancel') }}" inputClass="btn-secondary btn-sm mt-4 float-right" />
                @endif
                    <x-forms.input type="submit" name="submit" wrapClass="rundown-form-buttons" label="{{ __($submit_btn_label) }}" inputClass="btn-dark btn-sm mt-4 float-right" />
                </div>
            </form>


        @elseif ($formType == 'meta')
            <form method="POST" wire:submit.prevent="{{ $formAction }}">
                <div class="form-row">
                    <x-Forms.input type="text" name="title" value="{{ $story }}" wrapClass="col-2" wire="story" label="rundown.title" inputClass="form-control-sm">@error('story') <span class="text-danger">{{ $message }}</span> @enderror</x-Forms.input>
                    <x-Forms.Select name="type" wrapClass="col-1" selectClass="form-control-sm" disabled="{{ $type_disabled }}" :wire="[['type' => 'model', 'target' => 'type'],['type' => 'change', 'target' => 'typeChange']]" label="rundown.type" :options="$metaTypeOptions" />
                @if ($type == 'KEY')
                    <x-Forms.Select name="source" wrapClass="col-1" selectClass="form-control-sm" wire="source" label="rundown.source" :options="$mixerKeys" />
                @elseif ($type == 'MIXER')
                    <x-Forms.Select name="source" wrapClass="col" selectClass="form-control-sm" wire="source" label="rundown.source" :options="$sourceOptions" />
                @else
                    <x-Forms.source type="text" name="source" value="{{ $source }}" wrapClass="col" wire="source" sourceQuery="{{ $mediabowser }}" label="rundown.source" inputClass="form-control-sm" modalTarget="casparModal"/>
                @endif
                @if ($dataBtn)
                    <x-Forms.input value="{{ $dataBtn }}" type="button" name="edit_data" wrapClass="rundown-form-buttons" label="{{ __('rundown.edit_data') }}" inputClass="btn-dark btn-sm mt-4 float-right" />
                    <input type="hidden" name="metaData" id="metaData" wire:model="metaData"/>
                @elseif ($type == 'AUDIO')
                    <x-forms.input type="text" name="metaData" value="{{ $metaData }}" wrapClass="col" wire="metaData" label="rundown.audio" inputClass="form-control-sm" />
                @endif
                @if ($type == 'GFX')
                    <div class="col">
                        @if ($unit == 'time')
                        <x-Forms.time name="delay" value="{{ $delay }}" wrapClass="input-group input-group-custom" wire="delay" label="rundown.delay" inputClass="form-control-sm" step="1"> 
                            <div class="input-group-append">
                                <button class="btn btn-secondary btn-sm" type="button" wire:click="unit('number')" id="button-delay-toggle">hms</button>
                            </div>
                        </x-Forms.time>
                        @elseif ($unit == 'number')
                        <x-Forms.input type="number" name="delay" value="{{ $delay }}" wrapClass="input-group input-group-custom" wire="delay" label="rundown.delay" inputClass="form-control-sm"> 
                            <div class="input-group-append">
                                <button class="btn btn-secondary btn-sm" type="button" wire:click="unit('time')" id="button-delay-toggle">ms</button>
                            </div>
                        </x-Forms.input>
                        @endif
                    </div>
                @else
                    <x-Forms.time name="delay" value="{{ $delay }}" wrapClass="col" wire="delay" label="rundown.delay" inputClass="form-control-sm" step="1" />
                @endif    
                    <x-Forms.time name="duration" value="{{ $duration }}" wrapClass="col" wire="duration" label="rundown.duration" inputClass="form-control-sm" step="1" />
                    <x-Forms.input type="button" name="cancel" wrapClass="mr-2 rundown-form-buttons" wire="cancel_meta" label="{{ __('rundown.cancel') }}" inputClass="btn-secondary btn-sm mt-4 float-right" />
                    <x-Forms.input type="submit" name="submit" wrapClass="rundown-form-buttons" label="{{ __($submit_btn_label) }}" inputClass="btn-dark btn-sm mt-4 float-right" />
                </div>
            </form>
        @endif
        </div> <!-- EDITOR -->

        <div class="tab-pane fade @if($pane == 'presets')active show @endif" id="presets">
            presets
        </div> <!-- PRESETS -->
        <div class="tab-pane fade @if($pane == 'upload')active show @endif" id="upload">
            <form wire:submit.prevent="save">
                <div class="input-group w-25">
                    <div class="custom-file">
                      <input type="file" wire:model.lazy="xml" name="xml" class="shadow-none custom-file-input" id="input-xml" />
                      <label class="custom-file-label" for="input-xml">{{ __('rundown.xml-file') }}</label>
                    </div>
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit" id="inputGroupFileAddon04">{{ __('rundown.upload') }}</button>
                    </div>
                    @error('xml') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </form>
        </div> <!-- UPLOAD -->
    </div>
</div>