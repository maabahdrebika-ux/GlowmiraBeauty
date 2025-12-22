@extends('layouts.app')
@section('title', trans('aboutus.view_details'))

@section('content')
<div class="container-fluid">
    <div class="row card">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mt-0 header-title"><a href="{{ route('aboutus') }}">{{ trans('aboutus.aboutus') }}</a> / {{ trans('aboutus.view_details') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 card">
            <div class="m-b-30 card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">{{ trans('aboutus.intro_one_title') }} (AR)</label>
                        <p>{{ $aboutus->intro_one_title_ar }}</p>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="control-label">{{ trans('aboutus.intro_one_title') }} (EN)</label>
                        <p>{{ $aboutus->intro_one_title_en }}</p>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="control-label">{{ trans('aboutus.intro_one_desc') }} (AR)</label>
                        <p>{!! $aboutus->intro_one_desc_ar !!}</p>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="control-label">{{ trans('aboutus.intro_one_desc') }} (EN)</label>
                        <p>{!! $aboutus->intro_one_desc_en !!}</p>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="control-label">{{ trans('aboutus.intro_one_bg1') }}</label>
                        @if($aboutus->intro_one_bg1)
                        <img src="{{ url($aboutus->intro_one_bg1) }}" width="200" height="200">
                        @else
                        <p>No image</p>
                        @endif
                    </div>

                    <div class="form-group col-md-6">
                        <label class="control-label">{{ trans('aboutus.intro_one_bg2') }}</label>
                        @if($aboutus->intro_one_bg2)
                        <img src="{{ url($aboutus->intro_one_bg2) }}" width="200" height="200">
                        @else
                        <p>No image</p>
                        @endif
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <a href="{{ route('aboutus/edit') }}" class="btn btn-secondary">{{ trans('aboutus.editbtn') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection