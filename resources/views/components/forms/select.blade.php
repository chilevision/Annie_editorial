<div class="form-group {{ $wrapClass }}">
    <label for="input{{ $name }}">{{ $label }}</label>
    <select name="{{ $name }}" @if()wire:model="type" wire:change="typeChange" class="form-control form-control-sm shadow-none {{ $inputClass }}" id="input{{ $name }}">
        <option value="MIXER" selected>MIXER</option>
        <option value="VB">VB</option>
        <option value="PRE">PRE BLOCK</option>
        <option value="BREAK">BREAK</option>
    </select>
</div>