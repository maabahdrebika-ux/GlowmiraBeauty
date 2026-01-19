<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Coolor;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // added import

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q') ?? ''; // ensure q is not null
        $products = [];
        if ($q) {
            // Hybrid search: combine full-text search with keyword matching
            $products = DB::table('products as p')
                ->leftJoin('categories as c', 'p.categories_id', '=', 'c.id')
                ->select('p.id', 'p.image', 'p.name', 'p.namee', 'p.brandname_ar', 'p.brandname_en', 'p.barcode', 'p.price', 'p.categories_id', 'p.created_at', 'c.name as category_name', 'c.englishname as category_namee',
                         DB::raw('MATCH(p.name, p.namee, p.description_ar, p.description_en) AGAINST("' . $q . '" IN NATURAL LANGUAGE MODE) as relevance_score'),
                         DB::raw('CASE WHEN p.name LIKE "%' . $q . '%" THEN 10 WHEN p.namee LIKE "%' . $q . '%" THEN 10 WHEN p.brandname_ar LIKE "%' . $q . '%" THEN 8 WHEN p.brandname_en LIKE "%' . $q . '%" THEN 8 WHEN p.barcode LIKE "%' . $q . '%" THEN 5 ELSE 0 END as keyword_score'))
                ->where(function($query) use ($q) {
                    $query->whereRaw('MATCH(p.name, p.namee, p.description_ar, p.description_en) AGAINST(? IN NATURAL LANGUAGE MODE)', [$q])
                          ->orWhere('p.name', 'LIKE', '%' . $q . '%')
                          ->orWhere('p.namee', 'LIKE', '%' . $q . '%')
                          ->orWhere('p.brandname_ar', 'LIKE', '%' . $q . '%')
                          ->orWhere('p.brandname_en', 'LIKE', '%' . $q . '%')
                          ->orWhere('p.barcode', 'LIKE', '%' . $q . '%')
                          ->orWhere('c.name', 'LIKE', '%' . $q . '%')
                          ->orWhere('c.englishname', 'LIKE', '%' . $q . '%');
                })
                ->orderByRaw('(relevance_score + keyword_score) DESC')
                ->paginate(12)
                ->appends(['q' => $q]); // Append the query parameter for pagination
        }
        $discount = Discount::get();
        $sizes = Size::all(); // Fetch sizes
        $coolors = Coolor::all(); // Fetch coolors
        $colors = DB::table('stocks')
            ->join('coolors', 'stocks.coolors_id', '=', 'coolors.id')
            ->select('coolors.id', 'coolors.name')
            ->distinct()
            ->get();
        return view('front.search', compact('q', 'products'))
            ->with('discount', $discount)
            ->with('sizes', $sizes)
            ->with('coolors', $coolors)
            ->with('colors', $colors);
    }

}
