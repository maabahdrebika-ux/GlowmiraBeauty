<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
   

    /**
     * Change language
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function changeLanguage(Request $request)
    {
        $validated = $request->validate([
            'language' => 'required',
        ]);

        \Session::put('language', $request->language);

        return redirect()->back();
    }


}
