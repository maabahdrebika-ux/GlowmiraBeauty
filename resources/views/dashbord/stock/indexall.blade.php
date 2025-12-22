@extends('layouts.app')

@section('title', trans('stock.stock_title'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title">
                            <a href="{{ route('stock') }}">{{ trans('stock.stock_title') }}</a>
                            {{ trans('stock.stock_of_product') }} {{ $products->name }}
                        </h4>
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
                                    <th>{{ trans('stock.color') }}</th>
                                    <th>{{ trans('stock.sizes') }}</th>
                                    <th>{{ trans('stock.quantity') }}</th>
                                    <th>{{ trans('stock.status') }}</th>
                                </tr>
                            </thead>
                            <tbody><!-- filled by DataTables --></tbody>
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
        @if(app()->getLocale() === 'ar')
        language: {
            url: "{{ asset('Arabic.json') }}"
        },
        @endif

        lengthMenu: [5, 10],
        bLengthChange: true,
        processing: true,
        serverSide: true,

        ajax: {
            url: '{!! route('stock/stockall', ['id' => $products->id]) !!}',
            type: 'GET',
            dataSrc: function (json) {
                // تأكد أن API يعيد { data: [...] }
                return json.data || [];
            }
        },

        // الأعمدة بالترتيب نفسه في thead
        columns: [
            // اللون
            {
                data: 'gradename',
                name: 'gradename',
                defaultContent: '',
                render: function (data) {
                    return data ? data : '{{ trans("stock.not_available") }}';
                }
            },

            // المقاس
            {
                data: 'sizename',
                name: 'sizename',
                defaultContent: '',
                render: function (data) {
                    return data ? data : '{{ trans("stock.not_available") }}';
                }
            },

            // الكمية
            {
                data: 'total_quantity',
                name: 'total_quantity',
                defaultContent: 0,
                render: function (data) {
                    const qty = Number(data || 0);
                    return qty;
                }
            },

            // الحالة
            {
                data: 'total_quantity',
                orderable: false,
                searchable: false,
                render: function (data) {
                    const qty = Number(data || 0);
                    if (qty === 0) {
                        return '<span style="font-weight:bold;color:#d9534f;">{{ trans("stock.out_of_stock") }}</span>';
                    } else if (qty < 10) {
                        return '<span style="font-weight:bold;color:#f0ad4e;">{{ trans("stock.low_stock") }}</span>';
                    } else {
                        return '<span style="font-weight:bold;color:#5cb85c;">{{ trans("stock.in_stock") }}</span>';
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
