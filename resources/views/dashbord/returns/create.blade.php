@extends('layouts.app')
@section('title', trans('returns.title'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mt-0 header-title"><a href="{{ route('returns') }}">{{ trans('returns.breadcrumb') }}</a> / {{ trans('returns.add_return') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 card">
            <div class="card-body">
                <form method="POST" action="">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="exchangesnumber" class="control-label">{{ trans('returns.invoice_number') }} <span class="text-danger">{{ trans('returns.required') }}</span></label>
                            <input type="text" name="exchangesnumber"
                                class="form-control @error('exchangesnumber') is-invalid @enderror" value="{{ old('exchangesnumber') }}"
                                id="exchangesnumber" placeholder="{{ trans('returns.enter_invoice_number') }}">
                            @error('exchangesnumber')
                                <span class="invalid-feedback" role="alert" style="color: red">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-2 align-self-end">
                            <button type="button" id="searchinovue" class="btn btn-primary btn-block">{{ trans('returns.search') }}</button>
                        </div>
                    </div>
                </form>
                <div id="invoiceDetails">
                    <!-- سيتم تحميل بيانات الفاتورة هنا -->
                </div>
            </div>
        </div>
    </div>
</div>

   <!-- تضمين مكتبة SweetAlert2 -->

<script>
    $(document).ready(function() {
        $('#searchinovue').click(function() {
            var invoiceNumber = $('#exchangesnumber').val();

            if (invoiceNumber === '') {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ trans('returns.warning') }}',
                    text: '{{ trans('returns.enter_invoice_number_before_search') }}',
                });
                return;
            }

            $.ajax({
                url: "{{ route('returns/fetch/invoice') }}", // المسار في Laravel
                type: "GET",
                data: { exchangesnumber: invoiceNumber },
                success: function(response) {
                    if (response.success) {
                        $('#invoiceDetails').html(response.html); // تحديث الجدول بالبيانات الجديدة
                        Swal.fire({
                            icon: 'success',
                            title: '{{ trans('returns.success_return') }}',
                            text: '{{ trans('returns.added_successfully') }}',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ trans('returns.error') }}',
                            text: '{{ trans('returns.invoice_not_found') }}',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ trans('returns.error') }}',
                        text: '{{ trans('returns.search_error') }}',
                    });
                }
            });
        });
    });
</script>

    
 
@endsection
