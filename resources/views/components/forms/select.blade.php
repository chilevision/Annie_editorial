<div class="form-group @if(isset($wrapClass) || $wrapClass != ''){{ $wrapClass }}@endif">
    <label for="input{{ $name }}"> {{ __($label) }}</label>
    <select class="form-control shadow-none {{ $selectClass ?? ''}}" {{ $disabled ?? ''}} name="{{ $name }}" id="select{{ $name }}" name="{{ $name }}"
@if (isset($wires))
    @if (is_array($wires))
        @foreach ($wires as $wire)
            wire:{{ $wire['type'] }}="{{ $wire['target'] }}"
        @endforeach
    @else
            wire:model="{{ $wires }}"
    @endif
@endif
    >
@if (isset($options))
    @if(is_array($options))
        @foreach ($options as $option)
            <option value="{{ $option['value'] }}" @if ($selected == $option['value']) selected="selected" @endif>{{ $option['title'] }}</option>
        @endforeach
    @endif
@endif
    </select>
    @error($name) <span class="text-danger">{{ $message }}</span> @enderror
</div>