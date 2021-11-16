<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MainCategory\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $main_categories = MainCategory::latest()->paginate(50);
        return view('dashboard.main_category.index',compact('main_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!request()->ajax()) {
            return view('dashboard.main_category.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules=[
            'has_sub_category'=>'required|boolean',
            'is_free'=>'required|boolean',

            'note'=>'nullable|string'
         ];
         foreach (config('translatable.locales') as $locale) {
             $rules[$locale.'.name'] = 'required|string|between:2,250';
         }

        $validator = Validator::make($request->all(), $rules);

        $validator->sometimes('price', 'required|numeric', function($input) use($request){
            return ($request->all()['is_free'] == false && $request->all()['has_sub_category'] == false);
        });


        if($request->all()['is_free'] == true && $request->all()['has_sub_category']==true){
            return redirect()->back()->withFalse(trans('dashboard.messages.states_must_be_has_not_sub_categories_if_the_service_is_free'));
        }

        MainCategory::create($validator->validated());
        return redirect(route('dashboard.main_category.index'))->withTrue(trans('dashboard.messages.success_add'));
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
    public function edit(MainCategory $main_category)
    {
        return view('dashboard.main_category.edit',compact('main_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MainCategory $main_category)
    {
        $rules=[
            'has_sub_category'=>'required|boolean',
            'is_free'=>'required|boolean',

            'note'=>'nullable|string'
         ];
         foreach (config('translatable.locales') as $locale) {
             $rules[$locale.'.name'] = 'required|string|between:2,250';
         }

        $validator = Validator::make($request->all(), $rules);

        $validator->sometimes('price', 'required|numeric', function($input) use($request){
            return ($request->all()['is_free'] == false && $request->all()['has_sub_category'] == false);
        });


        if($request->all()['is_free'] == true && $request->all()['has_sub_category']==true){
            return redirect()->back()->withFalse(trans('dashboard.messages.states_must_be_has_not_sub_categories_if_the_service_is_free'));
        }
        $main_category->update($validator->validated());
        return redirect(route('dashboard.main_category.index'))->withTrue(trans('dashboard.messages.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MainCategory $main_category)
    {
         if ($main_category->delete()) {
            return response()->json(['value' => 1]);
        }
    }
}
