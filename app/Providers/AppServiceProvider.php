<?php

namespace App\Providers;

use App\Models\Aboutus;
use App\Models\Categories;
use App\Models\Contactus;
use App\Models\Discount;
use App\Models\Salesbanner;
use App\Models\Slider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap pagination styling
        Paginator::useBootstrap();

        // Set locale from session
        if (session()->has('language')) {
            app()->setLocale(session('language'));
        }

        // if (Schema::hasTable('contactuses')) {
        //     $Contactus = Contactus::first();

        //     view()->share('phonenumber', $Contactus->phonenumber);

        //     view()->share('facebook', $Contactus->facebook_url);

        //     // Pass social media links to views
        //     view()->share('facebook', $Contactus->facebook_url);
        //     view()->share('instagram', $Contactus->instagram_url);
        //     view()->share('youtube', $Contactus->youtube_url);
        //     view()->share('twitter', $Contactus->twitter_url);
        //     view()->share('pinterest', $Contactus->pinterest_url);
        //     view()->share('linkedin', $Contactus->linkedin_url);
        //     view()->share('whatsapp', $Contactus->whatsapp);


        // }

        // if (Schema::hasTable('sliders')) {
        //     $sliders = Slider::first();

        //     view()->share('sliders', $sliders);

        //     // Perform the necessary actions with $specializations
        // }
        if (Schema::hasTable('categories')) {
            $categories = Categories::where('active',1)->get();

            view()->share('categories', $categories);

            // Perform the necessary actions with $specializations
        }
     

        // if (Schema::hasTable('discounts')) {
        //     $discountsperodact = Discount::select('id', 'percentage', 'products_id', 'created_at')
        //         ->whereHas('products', function ($query) {
        //             $query->where('is_available', 1); // Ensure the product is active
        //         })
        //         ->latest()
        //         ->take(2) // Fetch only the last two discounts
        //         ->get();

        //     view()->share('discountsperodact', $discountsperodact);
        // }
    }
}
