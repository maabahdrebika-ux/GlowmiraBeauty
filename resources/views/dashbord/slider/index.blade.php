@extends('layouts.app')

@section('title', trans('app.slider'))

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
                                <a href="{{ route('slider/create') }}"
                                    class="btn btn-primary">{{ trans('app.addslider') }}</a>
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
                        <h4 class="mt-0 header-title">{{ trans('app.slider') }}</h4>
                        <div class="table-responsive">
                            <table id="datatable1" class="table table-bordered  "
                                style="border-spacing: 0; width: 100%;">

                                <thead>
                                    <tr>
                                        <th>{{ trans('app.view') }}</th>
                                        <th>{{ trans('app.actions') }}</th>
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
            are_you_sure: @json(__('app.confirm_remove_from_cart')),
            you_wont_be_able_to_revert_this: @json(__('app.try_again_later')),
            yes_delete_it: @json(__('app.cancel')),
            cancel: @json(__('app.back_to_home')),
            deleted: @json(__('app.added_to_cart')),
            slider_deleted: @json(__('app.error_occurred')),
            error: @json(__('app.error_occurred')),
            error_occurred: @json(__('app.error_occurred')),
            delete: @json(__('app.cancel')),
            copy: @json(__('app.copy')),
            export_to_excel: @json(__('app.export_to_excel')),
            show_columns: @json(__('app.view_all_notifications')),
            actions: @json(__('app.actions')),
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
                url: `/slider/${rowId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    Swal.fire(i18n.deleted, i18n.slider_deleted, "success");

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
                    url: '{{ route('slider/slider') }}', // تأكد أن المسار صحيح ويعيد {data:[]}
                    type: 'GET',
                    dataSrc: function(json) {
                        return json.data || [];
                    }
                },
                // تأكد أن هيكلة البيانات القادمة من السيرفر:
                // row = { imge: '...', delete: '...' }
                columns: [
                    // 1) الصورة
                    {
                        data: 'imge',
                        defaultContent: '-'
                    },

                    // 2) الإجراءات (حذف)
                    {
                        data: 'delete',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const id = row?.id ?? '';
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
