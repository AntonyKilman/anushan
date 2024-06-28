<?php

namespace App\Http\Controllers;

use App\Models\EmployeeType;
use Illuminate\Http\Request;

class EmployeeTypeController extends Controller
{
    // show
    public function index()
    {
        
        $employee_type = EmployeeType::get();

        return view('hr.employeeType')
                ->with('employee_type', $employee_type);

    }

    // create & update
    public function store(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $request-> validate([
                'type' => 'required|unique:employee_types,type',
            ]);
    
            $employee_type = new EmployeeType();
            $employee_type->type = $request->type;
            $employee_type->description = $request->description;
            $employee_type->save();

            $actvity = 'New Employee Type Create - '. $request->type;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
    
            return redirect()->route('employeetype.index')
                        ->with('success', 'employee type successfully Create!!');

        } else {

            $request-> validate([
                'type' => 'required|unique:employee_types,type,'.$id,
            ]);
    
            $employee_type = EmployeeType::find($id);
            $employee_type->type = $request->type;
            $employee_type->description = $request->description;
            $employee_type->save();

            $actvity = 'Employee Type Update - '. $request->type;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
            return redirect()->route('employeetype.index')
                        ->with('success', 'employee type successfully Updated!!');

        }

    }

    
    public function destroy(EmployeeType $employeeType)
    {
        //
    }
}
