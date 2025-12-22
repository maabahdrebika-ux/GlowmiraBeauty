<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use RealRashid\SweetAlert\Facades\Alert;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        
        return view('dashbord.slider.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {




        return view('dashbord.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'imge' => 'required|image|mimes:jpeg,png,jpg,gif|dimensions:width=990,height=500',
           
        ], [
     
            'imge.required' => 'يجب اختيار صورة.',
            'imge.image' => 'الملف يجب أن يكون صورة.',
            'imge.mimes' => 'الصور المسموح بها هي JPEG، PNG، JPG، GIF.',
            'imge.dimensions' => 'يجب أن تكون أبعاد الصورة 990*500 بكسل.',
            'imge.max' => 'حجم الصورة يجب ألا يتجاوز 2MB.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $Slider = new Slider();
             
                if ($request->file('imge')) {
                    $fileObject = $request->file('imge');
                    $image = "slider" . time()  . ".jpg";
                    $fileObject->move('images/slider/', $image);
                    $Slider->imge = $image;
                }
                $Slider->save();
            });

            Alert::success('تم إضافة  slider بنجاح');
            return redirect()->route('slider'); // إعادة التوجيه إلى صفحة السلايدر
        } catch (\Exception $e) {
            Alert::warning('حدث خطأ أثناء إضافة slider: ' . $e->getMessage());
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        try {
            // Decrypt the product ID
            $slider_id = decrypt($id);

            // Find the product in the database
            $Slider = Slider::findOrFail($slider_id);

            // Check if the product has an image, and delete it from the server
            if ($Slider->imge && file_exists(public_path('images/slider/' . $Slider->imge))) {
                unlink(public_path('images/slider/' . $Slider->imge));
            }

            // Delete the product record
            $Slider->delete();

            // Success alert
            Alert::success("تم حذف Slider بنجاح");

            // Redirect to the products listing page
            return redirect()->route('slider');
        } catch (\Exception $e) {
            // Error handling
            Alert::warning("حدث خطأ أثناء حذف slider", $e->getMessage());
            return redirect()->route('slider');
        }
    }
    public function sliders()
    {

        $Slider = Slider::all();
        return datatables()->of($Slider)

        ->addColumn('delete', function ($product) {
            $product_id = encrypt($product->id);
            return '<a href="' . route('slider/delete', $product_id) . '" onclick="return confirm(\'هل أنت متأكد أنك تريد حذف هذا slider?\')">
                     
                                                   <img src="' . asset('delete.png  ') . '" alt="Edit" style="width:26px; height:26px;">

                    </a>';
        })
            ->addColumn('imge', function ($Slider) {
                $imageUrl = asset('images/slider/' . $Slider->imge);
                return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" alt="' . $Slider->imge . '" style="max-width: 100px !important;"></a>';
            })

          
            ->rawColumns(['delete','imge'])


            ->make(true);
    }

    /**
     * 
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

}
