<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Aboutus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Image;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class AboutusController extends Controller
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
        $ab = Aboutus::first();

        return view('dashbord.aboutus.index')->with('ab', $ab);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {




        return view('dashbord.aboutus.create');
    }


    public function aboutus()
    {

        $aboutus = Aboutus::all();
        return datatables()->of($aboutus)

            ->addColumn('edit', function ($aboutus) {
                $aboutus_id = encrypt($aboutus->id);

                return '<a style="color: #f97424;" href="' . route('aboutus/edit') . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#aa6969">
  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
</svg>
</a>';
            })
         

            ->addColumn('dec', function ($aboutus) {
                $dec = strip_tags($aboutus->dec);
    
                return $dec;
            })
            ->addColumn('decen', function ($aboutus) {
                $decen = strip_tags($aboutus->decen);

                return $decen;
            })
            ->addColumn('created_at', function ($aboutus) {
                return $aboutus->created_at->format('Y-m-d');
            })
            ->addColumn('view_details', function ($aboutus) {
                return '<a style="color: blue;" href="' . route('aboutus/show', $aboutus->id) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="blue">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                </svg></a>';
            })
            ->addColumn('delete', function ($aboutus) {
                return '<a style="color: red;" href="#" onclick="confirmDelete(' . $aboutus->id . ')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="red">
                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg></a>';
            })
            // ->addColumn('imge', function ($Slider) {
            //     $imageUrl = asset('images/aboutus/' . $Slider->imge);
            //     return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $Slider->imge . '" alt="' . $Slider->imge . '" style="max-width: 100px !important;"></a>';
            // })

            ->rawColumns(['edit','decen','dec','view_details','delete'])


            ->make(true);
    }



    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'intro_one_title_ar.required' => 'يرجى إدخال عنوان المقدمة 1 بالعربية.',
            'intro_one_title_en.required' => 'Please enter Introduction One Title in English.',
            'intro_one_desc_ar.required' => 'يرجى إدخال وصف المقدمة 1 بالعربية.',
            'intro_one_desc_en.required' => 'Please enter Introduction One Description in English.',

            'intro_one_bg1.required' => 'Please upload a background image.',
            'intro_one_bg1.dimensions' => 'The first background image must be 390x445 pixels.',
            'intro_one_bg1.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',

            'intro_one_bg2.required' => 'Please upload a background image.',
            'intro_one_bg2.dimensions' => 'The second background image must be 370x440 pixels.',
            'intro_one_bg2.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
        ];
        $this->validate($request, [
            'intro_one_title_ar' => ['required'],
            'intro_one_title_en' => ['required'],
            'intro_one_desc_ar' => ['required'],
            'intro_one_desc_en' => ['required'],
            'intro_one_bg1' => 'required|image|mimes:jpeg,png,jpg,gif',
            'intro_one_bg2' => 'required|image|mimes:jpeg,png,jpg,gif',
        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $aboutus = new aboutus();

                $path = public_path('images/aboutus/');
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
                }

                if($request->hasFile('intro_one_bg1')){
                    $image = Image::make($request->file('intro_one_bg1'));
                    $image->resize(390, 445);
                    $filename = 'intro_one_bg1_' . time() . '.webp';
                    $image->save(public_path('images/aboutus/' . $filename));
                    $aboutus->intro_one_bg1 = 'images/aboutus/' . $filename;
                }

                if($request->hasFile('intro_one_bg2')){
                    $image = Image::make($request->file('intro_one_bg2'));
                    $image->resize(370, 440);
                    $filename = 'intro_one_bg2_' . time() . '.webp';
                    $image->save(public_path('images/aboutus/' . $filename));
                    $aboutus->intro_one_bg2 = 'images/aboutus/' . $filename;
                }

                $aboutus->fill($request->only([
                    'intro_one_title_ar', 'intro_one_title_en', 'intro_one_desc_ar', 'intro_one_desc_en',
                ]));
                
                $aboutus->save();
            });
            Alert::success(trans('aboutus.sadd'));

            return redirect()->route('aboutus');
        } catch (\Exception $e) {

            Alert::warning(trans('aboutus.fail'));

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $aboutus = Aboutus::findOrFail($id);
        return view('dashbord.aboutus.show')->with('aboutus', $aboutus);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {

        $ab = Aboutus::first();

        return view('dashbord.aboutus.edit')->with('ab', $ab);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $messages = [
            'intro_one_title_ar.required' => 'يرجى إدخال عنوان المقدمة 1 بالعربية.',
            'intro_one_title_en.required' => 'Please enter Introduction One Title in English.',
            'intro_one_desc_ar.required' => 'يرجى إدخال وصف المقدمة 1 بالعربية.', 
            'intro_one_desc_en.required' => 'Please enter Introduction One Description in English.',

            'intro_one_bg1.image' => 'The first background image must be an image file.',
            'intro_one_bg1.mimes' => 'Allowed formats for the first background image: jpeg, png, jpg, gif, webp.',

            'intro_one_bg2.image' => 'The second background image must be an image file.',
            'intro_one_bg2.mimes' => 'Allowed formats for the second background image: jpeg, png, jpg, gif, webp.',
        ];
        $this->validate($request, [
            'intro_one_title_ar' => ['required'],
            'intro_one_title_en' => ['required'],
            'intro_one_desc_ar' => ['required'],
            'intro_one_desc_en' => ['required'],
            'intro_one_bg1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'intro_one_bg2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $aboutus = Aboutus::first();

                if($request->hasFile('intro_one_bg1')){
                    $imagePath = public_path($aboutus->intro_one_bg1);
                    if(File::exists($imagePath)){
                        File::delete($imagePath);
                    }
                    $image = Image::make($request->file('intro_one_bg1'));
                    $image->resize(390, 445);
                    $filename = 'intro_one_bg1_' . time() . '.webp';
                    $image->save(public_path('images/aboutus/' . $filename));
                    $aboutus->intro_one_bg1 = 'images/aboutus/' . $filename;
                }

                if($request->hasFile('intro_one_bg2')){
                    $imagePath = public_path($aboutus->intro_one_bg2);
                    if(File::exists($imagePath)){
                        File::delete($imagePath);
                    }
                    $image = Image::make($request->file('intro_one_bg2'));
                    $image->resize(370, 440);
                    $filename = 'intro_one_bg2_' . time() . '.webp';
                    $image->save(public_path('images/aboutus/' . $filename));
                    $aboutus->intro_one_bg2 = 'images/aboutus/' . $filename;
                }

                $aboutus->fill($request->only([
                    'intro_one_title_ar', 'intro_one_title_en', 'intro_one_desc_ar', 'intro_one_desc_en',
                ]));

                $aboutus->save();
            });
            Alert::success(trans('aboutus.saed'));

            return redirect()->route('aboutus');
        } catch (\Exception $e) {

            Alert::warning(trans('aboutus.faile'));

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $aboutus = Aboutus::findOrFail($id);

            // Delete associated images if they exist
            if ($aboutus->intro_one_bg1 && File::exists(public_path($aboutus->intro_one_bg1))) {
                File::delete(public_path($aboutus->intro_one_bg1));
            }
            if ($aboutus->intro_one_bg2 && File::exists(public_path($aboutus->intro_one_bg2))) {
                File::delete(public_path($aboutus->intro_one_bg2));
            }

            $aboutus->delete();

            Alert::success(trans('aboutus.delete_success'));
            return redirect()->route('aboutus');
        } catch (\Exception $e) {
            Alert::warning(trans('aboutus.delete_failed'));
            return redirect()->back();
        }
    }
}
