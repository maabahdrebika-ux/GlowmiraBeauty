<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $Invoice->invoice_number }} - {{ __('invoice.invoice_title') }}</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            direction: ltr;
            text-align: left;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }
        .invoice-container {
            width: 80%;
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            margin-bottom: 20px;
            border-bottom: 4px solid #C5979A;
        }
        .header img {
            max-height: 80px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #C5979A;
        }
        .details-table, .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table td, .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            font-size: 16px;
        }
        .items-table th {
            background: #C5979A;
            color: white;
        }
        .total {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }
        .print-button {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background: #C5979A;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            border-radius: 5px;
        }
        .print-button:hover {
            background: #C5979A;
        }
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            .invoice-container {
                width: 100%;
                max-width: 100%;
                box-shadow: none;
                border: none;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="invoice-title">{{ __('invoice.invoice_title') }}</div>
            <img src="{{ asset('public/Admin/vertical/assets/images/logo_dark.png') }}" alt="Company Logo">
        </div>

        <table class="details-table">

            <tr>
                <td><strong>{{ __('invoice.invoice_number') }}:</strong> {{ $Invoice->invoice_number }}</td>
                <td><strong>{{ __('invoice.invoice_date') }}:</strong> {{ \Carbon\Carbon::parse($Invoice->created_at)->format('Y-m-d H:i') }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('invoice.customer_name') }}:</strong> {{ $Invoice->customers->name ?? __('invoice.no_customer') }}</td>
                <td><strong>{{ __('invoice.phone_number') }}:</strong> {{ $Invoice->customers->phone ?? __('invoice.no_customer') }}</td>

            </tr>

        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>{{ __('invoice.product_name') }}</th>
                    <th>{{ __('invoice.quantity') }}</th>
                    <th>{{ __('invoice.sizes') }}</th>
                    <th>{{ __('invoice.color') }}</th>
                    <th>{{ __('invoice.price_per_piece') }}</th>
                    <th>{{ __('invoice.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($InvoiceItems as $item)
                    <tr>
                        <td>{{ $item->products->name }}</td>
                        <td>{{ $item->quantty }}</td>
                        <td>{{ $item->sizes ? $item->sizes->name : __('invoice.no_size') }}</td>
                        <td>{{ $item->coolors ? $item->coolors->name : __('invoice.no_color') }}</td>
                        <td>{{ $item->price }} {{ __('invoice.currency') }}</td>
                        <td>{{ $item->quantty * $item->price }} {{ __('invoice.currency') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">{{ __('invoice.grand_total') }}: {{ $Invoice->total_price }} {{ __('invoice.currency') }}</p>

        <div class="footer">
            {{ __('invoice.thank_you') }} | {{ __('invoice.phone_number') }}: {{$contactus->phonenumber}} | {{ __('invoice.email') }}: {{$contactus->email}}
        </div>

        <button class="print-button" onclick="window.print()">🖨️ {{ __('invoice.print_invoice') }}</button>
    </div>
</body>
</html>