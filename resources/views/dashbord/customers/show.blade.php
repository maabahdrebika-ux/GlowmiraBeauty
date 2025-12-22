@extends('layouts.app')

@section('title', trans('customers.view_customer'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title">{{ trans('customers.view_customer') }}</h4>
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ trans('front.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">{{ trans('customers.customers') }}</a></li>
                            <li class="breadcrumb-item active">{{ $customer->name }}</li>
                        </ol>
                    </div>
                    <div class="col-md-4">
                        <div class="float-right d-none d-md-block">
                            <a href="{{ route('customers.edit', $customer->id) }}"
                                class="btn btn-warning"><i class="fa fa-edit"></i> {{ trans('customers.edit') }}</a>
                            <a href="{{ route('customers.index') }}"
                                class="btn btn-secondary"><i class="fa fa-arrow-left"></i> {{ trans('customers.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-lg-6">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('customers.customer_information') }}</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <td width="30%"><strong>{{ trans('customers.name') }}:</strong></td>
                                <td>{{ $customer->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('customers.email') }}:</strong></td>
                                <td>
                                    @if($customer->email)
                                        <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('customers.phone') }}:</strong></td>
                                <td>
                                    @if($customer->phone)
                                        <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('customers.address') }}:</strong></td>
                                <td>{{ $customer->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('customers.created_at') }}:</strong></td>
                                <td>{{ $customer->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('customers.updated_at') }}:</strong></td>
                                <td>{{ $customer->updated_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Statistics -->
        <div class="col-lg-6">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('customers.customer_statistics') }}</h4>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mini-stat">
                                <h3 class="text-info">{{ $customer->invoices->count() }}</h3>
                                <p class="text-muted">{{ trans('customers.total_invoices') }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mini-stat">
                                <h3 class="text-success">
                                    {{ $customer->invoices->sum(function($invoice) {
                                        return $invoice->invoiceitems ? $invoice->invoiceitems->sum('total') : 0;
                                    }) }}
                                </h3>
                                <p class="text-muted">{{ trans('customers.total_spent') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($customer->notes)
                    <div class="mt-4">
                        <h5>{{ trans('customers.notes') }}</h5>
                        <div class="border p-3 bg-light">
                            {{ $customer->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Invoices -->
    @if($customer->invoices->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('customers.customer_invoices') }}</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('invoices.invoice_number') }}</th>
                                    <th>{{ trans('invoices.date') }}</th>
                                    <th>{{ trans('invoices.total') }}</th>
                                    <th>{{ trans('invoices.status') }}</th>
                                    <th>{{ trans('invoices.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                    <td>{{ number_format($invoice->invoiceitems ? $invoice->invoiceitems->sum('total') : 0, 2) }}</td>
                                    <td>
                                            <span class="badge badge-success">{{ trans('invoices.paid') }}</span>
                                      
                                    <td>
                                        <a href="{{ route('Invoice/show', encrypt($invoice->id)) }}"
                                           class="btn btn-sm btn-info" title="View Invoice">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body text-center">
                    <h4 class="text-muted">{{ trans('customers.no_invoices_found') }}</h4>
                    <p class="text-muted">{{ trans('customers.this_customer_has_no_invoices_yet') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection