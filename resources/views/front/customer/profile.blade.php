<div class="row">
    <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
        <div class="checkout-area">
            <div class="checkout-wrap">
                <div class="checkout-top">
                    <h3>{{ __('app.edit_profile') }}</h3>
                </div>
                <div class="checkout-form">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="name">{{ __('app.full_name') }} *</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="email">{{ __('app.email') }} *</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="phone">{{ __('app.phone') }} *</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="address">{{ __('app.address') }}</label>
                                    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="password">{{ __('app.password') }} ({{ __('app.leave_blank') }})</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="password_confirmation">{{ __('app.confirm_password') }}</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="submit-btn">
                            <button type="submit" class="theme-btn">{{ __('app.update_profile') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
