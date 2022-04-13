<div class="form-group {{ $wrapClass }}">
    <button type="button" value="{{ $value }}" name="{{ $name }}" class="btn {{ $inputClass }}" wire:click="{{ $wires ?? '' }}" id="input-{{ $name }}">{{ __( $label ) }}</button>
    {{ $slot }}
</div>