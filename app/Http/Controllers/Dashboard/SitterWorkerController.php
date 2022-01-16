<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SitterWorkerRequest;
use App\Models\BabySitter;
use App\Models\User;
use Illuminate\Http\Request;

class SitterWorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->ajax()) {
            $sitter_workers = BabySitter::paginate(100);
            return view('dashboard.sitter_worker.index',compact('sitter_workers'));
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $centers = User::where('user_type','childcenter')->get()->pluck('name','id');
        return view('dashboard.sitter_worker.create',compact('centers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SitterWorkerRequest $request)
    {
        BabySitter::create($request->validated());
        return redirect(route('dashboard.sitter_worker.index'))->withTrue(trans('dashboard.messages.success_add'));
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
    public function edit($id)
    {
        $sitter_worker = BabySitter::findOrFail($id);
        $centers = User::where('user_type','childcenter')->get()->pluck('name','id');
        return view('dashboard.sitter_worker.edit',compact('sitter_worker','centers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SitterWorkerRequest $request,$id)
    {
        $babySitter = BabySitter::findOrFail($id);
        $babySitter->update($request->validated());
        return redirect(route('dashboard.sitter_worker.index'))->withTrue(trans('dashboard.messages.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $baby_sitter = BabySitter::findOrFail($id);
        if ($baby_sitter->delete()) {
          return response()->json(['value' => 1]);
        }
    }
}
