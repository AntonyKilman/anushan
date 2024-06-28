<?php

namespace App\Http\Controllers;

use App\Models\DepartmentTransfer;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DepartmentTransferController extends Controller
{
    
    // show 
    public function index()
    {
        $department_tran = DB::table('department_transfers')
                        ->select('department_transfers.*', 'employees.f_name as emp_name', 'from_departments.name as from_department_name', 'to_departments.name as to_department_name')
                        ->leftJoin('employees', 'employees.id', 'department_transfers.emp_id')
                        ->leftJoin('departments as from_departments', 'from_departments.id', 'department_transfers.from_department_id')
                        ->leftJoin('departments as to_departments', 'to_departments.id', 'department_transfers.to_department_id')
                        ->orderBy('department_transfers.id', 'desc')
                        ->get();

        $department = DB::table('departments')
                    ->select('*')
                    ->where('status', 'Active')
                    ->get();

        return view('hr.departmentTranfer', compact('department_tran', 'department'));

    }

    // create & update
    public function store(Request $request)
    {

        $rules = array(
            'emp_id' => 'required',
        );

        $customMessages = [
            'emp_id.required' => 'Select One Employee',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {  // check back end validator

            return redirect()->back()->withErrors($validator)->withInput($request->all());
        
        } else {

            $id = $request->id;  //id

            if ($id == 0) {  // create
                
                $department_tran = new DepartmentTransfer();
                $department_tran->emp_id = $request->emp_id;
                $department_tran->from_department_id = $request->from_department_id;
                $department_tran->to_department_id = $request->to_department_id;
                $department_tran->date_time = $request->date_time;
                $department_tran->description = $request->description;
                $department_tran->save();

                $employee = Employee::find($request->emp_id);
                $employee->department_id = $request->to_department_id;
                $employee->save();

                $actvity = 'New Department Transfer  Emp_id - '. $request->emp_id;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

                return redirect()->route('departmentTranfer.index')
                        ->with('success', 'Department Transfer successfully Created!!');

            } else {  // update
               
                $department_tran = DepartmentTransfer::find($id);
                $department_tran->emp_id = $request->emp_id;
                $department_tran->from_department_id = $request->from_department_id;
                $department_tran->to_department_id = $request->to_department_id;
                $department_tran->date_time = $request->date_time;
                $department_tran->description = $request->description;
                $department_tran->save();

                $employee = Employee::find($request->emp_id);
                $employee->department_id = $request->to_department_id;
                $employee->save();

                $actvity = 'Department Transfer Update  Emp_id - '. $request->emp_id;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

                return redirect()->route('departmentTranfer.index')
                        ->with('success', 'Department Transfer successfully Update!!');

            }
        }
        
    }

}
