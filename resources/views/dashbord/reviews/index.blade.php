@extends('layouts.app')
@section('title', __('reviews.management_title'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title">{{ __('reviews.management_title') }}</h4>
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
                    <h4 class="mt-0 header-title">{{ __('reviews.all_reviews') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>{{ __('reviews.customer_name') }}</th>
                                    <th>{{ __('reviews.product_name') }}</th>
                                    <th>{{ __('reviews.rating') }}</th>
                                    <th>{{ __('reviews.comment') }}</th>
                                    <th>{{ __('reviews.status') }}</th>
                                    <th>{{ __('reviews.verified_purchase') }}</th>
                                    <th>{{ __('reviews.created_at') }}</th>
                                    <th>{{ __('reviews.delete') }}</th>
                                    <th>{{ __('reviews.change_status') }}</th>
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
        "lengthMenu": [10, 25, 50, 100],
        "bLengthChange": true,
        serverSide: true,
        ajax: {
            url: '{{ route("reviews.reviews") }}'
        },
        columns: [
            { data: 'customer_name' },
            { data: 'product_name' },
            { data: 'rating' },
            { data: 'comment' },
            { data: 'status' },
            { data: 'verified' },
            { data: 'created_at' },
            { data:'delete' },
            { data: 'change_status' }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: "{{ __('reviews.copy') }}"
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: "{{ __('reviews.export_to_excel') }}"
            },
            {
                extend: 'colvis',
                text: "{{ __('reviews.columns') }}"
            }
        ]
    });
});
</script>

@endsection