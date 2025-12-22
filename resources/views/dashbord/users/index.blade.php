@extends('layouts.app')
@section('title', trans('app.users'))

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
                            <a href="{{ route('users/create') }}" class="btn btn-primary">{{ trans('users.add') }}</a>
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
                    <h4 class="mt-0 header-title">{{ trans('users.show_users') }}</h4>

                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{ trans('users.username') }}</th>
                                    <th>{{ trans('users.first_name') }}</th>
                                    <th>{{ trans('users.last_name') }}</th>
                                    <th>{{ trans('users.email') }}</th>
                                    <th>{{ trans('users.phonenumber') }}</th>
                                    <th>{{ trans('users.address') }}</th>
                                    <th>{{ trans('users.active') }}</th>
                                    <th>{{ trans('users.created_at') }}</th>
                                    <th>{{ trans('users.activate_deactivate') }}</th>
                                    <th>{{ trans('users.edit') }}</th>
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
            url: '{{ route("users/users") }}'
        },
        columns: [
            { data: 'username' },
            { data: 'first_name' },
            { data: 'last_name' },
            { data: 'email' },
            { data: 'phonenumber' },
            { data: 'address.name' },
            {
                data: 'active',
                render: function(data) {
                    if (data == 1) {
                        return '{{ trans("users.account_active") }} <i class="fa fa-circle" style="color:#2be71b;" aria-hidden="true"></i>';
                    } else {
                        return '{{ trans("users.account_inactive") }} <i class="fa fa-circle" style="color:#e71b1b;" aria-hidden="true"></i>';
                    }
                }
            },
            { data: 'created_at' },
            { data: 'changeStatus' },
            { data: 'edit' }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: '{{ trans("users.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '{{ trans("users.export_excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ trans("users.columns") }}'
            }
        ]
    });
});
</script>

@endsection
