@extends('layouts.app')
@section('title', trans('receipt.title'))

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
                            <a href="{{ route('receipts/create') }}" class="btn btn-primary">{{ trans('receipt.add_receipt') }}</a>
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
                    <h4 class="mt-0 header-title">{{ trans('receipt.display_receipt') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-bordered table-hover dataTable table-custom">
                            <thead>
                                <tr>
                                    <th>{{ trans('receipt.receipt_number') }}</th>
                                    <th>{{ trans('receipt.supplier') }}</th>
                                    <th>{{ trans('receipt.total') }}</th>
                                    <th>{{ trans('receipt.show_details') }}</th>
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
      $(document).ready(function () {
    $('#datatable1').DataTable({
        "language": {
            "url": "../Arabic.json" // Ensure the path to Arabic JSON file is correct
        },
        "lengthMenu": [5, 10],  // Set the page length options
        "bLengthChange": true,  // Allow users to change page length
        "serverSide": true,  // Enable server-side processing
        "ajax": {
            "url": '{{ route('receipts/receipts') }}', // Correct the URL to your API endpoint
            "type": 'GET',  // HTTP method (GET or POST)
            "dataSrc": function (json) {
                return json.data; // Adjust based on the response structure from your API
            }
        },
        "columns": [
            { data: 'receiptnumber', title: '{{ trans('receipt.receipt_number') }}' },
            {
                data: 'suppliers.name',
                title: '{{ trans('receipt.supplier') }}',
                defaultContent: '{{ trans('receipt.not_available') }}',
                render: function(data, type, row) {
                    return data ? data : '{{ trans('receipt.not_available') }}';
                }
            },
            {
                data: 'total_price',
                title: '{{ trans('receipt.total') }}',
                render: function(data, type, row) {
                    return data + ' {{ trans('receipt.currency') }}'; // Append currency
                }
            },
            { data: 'showall', title: '{{ trans('receipt.show_details') }}' },

        ],
        "dom": 'Blfrtip',  // Defines the table controls (copy, export, etc.)
        "buttons": [
            { extend: 'copyHtml5', text: '{{ trans('receipt.copy') }}' },
            { extend: 'excelHtml5', text: '{{ trans('receipt.excel') }}' },
            { extend: 'colvis', text: '{{ trans('receipt.columns') }}' }
        ]
    });
});

    </script>
@endsection
