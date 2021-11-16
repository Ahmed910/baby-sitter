<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\FirstSubCategory\FirstSubCategoryRequest;
use App\Models\FirstSubCategory;
use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class FirstSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $first_sub_categories = SubCategory::latest()->paginate(50);
        return view('dashboard.first_sub_category.index',compact('first_sub_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!request()->ajax()) {
            $main_categories = MainCategory::where('has_sub_category',true)->get()->pluck('name','id');
            return view('dashboard.first_sub_category.create',compact('main_categories'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FirstSubCategoryRequest $request)
    {

        SubCategory::create($request->validated());
        return redirect(route('dashboard.first_sub_category.index'))->withTrue(trans('dashboard.messages.success_add'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SubCategory $first_sub_category)
    {
        $main_categories = MainCategory::where('has_sub_category',true)->get()->pluck('name','id');
        return view('dashboard.first_sub_category.edit',compact('first_sub_category','main_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FirstSubCategoryRequest $request, $sub_category_id)
    {
        $sub_category=SubCategory::findOrFail($sub_category_id);
        $sub_category->update($request->validated());

        return redirect(route('dashboard.first_sub_category.index'))->withTrue(trans('dashboard.messages.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($sub_category_id)
    {
        $sub_category=SubCategory::findOrFail($sub_category_id);
         if ($sub_category->delete()) {
            return response()->json(['value' => 1]);
        }
    }
}
