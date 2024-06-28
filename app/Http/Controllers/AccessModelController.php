<?php

namespace App\Http\Controllers;

use App\Models\AccessModel;
use Illuminate\Http\Request;
use Auth;

class AccessModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $access_model = AccessModel::all();
        return view('Auth.AccessModel')->with('model_list', $access_model);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $a = false; //activity status
        $data = new AccessModel();
        $data->name = $request->input('name');
        $data->save();

        $a = app('App\Http\Controllers\ActivityLogController')->index( "Create Access Model");

        if ($a == true) {
            return redirect()->route('access_model.index')->with('msg', 'Successfully created!!!');
        } else {
            return "Error";
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccessModel  $accessModel
     * @return \Illuminate\Http\Response
     */
    public function show(AccessModel $accessModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccessModel  $accessModel
     * @return \Illuminate\Http\Response
     */
    public function edit(AccessModel $accessModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccessModel  $accessModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //update
        $a = false; //activity status
        $data = AccessModel::find($request->input('id'));
        $data->name = $request->input('name');
        $data->save();

        $a = app('App\Http\Controllers\ActivityLogController')->index( "Update Access Model");

        if ($a == true) {
            return redirect()->route('access_model.index')->with('msg', 'Successfully created!!!');
        } else {
            return "Error";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccessModel  $accessModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $a=false;
        $data = AccessModel::find($request->input('delete_id'));
        $data->delete();
        $a = app('App\Http\Controllers\ActivityLogController')->index( "Remove Access Model");

        if ($a == true) {
            return redirect()->route('access_model.index')->with('msg', 'Record has been removed!!!');
        } else {
            return "Error";
        }
    }
}
