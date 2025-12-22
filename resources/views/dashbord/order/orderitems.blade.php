    @extends('layouts.app')
@section('title', trans('order.order_details'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title">{{ trans('order.order_details') }}</h4>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ trans('front.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('ordersindex') }}">{{ trans('order.all_orders') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('order.order_details') }} {{ $order->ordersnumber }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('order.order_details') }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('order.order_number') }}</th>
                                    <th>{{ trans('order.total') }}</th>
                                    <th>{{ trans('order.full_name') }}</th>
                                    <th>{{ trans('order.phone_number') }}</th>
                                    <th>{{ trans('order.address') }}</th>
                                    <th>{{ trans('order.order_date') }}</th>
                                    <th>{{ trans('order.order_status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $order->ordersnumber }}</td>
                                    <td>{{ $order->total_price }}</td>
                                    <td>{{ $order->customer ? $order->customer->name : $order->full_name }}</td>
                                    <td>{{ $order->customer ? $order->customer->phone : $order->phonenumber }}</td>
                                    <td>{{ $order->customer ? $order->customer->address : $order->address }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->orderstatues->state ?? trans('order.not_available') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if($order->orderstatues->id == 1)
                        <form action="{{ route('order.update', encrypt($order->id)) }}" method="POST" class="mt-4">
                            @csrf
                            @method('PUT')
                            <h4 class="mt-0 header-title">{{ trans('order.order_details') }}</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('order.image') }}</th>
                                            <th>{{ trans('order.product') }}</th>
                                            <th>{{ trans('order.color') }}</th>
                                            <th>{{ trans('order.size') }}</th>
                                            <th>{{ trans('order.available_stock') }}</th>
                                            <th>{{ trans('order.required_quantity') }}</th>
                                            <th>{{ trans('order.price') }}</th>
                                            <th>{{ trans('order.remove') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ordersitem as $item)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('images/product/' . $item->products->image) }}" alt="Product Image" class="img-thumbnail" width="100">
                                            </td>
                                            <td>{{ $item->products->name }}</td>
                                            <td>{{ $item->coolors->name ?? trans('order.not_specified') }}</td>
                                            <td>{{ $item->sizes->name ?? trans('order.not_specified') }}</td>
                                            <td>{{ $item->availableStock > 0 ? $item->availableStock : 0 }}</td>
                                            <td>
                                                <input type="number" name="quantities[{{ $item->id }}]" value="{{ $item->quantty }}" min="1" max="{{ $item->availableStock }}" class="form-control form-control-sm">
                                            </td>
                                            <td>{{ $item->price }} {{ trans('order.currency') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeItemFromOrder({{ $item->id }}, '{{ route('order.item.remove', encrypt($item->id)) }}')">{{ trans('order.remove') }}</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-5 me-2">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-label="Refresh icon">
  <g fill="none" stroke="#C5979A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M21 12a9 9 0 1 1-2.64-6.36"/>
    <polyline points="21 3 21 8 16 8"/>
  </g>
</svg>
 {{ trans('order.update_order') }}
                                </button>
                                @if($order->orderstatues->id != 4)
                                    <a href="javascript:void(0)" class="btn btn-danger btn-lg px-5 me-2" onclick="confirmCancel('{{ route('pending/cancelfunction', encrypt($order->id)) }}')">
                                        <i class="fa fa-times"></i> {{ trans('order.cancel_order') }}
                                    </a>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            document.querySelector('.btn-danger').addEventListener('click', function () {
                                                Swal.fire({
                                                    title: '{{ trans('order.cancelled') }}',
                                                    text: '{{ trans('order.order_cancelled_successfully') }}',
                                                    icon: 'success',
                                                    confirmButtonText: '{{ trans('order.ok') }}'
                                                });
                                            });
                                        });
                                    </script>
                                @endif
                            </div>
                        </form>
                    @else
                        <h4 class="mt-0 header-title">{{ trans('order.order_details') }} ({{ trans('order.display_orders') }})</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ trans('order.image') }}</th>
                                        <th>{{ trans('order.product') }}</th>
                                        <th>{{ trans('order.color') }}</th>
                                        <th>{{ trans('order.size') }}</th>
                                        <th>{{ trans('order.available_stock') }}</th>
                                        <th>{{ trans('order.required_quantity') }}</th>
                                        <th>{{ trans('order.price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordersitem as $item)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('images/product/' . $item->products->image) }}" alt="Product Image" class="img-thumbnail" width="100">
                                        </td>
                                        <td>{{ $item->products->name }}</td>
                                        <td>{{ $item->coolors->name ?? trans('order.not_specified') }}</td>
                                        <td>{{ $item->sizes->name ?? trans('order.not_specified') }}</td>
                                        <td>{{ $item->availableStock > 0 ? $item->availableStock : 0 }}</td>
                                        <td>{{ $item->quantty }}</td>
                                        <td>{{ $item->price }} {{ trans('order.currency') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($order->orderstatues->id !== 4)

                        @if(isset($insufficientItems) && !$canProceed)
                            <div class="alert alert-danger mt-4">
                                <h4>{{ trans('order.cannot_execute_order') }}</h4>
                                <p>{{ trans('order.products_not_available') }}</p>
                                <ul>
                                    @foreach($insufficientItems as $item)
                                        <li>
                                            {{ trans('order.product') }}: {{ $item['product_name'] }} -
                                            {{ trans('order.color') }}: {{ $item['color'] }} -
                                            {{ trans('order.size') }}: {{ $item['size'] }} -
                                            {{ trans('order.required_quantity') }}: {{ $item['required_quantity'] }} -
                                            {{ trans('order.available_stock') }}: {{ $item['available_stock'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif

                    @if($canProceed && $order->orderstatues->id == 1)
                        <form action="{{ route('pending/preparationfuction', encrypt($order->id)) }}" method="POST" class="mt-4 text-center">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fa fa-play"></i> {{ trans('order.place_in_preparation') }}
                            </button>
                        </form>
                    @endif

                    @if($order->orderstatues->id == 2)
                        <form action="{{ route('order.complete', encrypt($order->id)) }}" method="POST" class="mt-4 text-center">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fa fa-check"></i> {{ trans('order.make_complete') }}
                            </button>
                        </form>
                        <form action="{{ route('pending/cancelfunction', encrypt($order->id)) }}" method="POST" class="mt-4 text-center">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg px-5">
                                <i class="fa fa-times"></i> {{ trans('order.cancel_order') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function removeItem(itemId) {
        Swal.fire({
            title: 'هل تريد إزالة هذا العنصر من الطلبية؟',
            text: "لن تتمكن من التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، قم بالإزالة',
            cancelButtonText: 'لا'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(`input[name="quantities[${itemId}]"]`).value = 0;
            }
        });
    }

    function confirmCancel(url) {
        Swal.fire({
            title: 'هل تريد إلغاء هذه الطلبية؟',
            text: "لن تتمكن من التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، قم بالإلغاء',
            cancelButtonText: 'لا'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        Swal.fire(
                            '{{ trans('order.cancelled') }}',
                            '{{ trans('order.order_cancelled_successfully') }}',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            '{{ trans('order.error') }}',
                            '{{ trans('order.error_cancelling_order') }}',
                            'error'
                        );
                    }
                });
            }
        });
    }

    function removeItemFromOrder(itemId, url) {
        Swal.fire({
            title: 'هل تريد إزالة هذا العنصر من الطلبية؟',
            text: "لن تتمكن من التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، قم بالإزالة',
            cancelButtonText: 'لا'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        Swal.fire(
                            '{{ trans('order.remove') }}!',
                            '{{ trans('order.remove') }}.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            '{{ trans('order.error') }}!',
                            '{{ trans('order.error_removing_item') }}.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@endsection
