@extends('front.app')

@section('title', __('app.order_details'))

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="checkout__form">
                <!-- Order Header -->
                <div class="checkout__form__contact">
                    <div class="checkout__form__contact__title">
                        <h5 class="checkout-title">{{ __('app.order_details') }}</h5>
                    </div>

                    <!-- Order Information -->
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="input-validator">
                                <label>{{ __('app.order_number') }} <span>*</span></label>
                                <div class="form-value">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="input-validator">
                                <label>{{ __('app.date') }} <span>*</span></label>
                                <div class="form-value">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-validator">
                                <label>{{ __('app.status') }} <span>*</span></label>
                                <div class="form-value">
                                 @if(app()->getLocale() == 'ar')
                                    {{ $order->orderstatues->state ?? 'N/A' }}
                                    @else
                                    {{ $order->orderstatues->state_en ?? 'N/A' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

             
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-12 ml-auto">
                    <div class="checkout__total">
                        <h5 class="checkout-title">{{ __('app.order_summary') }}</h5>

                        <div class="checkout__total__price">
                            <h5>{{ __('app.order_items') }}</h5>
                            <table>
                                <colgroup>
                                    <col style="width: 70%"/>
                                    <col style="width: 30%"/>
                                </colgroup>
                                <tbody>
                                    @foreach($order->orderitems as $item)
                                    <tr>
                                        <td>
                                            <span>{{ $item->quantty }} x </span>
                                            @if(app()->getLocale() == 'ar')
                                            {{ $item->products->name ?? 'N/A' }}
                                            @else
                                            {{ $item->products->namee ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->price, 2) }} {{ __('app.lyd') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="checkout__total__price__total-count">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>{{ __('app.subtotal') }}</td>
                                            <td>{{ number_format($order->total_price, 2) }} {{ __('app.lyd') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('app.total') }}</td>
                                            <td>{{ number_format($order->total_price, 2) }} {{ __('app.lyd') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4">
                            <a href="{{ route('customer.orders') }}" class="btn -dark">
                                <i class="fas fa-arrow-left me-2"></i>{{ __('app.back_to_orders') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add some custom CSS for this page -->
<style>
    .checkout__form {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .checkout-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
    }

    .input-validator {
        margin-bottom: 20px;
    }

    .input-validator label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #555;
    }

    .form-value {
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #f9f9f9;
        min-height: 44px;
        display: flex;
        align-items: center;
    }

    .checkout__total {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .checkout__total__price table {
        width: 100%;
        margin-bottom: 20px;
    }

    .checkout__total__price table td {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .checkout__total__price__total-count {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px solid #eee;
    }

    .checkout__total__price__total-count table td {
        font-weight: 600;
        font-size: 16px;
    }

    .btn -dark {
        background: #333;
        color: white;
        padding: 12px 25px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn -dark:hover {
        background: #555;
    }

    @media (max-width: 768px) {
        .col-lg-8, .col-lg-4 {
            padding: 0 15px;
        }

        .checkout__form, .checkout__total {
            padding: 20px;
        }
    }
</style>
@endsection
