@extends('layouts.app')
@section('add_styles')
<link href="{{ asset('css/amsify.suggestags.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
<div class="container">
	<div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
					<a href="/dashboard/rundown">{{ __('rundown.scripts') }}</a><i class="bi bi-caret-right"></i>{{ __('rundown.edit') }}: <i>{{ $rundown->title }}</i>
				</div>
				<div class="card-body col-md-5" style="float:none;margin:auto;">
					<p class="help-block">{{ __('rundown.new_helper') }}</p>
					<form id="rundwon-time-form" name="rundwon-time-form" action="/dashboard/rundown/updatecal" method="post">
						@csrf
						<input type="hidden" name="id" value="{{ $rundown->id }}" />
						@if (substr(url()->previous(), -4) == 'edit') <input type="hidden" name="redirect" value="{{url()->previous()}}" />@endif 
						<x-Forms.input type="text" name="rundown-title" value="{{old('rundown-title', $rundown->title)}}" wrapClass="mb-3" wire="" label="rundown.new_title" inputClass="" />
						<div class="form-row mb-3">
							<x-Forms.input type="date" name="start-date" value="{{old('start-date', $startdate)}}" wrapClass="" wire="" label="rundown.new_start" inputClass="customDatePicker"/>
							<x-Forms.time type="time" name="start-time" value="{{old('start-time', $starttime)}}" wrapClass="" wire="" label="" inputClass="ml-2 mt-2" step="0"/>
						</div>
						<div class="form-row mb-3">
							<x-Forms.input type="date" name="stop-date" value="{{old('stop-date', $stopdate)}}" wrapClass="" wire="" label="rundown.new_stop" inputClass="customDatePicker" />
							<x-Forms.time type="time" name="stop-time" value="{{old('stop-time', $stoptime)}}" wrapClass="" wire="" label="" inputClass="ml-2 mt-2" step="0"/>
						</div>
						<div class="form-group">
							<label for="rundownUsers">{{ __('rundown.users') }}:</label>
							<input type="text" class="form-control" name="users" id="rundownUsers" aria-describedby="rundownUsershelp" value="{{old('users', $users)}}" />
							<small id="rundownUsershelp" class="form-text text-muted">{{ __('rundown.users_help') }}</small>
						</div>
						

						<div class="form-row">

							<a href="@if (url()->current() != url()->previous()){{ url()->previous() }}@else{{ url(route('rundown.index')) }}@endif" class="btn btn-secondary pull-right" role="button">{{ __('rundown.cancel') }}</a>
							<input type="submit" id="submit-date-form" class="btn btn-custom ml-2 shadow-none" value="{{ __('rundown.update') }}">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footer_scripts')
<script src="{{ asset('js/jquery.amsify.suggestags.js') }}"></script>
<script>
	$('#rundownUsers').amsifySuggestags({
		suggestions: @php  echo json_encode($all_users); @endphp
	});

	$(document).on("keypress", 'form', function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        e.preventDefault();
        return false;
    }
});
</script>
@endsection