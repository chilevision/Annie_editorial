<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
    @if ($snappy != '')
    <input type="file" @if ($wires != '') wire:model="{{ $wires }}" @endif name="{{ $name }}" class="shadow-none form-control-file" id="input-{{ $name }}" />
@else
    <input type="file" @if ($wires != '') wire:model.lazy="{{ $wires }}" @endif name="{{ $name }}" class="shadow-none form-control-file" id="input-{{ $name }}" />
@endif
</div>