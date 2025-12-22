@extends('layouts.app')

@section('title', trans('customers.customers_title'))

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
                            <a href="{{ route('customers.create') }}" class="btn btn-primary">{{ trans('customers.add_customer') }}</a>
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
                    <h4 class="mt-0 header-title">{{ trans('customers.customers_management') }}</h4>
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{ trans('customers.name') }}</th>
                                    <th>{{ trans('customers.email') }}</th>
                                    <th>{{ trans('customers.phone') }}</th>
                                    <th>{{ trans('customers.address') }}</th>
                                    <th>{{ trans('customers.invoices') }}</th>
                                    <th>{{ trans('customers.notes') }}</th>
                                    <th>{{ trans(' bncustomers.actions') }}</th>
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

<!-- Customer Management JavaScript -->
<script>
    window.i18n = {
        are_you_sure: @json(__('validation.are_you_sure')),
        you_wont_be_able_to_revert_this: @json(__('customers.delete_customer_confirm')),
        yes_delete_it: @json(__('front.yes')),
        cancel: @json(__('front.cancel')),
        deleted: @json(__('front.success')),
        customer_deleted: @json(__('customers.customer_deleted')),
        error: @json(__('validation.error')),
        error_occurred: @json(__('validation.error_occurred')),
        copy: @json(__('front.copy')),
        export_to_excel: @json(__('front.export_to_excel')),
        show_columns: @json(__('front.columns')),
        cannot_delete_with_invoices: @json(__('customers.cannot_delete_with_invoices')),
        customer_has_invoices: @json(__('customers.customer_has_invoices')),
    };
</script>

<script>
    $(document).on('click', '.removeCustomer', function () {
        const customerId = $(this).data('id');
        if (!customerId) {
            Swal.fire(i18n.error, i18n.error_occurred, "error");
            return;
        }

        // Check if customer has invoices first
        $.ajax({
            url: `{{ route('customers.checkInvoices', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', customerId),
            type: 'GET',
            success: function (response) {
                if (response.has_invoices) {
                    Swal.fire({
                        title: i18n.cannot_delete_with_invoices,
                        text: i18n.customer_has_invoices,
                        icon: "error",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: i18n.cancel
                    });
                    return;
                }

                // If no invoices, proceed with delete confirmation
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
                        url: `{{ route('customers.destroy', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', customerId),
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire(i18n.deleted, i18n.customer_deleted, "success");
                                $('#datatable-buttons').DataTable().ajax.reload(null, false);
                            } else {
                                Swal.fire(i18n.error, response.message, "error");
                            }
                        },
                        error: function () {
                            Swal.fire(i18n.error, i18n.error_occurred, "error");
                        }
                    });
                });
            },
            error: function () {
                Swal.fire(i18n.error, i18n.error_occurred, "error");
            }
        });
    });
</script>

<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#datatable-buttons')) {
        $('#datatable-buttons').DataTable().destroy();
    }
    $('#datatable-buttons').DataTable({
        @if(App::getLocale() == 'ar')
        "language": {
            "url": "{{ asset('Arabic.json') }}"
        },
        @endif
        "lengthMenu": [5, 10],
        "bLengthChange": true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("customers.datatable") }}',
            type: 'GET',
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            {
                data: 'customer_name',
                defaultContent: '-'
            },
            {
                data: 'customer_email',
                defaultContent: '-'
            },
            {
                data: 'customer_phone',
                defaultContent: '-'
            },
            {
                data: 'customer_address',
                defaultContent: '-',
                orderable: false
            },
            {
                data: 'invoices_count',
                render: function(data, type, row) {
                    if (data > 0) {
                        return '<span class="badge badge-info"><i class="fa fa-file-invoice"></i> ' + data + '</span>';
                    }
                    return '<span class="badge badge-secondary">0</span>';
                }
            },
            {
                data: 'customer_notes',
                defaultContent: '-',
                orderable: false
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return data.actions;
                }
            }
        ],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [':visible']
                },
                text: '{{ trans("front.copy") }}'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                },
                text: '{{ trans("front.export_to_excel") }}'
            },
            {
                extend: 'colvis',
                text: '{{ trans("front.columns") }}'
            }
        ]
    });
});
</script>

@endsection
