@php
    $locale = session('language', app()->getLocale() ?: 'ar');
    $isRtl = in_array($locale, ['ar', 'he', 'fa', 'ur']);
    $adminDir = $isRtl ? 'Admin/vertical-rtl' : 'Admin/vertical';
    $languages = [
        'ar' => ['name' => 'العربية', 'flag' => '/libya.png'],
        'en' => ['name' => 'English', 'flag' => '/united-kingdom.png'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>@yield('title')</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="ThemeDesign" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('logo_dark.PNG') }}">

    <link href="{{ asset($adminDir.'/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($adminDir.'/assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($adminDir.'/assets/css/style.css') }}" rel="stylesheet" type="text/css">

    {{-- RTL CSS for Arabic --}}
    @if(app()->getLocale() === 'ar')
    <link href="{{ asset($adminDir.'/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Cairo', sans-serif !important;
        }
    </style>
    @endif
</head>

<body class="fixed-left">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
        </div>
    </div>

    <!-- Begin page -->
    <div class="home-btn d-none d-sm-block">
        <a href="{{ url('/') }}" class="text-dark"><i class="mdi mdi-home h1"></i></a>
    </div>

    <div class="account-pages">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div>
                        <div>
                            <a href="{{ url('/') }}" class="logo logo-admin"><img  src="{{ asset('logoo1.png') }}"  height="28" alt="logo"></a>
                        </div>
                        <h5 class="font-14 text-muted mb-4">{{ trans('login.responsive_dashboard') }}</h5>
                        <p class="text-muted mb-4">{{ trans('login.description') }}</p>

                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    @yield('content')
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>

    <!-- jQuery  -->
    <script src="{{ asset('Admin/vertical/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('Admin/vertical/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('Admin/vertical/assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('Admin/vertical/assets/js/detect.js') }}"></script>
    <script src="{{ asset('Admin/vertical/assets/js/fastclick.js') }}"></script>
    <script src="{{ asset('Admin/vertical/assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('Admin/vertical/assets/js/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('Admin/vertical/assets/js/waves.js') }}"></script>
    <script src="{{ asset('Admin/vertical/assets/js/jquery.nicescroll.js') }}"></script>
    <script src="{{ asset('Admin/vertical/assets/js/jquery.scrollTo.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('Admin/vertical/assets/js/app.js') }}"></script>
</body>
</html>
