@extends('layouts.app')

@section('content')
<div class="container light-style flex-grow-1 container-p-y">
	<div class="card">
		<div class="card-header">
			{{ __('rundown.scripts') }}
		</div>
		<div class="card-body">
			<a href="rundown/create" class="btn btn-custom mb-3">{{ __('rundown.new') }}</a>
			<livewire:rundown-index>
		</div>
	</div>
</div>
@endsection