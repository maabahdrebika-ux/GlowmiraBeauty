@extends('layouts.app')

@section('title', trans('report.return_title'))

@section('content')

<div class="container-fluid">
    <div class="row card">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mt-0 header-title"><a href="{{ route('report/all') }}">{{ trans('report.breadcrumb') }}</a>/{{ trans('report.return_reports') }}</h4>
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
                    <h4 class="mt-0 header-title">{{ trans('report.display_returns') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{ trans('report.return_number') }}</th>
                                    <th>{{ trans('report.product_name') }}</th>
                                    <th>{{ trans('report.size') }}</th>
                                    <th>{{ trans('report.color') }}</th>
                                    <th>{{ trans('report.total') }}</th>
                                    <th>{{ trans('report.return_date') }}</th>
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

        if (!fromDate && !toDate && !operationNumber) {
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
            url: '{{ route('report.searchreturn') }}',
            method: 'GET',
            data: {
                fromDate: fromDate,
                toDate: toDate,
                operationNumber: operationNumber,
            },
            success: function(response) {
                if (!response.data || response.data.length === 0) {
                    Swal.fire({
                        title: '{{ trans('report.error') }}',
                        text: '{{ trans('report.no_returns') }}',
                        icon: 'info'
                    });
                    $('#salesTableContainer').hide();
                    return;
                }
                $('#salesTableContainer').show();
                $('#datatable-buttons tbody').empty();
                let total = 0;

                $.each(response.data, function(index, retuerns) {
                    if (retuerns === null) {
                        const nullRow = '<tr><td colspan="6">{{ trans('report.no_returns') }}</td></tr>';
                        $('#datatable-buttons tbody').append(nullRow);
                        return true;
                    }
                    total += parseFloat(retuerns.price);
                    const row = '<tr>' +
                        '<td>' + retuerns.invoices.invoice_number + '</td>' +
                        '<td>' + retuerns.products.name + '</td>' +
                        '<td>' + (retuerns.grades ? retuerns.grades.name : '{{ trans('report.no_size') }}') + '</td>' +
                        '<td>' + (retuerns.sizes ? retuerns.sizes.name : '{{ trans('report.no_color') }}') + '</td>' +
                        '<td>' + retuerns.price + ' {{ trans('report.currency') }}</td>' +
                        '<td>' + retuerns.created_at + '</td>' +
                        '</tr>';
                    $('#datatable-buttons tbody').append(row);
                });

                const totalRow = '<tr style="font-weight:bold;">' +
                    '<td colspan="3">{{ trans('report.total_amount') }}</td>' +
                    '<td>' + total.toFixed(2) + ' {{ trans('report.currency') }}</td>' +
                    '<td colspan="2"></td>' +
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
