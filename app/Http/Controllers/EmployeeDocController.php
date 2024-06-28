<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeDocController extends Controller
{
    // show
    public function index()
    {

        $employee_doc = DB::table('employee_docs')
                        ->select('employee_docs.*', 'employees.f_name as employee_name', 'employees.l_name as emp_last_name', 'employees.emp_code')
                        ->leftJoin('employees', 'employees.id', 'employee_docs.employee_id')
                        ->orderBy('employee_docs.id', 'DESC')
                        ->get();

        return view('hr.employeeDoc')
                    ->with('employee_doc', $employee_doc);

    }

    // create & update
    public function store(Request $request)
    {
        
        $name = $request->name;  // Documents type

        if ($name == 'Photo') {  // photo
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,webp',
            ]);
        } else {  // pdf
            $request->validate([
                'file' => 'required|mimes:pdf',
            ]);
        }

        $id = $request->id; //id
        $doc = $request->file('file');  // file
        $emp_id = $request->emp_id;  // emp id
        
        if ($id == 0) {  // create

            $employee_doc = new EmployeeDoc();
            $employee_doc->employee_id = $emp_id;
            $employee_doc->name = $name;

            if ($doc) {
                $doc_name = "emp_doc/" . $name . '_'. $emp_id . '_' . time() . '.' . $doc->extension();
                $doc->move("emp_doc", $doc_name);

                $employee_doc->file = $doc_name;
            }

            $employee_doc->save();

            $actvity = 'Employee Document Add, emp_id - '. $emp_id;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);


            return redirect()->route('employeedoc.index')
                            ->with('success', 'Employee Doc successfully Store!!');

        } else {  // update
            
            $employee_doc = EmployeeDoc::find($id);
            $employee_doc->employee_id = $emp_id;
            $employee_doc->name = $name;

            if ($doc) {

                $old_doc = $employee_doc->file;
                if ($old_doc) {  // delete old doc
                    unlink($old_doc);
                }

                $doc_name = "emp_doc/" . $name . '_'. $emp_id . '_' . time() . '.' . $doc->extension();
                $doc->move("emp_doc", $doc_name);

                $employee_doc->file = $doc_name;
            }

            $employee_doc->save();

            $actvity = 'Employee Document Update, emp_id - '. $emp_id;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect()->route('employeedoc.index')
                            ->with('success', 'Employee Doc successfully Update!!');
        }
        
    }

}
