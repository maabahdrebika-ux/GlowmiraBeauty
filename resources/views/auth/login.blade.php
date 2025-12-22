@extends('layouts.authapp')
@section('title', trans('login.login'))

@section('content')
<div class="card mb-0">
    <div class="card-body">
        <div class="p-2">
            <h4 class="text-muted float-right font-18 mt-4">{{ trans('login.sign_in') }}</h4>
            <div>
                <a href="{{ url('/') }}" class="logo logo-admin"><img src="{{ asset('logoo11.PNG') }}" height="200" alt="logo"></a>
            </div>
        </div>

        <div class="p-2">
            {{-- Language selector with flag images --}}
            <div style="text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}; margin-bottom: 20px;">
                <a href="{{ route('changeLanguage', ['language' => 'en']) }}" style="margin-right: 10px;">
                    <img src="{{ asset('united-kingdom.png') }}" alt="English" style="height: 30px; width: auto; border: none;">
                </a>
                <a href="{{ route('changeLanguage', ['language' => 'ar']) }}">
                    <img src="{{ asset('libya.png') }}" alt="العربية" style="height: 30px; width: auto; border: none;">
                </a>
            </div>

            <form class="form-horizontal m-t-20" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group row">
                    <div class="col-12">
                        <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" required="" placeholder="{{ trans('login.email') }}" value="{{ old('email') }}">
                        @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" required="" placeholder="{{ trans('login.password') }}">
                        @error('password')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember">
                            <label class="custom-control-label" for="customCheck1">{{ trans('login.remember_me') }}</label>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center row m-t-20">
                    <div class="col-12">
                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">{{ trans('login.log_in') }}</button>
                    </div>
                </div>

                <div class="form-group m-t-10 mb-0 row">
                    <div class="col-sm-7 m-t-20">
                        <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock"></i> {{ trans('login.forgot_password') }}</a>
                    </div>
                   
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
