@extends('layouts.app')
@section('title', trans('blog.edit'))
@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('blogs.index') }}">{{ trans('blog.title') }}</a>/{{ trans('blog.edit') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        <form method="POST" class="" action="{{ route('blogs.update', encrypt($blog->id)) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ trans('blog.title_ar') }}</label>
                                    <input type="text" name="title_ar"
                                        class="form-control @error('title_ar') is-invalid @enderror"
                                        value="{{ $blog->title_ar }}" id="title_ar"
                                        placeholder="{{ trans('blog.title_ar') }}" required>
                                    @error('title_ar')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ trans('blog.title_en') }}</label>
                                    <input type="text" name="title_en"
                                        class="form-control @error('title_en') is-invalid @enderror"
                                        value="{{ $blog->title_en }}" id="title_en"
                                        placeholder="{{ trans('blog.title_en') }}" required>
                                    @error('title_en')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ trans('blog.description_ar') }}</label>
                                    <textarea name="description_ar"
                                        class="form-control @error('description_ar') is-invalid @enderror"
                                        id="description_ar"
                                        placeholder="{{ trans('blog.description_ar') }}" rows="4" required>{{ $blog->description_ar }}</textarea>
                                    @error('description_ar')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ trans('blog.description_en') }}</label>
                                    <textarea name="description_en"
                                        class="form-control @error('description_en') is-invalid @enderror"
                                        id="description_en"
                                        placeholder="{{ trans('blog.description_en') }}" rows="4" required>{{ $blog->description_en }}</textarea>
                                    @error('description_en')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>{{ trans('blog.image') }}</label>
                                    <input type="file" name="image"
                                        class="form-control @error('image') is-invalid @enderror"
                                        id="image" accept="image/*">
                                    <small class="form-text text-muted">{{ trans('blog.image_note') ?? 'Recommended size: 360x240 pixels' }}</small>
                                    @if($blog->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('images/blogs/' . $blog->image) }}" alt="Current Image" style="width: 120px; height: 80px; object-fit: cover;">
                                            <p class="text-muted">{{ trans('blog.current_image') ?? 'Current image' }}</p>
                                        </div>
                                    @endif
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('blog.editbtn') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
