@extends('layouts.app')

@section('content')
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
						<x-Forms.input type="text" name="rundown-title" value="{{ old('rundown-title') }}" wrapClass="mb-3" wire="" label="rundown.new_title" inputClass="" />
						<div class="form-row mb-3">
							<x-Forms.input type="date" name="start-date" value="{{ old('start-date') }}" wrapClass="" wire="" label="rundown.new_start" inputClass="customDatePicker" />
							<x-Forms.input type="time" name="start-time" value="{{ old('start-time') }}" wrapClass="" wire="" label="" inputClass="ml-2 mt-2" />
						</div>
						<div class="form-row mb-3">
							<x-Forms.input type="date" name="stop-date" value="{{ old('stop-date') }}" wrapClass="" wire="" label="rundown.new_stop" inputClass="customDatePicker" />
							<x-Forms.input type="time" name="stop-time" value="{{ old('stop-time') }}" wrapClass="" wire="" label="" inputClass="ml-2 mt-2" />
						</div>
						<div class="form-row">
							<a href="/dashboard/rundown" class="btn btn-secondary pull-right" role="button">{{ __('rundown.cancel') }}</a>
							<input type="submit" id="submit-date-form" class="btn btn-custom ml-2 shadow-none" value="{{ __('rundown.next') }}">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection