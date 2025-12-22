@extends('layouts.app')
@section('title', trans('roles.add'))
@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('roles.index') }}">{{ trans('roles.title') }}</a>/{{ trans('roles.add') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        <form method="POST" class="" action="{{ route('roles.store') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ trans('roles.name') }}</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" id="name"
                                        placeholder="{{ trans('roles.name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>{{ trans('roles.permissionss') }}</label>
                                    <div class="row">
                                        @forelse($permissions as $permission)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission{{ $permission->id }}">
                                                    <label style="margin-right: 20px;" class="form-check-label" for="permission{{ $permission->id }}">
                                                        @php
                                                            $permissionKey = str_replace(' ', '_', strtolower($permission->name));
                                                            $permissionTranslations = trans('roles.permissions');
                                                            $translatedName = $permissionTranslations[$permissionKey] ?? $permission->name;
                                                        @endphp
                                                        {{ $translatedName }}
                                                    </label>
                                                </div>
                                            </div>
                                        @empty
                                            <p>{{ trans('roles.no_permissions') }}</p>
                                        @endforelse
                                    </div>
                                    @error('permissions')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('roles.addbtn') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection