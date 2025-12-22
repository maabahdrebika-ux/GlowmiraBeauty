@extends('layouts.app')
@section('title', trans('supplier.add_new_supplier'))
@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('suppliers') }}">{{ trans('supplier.suppliers') }}</a>/{{ trans('supplier.add_new_supplier') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        <form method="POST" class="" action="{{ route('suppliers/store') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ trans('supplier.supplier_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" id="name"
                                        placeholder="{{ trans('supplier.enter_supplier_name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ trans('supplier.phone') }}</label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" id="phone"
                                        placeholder="{{ trans('supplier.enter_phone') }}">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ trans('supplier.email') }}</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" id="email"
                                        placeholder="{{ trans('supplier.enter_email') }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ trans('supplier.address') }}</label>
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address') }}" id="address"
                                        placeholder="{{ trans('supplier.enter_address') }}">
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>{{ trans('supplier.notes') }}</label>
                                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                        id="notes" rows="4" placeholder="{{ trans('supplier.enter_notes') }}">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('supplier.add') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
