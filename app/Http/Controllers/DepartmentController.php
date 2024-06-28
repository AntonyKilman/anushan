<?php

namespace App\Http\Controllers;

use App\Models\DepartmentHR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    // show all Department
    public function index()
    {
        $department = DB::table('departments')
                    ->select('departments.*', 'properities.name as pro_name')
                    ->leftJoin('properities', 'properities.id', 'departments.property_id')
                    ->get();

        $property = DB::table('properities')
                    ->select('*')
                    ->where('status', 'Active')
                    ->get();
        
        return view('hr.department')
                    ->with('department', $department)
                    ->with('property', $property);
    }

    // create & update
    public function store(Request $request)
    {
        $id = $request->id;  // id

        if ($id == 0) {  // create
            
            $request-> validate([
                'name' => 'required|unique:departments,name',
            ]);

            $department = new DepartmentHR();
            $department->name = $request->name;
            $department->property_id = $request->property_id;
            $department->description = $request->description;
            $department->status = 'Active';
            $department->save();

            $department->code = 'Dep-0' . $department->id;
            $department->save(); 

            $actvity = 'New Department Create - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect()->route('department.index')
                    ->with('success', 'Department successfully Created!!');

        } else {  // update
            
            $request-> validate([
                'name' => 'required|unique:departments,name,'.$id,
            ]);

            $department = DepartmentHR::find($id);
            $department->name = $request->name;
            $department->property_id = $request->property_id;
            $department->description = $request->description;
            $department->save();

            $actvity = 'Department Update - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect()->route('department.index')
                    ->with('success', 'Department successfully Update!!');

        }
        
    }

    // change status
    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        if ($status == "Active" || $status == "active") {
            $status = "Deactive";
        } else {
            $status = "Active";
        }
        
        $department = DepartmentHR::find($id);
        $department->status = $status;
        $department->save();

        $actvity = 'Deparment Status Change - '. $department->name;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

        return 'Done';
        
    }
}
