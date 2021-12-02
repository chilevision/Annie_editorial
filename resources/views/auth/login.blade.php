@extends('layouts.app')

@section('content')
<div class="alert alert-primary mt-5 pt-5 pb-5" role="alert">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col ml-3" id="login_img"></div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-custom">{{ __('Login') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror shadow-none" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror shadow-none" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-custom">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                        </form>
@if ($sso)
<hr/>
                        <div class="text-center">
                            <p>{{ __('app.sso_login') }}</p>
                            <a class="btn btn-dark" href="{{ route('cas.login') }}"><i class="bi bi-key"></i> SSO </a>
                        </div>
@endif

                        <div class="text-center mt-5">
                            <small class="text-muted">{{ __('app.accept') }} <a href="#" data-toggle="modal" data-target="#termsModal">{{ __('app.tnc') }}.</a></small>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title text-capitalize" id="termsModalLabel">{{ __('app.tnc') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body pr-5 pl-5 pt-4">
                                    <h5 class="text-dark">{{ __('gdpr.personal_data_head') }}</h5>
                                    <p class="text-dark">{{ __('gdpr.personal_data_body') }}</p>
                                    <h5 class="text-dark">{{ __('gdpr.leagal_ground_head') }}</h5>
                                    <p class="text-dark">{{ __('gdpr.leagal_ground_body') }}</p>
                                    <h5 class="text-dark">{{ __('gdpr.acquire_head') }}</h5>
                                    <p class="text-dark">{{ __('gdpr.acquire_body') }}</p>
                                    <h5 class="text-dark">{{ __('gdpr.data_access_head') }}</h5>
                                    <p class="text-dark">{{ __('gdpr.data_access_body') }}</p>
                                    <h5 class="text-dark">{{ __('gdpr.data_ttl_head') }}</h5>
                                    <p class="text-dark">{{ __('gdpr.data_ttl_body') }}</p>
                                    <h5 class="text-dark">{{ __('gdpr.cookies_head') }}</h5>
                                    <p class="text-dark">{{ __('gdpr.cookies_body') }}</p>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
