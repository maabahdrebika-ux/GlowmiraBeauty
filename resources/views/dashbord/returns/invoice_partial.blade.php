<div class="invoice-container">
    <table class="table table-bordered mt-3">
        <tr>
            <td><strong>{{ trans('returns.invoice_number') }}:</strong> {{ $exchange->invoice_number }}</td>
            <td><strong>{{ trans('returns.date') }}:</strong> {{ $created_at }}</td>
        </tr>
        <tr>
            <td><strong>{{ trans('returns.full_name') }}:</strong> {{ $exchange->customers->name }}</td>
            <td><strong>{{ trans('returns.phone_number') }}:</strong> {{ $exchange->customers->phone }}</td>
        </tr>
    </table>

    <table class="table table-striped table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>{{ trans('returns.product_name') }}</th>
                <th>{{ trans('returns.quantity') }}</th>
                <th>{{ trans('returns.sizes') }}</th>
                <th>{{ trans('returns.color') }}</th>
                <th>{{ trans('returns.price_per_piece') }}</th>
                <th>{{ trans('returns.total') }}</th>
                <th>{{ trans('returns.return_item') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exchangeItems as $item)
                <tr>
                    <td>{{ $item->products->name }}</td>
                    <td>{{ $item->quantty }}</td>
                    <td>{{ $item->sizes ? $item->sizes->name : trans('returns.not_available') }}</td>
                    <td>{{ $item->coolors ? $item->coolors->name : trans('returns.not_available') }}</td>
                    <td>{{ $item->price }} {{ trans('returns.currency') }}</td>
                    <td>{{ $item->quantty * $item->price }} {{ trans('returns.currency') }}</td>
                    <td>
                        <button class="btn btn-danger btn-sm return-item" data-item-id="{{ $item->id }}">
 <svg xmlns="http://www.w3.org/2000/svg" 
                 width="16" height="16" viewBox="0 0 24 24" 
                 fill="none" stroke="#ffffff" stroke-width="2" 
                 stroke-linecap="round" stroke-linejoin="round"
                 style="margin-right:5px;">
                <polyline points="1 4 1 10 7 10"/>
                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
            </svg>                            {{ trans('returns.return_item') }}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-end fw-bold">{{ trans('returns.total') }}: {{ $exchange->total_price }} {{ trans('returns.currency') }}</p>
</div>

<script>
    $(document).ready(function() {
        $('.return-item').click(function() {
            let itemId = $(this).data('item-id');

            Swal.fire({
                title: "{{ trans('returns.warning') }}",
                text: "{{ trans('returns.return_item') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "{{ trans('returns.return_item') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('returns/process') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            item_id: itemId
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "{{ trans('returns.success_return') }}",
                                    text: "{{ trans('returns.added_successfully') }}",
                                    icon: "success"
                                }).then(() => {
                                    window.location.href = "{{ route('returns') }}";
                                });
                            } else {
                                Swal.fire("{{ trans('returns.error') }}", "{{ trans('returns.product_not_found') }}", "error");
                            }
                        },
                        error: function() {
                            Swal.fire("{{ trans('returns.error') }}", "{{ trans('returns.search_error') }}", "error");
                        }
                    });
                }
            });
        });
    });
</script>
