@extends('layouts.app')
@section('title', trans('policy.title'))

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
                            @if(!$policie)
                            <a href="{{ route('policy.create') }}" class="btn btn-primary">{{ trans('policy.add_policy') }}</a>
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
                    <h4 class="mt-0 header-title">{{ trans('policy.display_policies') }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable table-custom">
                            <thead>
                                <tr>
                                    <th>{{ trans('policy.title_ar') }}</th>
                                    <th>{{ trans('policy.title_en') }}</th>
                                    <th>{{ trans('policy.description_ar') }}</th>
                                    <th>{{ trans('policy.description_en') }}</th>
                                    <th>{{ trans('policy.edit') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($policies as $policy)
                                    <tr>
                                        <td>{{ $policy->title_ar }}</td>
                                        <td>{{ $policy->title_en }}</td>
                                        <td>{{ $policy->description_ar }}</td>
                                        <td>{{ $policy->description_en }}</td>
                                        <td>
                                            <a href="{{ route('policy.edit', $policy->id) }}" class="btn btn-warning">{{ trans('policy.edit') }}</a>
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
</div>
@endsection
