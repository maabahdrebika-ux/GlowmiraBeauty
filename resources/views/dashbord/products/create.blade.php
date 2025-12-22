@extends('layouts.app')
@section('title', __('product.add_product_title'))
@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('products') }}">{{ __('product.products_link') }}</a>/{{ __('product.add_product') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        <form method="POST" action="" enctype="multipart/form-data">
                            @csrf

                    <!-- First Row: Category Selection -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="categories_id" class="control-label">{{ __('product.categories') }}</label>
                            <select name="categories_id" class="form-control @error('categories_id') is-invalid @enderror">
                                <option value="">{{ __('product.select_category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                                        @if(session('language', app()->getLocale()) == 'ar')
                                        {{ $category->name }}
                                    @else
                                        {{ $category->englishname }}
                                    @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('categories_id')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Second Row: Name and Namee -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name" class="control-label">{{ __('product.product_name') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" id="name" placeholder="{{ __('product.product_name') }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="namee" class="control-label">{{ __('product.product_name_english') }}</label>
                            <input type="text" name="namee" class="form-control @error('namee') is-invalid @enderror"
                                value="{{ old('namee') }}" id="namee" placeholder="{{ __('product.product_name_english') }}">
                            @error('namee')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="image" class="control-label">{{ __('product.cover_image_label') }}</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                id="image">
                            @error('image')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional fields: Barcode, Brandname, Country of Origin, Price -->
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="barcode" class="control-label">{{ __('product.barcode') }}</label>
                            <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                value="{{ old('barcode') }}" id="barcode" placeholder="{{ __('product.barcode') }}">
                            @error('barcode')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="brandname_ar" class="control-label">{{ __('product.brandname_ar') }}</label>
                            <input type="text" name="brandname_ar" class="form-control @error('brandname_ar') is-invalid @enderror"
                                value="{{ old('brandname_ar') }}" id="brandname_ar" placeholder="{{ __('product.brandname_ar') }}">
                            @error('brandname_ar')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="brandname_en" class="control-label">{{ __('product.brandname_en') }}</label>
                            <input type="text" name="brandname_en" class="form-control @error('brandname_en') is-invalid @enderror"
                                value="{{ old('brandname_en') }}" id="brandname_en" placeholder="{{ __('product.brandname_en') }}">
                            @error('brandname_en')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="price" class="control-label">{{ __('product.selling_price') }}</label>
                            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}" id="price" placeholder="{{ __('product.selling_price') }}">
                            @error('price')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Country of Origin fields -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="country_of_origin_ar" class="control-label">{{ __('product.country_of_origin_ar') }}</label>
                            <input type="text" name="country_of_origin_ar" class="form-control @error('country_of_origin_ar') is-invalid @enderror"
                                value="{{ old('country_of_origin_ar') }}" id="country_of_origin_ar" placeholder="{{ __('product.country_of_origin_ar') }}">
                            @error('country_of_origin_ar')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="country_of_origin_en" class="control-label">{{ __('product.country_of_origin_en') }}</label>
                            <input type="text" name="country_of_origin_en" class="form-control @error('country_of_origin_en') is-invalid @enderror"
                                value="{{ old('country_of_origin_en') }}" id="country_of_origin_en" placeholder="{{ __('product.country_of_origin_en') }}">
                            @error('country_of_origin_en')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="size" class="control-label">{{ __('product.sizes') }}</label>
                            <div id="size-container">
                                <div class="row mb-2">
                                    {{-- <div class="col-md-10">
                                        <input type="text" name="size[]"
                                            class="form-control @error('size') is-invalid @enderror"
                                            value="{{ old('size.0') }}" placeholder="XL">
                                    </div> --}}
                                    <div class="col-2">
                                        <button type="button" class="btn btn-success add-size">+</button>
                                    </div>
                                </div>
                            </div>
                            @error('size')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelector('.add-size').addEventListener('click', function() {
                                let sizeContainer = document.getElementById('size-container');
                                let newInputRow = document.createElement('div');
                                newInputRow.classList.add('row', 'mb-2');
                                newInputRow.style.marginTop = "50px"; // Apply the style

                                newInputRow.innerHTML = `
                                <div class="col-md-10">
                                    <input type="text" name="size[]" class="form-control" placeholder="XL">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger remove-size">-</button>
                                </div>
                            `;
                                sizeContainer.appendChild(newInputRow);
                            });

                            document.addEventListener('click', function(event) {
                                if (event.target.classList.contains('remove-size')) {
                                    event.target.closest('.row').remove();
                                }
                            });
                        });
                    </script>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="coolor" class="control-label">{{ __('product.color') }}</label>
                            <div id="coolor-container">
                                <div class="row mb-2">
                                    {{-- <div class="col-md-10">
                                        <input type="color" name="coolor[]" class="form-control @error('coolor') is-invalid @enderror" value="{{ old('coolor.0', '') }}">
                                    </div> --}}
                                    <div class="col-2">
                                        <button type="button" class="btn btn-success add-coolor">+</button>
                                    </div>
                                </div>
                            </div>
                            @error('coolor')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelector('.add-coolor').addEventListener('click', function() {
                                let coolorContainer = document.getElementById('coolor-container');
                                let newInputRow = document.createElement('div');
                                newInputRow.classList.add('row', 'mb-2');
                                newInputRow.style.marginTop = "10px"; // Adjusted spacing for better UI

                                newInputRow.innerHTML = `
                                <div class="col-md-10">
                                    <input type="text" name="coolor[]" class="form-control" placeholder="اخضر" >
                                </div>
                               
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger remove-coolor">-</button>
                                </div>
                            `;
                                coolorContainer.appendChild(newInputRow);
                            });

                            document.addEventListener('click', function(event) {
                                if (event.target.classList.contains('remove-coolor')) {
                                    event.target.closest('.row').remove();
                                }
                            });
                        });
                    </script>



                    <div class="form-row">
                        <div class="form-group col-md-12">

                    <label for="image" class="control-label">{{ __('product.additional_images') }}</label>
                        <div id="image-container">
                            <div class="row mb-2">
                                <div class="col-md-10">
                                    <input type="file" name="images[]"
                                        class="form-control @error('images') is-invalid @enderror">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-success add-image">+</button>
                                </div>
                            </div>
                        </div>
                        @error('images')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Handle add image button clicks (works for dynamically added buttons)
                            document.addEventListener('click', function(e) {
                                if (e.target.classList.contains('add-image')) {
                                    let imageContainer = document.getElementById('image-container');
                                    let newInputRow = document.createElement('div');
                                    newInputRow.classList.add('row', 'mb-2');
                                    newInputRow.style.marginTop = "10px";

                                    newInputRow.innerHTML = `
                                    <div class="col-md-10">
                                        <input type="file" name="images[]" class="form-control" multiple>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-danger remove-image">-</button>
                                        <button type="button" class="btn btn-success add-image">+</button>
                                    </div>
                                `;
                                    imageContainer.appendChild(newInputRow);
                                }
                            });

                            // Handle remove image button clicks
                            document.addEventListener('click', function(e) {
                                if (e.target.classList.contains('remove-image')) {
                                    let row = e.target.closest('.row');
                                    let container = document.getElementById('image-container');
                                    
                                    // Don't remove if it's the only row
                                    if (container.children.length > 1) {
                                        row.remove();
                                    } else {
                                        // Clear the input if it's the only row
                                        let input = row.querySelector('input[type="file"]');
                                        if (input) input.value = '';
                                    }
                                }
                            });
                        });
                    </script>
          

                    <!-- New Row: Descriptions in Arabic and English -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="description_ar" class="control-label">{{ __('product.product_description_arabic') }}</label>
                            <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror"
                                      id="description_ar" placeholder="{{ __('product.product_description_arabic') }}">{{ old('description_ar') }}</textarea>
                            @error('description_ar')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="description_en" class="control-label">{{ __('product.product_description_english') }}</label>
                            <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror"
                                      id="description_en" placeholder="{{ __('product.product_description_english') }}">{{ old('description_en') }}</textarea>
                            @error('description_en')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
          <div class="row">

                        <div class="form-group col-md-12">
                            <label for="notes" class="control-label">{{ __('product.notes') }}</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" id="notes"
                                placeholder="{{ __('product.notes') }}">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ __('product.add_product_button') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
