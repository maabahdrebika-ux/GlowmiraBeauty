@extends('layouts.app')
@section('title', trans('address.edit'))
@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('addresses') }}">{{ trans('app.address') }}</a>/{{ trans('address.edit') }}</h4>                </div>

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
                                    <label>{{ trans('address.name') }}</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ $address->name }}" id="name"
                                        placeholder="{{ trans('address.name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ trans('address.nameen') }}</label>
                                    <input type="text" name="nameen"
                                        class="form-control @error('nameen') is-invalid @enderror"
                                        value="{{ $address->nameen }}" id="nameen"
                                        placeholder="{{ trans('address.nameen') }}" required>
                                    @error('nameen')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('address.editbtn') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
