@extends('layouts.app')
@section('title', 'معرض الصور ')

@section('content')
<style>
    .modal-backdrop {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: #000;
    }

    .modal-backdrop {
        z-index: auto !important;
    }

    /* Responsive modal styles */
    @media (max-width: 767px) {
        .modal-dialog {
            width: 100%;
            height: 100%;
            margin: 0;
        }
        .modal-content {
            height: 100vh;
            border-radius: 0;
        }
    }
    @media (min-width: 768px) {
        .modal-dialog {
            width: 80%;
            margin: 30px auto;
        }
        .modal-content {
            max-height: 80vh;
            overflow-y: auto;
        }
    }

    .carousel-inner .item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .gallery-image {
        border-radius: 8px;
        border: 2px solid #f8f9fa;
        transition: border-color 0.3s ease;
    }

    .gallery-image:hover {
        border-color: #ae7171;
    }

    .enlarged-image {
        transition: all 0.3s ease-in-out;
        cursor: zoom-out;
    }

    .enlarged-image:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    /* Improved modal backdrop */
    .modal-backdrop.show {
        opacity: 0.8;
    }

    /* Enhanced carousel indicators */
    .carousel-indicators li {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 5px;
    }

    .carousel-indicators li.active {
        background-color: #ae7171;
    }
</style>

<div class="container-fluid">
    <div class="row card">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mt-0 header-title">
                            <a href="{{ route('products') }}">{{ __('product.products_link') }}</a> / {{ __('product.product_image_gallery') }} {{ $product->barcode }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 card">
            <div class="m-b-30">
                <div class="card-body">
                @if(isset($image) && $image->isEmpty())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning" role="alert">
                                <i class="fa fa-exclamation-triangle mr-2"></i>
                                {{ __('product.no_image_gallery') }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        @if(isset($image) && count($image) > 0)
                            @foreach($image as $index => $img)
                            <div class="col-md-3 col-sm-4 col-xs-6 mb-3">
                                <div class="box-content gallery-item">
                                    <img src="{{ asset('images/product/'.$img->name) }}" 
                                         alt="Image" 
                                         class="img-responsive gallery-image" 
                                         data-index="{{ $index }}" 
                                         style="cursor:pointer; width: 100%; height: 200px; object-fit: cover;">
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.gallery-image').on('click', function(){
            var index = $(this).data('index');
            $('#carouselImages').carousel(parseInt(index));
            $('#sliderModal').modal('show');
        });
    });
</script>

@endsection

<div class="modal fade" id="sliderModal" tabindex="-1" role="dialog" aria-labelledby="sliderModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sliderModalLabel">
                    <i class="fa fa-images mr-2"></i>{{ __('product.image_gallery_modal_title') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="carouselImages" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @if(isset($image) && count($image) > 0)
                            @foreach($image as $index => $img)
                            <li data-target="#carouselImages" data-slide-to="{{ $index }}" class="@if($index==0) active @endif"></li>
                            @endforeach
                        @endif
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        @if(isset($image) && count($image) > 0)
                            @foreach($image as $index => $img)
                            <div class="item @if($index==0) active @endif">
                                <div class="text-center" style="padding: 20px;">
                                    <img src="{{ asset('images/product/'.$img->name) }}" 
                                         alt="Image" 
                                         class="img-responsive center-block enlarged-image" 
                                         style="max-width: 100%; 
                                                max-height: 75vh; 
                                                width: auto; 
                                                height: auto; 
                                                object-fit: contain; 
                                                border-radius: 8px; 
                                                box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <a class="left carousel-control" href="#carouselImages" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">{{ __('product.previous') }}</span>
                    </a>
                    <a class="right carousel-control" href="#carouselImages" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">{{ __('product.next') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
