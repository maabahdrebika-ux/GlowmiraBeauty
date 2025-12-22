@extends('layouts.app')
@section('title', trans('category.edit'))
@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('categories.index') }}">{{ trans('category.title') }}</a>/{{ trans('category.edit') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        <form method="POST" class="" action="">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ trans('category.name') }}</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ $category->name }}" id="name"
                                        placeholder="{{ trans('category.name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ trans('category.englishname') }}</label>
                                    <input type="text" name="englishname"
                                        class="form-control @error('englishname') is-invalid @enderror"
                                        value="{{ $category->englishname }}" id="englishname"
                                        placeholder="{{ trans('category.englishname') }}" required>
                                    @error('englishname')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('category.editbtn') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
