@extends('layouts.app')
@section('title', trans('invoice.title'))
@section('content')

<div class="container-fluid">
    <div class="row card">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mt-0 header-title"><a href="{{ route('Invoice') }}">{{ trans('invoice.breadcrumb') }}</a>/{{ trans('invoice.display_sale') }}</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="float-right d-none d-md-block">
                            <a href="{{ route('Invoice/create') }}" class="btn btn-primary">{{ trans('invoice.add_sale') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 card">
            <div class="m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('invoice.display_sale') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{ trans('invoice.sale_number') }}</th>
                                    <th>{{ trans('invoice.full_name') }}</th>
                                    <th>{{ trans('invoice.total') }}</th>
                                    <th>{{ trans('invoice.exchange_type') }}</th>
                                    <th>{{ trans('invoice.show_details') }}</th>
                                    <th>{{ trans('invoice.print') }}</th>
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
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#datatable-buttons')) {
        $('#datatable-buttons').DataTable().destroy();
    }
    $('#datatable-buttons').DataTable({
        @if(App::getLocale() == 'ar')
        "language": {
            "url": "{{ asset('Arabic.json') }}"
        },
        @endif
        "lengthMenu": [5, 10],
        "bLengthChange": true,
        serverSide: true,
        ajax: {
            url: '{{ route("Invoice/Invoice") }}'
        },
        columns: [
            { data: 'invoice_number' },
            { data: 'customers.name' },
            {
                data: 'total_price',
                render: function(data, type, row) {
                    return data + ' {{ trans('invoice.currency') }}';
                }
            },
            {
                data: 'invoice_types.name',
                defaultContent: '{{ trans('invoice.not_available') }}',
                render: function(data, type, row) {
                    return data ? data : '{{ trans('invoice.not_available') }}';
                }
            },
            { data: 'showall' },
            { data: 'invoice' }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: '{{ trans("invoice.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '{{ trans("invoice.excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ trans("invoice.columns") }}'
            }
        ]
    });
});
</script>

@endsection
