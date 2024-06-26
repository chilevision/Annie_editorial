@extends('layouts.app')
@section('add_styles')
<link href="{{ asset('css/amsify.suggestags.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('css/simple-calendar.css') }}" />
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
						<x-forms.input type="text" name="rundown-title" value="{{old('rundown-title', $rundown->title)}}" wrapClass="mb-3" wire="" label="rundown.new_title" inputClass="" />
						<div class="form-row mb-3">
							<x-forms.input type="date" name="start-date" value="{{old('start-date', $startdate)}}" wrapClass="" wire="" label="rundown.new_start" inputClass="customDatePicker"/>
							<x-forms.time type="time" name="start-time" value="{{old('start-time', $starttime)}}" wrapClass="" wire="" label="" inputClass="ml-2 mt-2" step="0"/>
							<div class="col">
								<button type="button" class="btn btn-custom float-right mt-4" data-toggle="modal" data-target="#scheduleModal">
									{{ __('rundown.view-schedule') }}
								</button>
							</div>
						</div>
						<div class="form-row mb-3">
							<x-forms.input type="date" name="stop-date" value="{{old('stop-date', $stopdate)}}" wrapClass="" wire="" label="rundown.new_stop" inputClass="customDatePicker" />
							<x-forms.time type="time" name="stop-time" value="{{old('stop-time', $stoptime)}}" wrapClass="" wire="" label="" inputClass="ml-2 mt-2" step="0"/>
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
	<!-- Modal -->
	<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="scheduleLabel">{{ __('rundown.schedule') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<x-calendar/>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('app.close') }}</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footer_scripts')
<script src="{{ asset('js/jquery.amsify.suggestags.js') }}"></script>
<script src="{{ asset('js/jquery.simple-calendar.min.js') }}"></script>
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