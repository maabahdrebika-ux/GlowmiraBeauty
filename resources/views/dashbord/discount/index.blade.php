@extends('layouts.app')

@section('title', trans('discount.discounts_title'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-4">
                            <div class="float-right d-none d-md-block">
                                <a href="{{ route('discounts/create') }}"
                                    class="btn btn-primary">{{ trans('discount.add_discount') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">

                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">{{ trans('discount.view_discounts') }}</h4>
                        <div class="table-responsive">
                            <table id="datatable1" class="table table-bordered  "
                                style="border-spacing: 0; width: 100%;">

                                <thead>
                                    <tr>
                                        <th>{{ trans('discount.barcode') }}</th>
                                        <th>{{ trans('discount.product') }}</th>
                                        <th>{{ trans('discount.price') }}</th>
                                        <th>{{ trans('discount.discount_percentage') }}</th>
                                        <th>{{ trans('discount.price_after_discount') }}</th>
                                        <th>{{ trans('discount.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تمرير الترجمات إلى جافاسكريبت -->
    <script>
        window.i18n = {
            are_you_sure: @json(__('discount.are_you_sure')),
            you_wont_be_able_to_revert_this: @json(__('discount.you_wont_be_able_to_revert_this')),
            yes_delete_it: @json(__('discount.yes_delete_it')),
            cancel: @json(__('discount.cancel')),
            deleted: @json(__('discount.deleted')),
            discount_deleted: @json(__('discount.discount_deleted')),
            error: @json(__('discount.error')),
            error_occurred: @json(__('discount.error_occurred')),
            dinar: @json(__('discount.dinar')),
            percent: @json(__('discount.percent')),
            delete: @json(__('discount.delete')),
            copy: @json(__('discount.copy')),
            export_to_excel: @json(__('discount.export_to_excel')),
            show_columns: @json(__('discount.show_columns')),
            actions: @json(__('discount.actions')),
            price_after_discount: @json(__('discount.price_after_discount')),
        };
    </script>
<script>
    $(document).on('click', '.removeItem', function () {

        const rowId = $(this).data('idss');
        if (!rowId) {
            Swal.fire(i18n.error, i18n.error_occurred, "error");
            return;
        }

        Swal.fire({
            title: i18n.are_you_sure,
            text: i18n.you_wont_be_able_to_revert_this,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: i18n.yes_delete_it,
            cancelButtonText: i18n.cancel
        }).then((result) => {

            if (!result.isConfirmed) return;

            $.ajax({
                url: `/discounts/${rowId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    Swal.fire(i18n.deleted, i18n.discount_deleted, "success");

                    let table = $('#datatable1').DataTable();
                    table.ajax.reload(null, false);
                },
                error: function () {
                    Swal.fire(i18n.error, i18n.error_occurred, "error");
                }
            });

        });

    });
</script>


    <script>
        $(function() {
            $('#datatable1').DataTable({
                language: {
                    @if (app()->getLocale() === 'ar')
                        url: "{{ asset('Arabic.json') }}"
                    @endif
                },
                lengthMenu: [5, 10],
                bLengthChange: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('discounts/discounts') }}', // تأكد أن المسار صحيح ويعيد {data:[]}
                    type: 'GET',
                    dataSrc: function(json) {
                        return json.data || [];
                    }
                },
                // تأكد أن هيكلة البيانات القادمة من السيرفر:
                // row = { products: { barcode, name, price }, percentage, discountid }
                columns: [
                    // 1) الباركود
                    {
                        data: 'products.barcode',
                        defaultContent: '-'
                    },
 @if (session('language', app()->getLocale()) == 'ar')
        { data: 'products.name', defaultContent: '-' },
    @else
        { data: 'products.namee', defaultContent: '-' },
    @endif

                    // 2) اسم المنتج

                    // 3) السعر
                    {
                        data: 'products.price',
                        defaultContent: 0,
                        render: function(data, type, row) {
                            const price = Number(data || 0);
                            return price.toFixed(2) + ' ' + i18n.dinar;
                        }
                    },

                    // 4) نسبة الخصم
                    {
                        data: 'percentage',
                        defaultContent: 0,
                        render: function(data, type, row) {
                            const pct = Number(data || 0);
                            return pct + ' ' + i18n.percent;
                        }
                    },

                    // 5) السعر بعد الخصم
                    {
                        data: 'price_after_discount',
                        render: function(data, type, row) {
                            const discounted = Number(data || 0);
                            return discounted.toFixed(2) + ' ' + i18n.dinar;
                        }
                    },

                    // 6) الإجراءات (حذف)
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const id = row?.discountid ?? row?.id ?? '';
                            return `
                        <button style="background-color: white;" type="button" class="btn  removeItem" data-idss="${id}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                 viewBox="0 0 24 24" aria-label="Trash">
                                <g fill="none" stroke="#C5979A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"/>
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    <path d="M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14"/>
                                    <path d="M10 11v6M14 11v6"/>
                                </g>
                            </svg>
                        </button>
                    `;
                        }
                    }
                ],
                dom: 'Blfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        text: i18n.copy
                    },
                    {
                        extend: 'excelHtml5',
                        text: i18n.export_to_excel
                    },
                    {
                        extend: 'colvis',
                        text: i18n.show_columns
                    }
                ]
            });
        });
    </script>
@endsection
