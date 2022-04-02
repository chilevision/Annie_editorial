<div class="form-check {{ $wrapClass }}">
@if (!is_null($snappy))
    <input type="checkbox" value="{{ $value ?? '' }}" name="{{ $name }}" class="form-check-input {{ $inputClass ?? '' }}" id="input-{{ $name }}" 
        @if (!is_null($wires)) wire:model="{{ $wires ?? ''}}" @endif
        @if (!is_null($checked)) checked @endif
    />
@else
    <input type="checkbox" value="{{ $value ?? '' }}" name="{{ $name }}" class="form-check-input {{ $inputClass ?? '' }}" id="input-{{ $name }}" 
        @if (!is_null($wires)) wire:model.lazy="{{ $wires ?? ''}}" @endif
        @if (!is_null($checked)) checked @endif
    />
@endif
    <label class="form-check-label" for="input-{{ $name }}">{{ __($label) }}</label>
    @error($name) <span class="text-danger">{{ $message ?? '' }}</span> @enderror
</div>