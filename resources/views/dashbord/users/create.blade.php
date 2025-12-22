@extends('layouts.app')
@section('title', trans('users.add'))
@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('users') }}">{{ trans('app.users') }}</a>/{{ trans('users.add') }}</h4>                </div>
                
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
                                    <label>{{ trans('users.username') }}</label>
                                    <input type="text" name="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username') }}" id="username"
                                        placeholder="{{ trans('users.username') }}" required>
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ trans('users.first_name') }}</label>
                                    <input type="text" name="first_name"
                                        class="form-control @error('first_name') is-invalid @enderror"
                                        value="{{ old('first_name') }}" id="first_name"
                                        placeholder="{{ trans('users.first_name') }}" required>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ trans('users.last_name') }}</label>
                                    <input type="text" name="last_name"
                                        class="form-control @error('last_name') is-invalid @enderror"
                                        value="{{ old('last_name') }}" id="last_name"
                                        placeholder="{{ trans('users.last_name') }}" required>
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ trans('users.email') }}</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" id="email"
                                        placeholder="{{ trans('users.email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ trans('users.phonenumber') }}</label>
                                    <input type="text" name="phonenumber"
                                        class="form-control @error('phonenumber') is-invalid @enderror"
                                        value="{{ old('phonenumber') }}" id="phonenumber"
                                        placeholder="{{ trans('users.phonenumber') }}" required>
                                    @error('phonenumber')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ trans('users.address') }}</label>
                                    <select name="address"
                                        class="form-control @error('address') is-invalid @enderror select2 wd-250"
                                        data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                        data-parsley-errors-container="#slErrorContainer" required>
                                        @forelse ($Addresses as $Address)
                                            <option value="{{ encrypt($Address->id) }}">{{ $Address->name }}</option>
                                        @empty
                                            <option value="">{{ trans('users.city') }}</option>
                                        @endforelse
                                    </select>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ trans('users.password') }}</label>
                                    <input type="password" name="password" data-minlength="8"
                                        class="form-control @error('password') is-invalid @enderror"
                                        value="{{ old('password') }}" id="password"
                                        placeholder="{{ trans('users.password') }}" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    <div class="help-block">{{ trans('users.8digitsmini') }}</div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Role</label>
                                    <select name="role"
                                        class="form-control @error('role') is-invalid @enderror select2 wd-250"
                                        data-placeholder="Choose one" data-parsley-class-handler="#slWrapper"
                                        data-parsley-errors-container="#slErrorContainer" required>
                                        @forelse ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @empty
                                            <option value="">No roles available</option>
                                        @endforelse
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('users.addbtn') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
