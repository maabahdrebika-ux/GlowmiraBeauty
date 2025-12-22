@extends('layouts.app')
@section('title', trans('app.address'))

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
                            <a href="{{ route('addresses/create') }}" class="btn btn-primary">{{ trans('address.add') }}</a>
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
                    <h4 class="mt-0 header-title">{{ trans('address.showaddress') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>{{ trans('address.name_table') }}</th>
                                    <th>{{ trans('address.nameen_table') }}</th>
                                    <th>{{ trans('address.create_date_table') }}</th>
                                    <th>{{ trans('address.edit_table') }}</th>
                                    <th>{{ trans('address.delete_table') }}</th>
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
            url: '{{ route("addresses/addresses") }}'
        },
        columns: [
            { data: 'name' },
            { data: 'nameen' },
            { data: 'created_at' },
            { data: 'edit' },
            { data: 'delete' }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: '{{ trans("address.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '{{ trans("address.export_excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ trans("address.columns") }}'
            }
        ]
    });
});
</script>

@endsection
