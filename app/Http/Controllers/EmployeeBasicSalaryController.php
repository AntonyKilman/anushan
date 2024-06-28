<?php

namespace App\Http\Controllers;

use App\Models\EmployeeBasicSalary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeBasicSalaryController extends Controller
{
    
    // show
    public function index()
    {
        $emp_basic_salary = DB::table('hr_employee_basic_salaries as hebs')
                        ->select('hebs.*', 'employees.f_name as emp_name', 'employees.l_name as emp_last_name', 'employees.emp_code')
                        ->leftJoin('employees', 'employees.id', 'hebs.emp_id')
                        ->orderByDesc('hebs.id')
                        ->get();

        return view('hr.employeeBasicSalary', compact('emp_basic_salary'));

    }

    // search employee
    public function searchEmpBasicSalary(Request $request)
    {
        $key_word = $request->get('query');  // key word

        $employees = DB::table('employees')
                        ->select('employees.id', 'employees.f_name', 'employees.emp_code', 'employees.department_id as department_id', 'departments.name as department_name')
                        ->leftJoin('departments as departments', 'departments.id', 'employees.department_id')
                        ->whereNotExists(function ($query){
                            $query->select(DB::raw(1))
                                ->from('hr_employee_basic_salaries')
                                ->whereColumn('hr_employee_basic_salaries.emp_id', 'employees.id');
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

        return $employees;

    }

    // create & Update
    public function store(Request $request)
    {

        $id = $request->id;  // id

        if ($id == 0) {   // create

            $request-> validate([
                'emp_id' => 'required|unique:hr_employee_basic_salaries,emp_id',
                'basic_salary' => 'required'
            ],[
                'emp_id.required' => 'The employee field is required.',
                'emp_id.unique' => 'The employee has already been taken.',
                'basic_salary.required' => 'The basic salary field is required.',
            ]);
            
            $emp_basic_salary = new EmployeeBasicSalary();
            $emp_basic_salary->emp_id = $request->emp_id;
            $emp_basic_salary->basic_salary = $request->basic_salary;
            $emp_basic_salary->save();


            $actvity = 'New Employee Basic Salary Create  Emp_id - '. $request->emp_id;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            if (isset($request->request_from)) {
                $arry = [
                    'is_success' => true,
                ];
                return $arry;

            } else {
                return redirect()->route('hr.empBasicSalary.index')
                        ->with('success', 'Basic Salary successfully Created!!');
            }


        } else {  // update

            $request-> validate([
                'emp_id' => 'required|unique:hr_employee_basic_salaries,emp_id,'.$id,
                'basic_salary' => 'required'
            ],[
                'emp_id.required' => 'The employee field is required.',
                'emp_id.unique' => 'The employee has already been taken.',
                'basic_salary.required' => 'The basic salary field is required.',
            ]);
            
            $emp_basic_salary = EmployeeBasicSalary::find($id);
            $emp_basic_salary->emp_id = $request->emp_id;
            $emp_basic_salary->basic_salary = $request->basic_salary;
            $emp_basic_salary->save();


            $actvity = 'Employee Basic Salary Update  Emp_id - '. $request->emp_id;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect()->route('hr.empBasicSalary.index')
                    ->with('success', 'Basic Salary successfully Update!!');

        }
        
    }

}
