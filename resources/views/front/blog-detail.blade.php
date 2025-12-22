@extends('front.app')
@section('title', $blog->{'title_' . app()->getLocale()})

@section('content')
<div class="breadcrumb">
    <div class="container">
        <h3>{{ $blog->{'title_' . app()->getLocale()} }}</h3>
        <ul>
            <li><a href="{{ route('blog') }}">{{ trans('menu.blog') }}</a></li>
            <li class="active">{{ $blog->{'title_' . app()->getLocale()} }}</li>
        </ul>
    </div>
</div>

<div class="blog-detail container my-4">
    <div class="row">
        <div class="col-md-8">
            <img src="{{ asset('images/blogs/'.$blog->image) }}" alt="{{ $blog->{'title_' . app()->getLocale()} }}" class="img-fluid mb-3">
            <h1 style="line-height: 40px;">{{ $blog->{'title_' . app()->getLocale()} }}</h1>
            <p><small>{{ $blog->created_at->format('F d, Y') }}</small></p>
            <div class="blog-description" style="line-height: 31px;">
                {!! nl2br(e($blog->{'description_' . app()->getLocale()})) !!}
            </div>
        </div>
        <div class="col-md-4">
            <!-- Sidebar can be added here if needed -->
        </div>
    </div>
</div>
@endsection
