@extends('layouts.app')
@section('title', __('invoice.sale_details'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mt-0 header-title"><a href="{{ route('Invoice') }}">{{ __('invoice.sale') }}</a> / {{ __('invoice.sale_details') }} {{ $Invoice->invoice_number }}</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="float-right d-none d-md-block">
                            <button class="btn btn-primary" onclick="printSale()">
                                <i class="fa fa-print"></i> {{ __('invoice.print') }}
                            </button>
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

                    <!-- قسم الطباعة -->
                    <div class="printable-section" id="printableSection" style="border: 1px solid #ddd; padding: 20px; background: white;">

                        <!-- شعار الشركة -->
                        <div class="text-center">
                            <img src="{{ asset('Admin/vertical/assets/images/logo_dark.png') }}" alt="شعار الشركة" style="max-width: 150px; margin-bottom: 20px;">
                        </div>

                        <h4 style="color: #C5979A; font-size: 24px; font-weight: bold; margin-bottom: 20px;">{{ __('invoice.sale_details') }}</h4>

                        <div class="text-right" style="margin-bottom: 20px;">
                            <p><strong>{{ __('invoice.sale_number') }}:</strong> {{ $Invoice->invoice_number }}</p>
                            <p><strong>{{ __('invoice.full_name') }}:</strong> {{ $Invoice->full_name }}</p>
                            <p><strong>{{ __('invoice.date') }}:</strong> {{ \Carbon\Carbon::parse($Invoice->created_at)->format('Y-m-d H:i') }}</p>
                            <p><strong>{{ __('invoice.total') }}:</strong> {{ number_format($Invoice->total_price, 2) }} {{ __('invoice.currency') }}</p>
                            <p><strong>{{ __('invoice.Invoice_type') }}:</strong> {{ $Invoice->Invoicestypes->name ?? __('invoice.not_available') }}</p>
                        </div>

                        <!-- جدول العناصر -->
                        <table class="table table-bordered text-center">
                            <thead style="text-align: center">
                                <tr style="background: #C5979A; color: white;">
                                    <th style="text-align: center">{{ __('invoice.barcode') }}</th>
                                    <th style="text-align: center">{{ __('invoice.product_image') }}</th>
                                    <th style="text-align: center">{{ __('invoice.product_name') }}</th>
                                    <th style="text-align: center">{{ __('invoice.sizes') }}</th>
                                    <th style="text-align: center">{{ __('invoice.color') }}</th>
                                    <th style="text-align: center">{{ __('invoice.quantity') }}</th>
                                    <th style="text-align: center">{{ __('invoice.price_per_piece') }}</th>
                                    <th style="text-align: center">{{ __('invoice.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($InvoiceItems as $item)
                                    <tr>
                                        <td>{{ $item->products->barcode }}</td>
                                        <td>
                                            <img src="{{ asset('images/product/' . $item->products->image) }}" alt="Product Image" class="img-thumbnail" width="100">
                                        </td>
                                        <td>{{ $item->products->name }}</td>
                                        <td>{{ $item->sizes?->name ?? __('invoice.not_available') }}</td>
                                        <td>{{ $item->coolors?->name ?? __('invoice.not_available') }}</td>
                                        <td>{{ $item->quantty }}</td>
                                        <td>{{ number_format($item->price, 2) }} {{ __('invoice.currency') }}</td>
                                        <td>{{ number_format($item->quantty * $item->price, 2) }} {{ __('invoice.currency') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        function printSale() {
            var content = document.getElementById('printableSection').innerHTML;
            var newWindow = window.open('', '', 'width=800,height=600');

            newWindow.document.write('<html><head><title>{{ __('invoice.print_sale') }}</title>');
            newWindow.document.write('<link rel="stylesheet" href="{{ asset('css/app.css') }}">');
            newWindow.document.write('<style>');
            newWindow.document.write('body { font-family: Arial, sans-serif; text-align: right; direction: rtl; }');
            newWindow.document.write('.table { width: 100%; border-collapse: collapse; border: 1px solid #000; }');
            newWindow.document.write('.table th, .table td { border: 1px solid #000; padding: 8px; text-align: center; }');
            newWindow.document.write('.table th { background-color: #C5979A; color: white; }');
            newWindow.document.write('img { display: block; margin: 0 auto 20px; }');
            newWindow.document.write('</style>');

            newWindow.document.write('</head><body>');
            newWindow.document.write(content);
            newWindow.document.write('</body></html>');

            newWindow.document.close();
            newWindow.focus();
            newWindow.print();
            newWindow.close();
        }
    </script>
@endsection

