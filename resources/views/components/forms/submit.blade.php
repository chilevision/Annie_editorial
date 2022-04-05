<div class="form-group {{ $wrapClass }}">
    <button type="submit" name="{{ $name }}" class="btn {{ $inputClass }}" id="input-{{ $name }}">{{ __( $label ) }}</button>
    {{ $slot }}
</div>