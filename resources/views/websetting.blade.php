@extends('layouts.app')
@section('title', 'الرئيسية')

@section('content')
<style>
    /* تصميم زر 3D */
    .btn-3d {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: linear-gradient(145deg, #ffffff, #e6e6e6);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 5px 5px 15px #bebebe, -5px -5px 15px #ffffff;
        text-decoration: none;
        font-weight: bold;
        color: #333;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    /* تأثير النقر */
    .btn-3d:active {
        box-shadow: inset 5px 5px 10px #bebebe, inset -5px -5px 10px #ffffff;
        transform: translateY(4px);
    }

    /* أيقونات الأزرار */
    .btn-3d .icon {
        width: 80px;
        transition: transform 0.3s ease;
    }

    /* تأثير عند تمرير الماوس */
    .btn-3d:hover .icon {
        transform: scale(1.1);
    }

    /* تحسين النص */
    .btn-3d span {
        margin-top: 10px;
        font-size: 16px;
    }
</style>
    <!-- Main Section -->
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title">
                    <a href="{{ route('sitesetting') }}">اعدادات الموقع</a>
                </h4>
            </div>
        </div>
     
        <div class="col-md-12">
            <div class="box-content">
                <div class="col-md-12">
                   
                    <div class="col-md-3 col-sm-12 mb-4">
                        <a href="{{ route('slider') }}" class="btn-3d">
                            <img class="icon" src="{{ asset('slider.png') }}" alt="slider">
                            <span>Slider</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-12 mb-4">
                        <a href="{{route('aboutus')}}" class="btn-3d">
                            <img class="icon" src="{{ asset('info.png') }}" alt="aboutus">
                            <span>من نخن</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-12 mb-4">
                        <a href="{{route('salesbanners')}}" class="btn-3d">
                            <img class="icon" src="{{ asset('banner.png') }}" alt="salesbanners">
                            <span>اعلان التخفيض</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-12 mb-4">
                        <a href="{{route('contactus')}}" class="btn-3d">
                            <img class="icon" src="{{ asset('contactus.png') }}" alt="contactus">
                            <span>اتصل بنا</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
      
    </div>

   



   
    </div>

@endsection
