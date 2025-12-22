@extends('front.app')

@section('title', trans('app.register'))

@section('content')
<div class="breadcrumb">
    <div class="container">
        <h2>{{ trans('app.register') }}</h2>
        <ul>
            <li><a href="{{ route('/') }}">{{ trans('app.home') }}</a></li>
            <li class="active">{{ trans('app.register') }}</li>
        </ul>
    </div>
</div>

<div class="login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="login__content">
                    <div class="login__form">
                        <form action="{{ route('customer.register.post') }}" method="POST">
                            @csrf
                            <div class="checkout__form">
                                <div class="checkout__form__contact">
                                    <div class="checkout__form__contact__title">
                                        <h2 class="checkout-title">{{ trans('app.signup') }}</h2>
                                        <p>{{ trans('app.sign_up_to_account') }}</p>
                                    </div>
                                    
                                    <div class="input-validator">
                                        <input 
                                            type="text" 
                                            name="name" 
                                            value="{{ old('name') }}" 
                                            placeholder="{{ trans('app.name_placeholder') }}"
                                            required
                                        />
                                    </div>
                                    
                                    <div class="input-validator">
                                        <input 
                                            type="email" 
                                            name="email" 
                                            value="{{ old('email') }}" 
                                            placeholder="{{ trans('app.email_placeholder') }}"
                                            required
                                        />
                                    </div>
                                    
                                    <div class="input-validator">
                                        <input 
                                            type="text" 
                                            name="phone" 
                                            value="{{ old('phone') }}" 
                                            placeholder="{{ trans('app.phone_placeholder') }}"
                                            required
                                        />
                                    </div>
                                    
                                    <div class="input-validator">
                                        <input 
                                            type="text" 
                                            name="address" 
                                            value="{{ old('address') }}" 
                                            placeholder="{{ trans('app.address_placeholder') }}"
                                        />
                                    </div>
                                    
                                    <div class="input-validator">
                                        <label>{{ trans('app.password') }} <span>*</span>
                                            <input 
                                                type="password" 
                                                name="password" 
                                                placeholder="{{ trans('app.password_placeholder') }}"
                                                required
                                                id="password"
                                            />
                                        </label>
                                    </div>
                                    
                                    <div class="input-validator">
                                        <label>{{ trans('app.confirm_password') }} <span>*</span>
                                            <input 
                                                type="password" 
                                                name="password_confirmation" 
                                                placeholder="{{ trans('app.confirm_password_placeholder') }}"
                                                required
                                                id="password_confirmation"
                                            />
                                        </label>
                                    </div>
                                    
                                    <div class="input-validator">
                                        <button type="submit" class="btn -dark">
                                            {{ trans('app.signup') }}
                                        </button>
                                    </div>
                                    
                                    <div class="login__form__footer">
                                        <p>{{ trans('app.have_account') }} 
                                            <a href="{{ route('customer.login') }}" class="text-primary">
                                                {{ trans('app.login_account') }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Password toggle functionality for both password fields
    $('input[name="password"], input[name="password_confirmation"]').each(function() {
        $(this).after('<button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>');
    });
    
    $(document).on('click', '.password-toggle', function() {
        const passwordField = $(this).prev('input[type="password"]');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });
});
</script>
@endpush
