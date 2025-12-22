@extends('layouts.app')
@section('title', trans('dashboard.dashboard'))

@section('content')
  <div class="container-fluid">
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">{{ trans('dashboard.dashboard') }}</h4>
                </div>
              
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary mini-stat text-white">
            <div class="p-3 mini-stat-desc">
                <div class="clearfix">
                    <h6 class="text-uppercase mt-0 float-left text-white-50">{{ trans('dashboard.orders') }}</h6>
                    <h4 class="mb-3 mt-0 float-right">{{ \App\Models\Order::count() }}</h4>
                </div>
                <div>
                    <span class="badge badge-light text-info"> +11% </span> <span class="ml-2">{{ trans('dashboard.from_previous_period') }}</span>
                </div>
            </div>
            <div class="p-3">
                <div class="float-right">
                    <a href="{{ route('all/oreder') }}" class="text-white-50"><i class="mdi mdi-cube-outline h5"></i></a>
                </div>
                <p class="font-14 m-0">{{ trans('dashboard.last') }}: {{ \App\Models\Order::latest()->first()->id ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-info mini-stat text-white">
            <div class="p-3 mini-stat-desc">
                <div class="clearfix">
                    <h6 class="text-uppercase mt-0 float-left text-white-50">{{ trans('dashboard.revenue') }}</h6>
                    <h4 class="mb-3 mt-0 float-right">{{ number_format(\App\Models\Invoice::sum('total_price') ?: 0, 0) }} {{trans('app.lyd')}}</h4>
                </div>
                <div>
                  <span class="ml-2">{{ trans('dashboard.from_previous_period') }}</span>
                </div>
            </div>
            <div class="p-3">
                <div class="float-right">
                    <a href="{{ route('report/sales') }}" class="text-white-50"><i class="mdi mdi-buffer h5"></i></a>
                </div>
                <p class="font-14 m-0">{{ trans('dashboard.last') }}: {{ number_format(\App\Models\Invoice::latest()->first()->total_price ?? 0, 0) }} {{trans('app.lyd')}}</p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-pink mini-stat text-white">
            <div class="p-3 mini-stat-desc">
                <div class="clearfix">
                    <h6 class="text-uppercase mt-0 float-left text-white-50">{{ trans('dashboard.average_price') }}</h6>
                    <h4 class="mb-3 mt-0 float-right">{{ number_format(\App\Models\Product::avg('price') ?: 15.9, 1) }}</h4>
                </div>
                <div>
                   <span class="ml-2">{{ trans('dashboard.from_previous_period') }}</span>
                </div>
            </div>
            <div class="p-3">
                <div class="float-right">
                    <a href="{{ route('products') }}" class="text-white-50"><i class="mdi mdi-tag-text-outline h5"></i></a>
                </div>
                <p class="font-14 m-0">{{ trans('dashboard.last') }}: {{ number_format(\App\Models\Product::latest()->first()->price ?? 0, 1) }}</p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-success mini-stat text-white">
            <div class="p-3 mini-stat-desc">
                <div class="clearfix">
                    <h6 class="text-uppercase mt-0 float-left text-white-50">{{ trans('dashboard.product_sold') }}</h6>
                    <h4 class="mb-3 mt-0 float-right">{{ \App\Models\InvoiceItem::sum('quantty') ?: '1890' }}</h4>
                </div>
                <div>
                    <span class="ml-2">{{ trans('dashboard.from_previous_period') }}</span>
                </div>
            </div>
            <div class="p-3">
                <div class="float-right">
                    <a href="{{ route('stock') }}" class="text-white-50"><i class="mdi mdi-briefcase-check h5"></i></a>
                </div>
                <p class="font-14 m-0">{{ trans('dashboard.last') }}: {{ \App\Models\InvoiceItem::latest()->first()->quantty ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Orders and Sales Charts Section -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">{{ trans('dashboard.orders_chart') }}</h4>
                <canvas id="orders-chart" style="height: 300px !important;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">{{ trans('dashboard.sales_chart') }}</h4>
                <canvas id="sales-chart" style="height: 300px !important;"></canvas>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title mb-4">{{ trans('dashboard.latest_messages') }}</h4>
                <div class="latest-massage">
                    @php
                        $latestMessages = \App\Models\Inbox::latest()->take(5)->get();
                    @endphp
                    @forelse($latestMessages as $message)
                        <a href="{{ route('inbox') }}" class="latest-message-list">
                            <div class="border-bottom position-relative">
                                <div class="float-left user mr-3">
                                    <h5 class="bg-primary text-center rounded-circle text-white mt-0">{{ substr($message->name, 0, 1) }}</h5>
                                </div>
                                <div class="message-time">
                                    <p class="m-0 text-muted">{{ $message->created_at }}</p>
                                </div>
                                <div class="massage-desc">
                                    <h5 class="font-14 mt-0 text-dark">{{ $message->name }}</h5>
                                    <p class="text-muted">{{ Str::limit($message->message, 50) }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center text-muted">{{ trans('dashboard.no_messages') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title mb-4">{{ trans('dashboard.recent_activity') }}</h4>
                <ol class="activity-feed mb-0">
                    @php
                        $recentActivities = \App\Models\Order::latest()->take(4)->get();
                    @endphp
                    @forelse($recentActivities as $activity)
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date text-white-50">{{ \Carbon\Carbon::parse($activity->created_at)->format('M j') }}</span>
                                <span class="activity-text text-white">{{ trans('dashboard.order_placed') }} #{{ $activity->id }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="feed-item">
                            <div class="feed-item-list">
                                <span class="date text-white-50">{{ date('M j') }}</span>
                                <span class="activity-text text-white">{{ trans('dashboard.no_recent_activity') }}</span>
                            </div>
                        </li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>

  
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title mb-4">{{ trans('dashboard.latest_transactions') }}</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">{{ trans('dashboard.id_no') }}</th>
                                <th scope="col">{{ trans('dashboard.name') }}</th>
                                <th scope="col">{{ trans('dashboard.date') }}</th>
                                <th scope="col">{{ trans('dashboard.price') }}</th>
                                <th scope="col">{{ trans('dashboard.quantty') }}</th>
                                <th scope="col">{{ trans('dashboard.status') }}</th>
                                <th scope="col">{{ trans('dashboard.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $latestTransactions = \App\Models\Invoice::with(['customers', 'invoiceItems'])->latest()->take(5)->get();
                            @endphp
                            @forelse($latestTransactions as $transaction)
                                <tr>
                                    <th scope="row">#{{ $transaction->id }}</th>
                                    <td>{{ $transaction->customers->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M') }}</td>
                                    <td>{{ number_format($transaction->invoiceItems && $transaction->invoiceItems->sum('quantty') > 0 ? $transaction->total_price / $transaction->invoiceItems->sum('quantty') : 0, 0) }} {{trans('app.lyd')}}</td>
                                    <td>{{ $transaction->invoiceItems ? $transaction->invoiceItems->sum('quantty') : 0 }}</td>
                                    <td>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($transaction->total_price, 0) }} {{trans('app.lyd')}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ trans('dashboard.no_transactions') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

  
</div>
</div>

<!-- Chart Data and Initialization -->
@php
    // Get orders data for the last 7 days
    $ordersData = \App\Models\Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
    ->groupBy('date')
    ->orderBy('date')
    ->get();


    // Get sales data for the last 7 days
    $salesData = \App\Models\Invoice::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Prepare data for JavaScript
    $orderDates = $ordersData->pluck('date')->toArray();
    $orderCounts = $ordersData->pluck('count')->toArray();
    $salesDates = $salesData->pluck('date')->toArray();
    $salesTotals = $salesData->pluck('total')->toArray();
@endphp

<script>
    // Wait for DOM to be fully loaded and check if Chart.js is available
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js library is not loaded');
            return;
        }


        // Orders Chart
        var ordersCanvas = document.getElementById('orders-chart');
        if (!ordersCanvas) {
            console.error('Canvas element not found for orders chart');
            return;
        }
        var ordersCtx = ordersCanvas.getContext('2d');
        var ordersChart = new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: @json($orderDates),
            datasets: [{
                label: '{{ trans('dashboard.orders') }}',
                data: @json($orderCounts),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            }
        }
    });

    // Sales Chart
    var salesCanvas = document.getElementById('sales-chart');
    if (!salesCanvas) {
        console.error('Canvas element not found for sales chart');
        return;
    }
    var salesCtx = salesCanvas.getContext('2d');
    var salesChart = new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: @json($salesDates),
            datasets: [{
                label: '{{ trans('dashboard.sales') }} ({{trans('app.lyd')}})',
                data: @json($salesTotals),
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' {{trans('app.lyd')}}';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' {{trans('app.lyd')}}';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
