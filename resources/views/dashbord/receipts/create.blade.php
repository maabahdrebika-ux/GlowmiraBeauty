@extends('layouts.app')
@section('title', trans('receipt.title'))
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <div class="container-fluid">

        <div class="row card">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
         <h4 class="mt-0 header-title"><a
                                href="{{ route('receipts') }}">{{ trans('receipt.breadcrumb') }}</a>/{{ trans('receipt.add_receipt') }}</h4>                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="row">
            <div class="col-lg-12 card">
                <div class=" m-b-30">
                    <div class="card-body">

                        <form method="POST" class="" action="{{ route('receipts/store') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>{{ trans('receipt.supplier') }} <span class="text-danger">{{ trans('receipt.required') }}</span></label>
                                    <select name="suppliers_id" id="suppliers_id" class="form-control @error('suppliers_id') is-invalid @enderror" required>
                                        <option value="">{{ trans('receipt.choose_supplier') }}</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('suppliers_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }} - {{ $supplier->phone ?? trans('receipt.no_phone') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('suppliers_id')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <video id="barcodeScanner" style="width: 100%; display: none;"></video>

                                    <label>{{ trans('receipt.barcode') }}</label>
                                    <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                        value="{{ old('barcode') }}" id="barcode" placeholder="{{ trans('receipt.enter_barcode') }}">
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
                                        {{ trans('receipt.scan_barcode') }}
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
                                        {{ trans('receipt.search') }}
                                    </button>
                                </div>

                            </div>
                            <div class="row">
                                <!-- قائمة  المنتج -->

                                <div class="form-group col-md-2">
                                    <label>{{ trans('receipt.product') }}</label>
                                    <select name="items_id" id="items_id" class="form-control">

                                    </select>
                                </div>


                                <!-- قائمة اختيار اللون -->
                                <div class="form-group col-md-2">
                                    <label>{{ trans('receipt.color') }}</label>
                                    <select name="grades_id" id="grades_id" class="form-control">
                                        <option value="">{{ trans('receipt.choose_color') }}</option>
                                    </select>
                                </div>

                                <!-- قائمة اختيار المقاسات -->
                                <div class="form-group col-md-2">
                                    <label>{{ trans('receipt.sizes') }}</label>
                                    <select name="sizes_id" id="sizes_id" class="form-control">
                                        <option value="">{{ trans('receipt.choose_sizes') }}</option>
                                    </select>
                                </div>

                                <!-- إدخال الكمية -->
                                <div class="form-group col-md-2">
                                    <label>{{ trans('receipt.quantity') }}</label>
                                    <input type="number" name="quantty" class="form-control" id="quantty" min="1"
                                        placeholder="{{ trans('receipt.enter_quantity') }}">
                                </div>

                                <!-- إدخال السعر -->
                                <div class="form-group col-md-2">
                                    <label>{{ trans('receipt.price_per_piece') }}</label>
                                    <input type="number" name="itemPrice" class="form-control" id="itemPrice" placeholder="{{ trans('receipt.price') }}">
                                </div>

                                <!-- إدخال تاريخ الصلاحية -->
                                <div class="form-group col-md-2">
                                    <label>{{ trans('receipt.expired_date') }}</label>
                                    <input type="date" name="expired_date" class="form-control" id="expired_date" placeholder="{{ trans('receipt.expired_date') }}">
                                </div>

                                <!-- زر الإضافة -->
                                <div class="form-group col-md-2 align-self-end" style="margin-top:30px">
                                    <button type="button" id="addItem" class="btn btn-primary btn-block">{{ trans('receipt.add_item') }}</button>
                                </div>
                            </div>

                            <!-- جدول عرض العناصر -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('receipt.product_name') }}</th>
                                            <th>{{ trans('receipt.color') }}</th>
                                            <th>{{ trans('receipt.sizes') }}</th>
                                            <th>{{ trans('receipt.quantity') }}</th>
                                            <th>{{ trans('receipt.price') }}</th>
                                            <th>{{ trans('receipt.expired_date') }}</th>
                                            <th>{{ trans('receipt.total') }}</th>
                                            <th>{{ trans('receipt.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>

                                <div class="row mt-2">
                                    <div class="col-md-12 text-right">
                                        <strong>{{ trans('receipt.total') }}:</strong> <span id="totalAmount">0</span> {{ trans('receipt.currency') }}
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="items" id="itemsInput">

                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light">{{ trans('receipt.add') }}</button>
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
                        title: '{{ trans('receipt.warning') }}!',
                        text: '{{ trans('receipt.enter_barcode_before_search') }}',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('get/coolors/sizes') }}",
                    type: "GET",
                    data: {
                        barcode: barcode
                    },
                    success: function(response) {
                        // Clear existing items in the items_id select
                        $('#items_id').empty();
                        // معالجة الألوان (grades)
                        if (response.items_id) {
                            $('#items_id').append(
                                `<option value="${response.items_id}">${response.name}</option>`
                            );
                        }

                        $('#grades_id').empty();
                        if (response.coolors.length > 0) {
                            $('#grades_id').append('<option value="">اختر اللون</option>');
                            $.each(response.coolors, function(key, grade) {
                                $('#grades_id').append(
                                    `<option style="color: ${grade.color};" value="${grade.id}"><label style="color: ${grade.color};"></label> ${grade.name}</option>`
                                );
                            });
                        } else {
                            $('#grades_id').append('<option value="">{{ trans('receipt.not_available') }}</option>');
                        }

                        // معالجة المقاساتات (sizes)
                        $('#sizes_id').empty();
                        if (response.sizes.length > 0) {
                            $('#sizes_id').append('<option value="">اختر المقاسات</option>');
                            $.each(response.sizes, function(key, size) {
                                $('#sizes_id').append(
                                    `<option value="${size.id}">${size.name}</option>`
                                );
                            });
                        } else {
                            $('#sizes_id').append('<option value="">{{ trans('receipt.not_available') }}</option>');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ trans('receipt.error') }}!',
                            text: xhr.responseJSON ? xhr.responseJSON.error :
                                '{{ trans('receipt.search_error') }}',
                            confirmButtonText: 'حسناً'
                        });
                    }
                });
            });


            $('#addItem').click(function() {
                var itemId = $('#items_id').val();
                var itemName = $('#items_id option:selected').text();
                var gradeId = $('#grades_id').val();
                var gradeName = gradeId ? $('#grades_id option:selected').text() : 'لا يوجد';
                var sizeId = $('#sizes_id').val();
                var sizeName = sizeId ? $('#sizes_id option:selected').text() : 'لا يوجد';
                var quantity = $('#quantty').val();
                var price = $('#itemPrice').val();
                var expiredDate = $('#expired_date').val();

                if (!itemId) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ trans('receipt.warning') }}!',
                        text: '{{ trans('receipt.choose_product') }}',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                if (!quantity || !price) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ trans('receipt.warning') }}!',
                        text: '{{ trans('receipt.enter_price_quantity') }}',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                var total = quantity * price;
                var row = `<tr data-id="${itemId}" data-coolor-id="${gradeId}" data-size-id="${sizeId}" data-expired-date="${expiredDate}">
                    <td>${itemName}</td>
                    <td>${gradeName}</td>
                    <td>${sizeName}</td>
                    <td>${quantity}</td>
                    <td>${price}</td>
                    <td>${expiredDate}</td>
                    <td class="item-total">${total}</td>
                    <td><button type="button" class="removeItem" style="background: none !important; border: none;">
<svg xmlns="http://www.w3.org/2000/svg"
     viewBox="0 0 24 24"
     width="26" height="26"
     fill="none"
     stroke="currentColor"
     stroke-width="2"
     stroke-linecap="round"
     stroke-linejoin="round"
     style="color: #c5979a;">
  <path d="M3 6h18"/>
  <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
  <path d="M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14"/>
  <path d="M10 11v6M14 11v6"/>
</svg>
                        </button></td>
                </tr>`;

                $('#itemsTable tbody').append(row);
                updateTotal();
            });

            $('#itemsTable').on('click', '.removeItem', function() {
                $(this).closest('tr').remove();
                updateTotal();
            });

            function updateTotal() {
                var total = 0;
                $('.item-total').each(function() {
                    total += parseFloat($(this).text());
                });
                $('#totalAmount').text(total);
            }

            $('form').submit(function() {
                var items = [];
                $('#itemsTable tbody tr').each(function() {
                    items.push({
                        id: $(this).data('id'),
                        coolor_id: $(this).data('coolor-id') || null,
                        size_id: $(this).data('size-id') || null,
                        quantity: $(this).find('td:nth-child(4)').text(),
                        price: $(this).find('td:nth-child(5)').text(),
                        expired_date: $(this).data('expired-date') || null
                    });
                });
                $('#itemsInput').val(JSON.stringify(items));
            });
        });
    </script>
@endsection
