@extends('layouts.app')

@section('title', trans('contactus.edit'))

@section('content')

        <div class="container-fluid">

            <div class="row card">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mt-0 header-title"><a
                                        href="{{ route('contactus') }}">{{ trans('contactus.contactus') }}</a> /
                                    {{ trans('contactus.edit') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 card">
                    <div class="m-b-30">
                        <div class="card-body">
                            <form method="POST" action="" class="row">
                                @csrf
                                <div class="form-group col-md-4">
                                    <label for="email">{{ trans('contactus.email') }}</label>
                                    <input type="text" name="email" maxlength="50"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ optional($contactus)->email }}" id="email"
                                        placeholder="{{ trans('contactus.email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="phone">{{ trans('contactus.phone') }}</label>
                                    <input type="text" name="phone" maxlength="50"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ optional($contactus)->phonenumber }}" id="phone"
                                        placeholder="{{ trans('contactus.phone') }}" required>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="whatsapp">{{ trans('contactus.whatsapp') }}</label>
                                    <input type="text" name="whatsapp" maxlength="50"
                                        class="form-control @error('whatsapp') is-invalid @enderror"
                                        value="{{ optional($contactus)->whatsapp }}" id="whatsapp"
                                        placeholder="{{ trans('contactus.whatsapp') }}">
                                    @error('whatsapp')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="adress">{{ trans('contactus.adress_ar') }}</label>
                                    <input type="text" name="adress" maxlength="50"
                                        class="form-control @error('adress') is-invalid @enderror"
                                        value="{{ optional($contactus)->adress }}" id="adress"
                                        placeholder="{{ trans('contactus.adress_ar') }}" required>
                                    @error('adress')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="adressen">{{ trans('contactus.adress_en') }}</label>
                                    <input type="text" name="adressen" maxlength="50"
                                        class="form-control @error('adressen') is-invalid @enderror"
                                        value="{{ optional($contactus)->adressen }}" id="adressen"
                                        placeholder="{{ trans('contactus.adress_en') }}" required>
                                    @error('adressen')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="lan">{{ trans('contactus.lan') }}</label>
                                    <input type="text" name="lan" maxlength="50"
                                        class="form-control @error('lan') is-invalid @enderror"
                                        value="{{ optional($contactus)->lan }}" id="lan"
                                        placeholder="{{ trans('contactus.lan') }}" required>
                                    @error('lan')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="long">{{ trans('contactus.long') }}</label>
                                    <input type="text" name="long" maxlength="50"
                                        class="form-control @error('long') is-invalid @enderror"
                                        value="{{ optional($contactus)->long }}" id="long"
                                        placeholder="{{ trans('contactus.long') }}" required>
                                    @error('long')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="facebook_url">{{ trans('contactus.facebook_url') }}</label>
                                    <input type="url" name="facebook_url"
                                        class="form-control @error('facebook_url') is-invalid @enderror"
                                        value="{{ optional($contactus)->facebook_url }}" id="facebook_url"
                                        placeholder="{{ trans('contactus.facebook_url') }}">
                                    @error('facebook_url')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="instagram_url">{{ trans('contactus.instagram_url') }}</label>
                                    <input type="url" name="instagram_url"
                                        class="form-control @error('instagram_url') is-invalid @enderror"
                                        value="{{ optional($contactus)->instagram_url }}" id="instagram_url"
                                        placeholder="{{ trans('contactus.instagram_url') }}">
                                    @error('instagram_url')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="twitter_url">{{ trans('contactus.twitter_url') }}</label>
                                    <input type="url" name="twitter_url"
                                        class="form-control @error('twitter_url') is-invalid @enderror"
                                        value="{{ optional($contactus)->twitter_url }}" id="twitter_url"
                                        placeholder="{{ trans('contactus.twitter_url') }}">
                                    @error('twitter_url')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="linkedin_url">{{ trans('contactus.linkedin_url') }}</label>
                                    <input type="url" name="linkedin_url"
                                        class="form-control @error('linkedin_url') is-invalid @enderror"
                                        value="{{ optional($contactus)->linkedin_url }}" id="linkedin_url"
                                        placeholder="{{ trans('contactus.linkedin_url') }}">
                                    @error('linkedin_url')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="youtube_url">{{ trans('contactus.youtube_url') }}</label>
                                    <input type="url" name="youtube_url"
                                        class="form-control @error('youtube_url') is-invalid @enderror"
                                        value="{{ optional($contactus)->youtube_url }}" id="youtube_url"
                                        placeholder="{{ trans('contactus.youtube_url') }}">
                                    @error('youtube_url')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pinterest_url">{{ trans('contactus.pinterest_url') }}</label>
                                    <input type="url" name="pinterest_url"
                                        class="form-control @error('pinterest_url') is-invalid @enderror"
                                        value="{{ optional($contactus)->pinterest_url }}" id="pinterest_url"
                                        placeholder="{{ trans('contactus.pinterest_url') }}">
                                    @error('pinterest_url')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="ourworksa">{{ trans('contactus.ourworksa') }}</label>
                                    <textarea name="ourworksa" maxlength="5000" class="form-control @error('ourworksa') is-invalid @enderror"
                                        id="ourworksa" placeholder="{{ trans('contactus.ourworksa') }}" rows="5">{{ optional($contactus)->ourworksa }}</textarea>
                                    @error('ourworksa')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="ourworkse">{{ trans('contactus.ourworkse') }}</label>
                                    <textarea name="ourworkse" maxlength="5000" class="form-control @error('ourworkse') is-invalid @enderror"
                                        id="ourworkse" placeholder="{{ trans('contactus.ourworkse') }}" rows="5">{{ optional($contactus)->ourworkse }}</textarea>
                                    @error('ourworkse')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <button type="submit" style="margin-top: 33px;"
                                        class="btn btn-primary waves-effect waves-light">{{ trans('contactus.save_changes') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>




@endsection
