@extends('layouts.app')

@section('title', trans('report.title'))

@section('content')

<div class="container-fluid">
    <div class="row card">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mt-0 header-title"><a href="{{ route('report/all') }}">{{ trans('report.breadcrumb') }}</a>/{{ trans('report.sales_reports') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 card">
            <div class="m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('report.search') }}</h4>
                    <form id="searchForm" class="form-inline">
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="fromDate" class="sr-only">{{ trans('report.from_date') }}</label>
                            <input type="date" class="form-control" id="fromDate" placeholder="{{ trans('report.from_date') }}">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="toDate" class="sr-only">{{ trans('report.to_date') }}</label>
                            <input type="date" class="form-control" id="toDate" placeholder="{{ trans('report.to_date') }}">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="operationNumber" class="sr-only">{{ trans('report.operation_number') }}</label>
                            <input type="text" class="form-control" id="operationNumber" placeholder="{{ trans('report.operation_number') }}">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="phoneNumber" class="sr-only">{{ trans('report.phone_number') }}</label>
                            <input type="text" class="form-control" id="phoneNumber" placeholder="{{ trans('report.phone_number') }}">
                        </div>
                        <div class="form-group mx-sm-3 mb-2">
                            <label for="customerName" class="sr-only">{{ trans('report.customer_name') }}</label>
                            <input type="text" class="form-control" id="customerName" placeholder="{{ trans('report.customer_name') }}">
                        </div>
                        <button onclick="onSearchClick()" type="button" class="btn btn-primary mb-2" id="searchButton">{{ trans('report.search_button') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="salesTableContainer" style="display: none;">
        <div class="col-lg-12 card">
            <div class="m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('report.display_sales') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{ trans('report.operation_number_table') }}</th>
                                    <th>{{ trans('report.full_name') }}</th>
                                    <th>{{ trans('report.phone_number_table') }}</th>
                                    <th>{{ trans('report.total') }}</th>
                                    <th>{{ trans('report.exchange_type') }}</th>
                                    <th>{{ trans('report.sale_date') }}</th>
                                    <th>{{ trans('report.show_details') }}</th>
                                    <th>{{ trans('report.show_invoice') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function onSearchClick() {
        const fromDate = $('#fromDate').val();
        const toDate = $('#toDate').val();
        const operationNumber = $('#operationNumber').val();
        const phoneNumber = $('#phoneNumber').val();
        const customerName = $('#customerName').val();

        if (!fromDate && !toDate && !operationNumber && !phoneNumber && !customerName) {
            Swal.fire({
                title: '{{ trans('report.error') }}',
                text: '{{ trans('report.error_message') }}',
                icon: 'error'
            });
            return;
        }

        if (fromDate && toDate && fromDate > toDate) {
            Swal.fire({
                title: '{{ trans('report.error') }}',
                text: '{{ trans('report.date_error') }}',
                icon: 'error'
            });
            return;
        }

        $.ajax({
            url: '{{ route('report.searchSales') }}',
            method: 'GET',
            data: {
                fromDate: fromDate,
                toDate: toDate,
                operationNumber: operationNumber,
                phoneNumber: phoneNumber,
                customerName: customerName
            },
            success: function(response) {
                if (!response.data || response.data.length === 0) {
                    Swal.fire({
                        title: '{{ trans('report.error') }}',
                        text: '{{ trans('report.no_sales') }}',
                        icon: 'info'
                    });
                    $('#salesTableContainer').hide();
                    return;
                }
                $('#salesTableContainer').show();
                $('#datatable-buttons tbody').empty();
                let total = 0;

                $.each(response.data, function(index, sale) {
                    total += parseFloat(sale.total_price);
                    const row = '<tr>' +
                        '<td>' + sale.invoice_number + '</td>' +
                        '<td>' + sale.customers.name + '</td>' +
                        '<td>' + sale.customers.phone + '</td>' +
                        '<td>' + sale.total_price + ' {{ trans('report.currency') }}</td>' +
                        '<td>' + sale.invoice_types.name + '</td>' +
                        '<td>' + sale.created_at + '</td>' +
                        '<td>' + sale.showall + '</td>' +
                        '<td>' + sale.invoice + '</td>' +
                        '</tr>';
                    $('#datatable-buttons tbody').append(row);
                });

                const totalRow = '<tr style="font-weight:bold;">' +
                    '<td colspan="3">{{ trans('report.total_amount') }}</td>' +
                    '<td>' + total.toFixed(2) + ' {{ trans('report.currency') }}</td>' +
                    '<td colspan="3"></td>' +
                    '</tr>';
                $('#datatable-buttons tbody').append(totalRow);
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : '{{ trans('report.data_error') }}';
                Swal.fire({
                    title: '{{ trans('report.error') }}',
                    text: errorMsg,
                    icon: 'error'
                });
            }
        });
    }

</script>

@endsection
