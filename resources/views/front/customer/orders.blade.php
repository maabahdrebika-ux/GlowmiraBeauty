@if($orders->count() > 0)
    <form action="#">
        <table class="table-responsive cart-wrap">
            <thead>
                <tr>
                    <th class="images images-b">{{ __('app.order_number') }}</th>
                    <th class="product">{{ __('app.date') }}</th>
                    <th class="ptice">{{ __('app.status') }}</th>
                    <th class="ptice">{{ __('app.total') }}</th>
                    <th class="action remove-b">{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td class="images">#{{ $order->id }}</td>
                        <td class="product">{{ $order->created_at->format('d : m : Y') }}</td>
                        <td class="ptice">
                            @if($order->status == 'pending')
                                <span class="stock">{{ __('app.pending') }}</span>
                            @elseif($order->status == 'processing')
                                <span class="pro">{{ __('app.processing') }}</span>
                            @elseif($order->status == 'shipped')
                                <span class="Del">{{ __('app.shipped') }}</span>
                            @elseif($order->status == 'delivered')
                                <span class="Del">{{ __('app.delivered') }}</span>
                            @elseif($order->status == 'cancelled')
                                <span class="can">{{ __('app.cancelled') }}</span>
                            @endif
                        </td>
                        <td class="">{{ number_format($order->total, 2) }} {{ __('app.lyd') }}</td>
                        <td class="action">
                            <ul>
                                <li class="w-btn-view"><a data-bs-toggle="tooltip" data-bs-html="true" title="{{ __('app.view') }}" href="{{ route('customer.order.show', $order->id) }}"><i class="fi ti-eye"></i></a></li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>
@else
    <div class="alert alert-info text-center">
        {{ __('app.no_orders_yet') }}
    </div>
@endif
