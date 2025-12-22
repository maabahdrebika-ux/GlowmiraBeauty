@extends('layouts.app')

@section('title', trans('stock.stock_title'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                    </div>
                    <div class="col-md-4">
                        <div class="float-right d-none d-md-block">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('stock.view_stock') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>{{ trans('stock.image') }}</th> <!-- New column for product images -->
                                    <th>{{ trans('stock.barcode') }}</th>
                                    <th>{{ trans('stock.product_name') }}</th>
                                    <th>{{ trans('stock.quantity') }}</th>
                                    <th>{{ trans('stock.details') }}</th>
                                    <th>{{ trans('stock.status') }}</th>
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
            url: '{!! route('stock/stock') !!}'
        },
        columns: [
            {
                data: 'image', // New column for product images
                render: function(data, type, row) {
                    return `<img src="${data}" alt="Product Image" class="img-thumbnail" width="100">`;
                }
            },
            { data: 'productsbarcode' },
            { data: 'productsname' },
            { data: 'total_quantity' },
            { data: 'show' },
            {
                data: 'total_quantity',
                render: function (data, type, row) {
                    if (data == 0) {
                        return '<span style="font-weight: bold; color:red;">{{ trans("stock.out_of_stock") }}</span>';
                    } else if (data < 10) {
                        return '<span style="font-weight: bold; color:orange;">{{ trans("stock.low_stock") }}</span>';
                    } else {
                        return '<span style="font-weight: bold; color:green;">{{ trans("stock.in_stock") }}</span>';
                    }
                }
            }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: '{{ trans("stock.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '{{ trans("stock.export_excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ trans("stock.show_columns") }}'
            }
        ]
    });
});
</script>
@endsection
