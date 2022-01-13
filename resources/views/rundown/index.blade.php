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
@if (session('status'))
	<div class="alert alert-success">
		{!! session('status') !!}
	</div>
@endif
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