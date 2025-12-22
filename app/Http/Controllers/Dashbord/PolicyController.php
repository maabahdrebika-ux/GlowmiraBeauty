<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PolicyController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $policie = Policy::first();
        $policies=Policy::all();
        return view('dashbord.policy.index', compact('policies','policie'
        ));
    }

    public function create()
    {
        return view('dashbord.policy.create', compact('policyCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
        ]);

        Policy::create($request->all());

        Alert::success('تم إضافة السياسة بنجاح');
        return redirect()->route('policy.index');
    }

    public function edit($id)
    {
        $policy = Policy::findOrFail($id);
        return view('dashbord.policy.edit', compact('policy'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
        ]);

        $policy = Policy::findOrFail($id);
        $policy->update($request->all());

        Alert::success('تم تعديل السياسة بنجاح');
        return redirect()->route('policy.index');
    }

    public function destroy($id)
    {
        $policy = Policy::findOrFail($id);
        $policy->delete();

        Alert::success('تم حذف السياسة بنجاح');
        return redirect()->route('policy.index');
    }
}
