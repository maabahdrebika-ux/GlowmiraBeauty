@extends('layouts.app')
@section('title', trans('category.title'))

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
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">{{ trans('category.add') }}</a>
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
                    <h4 class="mt-0 header-title">{{ trans('category.show') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>{{ trans('category.name_table') }}</th>
                                    <th>{{ trans('category.englishname_table') }}</th>
                                    <th>{{ trans('category.create_date_table') }}</th>
                                    <th>{{ trans('category.status_table') }}</th>
                                    <th>{{ trans('category.edit_table') }}</th>
                                    <th>{{ trans('category.activate_deactivate') }}</th>
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
            url: '{{ route("categories.categories") }}'
        },
        columns: [
            { data: 'name' },
            { data: 'englishname' },
            { data: 'created_at' },
            { data: 'status' },
            { data: 'edit' },
            { data: 'change_status' }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: '{{ trans("category.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '{{ trans("category.export_excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ trans("category.columns") }}'
            }
        ]
    });
});
</script>

@endsection
