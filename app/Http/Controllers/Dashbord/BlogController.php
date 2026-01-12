<?php

namespace App\Http\Controllers\Dashbord;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Intervention\Image\ImageManager as Image;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the blogs.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashbord.blogs.index');
    }

    public function blogs()
    {
        $blogs = Blog::orderBy('created_at', 'DESC');
        return datatables()->of($blogs)
        ->addColumn('edit', function ($blog) {
            $blog_id = encrypt($blog->id);

            return '<a style="color: #f97424;" href="' . route('blogs.edit', $blog_id).'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#aa6969">
  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 1.41 0l1.83 1.83 3.75 3.75 1.83-1.83z"/>
</svg>
</a>';
        })

        ->addColumn('delete', function ($blog) {
            $blog_id = encrypt($blog->id);

            return ' <form action="' . route('blogs.destroy', $blog_id) . '" method="POST">
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

        ->addColumn('status', function ($blog) {
            return $blog->active ? trans('blog.active') : trans('blog.inactive');
        })

        ->addColumn('change_status', function ($blog) {
            $blog_id = encrypt($blog->id);
            $csrf_token = csrf_token();
            return '<form action="' . route('blogs.changeStatus', $blog_id) . '" method="POST" style="display: inline;">
                <input type="hidden" name="_token" value="' . $csrf_token . '">
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
     * Show the form for creating a new blog.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashbord.blogs.create');
    }

    /**
     * Store a newly created blog in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title_ar.required' => trans('blog.title_ar_required'),
            'title_en.required' => trans('blog.title_en_required'),
            'description_ar.required' => trans('blog.description_ar_required'),
            'description_en.required' => trans('blog.description_en_required'),
            'image.image' => trans('blog.image_invalid'),
            'image.mimes' => trans('blog.image_mimes'),
            'image.max' => trans('blog.image_max'),
        ]);

        try {
            DB::transaction(function () use ($request) {
                $blog = new Blog();
                $blog->title_ar = $request->title_ar;
                $blog->title_en = $request->title_en;
                $blog->description_ar = $request->description_ar;
                $blog->description_en = $request->description_en;

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $path = public_path('images/blogs/' . $filename);

                    // Resize image to 360x240
                    $manager = Image::gd();
                    $img = $manager->read($image->getRealPath());
                    $img->resize(360, 240, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($path);

                    $blog->image = $filename;
                }

                $blog->save();
            });

            Alert::success(trans('blog.add_success'));
            return redirect()->route('blogs.index');
        } catch (\Exception $e) {
            Alert::warning($e->getMessage());
            return redirect()->route('blogs.create');
        }
    }

    /**
     * Show the form for editing the specified blog.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $blog = Blog::find($id);
        return view('dashbord.blogs.edit')->with('blog', $blog);
    }

    /**
     * Update the specified blog in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $messages = [
            'title_ar.required' => trans('blog.title_ar_required'),
            'title_en.required' => trans('blog.title_en_required'),
            'description_ar.required' => trans('blog.description_ar_required'),
            'description_en.required' => trans('blog.description_en_required'),
            'image.image' => trans('blog.image_invalid'),
            'image.mimes' => trans('blog.image_mimes'),
            'image.max' => trans('blog.image_max'),
        ];

        $this->validate($request, [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        try {
            DB::transaction(function () use ($request, $id) {
                $blog = Blog::find($id);
                $blog->title_ar = $request->title_ar;
                $blog->title_en = $request->title_en;
                $blog->description_ar = $request->description_ar;
                $blog->description_en = $request->description_en;

                if ($request->hasFile('image')) {
                    // Delete old image if exists
                    if ($blog->image && file_exists(public_path('images/blogs/' . $blog->image))) {
                        unlink(public_path('images/blogs/' . $blog->image));
                    }

                    $image = $request->file('image');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $path = public_path('images/blogs/' . $filename);

                    // Resize image to 360x240
                    $manager = Image::gd();
                    $img = $manager->read($image->getRealPath());
                    $img->resize(360, 240, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($path);

                    $blog->image = $filename;
                }

                $blog->save();
            });

            Alert::success(trans('blog.update_success'));
            return redirect()->route('blogs.index');
        } catch (\Exception $e) {
            Alert::warning($e->getMessage());
            return redirect()->route('blogs.index');
        }
    }

    /**
     * Remove the specified blog from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $id = decrypt($id);
        $blog = Blog::find($id);

        // Delete image if exists
        if ($blog->image && file_exists(public_path('images/blogs/' . $blog->image))) {
            unlink(public_path('images/blogs/' . $blog->image));
        }

        $blog->delete();
        Alert::success(trans('blog.delete_success'));
        return redirect()->back();
    }


    public function changeBlogStatus(Request $request, $id)
    {
        // Decrypt the blog ID
        $blog_id = decrypt($id);
        $blog = Blog::find($blog_id);

        if (!$blog) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('blog.notfound')], 404);
            }
            Alert::warning(trans('blog.notfound'));
            return redirect('blogs');
        }

        try {
            // Use a database transaction for safety
            DB::transaction(function () use ($blog) {
                // Toggle the active status
                $blog->active = $blog->active == 1 ? 0 : 1;
                $blog->save();
            });

            // Log the activity and show a success alert
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => trans('blog.changestatuesalert')]);
            }
            Alert::success(trans('blog.changestatuesalert'));

            return redirect()->back();
        } catch (\Exception $e) {
            // Handle exceptions gracefully
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            Alert::warning($e->getMessage());

            return redirect()->back();
        }
    }
}
