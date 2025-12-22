
@extends('layouts.app')

@section('title', trans('contactus.contactus'))

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                        <div class="container-fluid">

<div class="row small-spacing">
  <div class="col-md-12">
    <div class="box-content box-radius box-shadow p-4 bg-white">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="box-title text-orange font-weight-bold mb-0">
          <a href="{{ route('contactus') }}" class="text-orange" style="text-decoration:none;">
            {{ trans('contactus.contactus') }}
          </a>
        </h4>
        <a class="btn btn-warning btn-sm d-flex align-items-center" href="{{ route('contactus/edit') }}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="#a65e4f" class="mr-1">
            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
          </svg>
          {{ trans('contactus.edit') }}
        </a>
      </div>

      <div class="row mb-4">
        <div class="col-md-4 mb-3">
          <label class="text-dark font-weight-bold">{{ trans('contactus.email') }}:</label>
          <p class="text-muted">{{ $contactus->email ?? 'لايوجد' }}</p>
        </div>
        <div class="col-md-4 mb-3">
          <label class="text-dark font-weight-bold">{{ trans('contactus.phone') }}:</label>
          <p class="text-muted">{{ $contactus->phonenumber ?? 'لايوجد' }}</p>
        </div>
        <div class="col-md-4 mb-3">
          <label class="text-dark font-weight-bold">{{ trans('contactus.whatsapp') }}:</label>
          <p class="text-muted">{{ $contactus->whatsapp ?? 'لايوجد' }}</p>
        </div>
        <div class="col-md-4 mb-3">
          <label class="text-dark font-weight-bold">{{ trans('contactus.adress_ar') }}:</label>
          <p class="text-muted">{{ $contactus->adress ?? 'لايوجد' }}</p>
        </div>
        <div class="col-md-4 mb-3">
          <label class="text-dark font-weight-bold">{{ trans('contactus.adress_en') }}:</label>
          <p class="text-muted">{{ $contactus->adressen ?? 'لايوجد' }}</p>
        </div>
        <div class="col-md-4 mb-3">
          <label class="text-dark font-weight-bold">{{ trans('contactus.lan') }}:</label>
          <p class="text-muted">{{ $contactus->lan ?? 'لايوجد' }}</p>
        </div>
        <div class="col-md-4 mb-3">
          <label class="text-dark font-weight-bold">{{ trans('contactus.long') }}:</label>
          <p class="text-muted">{{ $contactus->long ?? 'لايوجد' }}</p>
        </div>
        <div class="col-md-12 mb-3">
          <label class="text-dark font-weight-bold">{{ trans('contactus.ourworksa') }}:</label>
          <p class="text-muted">{{ $contactus->ourworksa ?? 'لايوجد' }}</p>
        </div>
        <div class="col-md-12 mb-3">
          <label class="text-dark font-weight-bold">{{ trans('contactus.ourworkse') }}:</label>
          <p class="text-muted">{{ $contactus->ourworkse ?? 'لايوجد' }}</p>
        </div>
      </div>

      <div class="col-md-12 mb-4">
        <h4 class="text-orange font-weight-bold mb-3">{{ trans('contactus.social_media_links') }}</h4>
        <ul class="list-inline">
          @if($contactus->facebook_url)
            <li class="list-inline-item mr-3">
              <a href="{{ $contactus->facebook_url }}" target="_blank" class="text-primary">
                <i class="fa fa-facebook fa-lg mr-1"></i> Facebook
              </a>
            </li>
          @endif
          @if($contactus->instagram_url)
            <li class="list-inline-item mr-3">
              <a href="{{ $contactus->instagram_url }}" target="_blank" class="text-danger">
                <i class="fa fa-instagram fa-lg mr-1"></i> Instagram
              </a>
            </li>
          @endif
          @if($contactus->twitter_url)
            <li class="list-inline-item mr-3">
              <a href="{{ $contactus->twitter_url }}" target="_blank" class="text-info">
                <i class="fa fa-twitter fa-lg mr-1"></i> Twitter
              </a>
            </li>
          @endif
          @if($contactus->linkedin_url)
            <li class="list-inline-item mr-3">
              <a href="{{ $contactus->linkedin_url }}" target="_blank" class="text-primary">
                <i class="fa fa-linkedin fa-lg mr-1"></i> LinkedIn
              </a>
            </li>
          @endif
          @if($contactus->youtube_url)
            <li class="list-inline-item mr-3">
              <a href="{{ $contactus->youtube_url }}" target="_blank" class="text-danger">
                <i class="fa fa-youtube fa-lg mr-1"></i> YouTube
              </a>
            </li>
          @endif
          @if($contactus->pinterest_url)
            <li class="list-inline-item mr-3">
              <a href="{{ $contactus->pinterest_url }}" target="_blank" class="text-danger">
                <i class="fa fa-pinterest fa-lg mr-1"></i> Pinterest
              </a>
            </li>
          @endif
        </ul>
      </div>

      <div class="col-md-12 mb-4">
        <h4 class="text-orange font-weight-bold mb-3">{{ trans('contactus.location_on_map') }}</h4>
        <div id="map" class="border rounded shadow-sm" style="width: 100%; height: 400px;"></div>
      </div>
    </div>
  </div>
</div>

<script>
  var lat = {{ $contactus->lan ?? 0 }};
  var lng = {{ $contactus->long ?? 0 }};
  
  var map = L.map('map').setView([lat, lng], 15); 

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  var marker = L.marker([lat, lng]).addTo(map);
  marker.bindPopup("<b>Location</b>").openPopup();
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap"></script>
                        </div>
@endsection
