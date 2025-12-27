<!DOCTYPE html>
@php 
  $lang  = session('language', app()->getLocale() ?? 'ar');
  $dir   = $lang === 'ar' ? 'rtl' : 'ltr';
  $isRTL = $dir === 'rtl';
  
  // Get cart data from session
  $cart = session('cart', []);
  $cartCount = array_reduce($cart, function($total, $item) {
      return $total + ($item['quantity'] ?? 0);
  }, 0);
  $cartTotal = array_reduce($cart, function($total, $item) {
      $price = $item['discounted_price'] ?? $item['price'] ?? 0;
      return $total + ($price * ($item['quantity'] ?? 0));
  }, 0);
@endphp
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GlowmiraBeauty -@yield('title')</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Spartan:wght@300;400;500;700;900&amp;display=swap" />
    
    @if (app()->getLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet" />
    @endif
    
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo_dark.PNG') }}" />
    <!--build:css assets/css/styles.min.css-->
    <link rel="stylesheet" href="{{ asset('app/assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('app/assets/css/slick.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('app/assets/css/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('app/assets/css/jquery.modal.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('app/assets/css/bootstrap-drawer.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('app/assets/css/style.css') }}" />
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!--endbuild-->
    <script src="/app/assets/js/jquery-3.5.1.min.js"></script>

    @if (app()->getLocale() == 'ar')
    <style>
   body {
                font-family: 'Cairo', sans-serif !important;
            }
    </style>
         
        @endif
   <style>
       
        
        .login-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            margin: 0 8px;
        }
        
        .login-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            text-decoration: none;
        }
        
        .login-btn i {
            margin-right: 5px;
        }
        
        /* User Header Section Styles */
        .user-header-section {
            position: relative;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 0 1rem;
        }
        
        .user-info-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }
        
        .user-info-link:hover {
            background: rgba(255, 255, 255, 0.1);
            text-decoration: none;
            color: white;
        }
        
        .user-mini-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar-mini {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .user-details-mini {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.2;
        }
        
        .user-email {
            font-size: 0.7rem;
            opacity: 0.8;
            line-height: 1.2;
        }
        
        .user-actions-dropdown {
            position: relative;
        }
        
        .logout-btn-header {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .logout-btn-header:hover {
            background: rgba(220, 53, 69, 0.8);
            border-color: rgba(220, 53, 69, 0.8);
            transform: scale(1.1);
        }
        
        /* Dashboard User Header Styles */
        .dashboard-user-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 0;
        }
        
        .user-info-card {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 2rem;
            background: linear-gradient(135deg, #aa6969 0%, #aa6969 100%);
            color: white;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        
        .user-details h3 {
            margin: 0 0 0.25rem 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .user-details p {
            margin: 0;
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .breadcrumb-container {
            padding: 1rem 2rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #aa6969;
        }
        
        .breadcrumb a {
            color:#aa6969;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            color: white;
        }
        
        /* User Dropdown Styles - Legacy (kept for compatibility) */
        .user-dropdown .dropdown-toggle {
            border: none !important;
            background: transparent !important;
            color: white !important;
        }
        
        .user-dropdown .dropdown-menu {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            margin-top: 0.5rem;
        }
        
        .user-dropdown .dropdown-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #aa6969;
            transition: all 0.3s ease;
        }
        
        .user-dropdown .dropdown-item:hover {
            background: #f8f9fa;
            color: #aa6969;
        }
        
        .user-dropdown .dropdown-item i {
            width: 16px;
            text-align: center;
        }
        
        .user-dropdown .dropdown-divider {
            margin: 0.5rem 0;
        }
        
        /* Cart Badge Styles */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            line-height: 1;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .cart-badge:empty {
            display: none;
        }
        
        .cart-badge[style*="display: none"] {
            display: none !important;
        }
        
        .menu-icon {
            position: relative;
            display: inline-block;
        }

        .menu-icon img {
            width: 24px;
            height: 24px;
        }

        /* Mobile Menu Toggle Button */
        .menu-mobile {
            display: none;
            margin-right: 15px;
        }

        .menu-mobile .menu-icon {
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .menu-mobile .menu-icon:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Show mobile menu button only on smaller screens */
        @media (max-width: 991px) {
            .menu-mobile {
                display: block;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .user-header-section {
                margin: 0 0.5rem;
                gap: 0.5rem;
            }
            
            .user-name {
                display: none;
            }
            
            .user-email {
                display: none;
            }
            
            .user-avatar-mini {
                width: 30px;
                height: 30px;
                font-size: 1rem;
            }
            
            .logout-btn-header {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }
            
            .dashboard-user-header {
                margin-bottom: 0;
            }
            
            .user-info-card {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
                padding: 1.5rem;
            }
            
            .breadcrumb-container {
                padding: 1rem;
            }
            
            .user-dropdown .dropdown-menu {
                position: static !important;
                float: none !important;
                width: auto !important;
                margin-top: 0 !important;
                background-color: transparent !important;
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
@if (app()->getLocale() == 'ar')

    <body dir="rtl">
    @else

        <body>
@endif
<div class="header -three">
    <div class="menu -style-3">
        <div class="container">
            <div class="menu__wrapper"><a href="{{ route('/') }}"><img
                        src="{{ asset('logo-white.png') }}" alt="Logo"  style="width: 100px;" /></a>
                <div class="navigator -white">
                    <ul>
                        <li><a href="{{ route('/') }}">{{ __('app.home') }}</a></li>
                        <li><a href="{{ route('about') }}">{{ __('menu.about') }}</a></li>
                        <li><a href="{{ route('all_products') }}">{{ __('app.products') }}</a></li>
                        <li><a href="{{ route('all_products') }}">{{ __('app.discount') }}</a></li>
                        <li><a href="{{ route('blog') }}">{{ __('menu.blog') }}</a></li>
                        <li><a href="{{ route('contacts') }}">{{ __('app.contact_us') }}</a></li>
                    </ul>
                </div>
                <div class="menu-functions -white">
                    <!-- Mobile Menu Toggle Button -->
                    <div class="menu-mobile">
                        <a class="menu-icon -mobile" href="#" data-toggle="drawer" data-target="#mobile-menu-drawer">
                            <i class="fas fa-bars" style="color: white; font-size: 24px;"></i>
                        </a>
                    </div>
                    @auth('customer')
                        <!-- User is logged in -->
                        <div class="user-header-section">
                            <a href="{{ route('customer.dashboard') }}" class="user-info-link">
                                <div class="user-mini-info">
                                    <div class="user-avatar-mini">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="user-details-mini">
                                        <span class="user-name">{{ Auth::guard('customer')->user()->name }}</span>
                                        <small class="user-email">{{ Auth::guard('customer')->user()->email }}</small>
                                    </div>
                                </div>
                            </a>
                            <div class="menu-cart">
                        <a class="menu-icon -cart" href="#" data-toggle="drawer" data-target="#cart-drawer">
                            <img src="{{ asset('app/assets/images/header/cart-icon-white.png') }}" alt="Cart icon" />
                            <span class="cart-badge cart__quantity">{{ $cartCount }}</span>
                        </a>
                        <h5 style="font-size: smaller;">{{ __('app.cart') }}:<span>{{ number_format($cartTotal, 2) }} {{ __('app.lyd') }}</span></h5>
                    </div>
                            <div class="user-actions-dropdown">
                                <form action="{{ route('customer.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="logout-btn-header" title="{{ __('app.logout') }}">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- User is not logged in -->
                        <a href="{{route('customer.login')}}" style="color: white;" class="btn btn-sm btn-outline-light mx-1">
                            <i class="fas fa-user"></i> {{ __('app.login') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    <div class="menu -style-4">
        <div class="container">
            <div class="menu__wrapper">
                <div class="navigator -off-submenu">
                    <ul>
                        @foreach($categories as $category)
                            <li><a href="{{ route('product/category', encrypt($category->id)) }}">
                                @if(app()->getLocale() == 'ar')
                                    {{ $category->name }}
                                @else
                                    {{ $category->englishname }}
                                @endif
                            </a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="menu-functions ">
                    <div class="language-switcher">
                        @if (app()->getLocale() == 'ar')
                            <a href="{{ route('changeLanguage', ['language' => 'en']) }}" class="btn btn-sm btn-outline-light mx-1">
                                <img src="{{ asset('united-kingdom.png') }}" alt="English"
                                    style="width: 20px; height: 15px; margin-right: 5px;">
                                EN
                            </a>
                        @else
                            <a href="{{ route('changeLanguage', ['language' => 'ar']) }}" class="btn btn-sm btn-outline-light mx-1">
                                <img src="{{ asset('libya.png') }}" alt="العربية"
                                    style="width: 20px; height: 15px; margin-right: 5px;">
                                AR
                            </a>
                        @endif
                    </div>
                    
                   
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div id="content">
    @yield('content')


    <div class="footer-one">
        <div class="container">
            <div class="footer-one__header">
                <div class="footer-one__header__logo"><a href="{{route('/')}}"><img
                            src="{{ asset('logo_dark.PNG') }}" alt="Logo" style="width: 100px;" /></a></div>
                
                <div class="footer-one__header__social">
                    <div class="social-icons -border">
                        <ul>
                            <li><a href="https://www.facebook.com/" style="'color: undefined'"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li><a href="https://twitter.com" style="'color: undefined'"><i
                                        class="fab fa-twitter"></i></a></li>
                            <li><a href="https://instagram.com/" style="'color: undefined'"><i
                                        class="fab fa-instagram"> </i></a></li>
                            <li><a href="https://www.youtube.com/" style="'color: undefined'"><i
                                        class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        
        </div>
        <div class="footer-one__footer">
            <div class="container">
                <div class="footer-one__footer__wrapper">
                    <p>© Copyright 2020 Beauty</p>
                    <ul>
                        <li><a href="contact.html">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="drawer drawer-right slide" id="cart-drawer" tabindex="-1" role="dialog"
        aria-labelledby="drawer-demo-title" aria-hidden="true">
        <div class="drawer-content drawer-content-scrollable" role="document">
            <div class="drawer-body">
                <div class="cart-sidebar">
                        <div class="cart-items__wrapper">
                            <h2>{{ __('app.shopping_cart') }}</h2>
                            @if(count($cart) > 0)
                                @foreach($cart as $item)
                                <div class="cart-item">
                                    <div class="cart-item__image">
                                        <img src="{{ asset('app/assets/images/product/' . ($item['product_image'] ?? '1.png')) }}"
                                                alt="Product image" />
                                    </div>
                                    <div class="cart-item__info">
                                        <a href="{{ route('product/info', $item['product_id']) }}">
                                            @if(app()->getLocale() == 'ar')
                                                {{ $item['product_name'] ?? 'اسم المنتج' }}
                                            @else
                                                {{ $item['product_namee'] ?? $item['product_name'] ?? 'Product Name' }}
                                            @endif
                                        </a>
                                        <h5>{{ number_format($item['discounted_price'] ?? $item['price'] ?? 0, 2) }} {{ __('products.lyd') }}</h5>
                                        <p>Quantity:<span> {{ $item['quantity'] ?? 1 }}</span></p>
                                    </div>
                                    <a class="cart-item__remove" href="#" onclick="removeFromCart({{ $item['product_id'] }})">
                                        <i class="fal fa-times"></i>
                                    </a>
                                </div>
                                @endforeach
                                <div class="cart-items__total">
                                    <div class="cart-items__total__price">
                                        <h5>{{ __('app.total') }}</h5><span>{{ number_format($cartTotal, 2) }} {{ __('app.lyd') }}</span>
                                    </div>
                                    <div class="cart-items__total__buttons">
                                        <a class="btn -dark" href="{{ route('cart.index') }}">{{ __('app.view_cart') }}</a>
                                        <a class="btn -red" href="{{ route('checkout') }}">{{ __('app.checkout') }}</a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p>{{ __('app.cart_empty') }}</p>
                                    <a href="{{ route('all_products') }}" class="btn btn-primary">{{ __('app.shop_now') ?? 'Shop Now' }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="drawer drawer-right slide" id="mobile-menu-drawer" tabindex="-1" role="dialog"
        aria-labelledby="drawer-demo-title" aria-hidden="true">
        <div class="drawer-content drawer-content-scrollable" role="document">
            <div class="drawer-body">
                <div class="cart-sidebar">
                    <div class="cart-items__wrapper">
                        <div class="navigation-sidebar">
                            <div class="navigator-mobile">
                                <ul>
                                    <li><a href="{{ route('/') }}">{{ __('app.home') }}</a></li>
                                    <li><a href="{{ route('about') }}">{{ __('menu.about') }}</a></li>
                                    <li><a href="{{ route('all_products') }}">{{ __('app.products') }}</a></li>
                                    <li><a href="{{ route('all_products') }}">{{ __('app.discount') }}</a></li>
                                    <li><a href="{{ route('blog') }}">{{ __('menu.blog') }}</a></li>
                                    <li><a href="{{ route('contacts') }}">{{ __('app.contact_us') }}</a></li>
                                </ul>
                            </div>
                            <div class="navigation-sidebar__footer">
                                
                                  <div class="language-switcher">
                        @if (app()->getLocale() == 'ar')
                            <a href="#" onclick="changeLanguage('en')" class="btn btn-sm btn-outline-light mx-1">
                                <img src="{{ asset('united-kingdom.png') }}" alt="English"
                                    style="width: 20px; height: 15px; margin-right: 5px;">
                                EN
                            </a>
                        @else
                            <a href="#" onclick="changeLanguage('ar')" class="btn btn-sm btn-outline-light mx-1">
                                <img src="{{ asset('libya.png') }}" alt="العربية"
                                    style="width: 20px; height: 15px; margin-right: 5px;">
                                AR
                            </a>
                        @endif
                    </div>
                            </div>
                            <div class="social-icons ">
                                <ul>
                                    <li><a href="https://www.facebook.com/" style="'color: undefined'"><i
                                                class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="https://twitter.com" style="'color: undefined'"><i
                                                class="fab fa-twitter"></i></a></li>
                                    <li><a href="https://instagram.com/" style="'color: undefined'"><i
                                                class="fab fa-instagram"> </i></a></li>
                                    <li><a href="https://www.youtube.com/" style="'color: undefined'"><i
                                                class="fab fa-youtube"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--build:js assets/js/main.min.js-->
</div>

<script src="{{ asset('app/assets/js/parallax.min.js') }}"></script>
<script src="{{ asset('app/assets/js/slick.min.js') }}"></script>
<script src="{{ asset('app/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('app/assets/js/jquery.modal.min.js') }}"></script>
<script src="{{ asset('app/assets/js/bootstrap-drawer.min.js') }}"></script>
<script src="{{ asset('app/assets/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('app/assets/js/main.min.js') }}"></script>
<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!--endbuild-->

{{-- SweetAlert Alert Component --}}
@include('sweetalert::alert')

<script>
// Universal cart count update function


// Remove item from cart function


// Auto-refresh cart count every 30 seconds
setInterval(updateCartCount, 30000);

// Update cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});

// Listen for cart count updates from other pages/scripts
window.addEventListener('cartCountUpdated', function(event) {
    console.log('Cart count updated globally:', event.detail.count);
});

// Update cart count when page becomes visible (user switches back to tab)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        updateCartCount();
    }
});

// Update cart count when window gains focus
window.addEventListener('focus', function() {
    updateCartCount();
});
</script>
</body>

</html>
