@extends('layouts.app')
@section('add_styles')
	<link rel="stylesheet" href="{{ asset('css/simple-calendar.css') }}" />
@stop
@section('content')
<div class="container light-style flex-grow-1 container-p-y">
	<div class="card mt-4">
		<div class="card-header">
			<h1 class="text-center dashboard">{{ __('dashboard.welcome') }}</h1>
		</div>
		<div class="card-body w-75 mx-auto">
				<img class="img-fluid" src="{{ asset('css/img/annie.jpg') }}" alt="Caspar" class="mx-auto">
				<p>{{ __('dashboard.intro') }}</p>
				<a class="btn btn-secondary" href="https://github.com/CasparCG/help/wiki" target="_blank">{{ __('dashboard.read_more') }}</a>
		</div>
	</div>

	<div class="card mt-4">
		<div class="card-header">
			<h3 class="text-center dashboard">{{ __('rundown.schedule') }}</h3>
		</div>
		<div class="card-body w-75 mx-auto">
			<x-calendar/>
			<div class="bg-custom text-white pt-2 pr-2 pb-2 pl-2"><div class="row"><h6 class="col-3">{{ __('dashboard.rundown_count') .' '. $rundowns}}</h6><h6 class="col">{{ __('dashboard.next_run') .' '. $nextRun}}</h6></div></div>
		</div>
	</div>
	<div class="card mt-4">
		<div class="card-header">
			<h3 class="text-center dashboard">{{ __('dashboard.friends') }}</h3>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card" style="width: 18rem;">
						<img src="{{ asset('css/img/casparcg.png') }}" class="card-img-top" alt="...">
						<div class="card-body">
						  <h5 class="card-title">Caspar CG</h5>
						  <p class="card-text">{{ __('dashboard.caspar') }}</p>
						  <a href="http://casparcg.com" class="btn btn-custom">{{ __('dashboard.read_more') }}</a>
						</div>
					  </div>
				</div>
				<div class="col">
					<div class="card" style="width: 18rem;">
						<img src="{{ asset('css/img/laravel.png') }}" class="card-img-top" alt="...">
						<div class="card-body">
						  <h5 class="card-title">Laravel</h5>
						  <p class="card-text">{{ __('dashboard.laravel') }}</p>
						  <a href="https://laravel.com" class="btn btn-custom">{{ __('dashboard.read_more') }}</a>
						</div>
					  </div>
				</div>
				<div class="col">
					<div class="card" style="width: 18rem;">
						<img src="{{ asset('css/img/pusher.png') }}" class="card-img-top" alt="...">
						<div class="card-body">
						  <h5 class="card-title">Pusher</h5>
						  <p class="card-text">{{ __('dashboard.pusher') }}</p>
						  <a href="https://pusher.com/" class="btn btn-custom">{{ __('dashboard.read_more') }}</a>
						</div>
					  </div>
			  </div>
		</div>
	</div>
</div>
@endsection
@section('footer_scripts')
<script src="{{ asset('js/jquery.simple-calendar.min.js') }}"></script>

@endsection