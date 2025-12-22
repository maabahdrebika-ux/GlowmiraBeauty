@extends('app')

@section('content')
    <section id="about-3" class="wide-60 about-section division" style="background-color: #f9f9f9; padding: 60px 0;">
        <div class="container">
            <div class="row d-flex align-items-center">

                <!-- ABOUT IMAGE -->
                <div class="col-md-5 col-lg-6">
                    <div class="about-3-img text-center mb-40">
                        <img class="img-fluid rounded" style="box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);" src="{{ asset('bk.jpg') }}" alt="about-image">
                    </div>
                </div>

                <!-- ABOUT TEXT -->
                <div class="col-md-7 col-lg-6">
                    <div class="about-3-txt mb-40">

                        <!-- Title -->
                        <h2 class="h2-sm" style="color: #333; font-weight: bold; text-transform: uppercase;">
                            @lang('about.title')
                        </h2>

                        <!-- Divider -->
                        <div style="width: 50px; height: 4px; background-color: #ff6347; margin: 20px 0;"></div>

                        <!-- Text -->
                        <p style="color: #555; font-size: 18px; line-height: 1.8;">
                          
                       

                        @if ($about)
                                @if (\Session::get('language') == 'ar')
                                    {{ $about->dec }}
                                @elseif(\Session::get('language') == 'en')
                                    {{ $about->decen }}
                                @else
                                    {{ $about->dec }}
                                @endif
                            @else
                            <span>@lang('about.no_description')</span>
                            @endif
                        </p>
                        <!-- Call to Action -->
                        <div style="margin-top: 20px;">
                            <a href="{{route('about')}}" class="btn btn-primary" style="background-color: black; border: none; padding: 10px 20px; font-size: 16px;">
                                @lang('about.learn_more')   
                            </a>

                        </div>

                    </div>
                </div> <!-- END ABOUT TEXT -->

            </div> <!-- End row -->
        </div> <!-- End container -->
    </section>
@endsection
