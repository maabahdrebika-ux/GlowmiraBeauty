@extends('layouts.app')
@section('title', trans('policy.title'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mt-0 header-title"><a href="{{ route('policy.index') }}">{{ trans('policy.breadcrumb') }}</a> / {{ trans('policy.edit_policy') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 card">
            <div class="card-body">
                <form method="POST" action="{{ route('policy.update', $policy->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="title_ar">{{ trans('policy.title_ar') }} <span class="text-danger">{{ trans('policy.required') }}</span></label>
                            <input type="text" name="title_ar" class="form-control" id="title_ar" value="{{ $policy->title_ar }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="title_en">{{ trans('policy.title_en') }} <span class="text-danger">{{ trans('policy.required') }}</span></label>
                            <input type="text" name="title_en" class="form-control" id="title_en" value="{{ $policy->title_en }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="description_ar">{{ trans('policy.description_ar') }} <span class="text-danger">{{ trans('policy.required') }}</span></label>
                            <textarea name="description_ar" class="form-control" id="description_ar" required>{{ $policy->description_ar }}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="description_en">{{ trans('policy.description_en') }} <span class="text-danger">{{ trans('policy.required') }}</span></label>
                            <textarea name="description_en" class="form-control" id="description_en" required>{{ $policy->description_en }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ trans('policy.update') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
