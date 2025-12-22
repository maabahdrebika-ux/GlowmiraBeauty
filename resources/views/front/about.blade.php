{{-- resources/views/front/about.blade.php --}}
@extends('front.app')
@section('title', trans('menu.who_we_are'))

@section('content')
    <div class="breadcrumb">
        <div class="container">
            <h2>{{ trans('menu.about') }}</h2>
            <ul>
                <li>{{ trans('menu.home') }}</li>
                <li class="active">{{ trans('menu.about') }}</li>
            </ul>
        </div>
    </div>
    <div class="about">
        <div class="introduction-one">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6">
                        <div class="introduction-one-image">
                            <div class="introduction-one-image__detail">
                                @if (!empty($ab->intro_one_bg1))
                                    <img data-depth="0.5" src="{{ asset($ab->intro_one_bg1) }}"
                                        alt="background">
                                @else
                                    <img src="{{ asset('app/assets/images/introduction/IntroductionOne/img1.png') }}"
                                        alt="background">
                                @endif

                                  @if (!empty($ab->intro_one_bg2))
                                    <img data-depth="0.5" src="{{ asset($ab->intro_one_bg2) }}"
                                        alt="background">
                                @else
                                      <img src="{{ asset('app/assets/images/introduction/IntroductionOne/img2.png') }}"
                                    alt="background">
                                @endif
                            
                            </div>
                            <div class="introduction-one-image__background">
                                <div class="background__item">
                                    <div class="wrapper" ref="{bg1}"><img data-depth="0.5"
                                            src="{{ asset('app/assets/images/introduction/IntroductionOne/bg1.png') }}"
                                            alt="background"></div>
                                </div>
                                <div class="background__item">
                                    <div class="wrapper" ref="{bg2}"><img data-depth="0.3" data-invert-x=""
                                            data-invert-y=""
                                            src="{{ asset('app/assets/images/introduction/IntroductionOne/bg2.png') }}"
                                            alt="background"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="introduction-one-content">
                            <h5>{{ trans('aboutus.about_glowmira') }}<span> Glowmira</span></h5>
                            <div class="section-title " style="margin-bottom: 1.875em">
                                 @if (app()->getLocale() == 'en')
                                    <h2>{{ $ab->intro_one_title_en }}</h2>
                                @else
                                    <h2>{{ $ab->intro_one_title_ar }}</h2>
                                @endif<img
                                    src="{{asset('app/assets/images/introduction/IntroductionOne/content-deco.png')}}" alt="Decoration">
                            </div>
 @if (app()->getLocale() == 'en')
                                <p>{!! $ab->intro_one_desc_en !!}</p>
                            @else
                                <p>{!! $ab->intro_one_desc_ar !!}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
   


     

    </div>



@endsection
