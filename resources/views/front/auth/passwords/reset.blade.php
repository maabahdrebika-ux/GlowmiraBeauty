@extends('front.app')

@section('title', trans('app.reset_password'))

@section('content')
    <div class="breadcrumb">
        <div class="container">
            <h2>{{ trans('app.reset_password') }}</h2>

            <ul>
                <li>{{ trans('app.home') }}</li>
                <li class="active">{{ trans('app.reset_password') }}</li>
            </ul>
        </div>
    </div>

    <div class="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div class="login__content">
                        <div class="login__form">
                            <form action="{{ route('customer.password.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                
                                <div class="checkout__form">
                                    <div class="checkout__form__contact">
                                        <div class="checkout__form__contact__title">
                                            <h2 class="checkout-title">{{ trans('app.reset_password') }}</h2>
                                            <p>{{ trans('app.enter_new_password') }}</p>
                                        </div>

                                        <!-- General Error Messages -->
                                        @if (session('error'))
                                            <div class="alert alert-danger" role="alert" style="color: red; background-color: #f8d7da; border-color: #f5c6cb; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        <div class="input-validator">
                                            <input type="email" name="email" value="{{ $email ?? old('email') }}"
                                                placeholder="{{ trans('app.email_placeholder') }}"
                                                class="@error('email') is-invalid @enderror" required readonly />
                                            @error('email')
                                                <div class="invalid-feedback" role="alert" style="color: red; font-size: 14px; margin-top: 5px; display: block;">
                                                    <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="input-validator">
                                            <label>{{ trans('app.password') }} <span>*</span>
                                                <input type="password" name="password"
                                                    placeholder="{{ trans('app.password_placeholder') }}"
                                                    class="@error('password') is-invalid @enderror" required
                                                    id="password" />
                                            </label>
                                            @error('password')
                                                <div class="invalid-feedback" role="alert" style="color: red; font-size: 14px; margin-top: 5px; display: block;">
                                                    <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="input-validator">
                                            <label>{{ trans('app.confirm_password') }} <span>*</span>
                                                <input type="password" name="password_confirmation"
                                                    placeholder="{{ trans('app.confirm_password_placeholder') }}"
                                                    class="@error('password_confirmation') is-invalid @enderror" required
                                                    id="password_confirmation" />
                                            </label>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback" role="alert" style="color: red; font-size: 14px; margin-top: 5px; display: block;">
                                                    <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="input-validator">
                                            <button type="submit" class="btn -dark">
                                                {{ trans('app.reset_password') }}
                                            </button>
                                        </div>

                                        <div class="login__form__footer">
                                            <p>{{ trans('app.remember_password') }}
                                                <a href="{{ route('customer.login') }}" class="text-primary">
                                                    {{ trans('app.login') }}
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

@push('styles')
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
        
        .input-validator {
            margin-bottom: 20px;
        }
        
        .alert {
            border: 1px solid transparent;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .invalid-feedback {
            width: 100%;
            margin-top: 5px;
            font-size: 14px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Password toggle functionality
            $('input[name="password"]').after(
                '<button type="button" class="password-toggle"><i class="fas fa-eye"></i></button>');
            
            $('input[name="password_confirmation"]').after(
                '<button type="button" class="password-toggle-confirm"><i class="fas fa-eye"></i></button>');

            $(document).on('click', '.password-toggle', function() {
                const passwordField = $(this).prev('input[name="password"]');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            $(document).on('click', '.password-toggle-confirm', function() {
                const passwordField = $(this).prev('input[name="password_confirmation"]');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush