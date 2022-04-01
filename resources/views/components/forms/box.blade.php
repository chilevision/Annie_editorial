<div class="form-check {{ $wrapClass }}">
@if (!isset($snappy) || $snappy != '')
    <input type="checkbox" value="{{ $value }}" name="{{ $name }}" class="form-check-input {{ $inputClass }}" id="input-{{ $name }}" 
        @if ($wires != '') wire:model="{{ $wires }}" @endif
        @if ($checked != '') checked @endif
    />
@else
    <input type="checkbox" value="{{ $value }}" name="{{ $name }}" class="form-check-input {{ $inputClass }}" id="input-{{ $name }}" 
        @if ($wires != '') wire:model.lazy="{{ $wires }}" @endif
        @if ($checked != '') checked @endif
    />
@endif
    <label class="form-check-label" for="input-{{ $name }}">{{ __($label) }}</label>
    @error($name) <span class="text-danger">{{ $message }}</span> @enderror
</div>