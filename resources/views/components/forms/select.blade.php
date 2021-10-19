<div class="form-group @if($wrapClass != ''){{ $wrapClass }}@endif">
    <label for="input{{ $name }}"> {{ __($label) }}</label>
    <select class="form-control shadow-none @if($selectClass != ''){{ $selectClass }}@endif" id="select{{ $name }}" name="{{ $name }}"
@if (is_array($wires))
    @foreach ($wires as $wire)
        wire:{{ $wire['type'] }}="{{ $wire['target'] }}"
    @endforeach
@else
        wire:model="{{ $wires }}"
@endif
    >
@if(is_array($options))
    @foreach ($options as $option)
        <option value="{{ $option['value'] }}">{{ $option['title'] }}</option>
    @endforeach
@endif
    </select>
</div>