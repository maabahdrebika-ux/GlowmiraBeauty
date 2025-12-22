@extends('layouts.app')

@section('title', 'إضافة Slider')

@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('slider') }}">{{ trans('app.slider') }}</a>/{{ trans('app.adduser') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        <form method="POST" action="{{ route('slider/store') }}" enctype="multipart/form-data">
                            @csrf

                    <!-- First Row: Image and buttons -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="image" class="control-label">{{ trans('app.view') }}</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                id="image" accept="image/*">
                            @error('image')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit button -->
                        <div class="form-group col-md-3 align-self-end" style="margin-top:30px">
                            <button type="submit"  class="btn btn-primary btn-block">{{ trans('app.addslider') }}</button>
                        </div>
                    </div>


                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
