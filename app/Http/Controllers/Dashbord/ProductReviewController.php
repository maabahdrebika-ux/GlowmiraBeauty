<?php

namespace App\Http\Controllers\Dashbord;

use App\Models\Review;
use App\Models\products;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ProductReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the product reviews.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashbord.reviews.index');
    }

    public function reviews()
    {
        $reviews = Review::with(['customer', 'product'])->orderBy('created_at', 'DESC');

        return datatables()->of($reviews)
        ->addColumn('customer_name', function ($review) {
            return $review->customer ? $review->customer->name : 'N/A';
        })
        ->addColumn('product_name', function ($review) {
            return $review->product ? $review->product->name : 'N/A';
        })
        ->addColumn('rating', function ($review) {
            $stars = '';
            for ($i = 1; $i <= 5; $i++) {
                $stars .= $i <= $review->rating ? '★' : '☆';
            }
            return $stars;
        })
        ->addColumn('status', function ($review) {
            return $review->is_approved ? '<span class="badge badge-success">مفعلة</span>' : '<span class="badge badge-warning">معطلة</span>';
        })
        ->addColumn('verified', function ($review) {
            return $review->is_verified_purchase ? '<span class="badge badge-info">مؤكدة</span>' : '<span class="badge badge-secondary">غير مؤكدة</span>';
        })
   

        ->addColumn('delete', function ($review) {
            $review_id = encrypt($review->id);

            return ' <form action="' . route('reviews.destroy', $review_id) . '" method="POST">
        <input type="hidden" name="_method" value="DELETE">'
                . csrf_field() .
                '<button type="submit" style="background: none;border: none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-label="Trash">
  <g fill="none" stroke="#C5979A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M3 6h18"/>
    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
    <path d="M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14"/>
    <path d="M10 11v6M14 11v6"/>
  </g>
</svg></button></form>';

        })

        ->addColumn('change_status', function ($review) {
            $review_id = encrypt($review->id);
            return '<form action="' . route('reviews.changeStatus', $review_id) . '" method="POST" style="display: inline;">
              
                <button type="submit" style="background: none;border:none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-label="Refresh icon">
                      <g fill="none" stroke="#C5979A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-2.64-6.36"/>
                        <polyline points="21 3 21 8 16 8"/>
                      </g>
                    </svg>
                </button>
            </form>';
        })

        ->rawColumns(['edit','delete','change_status', 'status', 'verified'])

            ->make(true);
    }

    /**
     * Show the form for editing the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    
    /**
     * Update the specified review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
   

    /**
     * Remove the specified review from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $id = decrypt($id);
        $review = Review::find($id);

        if (!$review) {
            Alert::warning('التقييم غير موجود');
            return redirect()->back();
        }

        $review->delete();
        Alert::success('تم حذف التقييم بنجاح');
        return redirect()->back();
    }

    public function changeReviewStatus(Request $request, $id)
    {
        // Decrypt the review ID
        $review_id = decrypt($id);
        $review = Review::find($review_id);

        if (!$review) {
            Alert::warning('التقييم غير موجود');
            return redirect('reviews');
        }

        try {
            // Use a database transaction for safety
            DB::transaction(function () use ($review) {
                // Toggle the active status
                $review->is_approved = $review->is_approved == 1 ? 0 : 1;
                $review->save();
            });

            // Log the activity and show a success alert
            Alert::success('تم تغيير حالة التقييم بنجاح');

            return redirect()->back();
        } catch (\Exception $e) {
            // Handle exceptions gracefully
            Alert::warning($e->getMessage());

            return redirect()->back();
        }
    }
}