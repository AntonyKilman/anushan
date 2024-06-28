<?php

namespace App\Http\Controllers;

use App\Models\AllowanceType;
use App\Models\EmployeeSalaryPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeSalaryPartController extends Controller
{
    
    // show
    public function index()
    {

        $emp_salary_part = DB::table('hr_employee_salary_parts as hesp')
                        ->selectRaw('hesp.emp_id, employees.emp_code, employees.f_name as emp_name, SUM(hesp.amount) as total_allowance, employees.l_name as emp_last_name')
                        ->leftJoin('employees', 'employees.id', 'hesp.emp_id')
                        ->groupBy('hesp.emp_id')
                        ->get();

        $allowance_type = AllowanceType::get();

        return view('hr.employeeSalaryPart', compact('emp_salary_part', 'allowance_type'));
    }

    // search employee 
    public function searchEmpForSalaryPart(Request $request)
    {
        $key_word = $request->get('query');  // key word

        $new_employees = DB::table('employees')
                        ->select('employees.id', 'employees.f_name', 'employees.emp_code', 'departments.name as department_name')
                        ->leftJoin('departments', 'departments.id', 'employees.department_id')
                        ->whereNotExists(function ($query){
                            $query->select(DB::raw(1))
                                ->from('hr_employee_salary_parts')
                                ->whereColumn('hr_employee_salary_parts.emp_id', 'employees.id');
                        })
                        ->where('employees.status', 'Active')
                        ->whereNotIn('employees.id', [1,2,3])  // super admin , admin
                        ->where(function($q) use($key_word){
                            $q->where('employees.f_name', 'like', '%' . $key_word . '%')
                                ->orWhere('employees.emp_code', 'like', '%' . $key_word . '%')
                                ->orWhere('departments.name', 'like', '%' . $key_word . '%');
                        })
                        ->limit(30)
                        ->get();

        return $new_employees;

    }

    // get allowance amount for employee
    public function getAllowanceAmountByEmpId(Request $request)
    {
        $emp_id = $request->emp_id;  // emp id

        $allow_amounts = DB::table('hr_employee_salary_parts')
                            ->select('allowance_type_id', 'amount')
                            ->where('emp_id', $emp_id)
                            ->get();

        return $allow_amounts;

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
            $emp_id = $request->emp_id;
            $allowance_id = $request->allowance_id;
            $allowance_val = $request->allowance_val;

            if ($id == 0) {  // create
                
                foreach ($allowance_id as $key => $allow_id) {
                    $emp_salary_part = new EmployeeSalaryPart();
                    $emp_salary_part->emp_id = $emp_id;
                    $emp_salary_part->allowance_type_id = $allow_id;
                    $emp_salary_part->amount = $allowance_val[$key];
                    $emp_salary_part->save();
                }

                $actvity = 'New Employee Salary Part Create  Emp_id - '. $emp_id;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

                if (isset($request->request_from)) {
                    $arry = [
                        'is_success' => true,
                    ];
                    return $arry;
    
                } else {
                    return redirect()->route('hr.empSalaryPart.index')
                            ->with('success', 'Employee Salary Part successfully Create!!');
                }


            } else {  // update
                
                // get employee salary part ids
                $this_emp_salary_part_ids = DB::table('hr_employee_salary_parts')
                                            ->select('id')
                                            ->where('emp_id', $emp_id)
                                            ->get();

                // delete this rows from DB
                foreach ($this_emp_salary_part_ids as $id ) {
                    $emp_salary_part_ = EmployeeSalaryPart::find($id->id);
                    $emp_salary_part_->delete();
                }

                // again create emp salary parts
                foreach ($allowance_id as $key => $allow_id) {
                    $emp_salary_part = new EmployeeSalaryPart();
                    $emp_salary_part->emp_id = $emp_id;
                    $emp_salary_part->allowance_type_id = $allow_id;
                    $emp_salary_part->amount = $allowance_val[$key];
                    $emp_salary_part->save();
                }

                $actvity = 'Employee Salary Part Update  Emp_id - '. $emp_id;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

                return redirect()->route('hr.empSalaryPart.index')
                        ->with('success', 'Employee Salary Part successfully Updated!!');

            }
            
        }
        
    }

    
}
