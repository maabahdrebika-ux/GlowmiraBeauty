@extends('front.app')

@section('title', __('order.success_title'))

@section('content')
<div class="container py-5">
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 text-center">
      <h1 class="h4 mb-2">{{ __('order.success_message') }}</h1>
      <img src="{{ asset('fulfillment.png') }}" alt="Success" class="img-fluid mb-3" style="max-width: 150px;">
      <p class="text-muted mb-4">{{ __('order.success_description') }}</p>
      @if($order)
        <p class="mb-1">{{ __('order.order_number') }}: <strong>{{ $order->ordersnumber ?? $order->id }}</strong></p>
        <p class="text-muted mb-4">{{ __('order.total') }}: {{ number_format($order->total_price, 2) }} {{ __('products.lyd') }}</p>
      @endif
      <a href="{{ route('all_products') }}" class="btn btn-primary">{{ __('order.return_shopping') }}</a>
    </div>
  </div>
</div>
@endsection