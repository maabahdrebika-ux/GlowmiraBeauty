<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Contactus;
use Illuminate\Http\Request;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
class ContactusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contactus=Contactus::first();
        return view('dashbord.contactus.index')->with('contactus',$contactus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function show(Contactus $contactus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $contactus=Contactus::first();
        return view('dashbord.contactus.edit')->with('contactus',$contactus);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $messages = [
            'email.required' => trans('contactus.email_R'),
            'phone.required' => trans('contactus.phone_R'),
            'adress.required' => trans('contactus.adress_R'),
            'adressen.required' => trans('contactus.adressen_R'),
            'lan.required' => trans('contactus.lan'),
            'long.required' => trans('contactus.long'),
            
        ];
        $this->validate($request, [
            'email' => ['required'],
            'phone' => ['required'],
            'adress' => ['required'],
            'adressen' => ['required'],
            'lan' => ['required'],
            'long' => ['required'],
            'facebook_url' => ['nullable', 'url'],
            'instagram_url' => ['nullable', 'url'],
            'twitter_url' => ['nullable', 'url'],
            'linkedin_url' => ['nullable', 'url'],
            'youtube_url' => ['nullable', 'url'],
            'pinterest_url' => ['nullable', 'url'],
            'ourworksa' => ['nullable'],
            'ourworkse' => ['nullable'],
        ], $messages);
        try {
            DB::transaction(function () use ($request) {
                $cu = Contactus::first();
                $cu->email = $request->email;
                $cu->phonenumber = $request->phone;
                $cu->adress = $request->adress;
                $cu->adressen = $request->adressen;
                $cu->lan = $request->lan;
                $cu->long = $request->long;
                $cu->whatsapp = $request->whatsapp;
                $cu->facebook_url = $request->facebook_url;
                $cu->twitter_url = $request->twitter_url;
                $cu->linkedin_url = $request->linkedin_url;
                $cu->youtube_url = $request->youtube_url;
                $cu->pinterest_url = $request->pinterest_url;

                $cu->ourworksa = $request->ourworksa;
                $cu->ourworkse = $request->ourworkse;

                $cu->save();
            });

            Alert::success(trans('contactus.successcityedit'));

            return redirect()->route('contactus');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contactus  $contactus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contactus $contactus)
    {
        //
    }
}
