<?php

namespace App\Http\Controllers\Dashbord;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashbord.categories.index');
    }

    public function categories()
    {
        $categories = Categories::orderBy('created_at', 'DESC');
        return datatables()->of($categories)
        ->addColumn('edit', function ($category) {
            $category_id = encrypt($category->id);

            return '<a style="color: #f97424;" href="' . route('categories.edit', $category_id).'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#aa6969">
  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 1.41 0l1.83 1.83 3.75 3.75 1.83-1.83z"/>
</svg>
</a>';
        })

        ->addColumn('delete', function ($category) {
            $category_id = encrypt($category->id);

            return ' <form action="' . route('categories.destroy', $category_id) . '" method="POST">
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

        ->addColumn('status', function ($category) {
            return $category->active ? "مفعلة" : "معطلة";
        })

        ->addColumn('change_status', function ($category) {
            $category_id = encrypt($category->id);
            return '<form action="' . route('categories.changeStatus', $category_id) . '" method="POST" style="display: inline;">
                @csrf
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

        ->rawColumns(['edit','delete','change_status'])

            ->make(true);
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashbord.categories.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'englishname' => 'required|string|max:255|unique:categories',


        ], [
            'name.required' => trans('category.name_required'),
            'englishname.required' => trans('category.englishname_required'),
     
        ]);

        try {
            DB::transaction(function () use ($request) {
                $category = new Categories();
                $category->name = $request->name;
                $category->englishname = $request->englishname;
               
                $category->save();
            });

            Alert::success(trans('category.add_success'));
            return redirect()->route('categories.index');
        } catch (\Exception $e) {
            Alert::warning($e->getMessage());
            return redirect()->route('categories.create');
        }
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $category = Categories::find($id);
        return view('dashbord.categories.edit')->with('category', $category);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $messages = [
            'name.required' => trans('category.name_required'),
            'englishname.required' => trans('category.englishname_required'),
        ];

        $this->validate($request, [
            'name' => ['required', 'string', 'max:50'],
            'englishname' => ['required', 'string', 'max:50'],
        ], $messages);

        try {
            DB::transaction(function () use ($request, $id) {
                $category = Categories::find($id);
                $category->name = $request->name;
                $category->englishname = $request->englishname;
                $category->save();
            });

            Alert::success(trans('category.update_success'));
            return redirect()->route('categories.index');
        } catch (\Exception $e) {
            Alert::warning($e->getMessage());
            return redirect()->route('categories.index');
        }
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $id = decrypt($id);
        $category = Categories::find($id);
        $category->delete();
        Alert::success(trans('category.delete_success'));
        return redirect()->back();
    }


    public function changeCategoryStatus(Request $request, $id)
    {
        // Decrypt the category ID
        $category_id = decrypt($id);
        $category = Categories::find($category_id);

        if (!$category) {
            Alert::warning(trans('categories.notfound'));
            return redirect('categories');
        }

        try {
            // Use a database transaction for safety
            DB::transaction(function () use ($category) {
                // Toggle the active status
                $category->active = $category->active == 1 ? 0 : 1;
                $category->save();
            });

            // Log the activity and show a success alert
            Alert::success(trans('categories.changestatuesalert'));

            return redirect()->back();
        } catch (\Exception $e) {
            // Handle exceptions gracefully
            Alert::warning($e->getMessage());

            return redirect()->back();
        }
    }
}
