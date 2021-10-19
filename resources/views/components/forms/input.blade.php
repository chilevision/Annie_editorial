<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
    <input type="{{ $type }}" @if ($wires != '') wire:model="{{ $wires }}" @endif class="form-control form-control-sm shadow-none {{ $inputClass }}" id="input-{{ $name }}" />
</div>