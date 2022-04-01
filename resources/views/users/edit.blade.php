@extends('layouts.app')
@section('content')
<div class="container light-style flex-grow-1 container-p-y">
	<div class="card">
		<div class="card-header">
			<a href="{{ route('users.index') }}">{{ __('app.users') }}</a><i class="bi bi-caret-right"></i>{{ __('rundown.edit') }} <cite id="navTitle" title="Source Title">{{ $user->name }}</cite>
		</div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @method('PUT')
                @csrf
@if (!$user->cas)
                <x-forms.input type="text" name="name" wrapClass="col" value="{{old('name', $user->name)}}" label="{{ __('settings.name') }}" inputClass="form-control" />
                <x-forms.input type="text" name="email" wrapClass="col" value="{{old('email', $user->email)}}" label="{{ __('settings.email') }}" inputClass="form-control" />
                <x-forms.input type="password" name="password" wrapClass="col" label="{{ __('settings.password') }}" inputClass="form-control" />
                <x-forms.input type="password" name="password_confirmation" wrapClass="col" label="{{ __('settings.password-confirm') }}" inputClass="form-control" />
@else 
                <input type="hidden" name="name" value="{{ $user->name }}" />
                <input type="hidden" name="email" value="{{ $user->email }}" />
@endif
                <div class="form-check col">
                    <input type="checkbox" name="admin" value="1" id="input-admin" @if($user->admin) checked @endif />
                    <label for="input-admin">{{ __('settings.admin') }}</label>
                </div>
                <div class="float-right">
                    <a href="{{ route('users.index') }}" role="button" class="btn btn-secondary">{{ __('settings.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('settings.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection