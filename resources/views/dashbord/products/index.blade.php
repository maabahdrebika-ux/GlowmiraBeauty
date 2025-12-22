@extends('layouts.app')
@section('title', __('product.products_title'))

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
                            <a href="{{ route('products/create') }}" class="btn btn-primary">{{ __('product.add_product') }}</a>
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
                    <h4 class="mt-0 header-title">{{ __('product.view_products') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered  " style="border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th>{{ __('product.cover_image') }}</th>
                                    <th>{{ __('product.category') }}</th>
                                    <th>{{ __('product.barcode') }}</th>
                                    <th>{{ __('product.product_name') }}</th>
                                    <th>{{ __('product.product_name_english') }}</th>
                                    <th>{{ __('product.brandname_ar') }}</th>
                                    <th>{{ __('product.brandname_en') }}</th>
                                    <th>{{ __('product.country_of_origin_ar') }}</th>
                                    <th>{{ __('product.country_of_origin_en') }}</th>
                                    <th>{{ __('product.color') }}</th>
                                    <th>{{ __('product.sizes') }}</th>
                                    <th>{{ __('product.product_description_arabic') }}</th>
                                    <th>{{ __('product.product_description_english') }}</th>
                                    <th>{{ __('product.selling_price') }}</th>
                                    <th>{{ __('product.available') }}</th>
                                    <th>{{ __('product.creation_date') }}</th>
                                    <th>{{ __('product.image_gallery') }}</th>
                                    <th>{{ __('product.available_unavailable') }}</th>
                                    <th>{{ __('product.edit') }}</th>
                                    <th>{{ __('product.delete') }}</th>
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
            url: '{{ route("products/products") }}'
        },
        columns: [
            { data: 'image' },
            { data: 'categories.name' },
            { data: 'barcode' },
            { data: 'name' },
            { data: 'namee' },
            { data: 'brandname_ar', render: function(data) { return data ? data : ''; } },
            { data: 'brandname_en', render: function(data) { return data ? data : ''; } },
            { data: 'country_of_origin_ar', render: function(data) { return data ? data : ''; } },
            { data: 'country_of_origin_en', render: function(data) { return data ? data : ''; } },
            { data: 'coolor' },
            { data: 'size' },
            { data: 'description_ar', render: function(data) { return data ? data : ''; } },
            { data: 'description_en', render: function(data) { return data ? data : ''; } },
            { data: 'price' },
            { data: 'is_available', render: function(data) {
                if (data == 1) {
                    return '{{ __("product.available_text") }} <i class="fa fa-circle" style="color:#2be71b;"></i>';
                } else {
                    return '{{ __("product.unavailable_text") }} <i class="fa fa-circle" style="color:#e71b1b;"></i>';
                }
            }},
            { data: 'created_at' },
            { data: 'gallery' },
            { data: 'changeStatus' },
            { data: 'edit' },
            { data: 'delete' }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: { columns: [':visible'] },
                text: '{{ __("product.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: { columns: ':visible' },
                text: '{{ __("product.export_excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ __("product.columns") }}'
            }
        ]
    });
});
</script>

@endsection
