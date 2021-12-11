@extends('layouts.app')
@section('content')
<div class="container light-style flex-grow-1 container-p-y">
    
	<div class="card">
		<div class="card-header">
			{{ __('app.users') }}
		</div>
        <div class="card-body">
            <a href="{{ route('users.create') }}" class="btn btn-custom float-right mb-3">{{ __('settings.create-user') }}</a>
        <livewire:users />
        </div>
    </div>
</div>
@endsection