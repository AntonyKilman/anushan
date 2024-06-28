<?php

namespace App\Http\Controllers;

use App\Models\DepartmentHR;
use App\Models\employee;
use App\Models\EmployeeDoc;
use App\Models\EmployeeType;
use App\Models\WorkType;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    // show
    public function index()
    {
        $super_admin_id = 1;  // super admin id

        $employees = DB::table('employees')
            ->select('employees.*', 'user_roles.user_role as role_name', 'departments.name as department_name', 'work_types.name as work_type_name', 'employee_types.type as employee_type_name')
            ->leftJoin('user_roles', 'user_roles.id', 'employees.role_id')
            ->leftJoin('departments', 'departments.id', 'employees.department_id')
            ->leftJoin('work_types', 'work_types.id', 'employees.work_type_id')
            ->leftJoin('employee_types', 'employee_types.id', 'employees.employee_type_id')
            ->whereNotIn('employees.role_id', [1,2])  // super admin & admin
            ->whereNotIn('employees.id', [1,2,3,4,5]) // not use employees
            ->orderBy('employees.status', 'ASC')
            ->orderBy('employees.id', 'DESC')
            ->get();

        $employee_type = EmployeeType::get();
        $work_type = WorkType::get();
        $deparments = DepartmentHR::where('status', 'Active')->get();

        $allowance_type = DB::table('hr_allowance_types')->get();

        $roles = DB::table('user_roles')
            ->select('*')
            ->where('id', '!=', $super_admin_id)
            ->get();


        return view('hr.employee')
            ->with('employees', $employees)
            ->with('deparments', $deparments)
            ->with('employee_type', $employee_type)
            ->with('work_type', $work_type)
            ->with('allowance_type', $allowance_type)
            ->with('roles', $roles);
    }

    //  create & update
    public function store(Request $request)
    {
        // create session for modal show
        Session::put('DataSave', "EmpDataOnlySave");

        $id = $request->id;  // id

        if ($id == 0) {  // create

            $request-> validate([
                'f_name' => 'required',
                'email' => 'nullable|unique:employees,email',
                'mobile' => 'required|numeric|unique:employees,mobile',
                'nic' => 'required|min:10|max:12|unique:employees,nic',
                'bank_account_number' => 'nullable|unique:employees,bank_account_number'
            ]);

            $employee = new Employee();
            $employee->f_name = $request->f_name;
            $employee->l_name = $request->l_name;
            $employee->email = $request->email;
            $employee->dob = $request->dob;
            $employee->nic = $request->nic;
            $employee->role_id = $request->role_id;
            $employee->department_id = $request->department_id;
            $employee->employee_type_id = $request->employee_type_id;
            $employee->work_type_id = $request->work_type_id;
            $employee->status = 'Active';
            $employee->mobile = $request->mobile;
            $employee->office_number = $request->office_number;
            $employee->joined_date = $request->joined_date;
            $employee->bank_account_number = $request->bank_account_number;
            $employee->bank_branch_name = $request->bank_branch_name;
            $employee->address = $request->address;
            $employee->leaving_date = isset($request->leaving_date) ? $request->leaving_date : null;
            $employee->leave_reason = isset($request->leave_reason) ? $request->leave_reason : null;
            $employee->emp_code = '-';
            $employee->save();

            $employee->emp_code = 'Emp-0' . $employee->id;
            $employee->save();

            $actvity = 'New Employee Create - ' . $request->f_name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            // delete session
            Session::forget('DataSave'); 
            
            if (isset($request->request_from)) {
                $arry = [
                    'is_success' => true,
                    'emp_id' => $employee->id
                ];
                return $arry;

            } else {
                return redirect()->route('employee.index')
                    ->with('success', 'Employee successfully Create!!');
            }
            

        } else {  // update

            $request-> validate([
                'f_name' => 'required',
                'email' => 'nullable|unique:employees,email,' .$id,
                'mobile' => 'required|numeric|unique:employees,mobile,' .$id,
                'nic' => 'required|min:10|max:12|unique:employees,nic,' .$id,
                'bank_account_number' => 'nullable|unique:employees,bank_account_number,' .$id
            ]);

            $employee = Employee::find($id);
            $employee->f_name = $request->f_name;
            $employee->l_name = $request->l_name;
            $employee->email = $request->email;
            $employee->dob = $request->dob;
            $employee->nic = $request->nic;
            $employee->role_id = $request->role_id;
            $employee->department_id = $request->department_id;
            $employee->employee_type_id = $request->employee_type_id;
            $employee->work_type_id = $request->work_type_id;
            $employee->mobile = $request->mobile;
            $employee->office_number = $request->office_number;
            $employee->joined_date = $request->joined_date;
            $employee->bank_account_number = $request->bank_account_number;
            $employee->bank_branch_name = $request->bank_branch_name;
            $employee->address = $request->address;
            $employee->leaving_date = $request->leaving_date;
            $employee->leave_reason = $request->leave_reason;
            $employee->save();

            $actvity = 'Employee Update - ' . $request->f_name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            // delete session
            Session::forget('DataSave'); 

            return redirect()->route('employee.index')
                ->with('success', 'Employee successfully Updated!!');
        }
    }

    // store employee all data
    public function storeEmpAllData(Request $request)
    {

        // create session for modal show
        Session::put('DataSave', "AllDataSave");

        $request-> validate([
            'f_name_' => 'required',
            'email_' => 'nullable|unique:employees,email',
            'nic_' => 'required|min:10|max:12|unique:employees,nic',
            'bank_account_number_' => 'nullable|unique:employees,bank_account_number',

            'basic_salary' => 'required',
        ]);

        $file = $request->file('file');  // file
        if ($file) {
            $request->validate([
                'file_type' => 'required',
            ]);

            $file_type = $request->file_type;  // Documents type
            if ($file_type == 'Photo') {  // photo
                $request->validate([
                    'file' => 'required|image|mimes:jpeg,png,jpg,webp',
                ]);
            } else {  // pdf
                $request->validate([
                    'file' => 'required|mimes:pdf',
                ]);
            }
        }

        $request_from = 'EmpAllData';

        // **************** new employee create ************************
        $empRequest = [
            'request_from' => $request_from,
            'id' => 0,
            'f_name' => $request->f_name_,
            'l_name' => $request->l_name_,
            'email' => $request->email_,
            'dob' => $request->dob_,
            'mobile' => $request->mobile_,
            'address' => $request->address_,
            'nic' => $request->nic_,
            'role_id' => $request->role_id_,
            'department_id' => $request->department_id_,
            'employee_type_id' => $request->employee_type_id_,
            'work_type_id' => $request->work_type_id_,
            'joined_date' => $request->joined_date_,
            'bank_account_number' => $request->bank_account_number_,
            'bank_branch_name' => $request->bank_branch_name_
        ];

        $empRequestBody = new Request($empRequest); // create request
        $empResponce = json_decode(json_encode($this->store($empRequestBody)));
        $emp_id = $empResponce->emp_id;
        // **************** new employee create ************************

        // **************** basic salary create ************************
        $basicSalaryRequest = [
            'request_from' => $request_from,
            'id' => 0,
            'emp_id' => $emp_id,
            'basic_salary' => $request->basic_salary
        ];

        $basicSalaryRequestBody = new Request($basicSalaryRequest); // create request
        $basicSalaryResponce = json_decode(json_encode(app('App\Http\Controllers\EmployeeBasicSalaryController')->store($basicSalaryRequestBody)));
        // **************** basic salary create ************************


        // **************** employee doc create ************************
        if ($file) { // have doc
            $employee_doc = new EmployeeDoc();
            $employee_doc->employee_id = $emp_id;
            $employee_doc->name = $file_type;
            if ($file) {
                $doc_name = "emp_doc/" . $file_type . '_'. $emp_id . '_' . time() . '.' . $file->extension();
                $file->move("emp_doc", $doc_name);

                $employee_doc->file = $doc_name;
            }
            $employee_doc->save();

            $actvity = 'Employee Document Add, emp_id - '. $emp_id;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
        }
        // **************** employee doc create ************************

        // **************** employee allowane create ************************
        $allowaneRequest = [
            'request_from' => $request_from,
            'id' => 0,
            'emp_id' => $emp_id,
            'allowance_id' => $request->allowance_id,
            'allowance_val' => $request->allowance_val
        ];

        $allowaneRequestBody = new Request($allowaneRequest); // create request
        $allowaneResponce = json_decode(json_encode(app('App\Http\Controllers\EmployeeSalaryPartController')->store($allowaneRequestBody)));
        // **************** employee allowane create ************************

        // delete session
        Session::forget('DataSave'); 

        return redirect()->route('employee.index')
            ->with('success', 'Employee successfully Create!!');


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

        $employee = Employee::find($id);
        $employee->status = $status;
        $employee->save();

        $actvity = 'Employee Status Change - ' . $employee->f_name;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

        return 'Done';
    }


    public function empViewDataAjax(Request $request)
    {
        $emp_id = $request->emp_id;

        $data = DB::table('employees')
                ->select(
                    'employees.*', 
                    'user_roles.user_role as role_name', 
                    'departments.name as department_name', 
                    'work_types.name as work_type_name', 
                    'employee_types.type as employee_type_name',
                    'hr_employee_basic_salaries.basic_salary',
                )
                ->leftJoin('user_roles', 'user_roles.id', 'employees.role_id')
                ->leftJoin('departments', 'departments.id', 'employees.department_id')
                ->leftJoin('work_types', 'work_types.id', 'employees.work_type_id')
                ->leftJoin('employee_types', 'employee_types.id', 'employees.employee_type_id')
                ->leftJoin('hr_employee_basic_salaries', 'hr_employee_basic_salaries.emp_id', 'employees.id')
                ->where('employees.id', $emp_id)
                ->first();

        $allow_amounts = DB::table('hr_employee_salary_parts')
                    ->select('allowance_type_id', 'amount')
                    ->where('emp_id', $emp_id)
                    ->get();

        $arry = [
            'data' => $data,
            'allow_amounts' => $allow_amounts,
        ];

        return $arry;
    }

    public function empSalary()
    {
        return view('hr.emp-salary');
    }
}
