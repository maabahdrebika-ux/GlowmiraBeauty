{{-- resources/views/front/checkout.blade.php --}}
@extends('front.app')
@section('title', __('products.checkout'))

@section('content')
<div class="breadcrumb">
    <div class="container">
        <h2>{{ __('products.checkout') }}</h2>
        <ul>
            <li><a href="{{ route('/') }}">{{ __('products.home') }}</a></li>
            <li><a href="{{ route('cart.index') }}">{{ __('products.cart') }}</a></li>
            <li class="active">{{ __('products.checkout') }}</li>
        </ul>
    </div>
</div>

<div class="shop">
    <div class="container">
        <div class="checkout">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        @auth('customer')
                        <form action="{{ route('order.store') }}" method="POST" id="checkout-form">
                            @csrf
                            <div class="checkout__form">
                                <!-- Customer Profile Information -->
                                <div class="checkout__form__contact">
                                    <div class="checkout__form__contact__title">
                                        <h5 class="checkout-title">{{ __('products.customer_info') }}</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="input-validator">
                                                <label>{{ __('products.username') }} <span>*</span>
                                                    <input type="text" 
                                                           name="username" 
                                                           value="{{ auth('customer')->user()->name ?? old('username') }}" 
                                                           required />
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="input-validator">
                                                <label>{{ __('products.phone_number') }} <span>*</span>
                                                    <input type="text" 
                                                           name="phonenumber" 
                                                           value="{{ auth('customer')->user()->phone ?? old('phonenumber') }}" 
                                                           required />
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-validator">
                                                <label>{{ __('products.email') }} <span>*</span>
                                                    <input type="email" 
                                                           name="email" 
                                                           value="{{ auth('customer')->user()->email ?? old('email') }}" 
                                                           required />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Selection -->
                                <div class="checkout__form__shipping">
                                    <h5 class="checkout-title">{{ __('products.select_delivery_address') }}</h5>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-validator">
                                                <label>{{ __('products.address') }} <span>*</span>
                                                     <label>{{ __('products.address') }} <span>*</span>
                                                    <input type="address_id" 
                                                           name="address_id" 
                                                           value="{{ auth('customer')->user()->address ?? old('address_id') }}" 
                                                           required />
                                                </label>
                                                     

                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-validator">
                                                <label>{{ __('products.order_note') }}
                                                    <input type="text" 
                                                           name="note" 
                                                           placeholder="{{ __('products.note_placeholder') }}" 
                                                           value="{{ old('note') }}" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @else
                        <div class="alert alert-warning">
                            <p>{{ __('products.please_login_to_checkout') }} <a href="{{ route('customer.login') }}">{{ __('products.login') }}</a></p>
                        </div>
                        @endauth
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-12 ml-auto">
                                <div class="checkout__total">
                                    <h5 class="checkout-title">{{ __('products.your_order') }}</h5>
                                    
                                  
                                    <!-- Order Summary -->
                                    <div class="checkout__total__price">
                                        <h5>{{ __('products.product') }}</h5>
                                        <table>
                                            <colgroup>
                                                <col style="width: 70%"/>
                                                <col style="width: 30%"/>
                                            </colgroup>
                                            <tbody>
                                                @foreach($cart as $item)
                                                <tr>
                                                    <td>
                                                        <span>{{ $item['quantity'] }} x </span>
                                                        @if(app()->getLocale() == 'ar')
                                                            {{ $item['product_name'] }}
                                                        @else
                                                            {{ $item['product_namee'] ?? $item['product_name'] }}
                                                        @endif
                                                        @if($item['color'])
                                                            <small>({{ __('products.color') }}: {{ $item['color'] }})</small>
                                                        @endif
                                                        @if($item['size'])
                                                            <small>({{ __('products.size') }}: {{ $item['size'] }})</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($item['total_price'], 2) }} {{ __('products.lyd') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        
                                        <div class="checkout__total__price__total-count">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ __('products.subtotal') }}</td>
                                                        <td id="subtotal">{{ number_format($subtotal, 2) }} {{ __('products.lyd') }}</td>
                                                    </tr>
                                                    <tr id="discount-row" style="display: {{ $discount > 0 ? 'table-row' : 'none' }};">
                                                        <td>{{ __('products.discount') }}</td>
                                                        <td id="discount-amount">-{{ number_format($discount, 2) }} {{ __('products.lyd') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __('products.total') }}</td>
                                                        <td id="total">{{ number_format($total, 2) }} {{ __('products.lyd') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Payment Methods -->
                                        <div class="checkout__total__price__payment">
                                            <h5>{{ __('products.payment_method') }}</h5>
                                            <label class="checkbox-label" for="payment">
                                                <input type="radio" 
                                                       id="payment"
                                                       name="payment_method" 
                                                       value="cash" 
                                                       {{ old('payment_method', 'cash') == 'cash' ? 'checked' : '' }} />
                                                {{ __('products.cash_on_delivery') }}
                                            </label>
                                            
                                        </div>
                                    </div>
                                    
                                    <button class="btn -red" form="checkout-form" type="submit" id="place-order">
                                        {{ __('products.place_order') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Success Modal -->
@if(session('success'))
<div class="modal fade" id="orderSuccessModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('products.order_success') }}</h5>
            </div>
            <div class="modal-body">
                <p>{{ session('success') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('/') }}'">
                    {{ __('products.continue_shopping') }}
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#orderSuccessModal').modal('show');
    });
</script>
@endif

@endsection

@section('styles')
<style>
/* Error highlighting for form fields */
.input-validator input.error,
.input-validator select.error {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.input-validator label .error {
    color: #dc3545;
}

/* Loading state for submit button */
#place-order.loading {
    opacity: 0.7;
    cursor: not-allowed;
}

#place-order.loading:before {
    content: '';
    display: inline-block;
    width: 14px;
    height: 14px;
    margin-right: 8px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Form field focus improvements */
.input-validator input:focus,
.input-validator select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Profile info section styling */
.checkout__form__contact {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.checkout-title {
    color: #333;
    margin-bottom: 15px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 8px;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Apply Coupon
    $('#apply-coupon').click(function(e) {
        e.preventDefault();
        const couponCode = $('#coupon-code').val();
        if (!couponCode) {
            alert('{{ __('products.enter_coupon_code') }}');
            return;
        }

        // Here you would typically make an AJAX call to validate the coupon
        // For now, we'll just show an alert
        alert('{{ __('products.coupon_validation_placeholder') }}');
    });

    // Form validation
    $('#checkout-form').submit(function(e) {
        // Basic validation for customer profile fields
        const requiredFields = ['email', 'username', 'phonenumber', 'address_id'];
        let isValid = true;
        
        // Clear previous error states
        $('.error').removeClass('error');
        
        requiredFields.forEach(function(field) {
            const value = $('[name="' + field + '"]').val();
            if (!value || value.trim() === '') {
                isValid = false;
                $('[name="' + field + '"]').addClass('error');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('{{ __('products.please_fill_required_fields') }}');
            // Scroll to first error field
            const firstError = $('.error').first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
                firstError.focus();
            }
            return false;
        }

        // If valid, allow form submission and show loading state
        const submitBtn = $('#place-order');
        submitBtn.addClass('loading').prop('disabled', true);
        
        return true;
    });
});
</script>
@endsection