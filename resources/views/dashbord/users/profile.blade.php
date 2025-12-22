@extends('layouts.app')
@section('title', trans('users.profilelogger'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title">{{ trans('users.profilelogger') }}</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="float-right d-none d-md-block">
                            <!-- Settings dropdown will be handled by the navbar -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('users.user_information') }}</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">{{ trans('users.username') }}</label>
                                <div class="form-control-plaintext">{{ $user->username }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">{{ trans('users.first_name') }}</label>
                                <div class="form-control-plaintext">{{ $user->first_name }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">{{ trans('users.last_name') }}</label>
                                <div class="form-control-plaintext">{{ $user->last_name }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">{{ trans('users.phonenumber') }}</label>
                                <div class="form-control-plaintext">{{ $user->phonenumber }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">{{ trans('users.email') }}</label>
                                <div class="form-control-plaintext">{{ $user->email }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">{{ trans('users.address') }}</label>
                                <div class="form-control-plaintext">{{ $user->address->name ?? trans('users.no_address') }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">{{ trans('users.created_at') }}</label>
                                <div class="form-control-plaintext">{{ $user->created_at }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Info Form -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('users.edit_user_info') }}</h4>

                    <form method="POST" action="{{ route('users/update', $user->id) }}" id="editInfoForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">{{ trans('users.username') }}</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                           value="{{ $user->username }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">{{ trans('users.email') }}</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ $user->email }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">{{ trans('users.first_name') }}</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                           value="{{ $user->first_name }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">{{ trans('users.last_name') }}</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                           value="{{ $user->last_name }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phonenumber">{{ trans('users.phonenumber') }}</label>
                                    <input type="text" class="form-control" id="phonenumber" name="phonenumber"
                                           value="{{ $user->phonenumber }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> {{ trans('users.update_info') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Form -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">{{ trans('users.change_password') }}</h4>

                    <form method="POST" action="{{ route('users/changepassword', $user->id) }}" id="changePasswordForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="current_password">{{ trans('users.current_password') }}</label>
                                    <input type="password" class="form-control" id="current_password"
                                           name="current_password" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="new_password">{{ trans('users.new_password') }}</label>
                                    <input type="password" class="form-control" id="new_password"
                                           name="new_password" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="new_password_confirmation">{{ trans('users.confirm_new_password') }}</label>
                                    <input type="password" class="form-control" id="new_password_confirmation"
                                           name="new_password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-key"></i> {{ trans('users.update_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control-plaintext {
        padding: 0.375rem 0;
        margin-bottom: 0;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: transparent;
        border: none;
        border-bottom: 1px solid #dee2e6;
    }
</style>

<script>
    $(document).ready(function() {
        // Form submission with AJAX
        $('#editInfoForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ trans("users.success") }}',
                        text: '{{ trans("users.info_updated") }}',
                        timer: 3000
                    });
                    // Refresh the page to show updated info
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n';
                    });

                    Swal.fire({
                        icon: 'error',
                        title: '{{ trans("users.error") }}',
                        text: errorMessage,
                        timer: 5000
                    });
                }
            });
        });

        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ trans("users.success") }}',
                        text: '{{ trans("users.password_changed") }}',
                        timer: 3000
                    });
                    // Clear password fields
                    $('#current_password').val('');
                    $('#new_password').val('');
                    $('#new_password_confirmation').val('');
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '\n';
                    });

                    Swal.fire({
                        icon: 'error',
                        title: '{{ trans("users.error") }}',
                        text: errorMessage,
                        timer: 5000
                    });
                }
            });
        });
    });
</script>

@endsection