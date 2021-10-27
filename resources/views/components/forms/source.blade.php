<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
    <div class="input-group">
        <input type="{{ $type }}" value="{{ $value }}" wire:model="{{ $wires }}" class="form-control shadow-none {{ $inputClass }}" id="input-{{ $name }}" placeholder="mediefil" aria-describedby="source-search"/>
        <div class="input-group-append">
            <button class="btn btn-sm btn-dark" type="button" id="source-search" wire:click="$emit('mediabrowser', '{{ $sourceQuery }}')"><i class="bi bi-search"></i></button>
        </div>
    </div>
</div>