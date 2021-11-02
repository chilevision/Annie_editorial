<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
    <input type="{{ $type }}" @if ($wires != '') wire:model="{{ $wires }}" @endif name="{{ $name }}" class="shadow-none form-control-file" id="input-{{ $name }}" />
</div>