<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
    <div class="input-group">
        <input type="{{ $type }}" value="{{ $value }}" name="{{ $name }}" wire:model="{{ $wires }}" class="form-control shadow-none {{ $inputClass }}" id="input-{{ $name }}" placeholder="{{ $source }}" aria-describedby="source-search"/>
        <div class="input-group-append">
            <button class="btn btn-sm btn-dark" type="button" id="source-search" onclick="mediabrowser('{{ $sourceQuery }}')" data-toggle="modal" data-target="#{{ $modalTarget }}"><i class="bi bi-search"></i></button>
        </div>
    </div>
    @error($name) <span class="text-danger">{{ $message }}</span> @enderror
    {{ $slot }}
</div>