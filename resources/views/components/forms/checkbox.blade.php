<div class="form-group @if($wrapClass != ''){{ $wrapClass }}@endif">
    <label for="input{{ $name }}"> {{ __($label) }}</label>
    <input type="checkbox" class="form-control form-control-sm shadow-none @if($inputClass != ''){{ $inputClass }}@endif" id="input{{ $name }}" name="{{ $name }}" />
</div>