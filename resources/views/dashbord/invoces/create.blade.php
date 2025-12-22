@extends('layouts.app')
@section('title', trans('invoice.title'))
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('Invoice') }}">{{ trans('invoice.breadcrumb') }}</a>/{{ trans('invoice.add_sale') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        <form method="POST" class="" action="{{ route('Invoice/store') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <video id="barcodeScanner" style="width: 100%; display: none;"></video>

                                    <label>{{ trans('invoice.barcode') }}</label>
                                    <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                        value="{{ old('barcode') }}" id="barcode" placeholder="{{ trans('invoice.enter_barcode') }}">
                                    @error('barcode')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 align-self-end" style="margin-top:30px">
                                    <button type="button" id="startScan"
                                        class="btn btn-primary btn-block d-flex align-items-center justify-content-center">
                                          <svg class="icon" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.75"
                 stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true" style="height: 10px;">
              <!-- تمثيل أعمدة الباركود -->
              <path d="M4 4v16M6 4v16M10 4v16M14 4v16M16 4v16M20 4v16"/>
            </svg>
                                        {{ trans('invoice.scan_barcode') }}
                                    </button>
                                </div>

                                <div class="form-group col-md-3 align-self-end" style="margin-top:30px">
                                    <button type="button" id="search"
                                        class="btn btn-primary btn-block d-flex align-items-center justify-content-center">
                                         <svg class="icon" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.75"
                 stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true" style="height: 10px;">
              <!-- عدسة البحث -->
              <circle cx="11" cy="11" r="7"/>
              <!-- مقبض العدسة -->
              <line x1="16.65" y1="16.65" x2="21" y2="21"/>
            </svg>
                                        {{ trans('invoice.search') }}
                                    </button>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ trans('invoice.customer') }} <span class="text-danger">{{ trans('invoice.required') }}</span></label>
                                    <select name="customers_id" id="customers_id" class="form-control @error('customers_id') is-invalid @enderror" required>
                                        <option value="">{{ trans('invoice.choose_customer') }}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                        @endforeach
                                    </select>
                                    @error('customers_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <!-- قائمة  المنتج -->

                                <div class="form-group col-md-2">
                                    <label>{{ trans('invoice.product') }}</label>
                                    <select name="items_id" id="items_id" class="form-control">

                                    </select>
                                </div>


                                <!-- قائمة اختيار اللون -->
                                <div class="form-group col-md-2">
                                    <label>{{ trans('invoice.color') }}</label>
                                    <select name="grades_id" id="grades_id" class="form-control">
                                        <option value="">{{ trans('invoice.choose_color') }}</option>
                                    </select>
                                </div>

                                <!-- قائمة اختيار المقاسات -->
                                <div class="form-group col-md-2">
                                    <label>{{ trans('invoice.sizes') }}</label>
                                    <select name="sizes_id" id="sizes_id" class="form-control">
                                        <option value="">{{ trans('invoice.choose_sizes') }}</option>
                                    </select>
                                </div>

                                <!-- إدخال الكمية -->
                                <div class="form-group col-md-2">
                                    <label>{{ trans('invoice.quantity') }}</label>
                                    <input type="number" name="quantty" class="form-control" id="quantty" min="1"
                                        placeholder="{{ trans('invoice.enter_quantity') }}">
                                </div>

                                <!-- إدخال السعر -->
                                <div class="form-group col-md-2">
                                    <label>{{ trans('invoice.price_per_piece') }}</label>
                                    <input type="number" name="itemPrice" class="form-control" id="itemPrice" placeholder="{{ trans('invoice.price') }}">
                                </div>

                                <!-- زر الإضافة -->
                                <div class="form-group col-md-2 align-self-end" style="margin-top:30px">
                                    <button type="button" id="addItem" class="btn btn-primary btn-block">{{ trans('invoice.add_item') }}</button>
                                </div>
                            </div>

                            <!-- جدول عرض العناصر -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('invoice.product_name') }}</th>
                                            <th>{{ trans('invoice.color') }}</th>
                                            <th>{{ trans('invoice.sizes') }}</th>
                                            <th>{{ trans('invoice.quantity') }}</th>
                                            <th>{{ trans('invoice.price') }}</th>
                                            <th>{{ trans('invoice.total') }}</th>
                                            <th>{{ trans('invoice.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>

                                <div class="row mt-2">
                                    <div class="col-md-12 text-right">
                                        <strong>{{ trans('invoice.total') }}:</strong> <span id="totalAmount">0</span> {{ trans('invoice.currency') }}
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="items" id="itemsInput">

                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('invoice.add') }}</button>
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
                        title: '{{ trans('invoice.warning') }}',
                        text: '{{ trans('invoice.enter_barcode_before_search') }}',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('Invoice/stockall') }}",
                    type: "GET",
                    data: {
                        barcode: barcode
                    },
                    success: function(response) {
                        console.log(response);
                        // Clear existing items in the items_id select
                        $('#items_id').empty();
                        // معالجة الألوان (grades)
                        if (response.items_id) {

                            $('#items_id').append(
                                `<option value="${response.items_id}"  data-stock="${response.total_stock}">${response.name}</option>`
                            );
                        }

                        if (response.price) {
                            $('#itemPrice').val(response.price)

                        }



                        $('#grades_id').empty();
                        if (response.grades.length > 0) {
                            $('#grades_id').append('<option value="">{{ trans('invoice.choose_color') }}</option>');
                            $.each(response.grades, function(key, grade) {
                                $('#grades_id').append(
                                        `<option value="${grade.id}" data-stock="${grade.stock}">${grade.name || '{{ trans('invoice.not_available') }}'} (المتوفر: ${grade.stock})</option>`
                                    );
                            });
                        } else {
                            $('#grades_id').append('<option value="">{{ trans('invoice.not_available') }}</option>');
                        }

                        // معالجة المقاساتات (sizes)
                        $('#sizes_id').empty();
                        if (response.sizes.length > 0) {
                            $('#sizes_id').append('<option value="">{{ trans('invoice.choose_sizes') }}</option>');
                            $.each(response.sizes, function(key, size) {
                                $('#sizes_id').append(
                                        `<option value="${size.id}" data-stock="${size.stock}">${size.name || '{{ trans('invoice.not_available') }}'} (المتوفر: ${size.stock})</option>`
                                    );
                            });
                        } else {
                            $('#sizes_id').append('<option value="">{{ trans('invoice.not_available') }}</option>');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ trans('invoice.error') }}',
                            text: xhr.responseJSON ? xhr.responseJSON.error :
                                '{{ trans('invoice.search_error') }}',
                            confirmButtonText: 'حسناً'
                        });
                    }
                });
            });

            $('#addItem').on('click', function() {
                var itemId = $('#items_id').val();
                var itemName = $('#items_id option:selected').text() || 'لا يوجد';
                var gradeId = $('#grades_id').val();
                var gradeName = gradeId ? $('#grades_id option:selected').text() : 'لا يوجد';
                var sizeId = $('#sizes_id').val();
                var sizeName = sizeId ? $('#sizes_id option:selected').text() : 'لا يوجد';
                var quantity = $('#quantty').val();
                var price = $('#itemPrice').val() || 0;

                // If quantity is null or less than or equal to 0, show a message and stop
                if (!itemId || !quantity || quantity <= 0 || price <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ trans('invoice.error') }}',
                        text: '{{ trans('invoice.choose_product') }}'
                    });
                    return;
                }

                var stockGrade = $('#grades_id option:selected').data('stock') || 0;
                var stockSize = $('#sizes_id option:selected').data('stock') || 0;
                var generalStock = $('#items_id option:selected').data('stock') || 0;

                // Validate stock based on selected grade, size, or general stock
                if (gradeId && parseInt(quantity) > parseInt(stockGrade)) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ trans('invoice.error') }}',
                        text: `الكمية المدخلة (${quantity}) أكبر من المخزون المتوفر (${stockGrade}) لهذا اللون.`
                    });
                    return;
                }

                if (sizeId && parseInt(quantity) > parseInt(stockSize)) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ trans('invoice.error') }}',
                        text: `الكمية المدخلة (${quantity}) أكبر من المخزون المتوفر (${stockSize}) لهذا المقاسات.`
                    });
                    return;
                }

                if (!gradeId && !sizeId && parseInt(quantity) > parseInt(generalStock)) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ trans('invoice.error') }}',
                        text: `الكمية المدخلة (${quantity}) أكبر من المخزون المتوفر (${generalStock}).`
                    });
                    return;
                }

                // Deduct stock from generalStock if size and grades are null
                if (!gradeId && !sizeId) {
                    generalStock -= quantity;
                    $('#items_id option:selected').data('stock', generalStock);
                }

                var total = quantity * price;

                var row = `<tr>
                    <td>${itemName} <input type="hidden" class="items-id" value="${itemId}"></td>
                    <td>${gradeName} <input type="hidden" class="grades-id" value="${gradeId || ''}"></td>
                    <td>${sizeName} <input type="hidden" class="sizes-id" value="${sizeId || ''}"></td>
                    <td><input type="number" class="form-control quantity-input" value="${quantity}" min="1" max="${stockGrade || stockSize || generalStock}" data-stock="${stockGrade || stockSize || generalStock}"></td>
                    <td><input type="number" readonly class="form-control price-input" value="${price}" min="0"></td>
                    <td class="item-total">${total}</td>
                    <td><button type="button" class="btn btn-danger removeItem" style="background-color: white;border-color: white;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-label="Trash">
  <g fill="none" stroke="#C5979A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M3 6h18"/>
    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
    <path d="M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14"/>
    <path d="M10 11v6M14 11v6"/>
  </g>
</svg></button></td>
                </tr>`;

                $('#itemsTable tbody').append(row);

                updateTotalAmount();

                $('#items_id, #grades_id, #sizes_id, #quantty, #itemPrice').val('');
            });

            $(document).on('click', '.removeItem', function() {
                $(this).closest('tr').remove();
                updateTotalAmount();
            });

            $(document).on('input', '.quantity-input', function() {
                var input = $(this);
                var stock = parseInt(input.data('stock')) || 0;
                var quantity = parseInt(input.val()) || 1;

                if (quantity > stock) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ trans('invoice.error') }}',
                        text: `الكمية المدخلة (${quantity}) أكبر من المخزون المتوفر (${stock}).`,
                    });
                    input.val(stock); // إرجاع الكمية إلى الحد الأقصى المتاح
                }

                var row = input.closest('tr');
                var price = parseFloat(row.find('.price-input').val()) || 0;
                var total = quantity * price;
                row.find('.item-total').text(total);
                updateTotalAmount();
            });


            function updateTotalAmount() {
                var totalAmount = 0;
                $('#itemsTable tbody tr').each(function() {
                    totalAmount += parseFloat($(this).find('.item-total').text());
                });
                $('#totalAmount').text(totalAmount.toFixed(2));
            }


            $('form').submit(function() {
                var items = [];
                $('#itemsTable tbody tr').each(function() {
                    items.push({
                        item_id: $(this).find('.items-id').val(),
                        grade_id: $(this).find('.grades-id').val(),
                        size_id: $(this).find('.sizes-id').val(),
                        quantity: $(this).find('.quantity-input').val(),
                        price: $(this).find('.price-input').val()
                    });
                });
                $('#itemsInput').val(JSON.stringify(items));
            });
        });
    </script>
@endsection
