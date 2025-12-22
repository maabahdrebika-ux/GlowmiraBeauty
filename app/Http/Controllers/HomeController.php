<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\exchangeitem;
use App\Models\products;
use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function re()
    {

        return view('reindex');

    }

    
    public function sitesetting()
    {

        return view('websetting');

    }

    public function ordersindex()
    {

        return view('orderindex');

    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

       

     

        return view('home')
       ;
    }
}
