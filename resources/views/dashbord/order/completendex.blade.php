@extends('layouts.app')
@section('title', trans('order.completed_orders'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title">{{ trans('order.completed_orders') }}</h4>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ trans('front.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('ordersindex') }}">{{ trans('order.all_orders') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('order.completed_orders') }}</li>
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
                    <h4 class="mt-0 header-title">{{ trans('order.display_orders') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{ trans('order.order_number') }}</th>
                                    <th>{{ trans('order.total') }}</th>
                                    <th>{{ trans('order.full_name') }}</th>
                                    <th>{{ trans('order.phone_number') }}</th>
                                    <th>{{ trans('order.address') }}</th>
                                    <th>{{ trans('order.order_status') }}</th>
                                    <th>{{ trans('order.order_date') }}</th>
                                    <th>{{ trans('order.order_details') }}</th>
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
    window.i18n = {
        copy: '{{ trans('order.copy') }}',
        export_to_excel: '{{ trans('order.export_to_excel') }}',
        columns: '{{ trans('order.columns') }}',
    };
</script>

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
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('complete/oreders') }}',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            {
                data: 'ordersnumber',
                defaultContent: '-'
            },
            {
                data: 'total_price',
                render: function(data, type, row) {
                    return data + " {{ trans('order.currency') }}";
                }
            },
            {
                data: 'full_name',
                defaultContent: '-'
            },
            {
                data: 'phonenumber',
                defaultContent: '-'
            },
            {
                data: 'address',
                defaultContent: '-',
                orderable: false
            },
            {
                data: 'orderstatues.state',
                defaultContent: '-'
            },
            {
                data: 'created_at',
                defaultContent: '-'
            },
            {
                data: 'orderinfo',
                orderable: false,
                searchable: false
            }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: i18n.copy
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: i18n.export_to_excel
            },
            {
                extend: 'colvis',
                text: i18n.columns
            }
        ]
    });
});
</script>

@endsection
