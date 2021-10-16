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
						<a href="/dashboard/rundown">{{ __('rundown.scripts') }}</a><i class="bi bi-caret-right"></i>{{ __('rundown.edit') }}: <cite title="Source Title"> {{ $rundown->title }} </cite>
				</div>
			
				<div class="card-body col-md-5" style="float:none;margin:auto;">
					<p class="help-block">{{ __('rundown.edit_helper') }}</p>
					<form id="rundwon-time-form" name="rundwon-time-form" action="/dashboard/rundown/updatecal" method="post">
						@csrf
						<input type="hidden" name="id" value="{{ $rundown->id }}" />
						<div class="mb-3">
						  <label for="rundownName" class="form-label">{{ __('rundown.edit_title') }}</label>
						  <input type="text" class="form-control shadow-none" name="rundown-title" id="rundownName" value="{{ $rundown->title }}">
						</div>
						<label for="start-date" class="form-label">{{ __('rundown.edit_start') }}</label>
						<div class="mb-3 row">
							<div class="col">
								<input type="date" id="start-date" name="start-date" class="customDatePicker form-control shadow-none" value="{{ date('Y-m-d', strtotime($rundown->starttime)) }}">
							</div>
							<div class="col">
								<input type="time" name="start-time" class="form-control customTimePicker shadow-none" value="{{ date('H:i', strtotime($rundown->starttime)) }}">
							</div>
							<div class="col"></div>
						</div>
						<label for="stop-date" class="form-label">{{ __('rundown.edit_stop') }}</label>
						<div class="mb-3 row">
							<div class="col">
								<input type="date" id="stop-date" name="stop-date" class="customDatePicker form-control shadow-none" value="{{ date('Y-m-d', strtotime($rundown->stoptime)) }}">
							</div>
							<div class="col">
								<input type="time" name="stop-time" class="form-control customTimePicker shadow-none" value="{{ date('H:i', strtotime($rundown->stoptime)) }}">
							</div>
							<div class="col"></div>
						</div>						
						<a href="/dashboard/rundown" class="btn btn-secondary pull-right" role="button">{{ __('rundown.cancel') }}</a>
						<input type="submit" id="submit-date-form" class="btn btn-custom pull-right shadow-none" value="{{ __('rundown.update') }}">
					  </form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection