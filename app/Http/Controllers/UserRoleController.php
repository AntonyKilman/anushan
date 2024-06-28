<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $super_admin_id = 1;
        $role_list = DB::table('user_roles')
            ->select('user_roles.*')
            ->where('id', '!=', $super_admin_id)
            ->get();
        return view('Auth.UserRole')->with('role_list', $role_list);
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
        $a=false;
        $data = new UserRole();
        $data->user_role = $request->input('name');
        $data->save();
        $a = app('App\Http\Controllers\ActivityLogController')->index("Create User Role");
        if ($a == true) {
            return redirect()->route('role.index')->with('msg', 'Successfully created!!!');
        } else {
            return "Error";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function show(UserRole $userRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRole $userRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $a=false;
        $data =UserRole::find($request->input('id'));
        $data->user_role = $request->input('name');
        $data->save();

        $a = app('App\Http\Controllers\ActivityLogController')->index(Auth::user()->emp_id, "Update User Role");
        if ($a == true) {
            return redirect()->route('role.index')->with('msg', 'Successfully Updated!!!');
        } else {
            return "Error";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $data = UserRole::find($request->input('delete_id'));
        $data->delete();
        

        $a = app('App\Http\Controllers\ActivityLogController')->index(Auth::user()->id, "Removed User Role".$data->user_role);
        if ($a == true) {
            return redirect()->route('role.index')->with('msg', 'Record has been removed!!!');
        } else {
            return "Error";
        }
        
    }
}
