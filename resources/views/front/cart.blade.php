{{-- resources/views/front/cart.blade.php --}}
@extends('front.app')
@section('title', __('products.shopping_cart'))

@section('content')
<div class="breadcrumb">
    <div class="container">
        <h2>{{ __('products.shopping_cart') }}</h2>
        <ul>
            <li><a href="{{ route('/') }}">{{ __('products.home') }}</a></li>
            <li><a href="{{ route('all_products') }}">{{ __('products.shop') }}</a></li>
            <li class="active">{{ __('products.shopping_cart') }}</li>
        </ul>
    </div>
</div>

<div class="shop">
    <div class="container">
        @if(count($cart) > 0)
        <div class="cart">
            <div class="container">
                <div class="cart__table">
                    <div class="cart__table__wrapper">
                        <table>
                            <colgroup>
                                <col style="width: 40%"/>
                                <col style="width: 17%"/>
                                <col style="width: 17%"/>
                                <col style="width: 17%"/>
                                <col style="width: 9%"/>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>{{ __('products.product') }}</th>
                                    <th>{{ __('products.price') }}</th>
                                    <th>{{ __('products.quantity') }}</th>
                                    <th>{{ __('products.total') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $item)
                                <tr>
                                    <td>
                                        <div class="cart-product">
                                            <div class="cart-product__image">
                                                <img src="{{ $item['product_image'] ? asset('images/product/'.rawurlencode($item['product_image'])) : asset('images/product/default.png') }}"
                                                     alt="@if(app()->getLocale() == 'ar'){{ $item['product_name'] }}@else{{ $item['product_namee'] ?? $item['product_name'] }}@endif"/>
                                            </div>
                                            <div class="cart-product__content">
                                                @if($item['color'] || $item['size'])
                                                    <h5>{{ $item['color'] ?: $item['size'] }}</h5>
                                                @endif
                                                <a href="{{ route('product/info', $item['product_id']) }}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{ $item['product_name'] }}
                                                    @else
                                                        {{ $item['product_namee'] ?? $item['product_name'] }}
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item['discounted_price'], 2) }} {{ __('products.lyd') }}</td>
                                    <td>
                                        <div class="quantity-controller">
                                            <div class="quantity-controller__btn -descrease" onclick="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] - 1 }})">-</div>
                                            <div class="quantity-controller__number">{{ $item['quantity'] }}</div>
                                            <div class="quantity-controller__btn -increase" onclick="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] + 1 }})">+</div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item['discounted_price'] * $item['quantity'], 2) }} {{ __('products.lyd') }}</td>
                                    <td><a href="#" onclick="removeFromCart({{ $item['product_id'] }})"><i class="fal fa-times"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="cart__table__footer">
                        <a href="{{ route('all_products') }}"><i class="fal fa-long-arrow-left"></i>{{ __('products.continue_shopping') }}</a>
                        <a href="#" onclick="clearCart()"><i class="fal fa-trash"></i>{{ __('products.clear_shopping_cart') }}</a>
                    </div>
                </div>
                <div class="cart__total">
                    <div class="row">
                      
                        <div class="col-12 col-md-4">
                            <div class="cart__total__content">
                                <h3>{{ __('products.cart') }}</h3>
                                <table>
                                    <tbody>
                                        <tr>
                                            <th>{{ __('products.subtotal') }}</th>
                                            <td>{{ number_format($subtotal, 2) }} {{ __('products.lyd') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ __('products.total') }}</th>
                                            <td class="final-price">{{ number_format($total, 2) }} {{ __('products.lyd') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a class="btn -dark" href="{{ route('checkout') }}">{{ __('products.proceed_to_checkout') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="empty-cart text-center">
            <i style="color: #aa6969" class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
            <h3>{{ __('products.your_cart_is_empty') }}</h3>
            <p>{{ __('products.add_some_products_to_your_cart') }}</p>
            <a href="{{ route('all_products') }}" class="btn -dark">
                {{ __('products.continue_shopping') }}
            </a>
        </div>
        @endif
    </div>
</div>

<script>
function updateQuantity(productId, quantity) {
    if (quantity < 1) {
        removeFromCart(productId);
        return;
    }

    fetch(`/cart/update/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            Swal.fire({
                title: '{{ __("products.error") }}',
                text: data.message || '{{ __("products.error_updating_quantity") }}',
                icon: 'error'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: '{{ __("products.error") }}',
            text: '{{ __("products.error_updating_quantity") }}',
            icon: 'error'
        });
    });
}

function removeFromCart(productId) {
    Swal.fire({
        title: '{{ __("products.are_you_sure_remove_item") }}',
        text: '{{ __("products.remove_item_warning") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("products.yes_remove") }}',
        cancelButtonText: '{{ __("products.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("products.removed") }}',
                        text: '{{ __("products.item_removed_success") }}',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("products.error") }}',
                        text: data.message || '{{ __("products.error_removing_item") }}',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: '{{ __("products.error") }}',
                    text: '{{ __("products.error_removing_item") }}',
                    icon: 'error'
                });
            });
        }
    });
}

function clearCart() {
    Swal.fire({
        title: '{{ __("products.are_you_sure_clear_cart") }}',
        text: '{{ __("products.clear_cart_warning") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __("products.yes_clear") }}',
        cancelButtonText: '{{ __("products.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/cart/clear', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ order_status: 'cleared' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '{{ __("products.cleared") }}',
                        text: '{{ __("products.cart_cleared_success") }}',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: '{{ __("products.error") }}',
                        text: data.message || '{{ __("products.error_clearing_cart") }}',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: '{{ __("products.error") }}',
                    text: '{{ __("products.error_clearing_cart") }}',
                    icon: 'error'
                });
            });
        }
    });
}

function applyCoupon() {
    const couponCode = document.querySelector('input[name="discountCode"]').value;
    if (!couponCode) {
        Swal.fire({
            title: '{{ __("products.warning") }}',
            text: '{{ __("products.please_enter_coupon_code") }}',
            icon: 'warning'
        });
        return;
    }
    
    // Here you would typically make an AJAX call to validate the coupon
    // For now, we'll just show an alert
    Swal.fire({
        title: '{{ __("products.info") }}',
        text: '{{ __("products.coupon_validation_placeholder") }}',
        icon: 'info'
    });
}
</script>

<style>
.empty-cart {
    padding: 60px 0;
}

.cart-product {
    display: flex;
    align-items: center;
    gap: 15px;
}

.cart-product__image img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.cart-product__content h5 {
    margin: 0 0 5px 0;
    font-size: 14px;
    color: #666;
    text-transform: uppercase;
}

.cart-product__content a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
}

.cart-product__content a:hover {
    color: #ae7171;
}

.quantity-controller {
    display: flex;
    align-items: center;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    overflow: hidden;
}

.quantity-controller__btn {
    background: #f8f9fa;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.quantity-controller__btn:hover {
    background: #e9ecef;
}

.quantity-controller__number {
    padding: 8px 15px;
    border-left: 1px solid #dee2e6;
    border-right: 1px solid #dee2e6;
    min-width: 50px;
    text-align: center;
}

.cart__table__footer {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
}

.cart__table__footer a {
    color: #ae7171;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.cart__table__footer a:hover {
    color: #0056b3;
}

.cart__total {
    margin-top: 40px;
}

.cart__total__discount {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 8px;
}

.cart__total__discount p {
    margin-bottom: 15px;
    color: #666;
}

.input-validator {
    display: flex;
    gap: 10px;
}

.input-validator input {
    flex: 1;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
}

.cart__total__content {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 8px;
}

.cart__total__content h3 {
    margin-bottom: 20px;
    font-size: 24px;
}

.cart__total__content table {
    width: 100%;
    margin-bottom: 20px;
}

.cart__total__content th,
.cart__total__content td {
    padding: 10px 0;
    border-bottom: 1px solid #dee2e6;
}

.cart__total__content th {
    text-align: left;
    font-weight: 600;
}

.cart__total__content .final-price {
    font-size: 20px;
    font-weight: bold;
    color: #ae7171;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.btn.-dark {
    background: #343a40;
    color: white;
}

.btn.-dark:hover {
    background: #495057;
}

@media (max-width: 768px) {
    .cart__table__wrapper {
        overflow-x: auto;
    }
    
    .cart__table__footer {
        flex-direction: column;
        gap: 15px;
    }
    
    .input-validator {
        flex-direction: column;
    }
    
    .cart-product {
        flex-direction: column;
        text-align: center;
    }
    
    .cart-product__image img {
        width: 60px;
        height: 60px;
    }
}
</style>

@endsection