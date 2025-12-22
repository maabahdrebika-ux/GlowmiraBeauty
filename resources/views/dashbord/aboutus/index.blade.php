@extends('layouts.app')
@section('title', trans('aboutus.showwho'))

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
                            @if(!$ab)
                            <a href="{{ route('aboutus/create') }}" class="btn btn-primary">{{ trans('aboutus.addbtn') }}</a>
                            @endif
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
                    <h4 class="mt-0 header-title">{{ trans('aboutus.showwho') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>{{ trans('aboutus.intro_one_title_ar') }}</th>
                                    <th>{{ trans('aboutus.intro_one_title_en') }}</th>
                                    <th>{{ trans('aboutus.created_date') }}</th>
                                    <th>{{ trans('aboutus.view_details') }}</th>
                                    <th>{{ trans('aboutus.edit') }}</th>
                                    <th>{{ trans('aboutus.delete') }}</th>
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
            url: '{{ route("aboutus/aboutus") }}'
        },
        columns: [
            { data: 'intro_one_title_ar' },
            { data: 'intro_one_title_en' },
            { data: 'created_at' },
            { data: 'view_details' },
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
                text: '{{ trans("aboutus.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: '{{ trans("aboutus.export_excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ trans("aboutus.columns") }}'
            }
        ]
    });
});

function confirmDelete(id) {
    if (confirm('{{ trans("aboutus.confirm_delete") }}')) {
        // Create a form to send DELETE request
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("aboutus/delete") }}/' + id;

        // Add CSRF token
        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add method spoofing for DELETE
        var methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>

@endsection
