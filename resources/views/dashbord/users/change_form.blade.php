@extends('layouts.app')
@section('title', trans('users.changepass'))
@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('users') }}">{{ trans('app.users') }}</a>/{{ trans('users.changepass') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

                        <form method="POST" class="" action="">
                            @csrf
                            <div class="form-group">
                                <label for="inputName" class="control-label">{{trans('users.current-password')}}</label>
                                <input type="password" name="current-password" class="form-control @error('current-password') is-invalid @enderror" value="{{ old('current-password') }}" id="current-password" placeholder="{{trans('users.current-password')}}" >
                                @error('current-password')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="control-label">{{trans('users.new-password')}}</label>
                                <input type="password" name="new-password" class="form-control @error('new-password') is-invalid @enderror" value="{{ old('new-password') }}" id="new-password" placeholder="{{trans('users.new-password')}}" >
                                @error('new-password')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="control-label">{{trans('users.new-password-confirm')}}</label>
                                <input type="password" name="new-password-confirm" class="form-control @error('new-password-confirm') is-invalid @enderror" value="{{ old('new-password-confirm') }}" id="new-password-confirm" placeholder="{{trans('users.new-password-confirm')}}" >
                                @error('new-password-confirm')
                                <span class="invalid-feedback" style="color: red" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">{{trans('users.passbtn')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
