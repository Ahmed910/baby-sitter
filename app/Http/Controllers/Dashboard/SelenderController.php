<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Selender\SelenderRequest;
use App\Models\Selender;
use Illuminate\Http\Request;

class SelenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            $selenders = Selender::latest()->paginate(100);
            return view('dashboard.selender.index',compact('selenders'));
          }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!request()->ajax()) {
            return view('dashboard.selender.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SelenderRequest $request)
    {
        if (!request()->ajax()) {
            Selender::create($request->validated());
            return redirect(route('dashboard.selender.index'))->withTrue(trans('dashboard.messages.success_add'));
         }
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
    public function edit(Selender $selender)
    {
        if (!request()->ajax()) {
            return view('dashboard.selender.edit',compact('selender'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SelenderRequest $request, $selender_id)
    {
        $selender = Selender::findOrFail($selender_id);

        if (!request()->ajax()) {
            $selender->update($request->validated());
            return redirect(route('dashboard.selender.index'))->withTrue(trans('dashboard.messages.success_update'));
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Selender $selender)
    {
        if ($selender->delete()) {
            return response()->json(['value' => 1]);
          }
    }
}
