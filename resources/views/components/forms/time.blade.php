<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
@if ($snappy != '')
    <input type="time" value="{{ $value }}" name="{{ $name }}" wire:model="{{ $wires }}" class="form-control shadow-none {{ $inputClass }}" id="input-{{ $name }}" step="{{ $step }}" />
@else
    <input type="time" value="{{ $value }}" name="{{ $name }}" wire:model.lazy="{{ $wires }}" class="form-control shadow-none {{ $inputClass }}" id="input-{{ $name }}" step="{{ $step }}" />
@endif
    @error($name) <span class="text-danger">{{ $message }}</span> @enderror
    {{ $slot }}
</div>