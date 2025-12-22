@php
    $locale = session('language', app()->getLocale() ?: 'ar');
    $isRtl = in_array($locale, ['ar', 'he', 'fa', 'ur']);
    $languages = [
        'ar' => ['name' => 'العربية', 'flag' => '/libya.png'],
        'en' => ['name' => 'English', 'flag' => '/united-kingdom.png'],
    ];
    $adminDir = $isRtl ? 'Admin/vertical-rtl' : 'Admin/vertical';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}" class="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>GlowmiraBeauty -@yield('title')</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="ThemeDesign" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('logo_dark.PNG') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- morris css -->
    <link rel="stylesheet" href="{{ asset('Admin/plugins/morris/morris.css') }}">

    <!-- Chart.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.css">

    <link href="{{ asset($adminDir.'/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($adminDir.'/assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($adminDir.'/assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- DataTables -->
    <link href="{{ asset('Admin/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('Admin/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('Admin/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    @if ($isRtl)
        <link href="{{ asset($adminDir.'/assets/css/style.css') }}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endif

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset($adminDir.'/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset($adminDir.'/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($adminDir.'/assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset($adminDir.'/assets/js/detect.js') }}"></script>
    <script src="{{ asset($adminDir.'/assets/js/fastclick.js') }}"></script>
    <script src="{{ asset($adminDir.'/assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset($adminDir.'/assets/js/jquery.blockUI.js') }}"></script>
    <script src="{{ asset($adminDir.'/assets/js/waves.js') }}"></script>
    <script src="{{ asset($adminDir.'/assets/js/jquery.nicescroll.js') }}"></script>
    <script src="{{ asset($adminDir.'/assets/js/jquery.scrollTo.min.js') }}"></script>

    <!--Morris Chart-->
    <script src="{{ asset('Admin/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('Admin/plugins/raphael/raphael.min.js') }}"></script>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('Admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('Admin/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('Admin/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('Admin/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('Admin/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('Admin/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('Admin/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('Admin/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('Admin/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('Admin/plugins/datatables/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('Admin/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('Admin/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('Admin/vertical/assets/pages/datatables.init.js') }}"></script>

          <script src="{{ asset('Admin/plugins/sweet-alert2/sweetalert2.all.js') }}"></script>


    <style>
        /* Font family */
        @if ($isRtl)
            body, table, .table, .dataTable { font-family: 'Cairo', sans-serif !important; }
        @else
            body, table, .table, .dataTable { font-family: 'El Messiri', serif !important; }
        @endif

        /* RTL adjustments */
        @if ($isRtl)
            .float-right { float: left !important; }
            .float-left { float: right !important; }
            .text-right { text-align: left !important; }
            .text-left { text-align: right !important; }
            .mr-3 { margin-right: 0; margin-left: 0.75rem; }
            .dataTables_length { text-align: left !important; }
        @else
            .dataTables_length { text-align: right !important; }
        @endif

        /* Language switcher */
        .language-switcher .dropdown-menu {
            min-width: 10rem;
            text-align: {{ $isRtl ? 'right' : 'left' }};
            {{ $isRtl ? 'left:0; right:auto;' : 'right:0; left:auto;' }}
        }
        .language-switcher img {
            width: 20px;
            height: 15px;
        }

        /* Review Management Navigation */
        .sidebar-menu .has_sub > a .fa-star {
            color: #ffd700;
            margin-right: 8px;
        }
        .sidebar-menu .has_sub > a {
            position: relative;
        }
        .sidebar-menu .has_sub > a:hover .fa-star {
            color: #fff;
            transform: scale(1.1);
            transition: all 0.3s ease;
        }
        .sidebar-menu .has_sub ul li a {
            padding: 8px 20px;
            display: block;
            color: #8c8c8c;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu .has_sub ul li a:hover {
            color: #fff;
            background-color: #2d3748;
            border-left-color: #ffd700;
        }
        .sidebar-menu .has_sub ul li.active a {
            color: #fff;
            background-color: #667eea;
            border-left-color: #ffd700;
        }
        .sidebar-menu .has_sub.active > a {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }
        .sidebar-menu .has_sub.active > a .fa-star {
            color: #ffd700;
        }
    </style>

    <script>
        function fetchNotifications() {
            $.ajax({
                url: "{{ route('notifications.fetch') }}",
                method: "GET",
                success: function(data) {
                    const badge = $('.ico-item .badge');
                    if (data.unreadCount > 0) {
                        badge.text(data.unreadCount).show();
                    } else {
                        badge.hide();
                    }

                    let notificationsList = '';
                    if (data.notifications && data.notifications.length > 0) {
                        data.notifications.forEach(notification => {
                            notificationsList += `
                                <li>
                                    <a style="width:100%" class="dropdown-item d-flex align-items-start ${notification.is_read ? '' : 'font-weight-bold'}"
                                       href="${notification.readUrl}">
                                         <div class="mr-3"><i class="fa fa-bell text-primary"></i></div>
                                         <div style="flex:1;">
                                             <div class="d-flex justify-content-between align-items-center">
                                                 <span>${notification.message}</span>
                                             </div>
                                             <small class="text-muted d-block">${notification.timeAgo}</small>
                                         </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                            `;
                        });
                    } else {
                        notificationsList = `<li class="dropdown-item text-muted text-center">no notification yet</li>`;
                    }

                    $('.sub-ico-item .notifications-body').html(notificationsList);
                },
                error: function() {
                    console.error('Failed to fetch notifications.');
                }
            });
        }
        setInterval(fetchNotifications, 10000);
        $(document).ready(fetchNotifications);
    </script>
</head>

@if($isRtl)
    <body class="fixed-left rtl" style="font-family: Cairo !important;">
@else   
<body class="fixed-left">
@endif
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
    <div id="wrapper">

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                <i class="mdi mdi-close"></i>
            </button>

            <div class="left-side-logo d-block d-lg-none">
                <div class="text-center">
                    <a href="{{ route('home') }}" class="logo"><img src="{{ asset($adminDir.'/assets/images/logo_dark.png') }}" height="100" alt="logo"></a>
                </div>
            </div>

            <div class="sidebar-inner slimscrollleft">
                <div id="sidebar-menu">
                    <ul>
                        <li class="{{ Request::is('home*') ? 'active' : '' }}">
                            <a href="{{ route('home') }}"><i class="dripicons-home"></i> <span>{{ trans('app.dashboard') }}</span></a>
                        </li>
                        <li class="{{ Request::is('users*') ? 'active' : '' }}">
                            <a href="{{ route('users') }}"><i class="dripicons-user-group"></i> <span>{{ trans('app.users') }}</span></a>
                        </li>
                        <li class="{{ Request::is('roles*') ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}"><i class="dripicons-lock"></i> <span>{{ trans('roles.roles_permissions') }}</span></a>
                        </li>
                        <li class="{{ Request::is('addresses*') ? 'active' : '' }}">
                            <a href="{{ route('addresses') }}"><i class="dripicons-location"></i> <span>{{ trans('app.address') }}</span></a>
                        </li>
                        <li class="{{ Request::is('customers*') ? 'active' : '' }}">
                            <a href="{{ route('customers.index') }}"><i class="dripicons-user"></i> <span>{{ trans('customers.customers') }}</span></a>
                        </li>
                        <li class="{{ Request::is('categories*') ? 'active' : '' }}">
                            <a href="{{ route('categories.index') }}"><i class="dripicons-list"></i> <span>{{ trans('app.categories') }}</span></a>
                        </li>
                     
                        <li class="{{ Request::is('products*') ? 'active' : '' }}">
                            <a href="{{ route('products') }}"><i class="fab fa-microsoft"></i> <span>{{ trans('app.products') }}</span></a>
                        </li>
                        <li class="{{ Request::is('discounts*') ? 'active' : '' }}">
                            <a href="{{ route('discounts') }}"><i class="fa fa-percent"></i> <span>{{ trans('app.discounts') }}</span></a>
                        </li>
                        <li class="{{ Request::is('stock*') ? 'active' : '' }}">
                            <a href="{{ route('stock') }}"><i class=" dripicons-box "></i> <span>{{ trans('app.stock_management') }}</span></a>
                        </li>
                        <li class="{{ Request::is('suppliers*') ? 'active' : '' }}">
                            <a href="{{ route('suppliers') }}"><i class="fa fa-truck"></i> <span>{{ trans('app.suppliers') }}</span></a>
                        </li>
                        <li class="{{ Request::is('receipts*') ? 'active' : '' }}">
                            <a href="{{ route('receipts') }}"><i class="dripicons-document"></i> <span>{{ trans('app.receipt_management') }}</span></a>
                        </li>
                        <li class="has_sub {{ Request::is('pending/order') || Request::is('cancel/order') ? 'active' : '' }}">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-briefcase"></i> <span>{{ trans('app.order_management') }}</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('all/oreder') }}">{{ trans('app.all_orders') }}</a></li>
                                <li><a href="{{ route('pending/oreder') }}">{{ trans('app.pending') }}</a></li>
                                <li><a href="{{ route('underprocess/oreder') }}">{{ trans('app.under_process') }}</a></li>
                                <li><a href="{{ route('complete/oreder') }}">{{ trans('app.complete') }}</a></li>
                                <li><a href="{{ route('cancel/oreder') }}">{{ trans('app.cancel') }}</a></li>
                            </ul>
                        </li>
                        <li class="{{ Request::is('Invoice*') ? 'active' : '' }}">
                            <a href="{{ route('Invoice') }}"><i class="dripicons-tag"></i> <span>{{ trans('app.sales_management') }}</span></a>
                        </li>
                        <li class="{{ Request::is('returns*') ? 'active' : '' }}">
                            <a href="{{ route('returns') }}"><i class="dripicons-view-list-large"></i> <span>{{ trans('app.returns_management') }}</span></a>
                        </li>
                        <li class="has_sub {{ Request::is('report/sales') || Request::is('report/return') ? 'active' : '' }}">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-folder-open"></i> <span>{{ trans('app.reports_management') }}</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('report/sales') }}">{{ trans('app.sales') }}</a></li>
                                <li><a href="{{ route('report/return') }}">{{ trans('app.returns') }}</a></li>
                            </ul>
                        </li>
                        <li class="has_sub {{ Request::is('slider*') || Request::is('salesbanners') || Request::is('aboutus') || Request::is('sitesetting') || Request::is('contactus') ? 'active' : '' }}">
                            <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-gear"></i> <span>{{ trans('app.site_settings') }}</span> <span class="menu-arrow float-right"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="{{ route('slider') }}">{{ trans('app.slider') }}</a></li>
                                <li><a href="{{ route('aboutus') }}">{{ trans('aboutus.aboutus') }}</a></li>
                                <li><a href="{{ route('contactus') }}">{{ trans('app.contact_us') }}</a></li>
                            </ul>
                        </li>
                           <li class="{{ Request::is('blogs*') ? 'active' : '' }}">
                            <a href="{{ route('blogs.index') }}"><i class="dripicons-blog"></i> <span>{{ trans('blog.title') }}</span></a>
                        </li>

                        <li class="{{ Request::is('reviews*') ? 'active' : '' }}">
                            <a href="{{ route('reviews.index') }}"><i class="fa fa-star"></i>  <span>{{ trans('reviews.management_title') }}</span></a>
                        </li>
                    
                        <li class="{{ Request::is('policy*') ? 'active' : '' }}">
                            <a href="{{ route('policy.index') }}"><i class="dripicons-information"></i> <span>{{ trans('app.site_policies') }}</span></a>
                        </li>
                        <li class="{{ Request::is('inbox*') ? 'active' : '' }}">
                            <a href="{{ route('inbox') }}"><i class="dripicons-mail"></i> <span>{{ trans('app.inbox') }}</span></a>
                        </li>
                        <li class="logout-item" style="border-top: 1px solid #ddd; margin-top: 10px;">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="dripicons-exit"></i> {{ trans('app.logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

    
        <!-- Start right Content here -->

        <div class="content-page">
            <!-- Start content -->
            <div class="content">

                <!-- Top Bar Start -->
                <div class="topbar">

                    <div class="topbar-left	d-none d-lg-block">
                        <div class="text-center">
                            <a href="{{ route('home') }}" class="logo"><img src="{{ asset('logo_dark.PNG') }}" height="100" alt="logo"></a>
                        </div>
                    </div>

                    <nav class="navbar-custom">

                         <!-- Search input -->
                       

                        <ul class="list-inline float-right mb-0">
                          

                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                    <i class="mdi mdi-bell-outline noti-icon"></i>
                                    <span class="badge badge-danger badge-pill noti-icon-badge">{{ \App\Models\Notification::where('is_read', false)->count() ?: '0' }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg dropdown-menu-animated">
                                    <!-- item-->
                                    <div class="dropdown-item noti-title">
                                        <h5>{{ trans('app.notification') }} ({{ \App\Models\Notification::where('is_read', false)->count() ?: '0' }})</h5>
                                    </div>

                                    <div class="slimscroll-noti">
                                        @php
                                            $latestNotifications = \App\Models\Notification::latest()->take(5)->get();
                                        @endphp
                                        @forelse($latestNotifications as $notification)
                                            <!-- item-->
                                            <a href="{{ route('notifications.read', $notification->id) }}" class="dropdown-item notify-item {{ $notification->is_read ? '' : 'active' }}">
                                                <div class="notify-icon bg-success"><i class="mdi mdi-cart-outline"></i></div>
                                                <p class="notify-details"><b>{{ $notification->message }}</b><span class="text-muted">{{ $notification->created_at }}</span></p>
                                            </a>
                                        @empty
                                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                                <div class="notify-icon bg-info"><i class="mdi mdi-filter-outline"></i></div>
                                                <p class="notify-details"><b>no notification yet</b></p>
                                            </a>
                                        @endforelse
                                    </div>

                                    <!-- All-->
                                    <a href="{{ route('notifications.index') }}" class="dropdown-item notify-all">
                                        {{ trans('app.view_all') }}
                                    </a>

                                </div>
                            </li>

                            <li class="list-inline-item dropdown notification-list nav-user">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                    <img src="{{ asset('admin.png') }}" alt="user" class="rounded-circle">
                                    <span class="d-none d-md-inline-block ml-1">{{ Auth::user()->username }} <i class="mdi mdi-chevron-down"></i> </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                                    <a class="dropdown-item" href="{{ route('users/profile', encrypt(Auth::user()->id)) }}"><i class="dripicons-user text-muted"></i> {{ trans('app.profile') }}</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="dripicons-exit text-muted"></i> {{ trans('app.logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                            {{-- Language Switcher --}}
                            
                        </ul>

                        <ul class="list-inline menu-left mb-0">
                            <li class="list-inline-item">
                                <button type="button" class="button-menu-mobile open-left waves-effect">
                                    <i class="mdi mdi-menu"></i>
                                </button>
                            </li>
                          
                            <li class="list-inline-item">
                                @if($locale == 'ar')
                                    <a style="color: white !important;" href="{{ route('changeLanguage', ['language' => 'en']) }}" class="" >
                                        <img src="{{ $languages['en']['flag'] }}" alt="{{ $languages['en']['name'] }}" style="margin-right:5px; width: 20px; height: 15px;">
                                        {{ $languages['en']['name'] }}
                                    </a>
                                @else
                                    <a style="color: white !important;" href="{{ route('changeLanguage', ['language' => 'ar']) }}" class="" >
                                        <img src="{{ $languages['ar']['flag'] }}" alt="{{ $languages['ar']['name'] }}" style="margin-right:5px; width: 20px; height: 15px;">
                                        {{ $languages['ar']['name'] }}
                                    </a>
                                @endif
                            </li>


                        </ul>


                    </nav>

                </div>
                <!-- Top Bar End -->

<div class="page-content-wrapper">

                  

                        @yield('content')
            @include('sweetalert::alert')


                </div> <!-- Page content Wrapper -->

            </div> <!-- content -->

            <footer class="footer">
                © {{ date('Y') }} {{ trans('app.copyright') }} <span class="d-none d-md-inline-block"></span>
            </footer>

        </div>
        <!-- End Right content here -->

    </div>
    <!-- END wrapper -->

    <!-- dashboard js -->
    <script src="{{ asset($adminDir.'/assets/pages/dashboard.int.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset($adminDir.'/assets/js/app.js') }}"></script>

    <!-- jQuery  -->
  
</body>

</html>
