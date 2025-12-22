@extends('layouts.app')
@section('title', trans('roles.title'))

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
                            <a href="{{ route('roles.create') }}" class="btn btn-primary">{{ trans('roles.add') }}</a>
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
                    <h4 class="mt-0 header-title">{{ trans('roles.show') }}</h4>
                    <div class="table-responsive">
                        <table id="roles-table" class="table table-bordered dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>{{ trans('roles.id_table') }}</th>
                                    <th>{{ trans('roles.name_table') }}</th>
                                    <th>{{ trans('roles.permissions_table') }}</th>
                                    <th>{{ trans('roles.created_at_table') }}</th>
                                    <th>{{ trans('roles.actions_table') }}</th>
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
    if ($.fn.DataTable.isDataTable('#roles-table')) {
        $('#roles-table').DataTable().destroy();
    }
    $('#roles-table').DataTable({
        @if(App::getLocale() == 'ar')
        "language": {
            "url": "{{ asset('Arabic.json') }}"
        },
        @endif
        "lengthMenu": [5, 10],
        "bLengthChange": true,
        serverSide: true,
        ajax: {
            url: '{{ route("roles.roles") }}'
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'permissions' },
            { data: 'created_at' },
            { data: 'edit' }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: '{{ trans("roles.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '{{ trans("roles.export_excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ trans("roles.columns") }}'
            }
        ]
    });
});
</script>

@endsection