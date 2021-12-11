<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
@if ($snappy != '')
    <input type="{{ $type }}" value="{{ $value }}" @if ($wires != '') wire:model="{{ $wires }}" @endif name="{{ $name }}" class="@if($type != 'checkbox') form-control shadow-none @endif{{ $inputClass }}" id="input-{{ $name }}" />
@else
    <input type="{{ $type }}" value="{{ $value }}" @if ($wires != '') wire:model.lazy="{{ $wires }}" @endif name="{{ $name }}" class="@if($type != 'checkbox') form-control shadow-none @endif{{ $inputClass }}" id="input-{{ $name }}" />
@endif
    @error($name) <span class="text-danger">{{ $message }}</span> @enderror
</div>