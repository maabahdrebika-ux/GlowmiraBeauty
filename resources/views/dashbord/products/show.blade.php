@extends('layouts.app')
@section('title', __('product.show_product_title'))
@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('products') }}">{{ __('product.products_link') }}</a>/{{ __('product.show_product') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        <!-- First Row: Category Selection -->
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="control-label">{{ __('product.categories') }}</label>
                                <p class="form-control-plaintext">{{ $product->categories->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Second Row: Name and Namee -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('product.product_name') }}</label>
                                <p class="form-control-plaintext">{{ $product->name }}</p>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('product.product_name_english') }}</label>
                                <p class="form-control-plaintext">{{ $product->namee }}</p>
                            </div>
                        </div>

                        <!-- Third Row: Barcode, Brandname, Country of Origin, Price -->
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="control-label">{{ __('product.barcode') }}</label>
                                <p class="form-control-plaintext">{{ $product->barcode }}</p>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="control-label">{{ __('product.brandname_ar') }}</label>
                                <p class="form-control-plaintext">{{ $product->brandname_ar ?? 'N/A' }}</p>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="control-label">{{ __('product.brandname_en') }}</label>
                                <p class="form-control-plaintext">{{ $product->brandname_en ?? 'N/A' }}</p>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="control-label">{{ __('product.selling_price') }}</label>
                                <p class="form-control-plaintext">{{ $product->price }}</p>
                            </div>
                        </div>

                        <!-- Country of Origin fields -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('product.country_of_origin_ar') }}</label>
                                <p class="form-control-plaintext">{{ $product->country_of_origin_ar ?? 'N/A' }}</p>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('product.country_of_origin_en') }}</label>
                                <p class="form-control-plaintext">{{ $product->country_of_origin_en ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Sizes -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('product.sizes') }}</label>
                                @if($product->sizes->count() > 0)
                                    <ul class="list-group">
                                        @foreach($product->sizes as $size)
                                            <li class="list-group-item">{{ $size->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="form-control-plaintext">{{ __('product.no_sizes') }}</p>
                                @endif
                            </div>

                            <!-- Colors -->
                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('product.color') }}</label>
                                @if($product->coolors->count() > 0)
                                    <ul class="list-group">
                                        @foreach($product->coolors as $coolor)
                                            <li class="list-group-item">{{ $coolor->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="form-control-plaintext">{{ __('product.no_colors') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="control-label">{{ __('product.cover_image_label') }}</label>
                                @if($product->image)
                                    <img src="{{ asset('images/product/' . rawurlencode($product->image)) }}" alt="{{ $product->name }}" class="img-thumbnail" width="200">
                                @else
                                    <p class="form-control-plaintext">{{ __('product.no_image_available') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Additional Images -->
                        @if($product->imagesfiles->count() > 0)
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="control-label">{{ __('product.additional_images') }}</label>
                                <div class="row">
                                    @foreach($product->imagesfiles as $image)
                                        <div class="col-md-3 mb-3">
                                            <img src="{{ asset('images/product/' . rawurlencode($image->name)) }}" alt="Additional Image" class="img-thumbnail" width="150">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Descriptions -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('product.product_description_arabic') }}</label>
                                <p class="form-control-plaintext">{{ $product->description_ar ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('product.product_description_english') }}</label>
                                <p class="form-control-plaintext">{{ $product->description_en ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Additional Descriptions -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('product.description') }}</label>
                                <p class="form-control-plaintext">{{ $product->description ?? 'N/A' }}</p>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('product.description_english') }}</label>
                                <p class="form-control-plaintext">{{ $product->descriptione ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="control-label">{{ __('product.notes') }}</label>
                                <p class="form-control-plaintext">{{ $product->notes ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="control-label">{{ __('product.status') }}</label>
                                <p class="form-control-plaintext">{{ $product->is_available ? __('product.available') : __('product.unavailable') }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <a href="{{ route('products') }}" class="btn btn-secondary">{{ __('product.back_to_products') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
