<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
    <input type="{{ $type }}" value="{{ $value }}" @if ($wires != '') wire:model="{{ $wires }}" @endif class="form-control shadow-none {{ $inputClass }}" id="input-{{ $name }}" />
</div>