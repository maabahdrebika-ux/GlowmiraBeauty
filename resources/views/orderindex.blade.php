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
                    <a href="{{ route('ordersindex') }}">الطلبات</a>
                </h4>
            </div>
        </div>
     
        <div class="col-md-12">
            <div class="box-content">
                <div class="col-md-12">
                   
                    <div class="col-md-3 col-sm-12 mb-4">
                        <a href="{{ route('pending/oreder') }}" class="btn-3d">
                            <img class="icon" src="{{ asset('preparation.png') }}" alt="pending">
                            <span>قيد الانتظار</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-12 mb-4">
                        <a href="{{route('underprocess/oreder')}}" class="btn-3d">
                            <img class="icon" src="{{ asset('express-delivery.png') }}" alt="express-delivery">
                            <span>قيد التجهيز</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-12 mb-4">
                        <a href="{{route('complete/oreder')}}" class="btn-3d">
                            <img class="icon" src="{{ asset('banner.png') }}" alt="salesbanners">
                            <span>مكتملة</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-12 mb-4">
                        <a href="{{route('cancel/oreder')}}" class="btn-3d">
                            <img class="icon" src="{{ asset('cancel-order.png') }}" alt="cancel-order">
                            <span>ملغية</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
      
    </div>

   



   
    </div>

@endsection
