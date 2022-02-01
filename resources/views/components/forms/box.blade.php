<div class="form-check {{ $wrapClass }}">
@if ($snappy != '')
    <input type="checkbox" value="{{ $value }}" @if ($wires != '') wire:model="{{ $wires }}" @endif name="{{ $name }}" class="form-check-input {{ $inputClass }}" id="input-{{ $name }}" />
@else
    <input type="checkbox" value="{{ $value }}" @if ($wires != '') wire:model.lazy="{{ $wires }}" @endif name="{{ $name }}" class="form-check-input {{ $inputClass }}" id="input-{{ $name }}" />
@endif
    <label class="form-check-label" for="input-{{ $name }}">{{ __($label) }}</label>
    @error($name) <span class="text-danger">{{ $message }}</span> @enderror
</div>