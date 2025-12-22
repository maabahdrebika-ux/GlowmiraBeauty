@extends('layouts.app')
@section('title',trans('aboutus.editbtn'))

@section('content')
<div class="container-fluid">
    <div class="row card">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mt-0 header-title"><a href="{{ route('aboutus') }}">{{trans('aboutus.aboutus')}}</a> / {{trans('aboutus.editbtn')}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 card">
            <div class="m-b-30 card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="inputIntroOneTitleAr" class="control-label">{{ trans('aboutus.intro_one_title') }} (AR)</label>
                            <input type="text" id="inputIntroOneTitleAr" name="intro_one_title_ar" value="{{ old('intro_one_title_ar', $ab->intro_one_title_ar) }}" class="form-control @error('intro_one_title_ar') is-invalid @enderror">
                            @error('intro_one_title_ar')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputIntroOneTitleEn" class="control-label">{{ trans('aboutus.intro_one_title') }} (EN)</label>
                            <input type="text" id="inputIntroOneTitleEn" name="intro_one_title_en" value="{{ old('intro_one_title_en', $ab->intro_one_title_en) }}" class="form-control @error('intro_one_title_en') is-invalid @enderror">
                            @error('intro_one_title_en')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputIntroOneDescAr" class="control-label">{{ trans('aboutus.intro_one_desc') }} (AR)</label>
                            <textarea id="inputIntroOneDescAr" name="intro_one_desc_ar" class="form-control summernote @error('intro_one_desc_ar') is-invalid @enderror" rows="5">{!! old('intro_one_desc_ar', $ab->intro_one_desc_ar) !!}</textarea>
                            @error('intro_one_desc_ar')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputIntroOneDescEn" class="control-label">{{ trans('aboutus.intro_one_desc') }} (EN)</label>
                            <textarea id="inputIntroOneDescEn" name="intro_one_desc_en" class="form-control summernote @error('intro_one_desc_en') is-invalid @enderror" rows="5">{!! old('intro_one_desc_en', $ab->intro_one_desc_en) !!}</textarea>
                            @error('intro_one_desc_en')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                      
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="inputIntroOneBg1" class="control-label">{{ trans('aboutus.intro_one_bg1') }} (390x445)</label>
                            <input type="file" id="inputIntroOneBg1" name="intro_one_bg1" class="form-control @error('intro_one_bg1') is-invalid @enderror">
                            @error('intro_one_bg1')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                            @if($ab->intro_one_bg1)
                            <img src="{{ url($ab->intro_one_bg1) }}" width="100" height="100">
                            @endif
                        </div>

                        <div class="form-group col-md-3">
                            <label for="inputIntroOneBg2" class="control-label">{{ trans('aboutus.intro_one_bg2') }} (370x440)</label>
                            <input type="file" id="inputIntroOneBg2" name="intro_one_bg2" class="form-control @error('intro_one_bg2') is-invalid @enderror">
                            @error('intro_one_bg2')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                            @if($ab->intro_one_bg2)
                            <img src="{{ url($ab->intro_one_bg2) }}" width="100" height="100">
                            @endif
                        </div>
                    </div>
                  
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            {{trans('address.editbtn')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

