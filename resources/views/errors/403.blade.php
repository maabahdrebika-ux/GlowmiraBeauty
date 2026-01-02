@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="dripicons-warning"></i>
                        {{ __('403 - Access Forbidden') }}
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="dripicons-lock" style="font-size: 60px; color: #dc3545;"></i>
                    </div>
                    <h2 class="text-danger">{{ __('You do not have permission to access this resource.') }}</h2>
                    <p class="text-muted mt-3">
                        {{ __('The page you are trying to access requires higher permissions.') }}
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="dripicons-home"></i> {{ __('Return to Dashboard') }}
                        </a>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary ml-2">
                            <i class="dripicons-arrow-left"></i> {{ __('Go Back') }}
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