@extends('front.app')

@section('title', trans('app.forgot_your_password'))

@section('content')
    <div class="breadcrumb">
        <div class="container">
            <h2>{{ trans('app.forgot_your_password') }}</h2>

            <ul>
                <li>{{ trans('app.home') }}</li>
                <li class="active">{{ trans('app.forgot_your_password') }}</li>
            </ul>
        </div>
    </div>

    <div class="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div class="login__content">
                        <div class="login__form">
                            <form action="{{ route('password.email') }}" method="POST">
                                @csrf
                                <div class="checkout__form">
                                    <div class="checkout__form__contact">
                                        <div class="checkout__form__contact__title">
                                            <h2 class="checkout-title">{{ trans('app.forgot_your_password') }}</h2>
                                            <p>{{ __('passwords.enter_email_to_receive_reset_link') }}</p>
                                        </div>

                                        <!-- General Error Messages -->
                                        @if (session('error'))
                                            <div class="alert alert-danger" role="alert" style="color: red; background-color: #f8d7da; border-color: #f5c6cb; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        @if (session('status'))
                                            <div class="alert alert-success" role="alert" style="color: #155724; background-color: #d4edda; border-color: #c3e6cb; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                                                {{ session('status') }}
                                            </div>
                                        @endif

                                        <div class="input-validator">
                                            <input type="email" name="email" value="{{ old('email') }}"
                                                placeholder="{{ trans('app.email_placeholder') }}"
                                                class="@error('email') is-invalid @enderror" required />
                                            @error('email')
                                                <div class="invalid-feedback" role="alert" style="color: red; font-size: 14px; margin-top: 5px; display: block;">
                                                    <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="input-validator">
                                            <button type="submit" class="btn -dark">
                                                {{ __('passwords.send_reset_link') }}
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

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
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
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush