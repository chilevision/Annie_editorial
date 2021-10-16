<div class="form-group {{ $wrapClass }}">
    <label for="input{{ $name }}">{{ $label }}</label>
    <input type="text" @if($wire != '')wire:model="{{ $wire }}"@endif name="{{ $name }}" class="form-control form-control-sm shadow-none {{ $inputClass }}" id="input{{ $name }}">
</div>{{ "\n" }}