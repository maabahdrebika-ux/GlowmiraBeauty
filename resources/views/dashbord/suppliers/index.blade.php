@extends('layouts.app')
@section('title', trans('supplier.management'))

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
                            <a href="{{ route('suppliers/create') }}" class="btn btn-primary">{{ trans('supplier.add_new') }}</a>
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
                    <h4 class="mt-0 header-title">{{ trans('supplier.management') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>{{ trans('supplier.name') }}</th>
                                    <th>{{ trans('supplier.phone') }}</th>
                                    <th>{{ trans('supplier.email') }}</th>
                                    <th>{{ trans('supplier.address') }}</th>
                                    <th>{{ trans('supplier.actions') }}</th>
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
            url: '{{ route('suppliers/data') }}',
            type: 'GET'
        },
        columns: [
            { data: 'name', defaultContent: '' },
            { data: 'phone', defaultContent: '{{ trans('supplier.no_data') }}' },
            { data: 'email', defaultContent: '{{ trans('supplier.no_data') }}' },
            { data: 'address', defaultContent: '{{ trans('supplier.no_data') }}' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: '{{ trans("supplier.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '{{ trans("supplier.export_excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ trans("supplier.show_columns") }}'
            }
        ]
    });
});
</script>
@endsection
