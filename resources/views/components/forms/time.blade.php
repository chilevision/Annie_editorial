<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
    <input type="{{ $type }}" value="{{ $value }}" name="{{ $name }}" wire:model="{{ $wires }}" class="form-control shadow-none {{ $inputClass }}" id="input-{{ $name }}" step="{{ $step }}" />
</div>