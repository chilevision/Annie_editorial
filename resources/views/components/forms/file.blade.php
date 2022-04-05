<div class="form-group {{ $wrapClass }}">
    <label for="input-{{ $name }}">{{ __($label) }}</label>
    @if (isset($snappy) || $snappy != '')
        <input type="file" @if (isset($wires) ||$wires != '') wire:model="{{ $wires ?? ''}}" @endif name="{{ $name }}" class="shadow-none form-control-file" id="input-{{ $name }}" />
    @else
        <input type="file" @if (isset($wires) || $wires != '') wire:model.lazy="{{ $wires ?? '' }}" @endif name="{{ $name }}" class="shadow-none form-control-file" id="input-{{ $name }}" />
    @endif
    @error($name) <span class="text-danger">{{ $message }}</span> @enderror
    {{ $slot }}
</div>