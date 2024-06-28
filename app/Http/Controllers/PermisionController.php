<?php

namespace App\Http\Controllers;

use App\Models\Permision;
use App\Models\AccessModel;
use App\Models\AccessPoint;
use Illuminate\Http\Request;
use App\Models\UserRole;
use Auth;

class PermisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //get user role permission array
        $permission = Permision::where('user_role_id', $id)->first();
        // get user role by id
        $user_role = UserRole::find($id);
        // return $user_role;
        //get Access models
        $access_model = AccessModel::all();
        // return $access_model ;
        //get all access point
        $access_point = AccessPoint::all();
        // return $access_point ;
        return view('Auth.Permission')
            ->with('access_model', $access_model)
            ->with('user_role', $user_role)
            ->with('permission', $permission)
            ->with('access_point', $access_point);
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
        $role = $request->input('user_role_id');
        $prives = $request->input('checkboxvar');
       
        //  return count($prives);
        if ($request->input('permission_id') == 0) {
            //insert
            $a = false;
            $data = new Permision();
            $data->user_role_id = $role;
            $data->permision = json_encode($prives);
            $data->save();
            $a = app('App\Http\Controllers\ActivityLogController')->index("Create Permission");
            if ($a == true) {
                return redirect()->route('permission.index', ['id' => $role])->with('msg', 'Successfully created!!!');
            } else {
                return "Error";
            }
        } else {
            $a = false;
            $data = Permision::find($request->input('permission_id'));
            $data->user_role_id = $role;
          
            try {
                $data->permision = json_encode($prives);
                $data->save();
            } catch (\Throwable $th) {
                return redirect()->with('msg', $th->getMessage());
            }

            $a = app('App\Http\Controllers\ActivityLogController')->index("Update Permission");
            if ($a == true) {
                return redirect()->route('permission.index', ['id' => $role])->with('msg', 'Successfully created!!!');
            } else {
                return "Error";
            }

            //update
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permision  $permision
     * @return \Illuminate\Http\Response
     */
    public function show(Permision $permision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permision  $permision
     * @return \Illuminate\Http\Response
     */
    public function edit(Permision $permision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permision  $permision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permision $permision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permision  $permision
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permision $permision)
    {
        //
    }
}
