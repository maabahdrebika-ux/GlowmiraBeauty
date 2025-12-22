@extends('layouts.app')

@section('title', 'إضافة تخفيض')

@section('content')
    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('discounts') }}">{{ trans('discount.discounts_link') }}</a>/{{ trans('discount.add_discount') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        <form method="POST" action="{{ route('discounts/store') }}" enctype="multipart/form-data">
                            @csrf

                    <!-- First Row: Barcode and buttons -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <video id="barcodeScanner" style="width: 100%; display: none;"></video>

                            <label for="items_id" class="control-label">{{ trans('discount.barcode') }}</label>
                            <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                value="{{ old('barcode') }}" id="barcode" placeholder="{{ trans('discount.barcode') }}">
                            @error('barcode')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-3 align-self-end" style="margin-top:30px">
                            <button type="button" id="startScan" class="btn btn-primary btn-block d-flex align-items-center justify-content-center">
                                <img src="{{ asset('barcode.png') }}" alt="Scan" style="width: 24px; height: 24px; margin-right: 5px;">
                                {{ trans('discount.scan_barcode') }}
                            </button>
                        </div>

                        <div class="form-group col-md-3 align-self-end" style="margin-top:30px">
                            <button type="button" id="search" class="btn btn-primary btn-block d-flex align-items-center justify-content-center">
                                <img src="{{ asset('search.png') }}" alt="Search" style="width: 24px; height: 24px; margin-right: 5px;">
                                {{ trans('discount.search') }}
                            </button>
                        </div>

                    </div>
                    <div class="form-row">
                        <!-- Product -->

                        <div class="form-group col-md-4">
                            <label for="items_id" class="control-label">{{ trans('discount.product') }}</label>
                            <input type="text" name="products_id" class="form-control @error('products_id') is-invalid @enderror"
                                value="{{ old('products_id') }}" id="products_id" placeholder="{{ trans('discount.product_placeholder') }}">
                            @error('products_id')
                                <span class="invalid-feedback" role="alert" style="color: red">{{ $message }}</span>
                            @enderror
                            <!-- حقل مخفي لتخزين معرف المنتج -->
                            <input type="hidden" name="proudatid" id="proudatid" value="{{ old('proudatid') }}">
                        </div>



                        <!-- Percentage -->
                        <div class="form-group col-md-4">
                            <label for="percentage" class="control-label">{{ trans('discount.discount_percentage') }}</label>
                            <input type="number" name="percentage" class="form-control" id="percentage" min="1"
                                placeholder="{{ trans('discount.discount_percentage_placeholder') }}">
                        </div>



                        <!-- Submit button -->
                        <div class="form-group col-md-4 align-self-end" style="margin-top:30px">
                            <button type="submit"  class="btn btn-primary btn-block">{{ trans('discount.add_discount') }}</button>
                        </div>
                    </div>


                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('#startScan').on('click', function() {
                $('#barcodeScanner').show(); // إظهار الفيديو

                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: document.querySelector("#barcodeScanner"), // عنصر الفيديو
                        constraints: {
                            facingMode: "environment" // استخدام الكاميرا الخلفية
                        }
                    },
                    decoder: {
                        readers: ["code_128_reader", "ean_reader",
                            "ean_8_reader"
                        ] // أنواع الباركود المدعومة
                    }
                }, function(err) {
                    if (err) {
                        console.error(err);
                        return;
                    }
                    Quagga.start();
                });

                // معالجة النتيجة بعد قراءة الباركود
                Quagga.onDetected(function(result) {
                    var barcode = result.codeResult.code;
                    $('#barcode').val(barcode); // تعبئة حقل الباركود
                    $('#barcodeScanner').hide(); // إخفاء الكاميرا بعد القراءة
                    Quagga.stop(); // إيقاف المسح بعد العثور على الباركود
                });
            });
            // 
            $('#search').on('click', function() {
                var barcode = $('#barcode').val().trim();

                if (barcode === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ trans('discount.are_you_sure') }}',
                        text: '{{ trans('discount.enter_barcode_before_search') }}',
                        confirmButtonText: '{{ trans('discount.yes_delete_it') }}'
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('discounts/getproudact') }}",
                    type: "GET",
                    data: {
                        barcode: barcode
                    },
                    success: function(response) {
                            $('#products_id').val(response.products.name);   
                            $('#proudatid').val(response.products.id);
                        },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ trans('discount.error') }}',
                            text: xhr.responseJSON ? xhr.responseJSON.error :
                                '{{ trans('discount.error_during_search') }}',
                            confirmButtonText: '{{ trans('discount.yes_delete_it') }}'
                        });
                    }
                });
            });



         
        });
    </script>
  
@endsection
