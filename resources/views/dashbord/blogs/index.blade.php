@extends('layouts.app')
@section('title', trans('blog.title'))

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
                            <a href="{{ route('blogs.create') }}" class="btn btn-primary">{{ trans('blog.add') }}</a>
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
                    <h4 class="mt-0 header-title">{{ trans('blog.show') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>{{ trans('blog.title_ar_table') }}</th>
                                    <th>{{ trans('blog.title_en_table') }}</th>
                                    <th>{{ trans('blog.image_table') }}</th>
                                    <th>{{ trans('blog.create_date_table') }}</th>
                                    <th>{{ trans('blog.status_table') }}</th>
                                    <th>{{ trans('blog.edit_table') }}</th>
                                    <th>{{ trans('blog.activate_deactivate') }}</th>
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
            url: '{{ route("blogs.blogs") }}'
        },
        columns: [
            { data: 'title_ar' },
            { data: 'title_en' },
            {
                data: 'image',
                orderable: false,
                render: function(data, type, row) {
                    if (data) {
                        return '<img src="' + '{{ asset("images/blogs/") }}/' + data + '" alt="Blog Image" style="width: 60px; height: 40px; object-fit: cover;">';
                    }
                    return 'No Image';
                }
            },
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
                text: '{{ trans("blog.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '{{ trans("blog.export_excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ trans("blog.columns") }}'
            }
        ]
    });
});
</script>

@endsection
