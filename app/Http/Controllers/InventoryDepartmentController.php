<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryDepartment;

class InventoryDepartmentController extends Controller
{
    function departmentAdd(Request $req){

        $req-> validate([
            'dept_name' => 'required|unique:inventory_departments,dept_name',

        ]);

        $InventoryDepartment = new InventoryDepartment();
        $InventoryDepartment -> dept_name = $req -> dept_name;
        $InventoryDepartment -> dept_des = $req -> dept_des;
        
        try{
            $InventoryDepartment -> save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Department");
            } catch (\Throwable $th) {
            }
            return redirect('departmentShow')->with('success','Successfully Recorded');
        }
        catch (\Throwable $e) {
           return redirect()->back()->with('error','Something wrong try again');
        }

    }
    
    function departmentShow(){
        $datas = InventoryDepartment::all();
        return view('departmentShowAll', compact('datas'));
    }

    function departmentUpdate(Request $req){

        $req-> validate([

            'dept_name' => 'required|unique:inventory_departments,dept_name,'.$req->id,

        ]);

        $InventoryDepartment = InventoryDepartment::find($req->id);
        $InventoryDepartment -> dept_name = $req -> dept_name;
        $InventoryDepartment -> dept_des = $req -> dept_des;
        
        try{
            $InventoryDepartment -> save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Department");
            } catch (\Throwable $th) {
            }
            return redirect('departmentShow')->with('success','Successfully Updated');
        }
        catch (\Throwable $e) {
           return redirect()->back()->with('error','Something wrong try again');
        }

    }



}
