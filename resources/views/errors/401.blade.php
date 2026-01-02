@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="dripicons-warning"></i>
                        {{ __('401 - Unauthorized') }}
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="dripicons-lock-open" style="font-size: 60px; color: #ffc107;"></i>
                    </div>
                    <h2 class="text-warning">{{ __('Authentication Required') }}</h2>
                    <p class="text-muted mt-3">
                        {{ __('You need to be logged in to access this resource.') }}
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="dripicons-login"></i> {{ __('Login') }}
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-secondary ml-2">
                            <i class="dripicons-home"></i> {{ __('Return to Dashboard') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        margin-top: 50px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    .card-header {
        border-bottom: 1px solid #dee2e6;
    }
    
    .btn {
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
</style>
@endsection