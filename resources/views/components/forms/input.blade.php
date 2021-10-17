<div class="form-group @if($wrapClass != ''){{ $wrapClass }}@endif">
    <label for="input{{ $name }}"> {{ __($label) }}</label>
    <input type="text" @if($value != ''){{ $value }}@endif class="form-control form-control-sm shadow-none @if($inputClass != ''){{ $inputClass }}@endif" id="input{{ $name }}" name="{{ $name }}" 
    @if (is_array($wires))
        @foreach ($wires as $wire)
            wire:{{ $wire['type'] }}="{{ $wire['target'] }}"
        @endforeach
    @else
    wire:model="{{ $wires }}"
    @endif
    />
</div>