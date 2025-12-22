@extends('layouts.app')
@section('title', trans('returns.title'))

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
                            <a href="{{ route('returns/create') }}" class="btn btn-primary">{{ trans('returns.add_return') }}</a>
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
                    <h4 class="mt-0 header-title">{{ trans('returns.display_returns') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-bordered table-hover dataTable table-custom">
                            <thead>
                                <tr>
                                    <th>{{ trans('returns.return_number') }}</th>
                                    <th>{{ trans('returns.product_image') }}</th>
                                    <th>{{ trans('returns.product_name') }}</th>
                                    <th>{{ trans('returns.sizes') }}</th>
                                    <th>{{ trans('returns.color') }}</th>
                                    <th>{{ trans('returns.total') }}</th>
                                    <th>{{ trans('returns.return_date') }}</th>
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
            $('#datatable1').DataTable({
                "language": {
                    "url": "../Arabic.json" // Ensure the path to Arabic JSON file is correct
                },
                "lengthMenu": [5, 10], // Set the page length options
                "bLengthChange": true, // Allow users to change page length
                "serverSide": true, // Enable server-side processing
                "ajax": {
                    "url": '{{ route('returns/returns') }}', // Correct the URL to your API endpoint
                    "type": 'GET', // HTTP method (GET or POST)
                    "dataSrc": function(json) {
                        return json.data; // Adjust based on the response structure from your API
                    }
                },
                "columns": [
                    { data: 'id', title: '{{ trans('returns.return_number') }}' },
                    {
                        data: 'products.image',
                        title: '{{ trans('returns.product_image') }}',
                        render: function(data, type, row) {
                            return data ? '<img src="' + data + '" alt="Product Image" class="img-thumbnail" width="50">' : '{{ trans('returns.not_available') }}';
                        }
                    },
                    { data: 'products.name', title: '{{ trans('returns.product_name') }}' },
                    {
                        data: 'sizes.name',
                        title: '{{ trans('returns.sizes') }}',
                        defaultContent: '{{ trans('returns.not_available') }}',
                        render: function(data, type, row) {
                            return data ? data : '{{ trans('returns.not_available') }}';
                        }
                    },
                    {
                        data: 'grades.name',
                        title: '{{ trans('returns.color') }}',
                        defaultContent: '{{ trans('returns.not_available') }}',
                        render: function(data, type, row) {
                            return data ? data : '{{ trans('returns.not_available') }}';
                        }
                    },
                    {
                        data: 'price',
                        title: '{{ trans('returns.total') }}',
                        render: function(data, type, row) {
                            return data + ' {{ trans('returns.currency') }}'; // Append currency
                        }
                    },
                    {
                        data: 'created_at',
                        title: '{{ trans('returns.return_date') }}',
                        render: function(data, type, row) {
                            return new Date(data).toLocaleDateString('ar-SA'); // Format date in Arabic
                        }
                    }
                ],

                "dom": 'Blfrtip', // Defines the table controls (copy, export, etc.)
                "buttons": [
                    { extend: 'copyHtml5', text: '{{ trans('returns.copy') }}' },
                    { extend: 'excelHtml5', text: '{{ trans('returns.excel') }}' },
                    { extend: 'colvis', text: '{{ trans('returns.columns') }}' }
                ]
            });
        });
    </script>
@endsection
