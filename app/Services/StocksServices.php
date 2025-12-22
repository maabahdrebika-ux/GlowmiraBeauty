<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Stock;

class StocksServices
{
    public function addstock($quantty, $coolors_id, $sizes_id, $products_id, $expired_date = null)
    {

        $Stock = new Stock();
        $Stock->quantty = $quantty;
        $Stock->coolors_id = $coolors_id;
        $Stock->sizes_id = $sizes_id;
        $Stock->products_id = $products_id;
        $Stock->expired_date = $expired_date;


        $Stock->save();
        return $quantty;
    }


    public function updatestock($quantty, $coolors_id, $sizes_id, $products_id, $expired_date = null)
    {



        if (is_null($coolors_id)) {
            $Stock = Stock::where('products_id', $products_id)
                         ->where('sizes_id', $sizes_id)
                         ->first();
        } elseif (is_null($sizes_id)) {
            $Stock = Stock::where('products_id', $products_id)
                         ->where('coolors_id', $coolors_id)
                         ->first();
        } elseif (is_null($sizes_id) && is_null($coolors_id)) {
            $Stock = Stock::where('products_id', $products_id)
                         ->first();
        }
        elseif (is_null($coolors_id)) {
            $Stock = Stock::where('products_id', $products_id)
            ->where('sizes_id', $sizes_id)

                         ->first();
        }


        $Stock->quantty =$Stock->quantty+ $quantty;
        if ($expired_date) {
            $Stock->expired_date = $expired_date;
        }
        $Stock->save();
        return $quantty;
    }


    public function updatestockcute($quantty, $coolors_id, $sizes_id, $products_id, $expired_date = null)
    {


        if (is_null($coolors_id)) {
            $Stock = Stock::where('products_id', $products_id)
                         ->where('sizes_id', $sizes_id)
                         ->first();
        } elseif (is_null($sizes_id)) {
            $Stock = Stock::where('products_id', $products_id)
                         ->where('coolors_id', $coolors_id)
                         ->first();
        } elseif (is_null($sizes_id) && is_null($coolors_id)) {
            $Stock = Stock::where('products_id', $products_id)
                         ->first();
        }
        elseif (is_null($coolors_id)) {
            $Stock = Stock::where('products_id', $products_id)
            ->where('sizes_id', $sizes_id)

                         ->first();
        }
        $Stock->quantty =$Stock->quantty-$quantty;
        if ($expired_date) {
            $Stock->expired_date = $expired_date;
        }
        $Stock->save();
        return $quantty;
    }
  
}
