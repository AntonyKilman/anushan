<?php

namespace App\Http\Controllers;

use App\Models\AccessPoint;
use Illuminate\Http\Request;
use App\Models\AccessModel;
use Auth;

class AccessPointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $access_model = AccessModel::find($id);
        $access_point = AccessPoint::where('access_model_id', $id)->get();
        return view('Auth.AccessPoint')->with('access_model', $access_model)->with('access_point', $access_point);
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
        $data = new AccessPoint();
        $data->display_name = $request->input('name');
        $data->value = $request->input('value'); 
        $data->description = $request->input('description'); 
        
        $data->access_model_id = $request->input('access_model_id');
        $data->save();

        $a = app('App\Http\Controllers\ActivityLogController')->index( "Create Access Point");

        if ($a == true) {
            return redirect()->route('access_point.index', ['id' => $request->input('access_model_id')])->with('msg', 'Successfully created!!!');

        } else {
            return "Error";
        }

      }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccessPoint  $accessPoint
     * @return \Illuminate\Http\Response
     */
    public function show(AccessPoint $accessPoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccessPoint  $accessPoint
     * @return \Illuminate\Http\Response
     */
    public function edit(AccessPoint $accessPoint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccessPoint  $accessPoint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $a=false;
        $data = AccessPoint::find($request->input('id'));
        $data->display_name = $request->input('name');
        $data->value = $request->input('value');
        $data->description = $request->input('description'); 
        $data->save();

        $a = app('App\Http\Controllers\ActivityLogController')->index( "Update Access Point");

        if ($a == true) {
            return redirect()->route('access_point.index', ['id' => $request->input('access_model_id')])->with('msg', 'Successfully created!!!');

        } else {
            return "Error";
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccessPoint  $accessPoint
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $a=false;
        $data = AccessPoint::find($request->input('delete_id'));
        $data->delete();
        $a = app('App\Http\Controllers\ActivityLogController')->index( "Remove Access Point");
        if ($a == true) {
            return redirect()->route('access_point.index', ['id' => $request->input('access_model_id')])->with('msg', 'Successfully Removed!!!');
        } else {
            return "Error";
        }

    }
}
