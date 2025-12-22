@extends('layouts.app')
@section('title', __('product.edit_product_title'))
@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('products') }}">{{ __('product.products_link') }}</a>/{{ __('product.edit_product') }}</h4>                </div>

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
                            @method('post')

                <!-- First Row: Category Selection -->
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="categories_id" class="control-label">{{ __('product.category') }}</label>
                        <select name="categories_id" class="form-control @error('categories_id') is-invalid @enderror">
                            <option value="">{{ __('product.select_category_edit') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('categories_id', $product->categories_id) == $category->id ? 'selected' : '' }}>
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
                               value="{{ old('name', $product->name) }}" id="name" placeholder="{{ __('product.product_name') }}">
                        @error('name')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="namee" class="control-label">{{ __('product.product_name_english') }}</label>
                        <input type="text" name="namee" class="form-control @error('namee') is-invalid @enderror"
                               value="{{ old('namee', $product->namee) }}" id="namee" placeholder="{{ __('product.product_name_english') }}">
                        @error('namee')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Third Row: Barcode, Brandname, Country of Origin, Price -->
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="barcode" class="control-label">{{ __('product.barcode') }}</label>
                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                               value="{{ old('barcode', $product->barcode) }}" id="barcode" placeholder="{{ __('product.barcode') }}">
                        @error('barcode')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="brandname_ar" class="control-label">{{ __('product.brandname_ar') }}</label>
                        <input type="text" name="brandname_ar" class="form-control @error('brandname_ar') is-invalid @enderror"
                               value="{{ old('brandname_ar', $product->brandname_ar) }}" id="brandname_ar" placeholder="{{ __('product.brandname_ar') }}">
                        @error('brandname_ar')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="brandname_en" class="control-label">{{ __('product.brandname_en') }}</label>
                        <input type="text" name="brandname_en" class="form-control @error('brandname_en') is-invalid @enderror"
                               value="{{ old('brandname_en', $product->brandname_en) }}" id="brandname_en" placeholder="{{ __('product.brandname_en') }}">
                        @error('brandname_en')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="price" class="control-label">{{ __('product.selling_price') }}</label>
                        <input type="text" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $product->price) }}" id="price" placeholder="{{ __('product.selling_price') }}">
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
                               value="{{ old('country_of_origin_ar', $product->country_of_origin_ar) }}" id="country_of_origin_ar" placeholder="{{ __('product.country_of_origin_ar') }}">
                        @error('country_of_origin_ar')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="country_of_origin_en" class="control-label">{{ __('product.country_of_origin_en') }}</label>
                        <input type="text" name="country_of_origin_en" class="form-control @error('country_of_origin_en') is-invalid @enderror"
                               value="{{ old('country_of_origin_en', $product->country_of_origin_en) }}" id="country_of_origin_en" placeholder="{{ __('product.country_of_origin_en') }}">
                        @error('country_of_origin_en')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

           

                <!-- New Row: Arabic and English Descriptions -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="description_ar" class="control-label">{{ __('product.product_description_arabic') }}</label>
                        <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror"
                                  id="description_ar" placeholder="{{ __('product.product_description_arabic') }}">{{ old('description_ar', $product->description_ar) }}</textarea>
                        @error('description_ar')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="description_en" class="control-label">{{ __('product.product_description_english') }}</label>
                        <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror"
                                  id="description_en" placeholder="{{ __('product.product_description_english') }}">{{ old('description_en', $product->description_en) }}</textarea>
                        @error('description_en')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Fourth Row: Descriptions and Notes -->
               

                <!-- Fifth Row: Notes -->
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="notes" class="control-label">{{ __('product.notes') }}</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" placeholder="{{ __('product.notes') }}">{{ old('notes', $product->notes) }}</textarea>
                        @error('notes')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Sixth Row: Price and Cover Image -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="price" class="control-label">{{ __('product.selling_price') }}</label>
                        <input type="text" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $product->price) }}" id="price" placeholder="{{ __('product.selling_price') }}">
                        @error('price')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        @if($product->image)
                        <div class="mt-2">
                            <label>{{ __('product.current_image') }}</label>
                            <img src="{{ asset('images/product/' . $product->image) }}" alt="Product Image" id="imagePreview" class="img-thumbnail" width="150">
                        </div>
                    @else
                        <div class="mt-2">
                            <label>{{ __('product.no_image_available') }}</label>
                        </div>
                    @endif
                        <label for="image" class="control-label">{{ __('product.cover_image_label') }}</label>

                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="image" onchange="previewImage(event)">

                        @error('image')
                            <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- New Row: Sizes -->
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="size" class="control-label">{{ __('product.sizes') }}</label>
                        <div id="size-container">
                            @if($product->sizes->count() > 0)
                                @foreach($product->sizes as $size)
                                <div class="row mb-2">
                                    <div class="col-md-10">
                                        <div class="input-group">
                            <input type="text" name="size[]" value="{{ $size->name }}" class="form-control" placeholder="{{ __('product.enter_sizes') }}">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-danger remove-size">-</button>
                                        @if($loop->last)
                                            <button type="button" class="btn btn-success add-size">{{ __('product.add') }}</button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="row mb-2">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <input type="text" name="size[]" class="form-control" placeholder="{{ __('product.enter_size') }}">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-danger remove-size">-</button>
                                        <button type="button" class="btn btn-success add-size">{{ __('product.add') }}</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- New Row: Coolors -->
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="coolor" class="control-label">{{ __('product.color') }}</label>
                        <div id="coolor-container">
                            @if($product->coolors->count() > 0)
                                @foreach($product->coolors as $coolor)
                                <div class="row mb-2">
                                    <div class="col-md-10">
                                        <div class="input-group">
                            <input type="text" name="coolor[]" value="{{ $coolor->name }}" class="form-control" onchange="this.nextElementSibling.innerText = this.value;" placeholder="{{ __('product.color') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ $coolor->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-danger remove-coolor">-</button>
                                        @if($loop->last)
                                            <button type="button" class="btn btn-success add-coolor">{{ __('product.add_color') }}</button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="row mb-2">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <input type="color" name="coolor[]" class="form-control" value="#000000" onchange="this.nextElementSibling.innerText = this.value;">
                                            <div class="input-group-append">
                                                <span class="input-group-text">#000000</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-danger remove-coolor">-</button>
                                        <button type="button" class="btn btn-success add-coolor">{{ __('product.add_color') }}</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- New Gallery Section --}}
               

                {{-- New Gallery Section --}}
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="gallery" class="control-label">{{ __('product.image_gallery') }}</label>
                        <div class="gallery">
                            @foreach($image as $img)
                            <div style="display: inline-block; position: relative; margin:5px;">
                                <img src="{{ asset('images/product/' . $img->name) }}" alt="Gallery Image" class="img-thumbnail" width="100">
                                <a href="{{ route('products/deleteImage', encrypt($img->id)) }}" style="position: absolute; top: 0; right: 0;" 
                                   onclick="deleteImageConfirmation(event, this)">
                                   <i class="fa fa-times" style="color:red; background:#fff; border-radius:50%; padding:2px;"></i>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- New Additional Images Input --}}
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

                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ __('product.update_product_button') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<script>
    function deleteImageConfirmation(event, element) {
        event.preventDefault();
        Swal.fire({
            title: '{{ __('product.confirm_delete') }}',
            text: "{{ __('product.confirm_delete_image') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('product.yes_delete') }}',
            cancelButtonText: '{{ __('product.cancel') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = element.getAttribute('href');
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update dynamic addition for Sizes
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-size')) {
                // Remove existing add buttons from all rows
                document.querySelectorAll('#size-container .add-size').forEach(btn => btn.remove());
                let sizeContainer = document.getElementById('size-container');
                let newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-2');
                newRow.style.marginTop = "10px";
                newRow.innerHTML = `
                    <div class="col-md-10">
                        <div class="input-group">
                            <input type="text" name="size[]" class="form-control" placeholder="ادخل حجم المنتج">
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger remove-size">-</button>
                        <button type="button" class="btn btn-success add-size">+ إضافة</button>
                    </div>
                `;
                sizeContainer.appendChild(newRow);
            }
        });
    
        document.addEventListener('click', function(e){
            if (e.target.classList.contains('remove-size')) {
                e.target.closest('.row').remove();
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inline dynamic addition for Coolors
        document.addEventListener('click', function(e) {
            if(e.target.classList.contains('add-coolor')) {
                // Remove all existing add-coolor buttons
                document.querySelectorAll('#coolor-container .add-coolor').forEach(btn => btn.remove());
                let coolorContainer = document.getElementById('coolor-container');
                let newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-2');
                newRow.style.marginTop = "10px";
                newRow.innerHTML = `
                    <div class="col-md-10">
                        <div class="input-group">
                            <input type="color" name="coolor[]" class="form-control" value="#000000" onchange="this.nextElementSibling.innerText = this.value;">
                            <div class="input-group-append">
                                <span class="input-group-text">#000000</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger remove-coolor">-</button>
                        <button type="button" class="btn btn-success add-coolor">+ إضافة لون</button>
                    </div>
                `;
                coolorContainer.appendChild(newRow);
            }
        });
        document.addEventListener('click', function(e) {
            if(e.target.classList.contains('remove-coolor')) {
                e.target.closest('.row').remove();
            }
        });
    });
</script>

@endsection
