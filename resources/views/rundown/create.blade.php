<?php date_default_timezone_set('Europe/Stockholm'); ?>
@extends('layouts.app')

@section('content')
@if (count($errors) > 0)
<div class="alert alert-danger">
	<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif
<div class="container">
    
	<div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
						<a href="/dashboard/rundown">{{ __('rundown.scripts') }}</a><i class="bi bi-caret-right"></i>{{ __('rundown.new') }}
				</div>
				<div class="card-body col-md-5" style="float:none;margin:auto;">
					<p class="help-block">{{ __('rundown.new_helper') }}</p>
					<form id="rundwon-time-form" name="rundwon-time-form" action="/dashboard/rundown" method="post">
						@csrf
						<div class="mb-3">
						  <label for="rundownName" class="form-label">{{ __('rundown.new_title') }}</label>
						  <input type="text" class="form-control shadow-none" name="rundown-title" id="rundownName" value="{{ old('rundown-title') }}">
						</div>
						<label for="start-date" class="form-label">{{ __('rundown.new_start') }}</label>
						<div class="mb-3 row">
							<div class="col">
								<input type="date" id="start-date shadow-none" name="start-date" class="customDatePicker form-control" value="{{ old('start-date') }}">
							</div>
							<div class="col">
								<input type="time" name="start-time shadow-none" class="form-control customTimePicker" value="{{ old('start-time') }}">
							</div>
							<div class="col"></div>
						</div>
						<label for="stop-date" class="form-label">{{ __('rundown.new_stop') }}</label>
						<div class="mb-3 row">
							<div class="col">
								<input type="date" id="stop-date" name="stop-date" class="customDatePicker form-control shadow-none" value="{{ old('stop-date') }}">
							</div>
							<div class="col">
								<input type="time" name="stop-time" class="form-control customTimePicker shadow-none" value="{{ old('stop-time') }}">
							</div>
							<div class="col"></div>
						</div>						
						<a href="/dashboard/rundown" class="btn btn-secondary pull-right" role="button">{{ __('rundown.cancel') }}</a>
						<input type="submit" id="submit-date-form" class="btn btn-custom pull-right shadow-none" value="{{ __('rundown.next') }}">
					  </form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection