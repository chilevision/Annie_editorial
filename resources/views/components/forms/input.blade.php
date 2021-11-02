<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
    <input type="{{ $type }}" value="{{ $value }}" @if ($wires != '') wire:model="{{ $wires }}" @endif name="{{ $name }}" class="@if($type != 'checkbox') form-control shadow-none @endif{{ $inputClass }}" id="input-{{ $name }}" />
</div>