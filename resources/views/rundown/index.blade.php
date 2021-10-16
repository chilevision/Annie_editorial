@extends('layouts.app')
@section('add_to_head')
@stop
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
	@if (session('status'))
		<div class="alert alert-success">
			{{ session('status') }}
		@if (session('link'))
			<a href="/dashboard/rundown/xml/{{ session('link') }}">{{ __('rundown.message_ccgfile') }}</a>
		@endif
		</div>
	@endif
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header">
						{{ __('rundown.scripts') }}
					</div>
					<div class="card-body">
						<a href="rundown/create" class="btn btn-dark mb-3">{{ __('rundown.new') }}</a>
						<livewire:rundown-table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection