<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SecondSubCategory\SecondSubCategoryRequest;
use App\Models\SecondCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SecondCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $second_sub_categories = SecondCategory::latest()->paginate(50);
        return view('dashboard.second_sub_category.index',compact('second_sub_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!request()->ajax()) {
            $sub_categories = SubCategory::where('has_sub_category',true)->get()->pluck('name','id');
            return view('dashboard.second_sub_category.create',compact('sub_categories'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SecondSubCategoryRequest $request)
    {

        SecondCategory::create($request->validated());
        return redirect(route('dashboard.second_sub_category.index'))->withTrue(trans('dashboard.messages.success_add'));
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
    public function edit(SecondCategory $second_sub_category)
    {
        $sub_categories = SubCategory::where('has_sub_category',true)->get()->pluck('name','id');
        return view('dashboard.second_sub_category.edit',compact('second_sub_category','sub_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SecondSubCategoryRequest $request, $second_sub_category_id)
    {
        $second_sub_category=SecondCategory::findOrFail($second_sub_category_id);
        $second_sub_category->update($request->validated());

        return redirect(route('dashboard.second_sub_category.index'))->withTrue(trans('dashboard.messages.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($second_sub_category_id)
    {
        $second_sub_category=SecondCategory::findOrFail($second_sub_category_id);
         if ($second_sub_category->delete()) {
            return response()->json(['value' => 1]);
        }
    }
}
