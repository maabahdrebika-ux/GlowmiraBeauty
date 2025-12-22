
{{-- resources/views/front/about.blade.php --}}
@extends('front.app')
@section('title', trans('contactus.contactus'))

@section('content')
 <div class="breadcrumb">
            <div class="container">
              <h2>{{ trans('menu.contact') }}</h2>
              <ul><li><a href="/">{{ trans('menu.home') }}</a></li><li class="active">{{ trans('menu.contact') }}</li></ul>
            </div>
          </div>
      <div class="contact">
        <div class="container">
          <div class="row">
            <div class="col-12 col-md-6">
              <h3 class="contact-title">{{ trans('contactus.ourinformation') }}</h3>
              <div class="contact-info__item">
                <div class="contact-info__item__icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="contact-info__item__detail">
                  <h3>{{ trans('contactus.adress_en') }}</h3>
                  @if(app()->getLocale() == 'ar')
                  <p>{{ $contact->adress }}</p>
                  @else
                  <p>{{ $contact->adressen }}</p>
                  @endif
                </div>
              </div>
              <div class="contact-info__item">
                <div class="contact-info__item__icon"><i class="fas fa-phone-alt"></i></div>
                <div class="contact-info__item__detail">
                  <h3>{{ trans('contactus.phone') }}</h3>
                  <p>{{ $contact->phonenumber }}</p>
                </div>
              </div>
              <div class="contact-info__item">
                <div class="contact-info__item__icon"><i class="fab fa-whatsapp"></i></div>
                <div class="contact-info__item__detail">
                  <h3>{{ trans('contactus.whatsapp') }}</h3>
                  <p>{{ $contact->whatsapp }}</p>
                </div>
              </div>
              <div class="contact-info__item">
                <div class="contact-info__item__icon"><i class="far fa-envelope"></i></div>
                <div class="contact-info__item__detail">
                  <h3>{{ trans('contactus.email') }}</h3>
                  <p>{{ $contact->email }}</p>
                </div>
              </div>
               <div class="contact-info__item">
                <div class="contact-info__item__icon"><i class="far fa-clock"></i></div>
                <div class="contact-info__item__detail">
                  <h3>{{ trans('contactus.ourworks') }}</h3>
                  @if(app()->getLocale() == 'ar')
                  <p>{{ $contact->ourworksa }}</p>
                  @else
                  <p>{{ $contact->ourworkse }}</p>
                  @endif
                </div>
              </div>
          
            </div>
            <div class="col-12 col-md-6">
            <h3 class="contact-title">{{ trans('contactus.contactus') }}</h3>
              <div class="contact-form">
                <form method="POST" action="{{ route('send') }}">
                  @csrf
                  <div class="input-validator">
                    <input type="text" name="name" placeholder="{{ trans('contactus.your_name') }}" class="@error('name') is-invalid @enderror"/>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="input-validator">
                    <input type="email" name="email" placeholder="{{ trans('contactus.your_email') }}" class="@error('email') is-invalid @enderror"/>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="input-validator">
                    <input type="text" name="subject" placeholder="{{ trans('contactus.subject') }}" class="@error('subject') is-invalid @enderror"/>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="input-validator">
                    <textarea name="message" id="" cols="30" rows="3" placeholder="{{ trans('contactus.your_message') }}" class="@error('message') is-invalid @enderror"></textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <button type="submit" class="btn -dark">{{ trans('contactus.send_message') }}</button>
                </form>
              </div>
            </div>
            <div class="col-12">
              <iframe class="contact-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26544.761428132653!2d{{ $mapLocation['lng'] }}!3d{{ $mapLocation['lat'] }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab9bd9861ca1%3A0xe7887f7b72ca17a9!2zSMOgIE7hu5lpLCBIb8OgbiBLaeG6v20sIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1594639675485!5m2!1svi!2s" width="100%" height="450" frameborder="0" allowfullscreen=""></iframe>
            </div>
          </div>
        </div>
      </div>
      
@endsection
