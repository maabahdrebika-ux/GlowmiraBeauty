@extends('layouts.app')
@section('title', trans('app.notifications'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title">{{ trans('app.notifications') }}</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="float-right d-none d-md-block">
                            <!-- Action buttons can be added here -->
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
                    <h4 class="mt-0 header-title">{{ trans('app.all_notifications') }}</h4>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ trans('app.message') }}</th>
                                    <th>{{ trans('app.date') }}</th>
                                    <th>{{ trans('app.time_ago') }}</th>
                                    <th>{{ trans('app.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                <tr class="{{ $notification->is_read ? '' : 'table-warning' }}">
                                    <td>
                                        <a href="{{ route($notification->url) ?? '#' }}" class="font-weight-bold" style="color: #C5979A !important;">
                                            <i class="fa fa-bell text-primary"></i> {{ $notification->message }}
                                        </a>
                                    </td>
                                    <td>{{ $notification->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $notification->created_at }}</td>
                                    <td>
                                        @if($notification->is_read)
                                            <span class="badge badge-success">{{ trans('app.read') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ trans('app.unread') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3" style="text-align: center;">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
