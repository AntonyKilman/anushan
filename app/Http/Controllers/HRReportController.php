<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HRReportController extends Controller
{

    // employee report
    public function employeeReport(Request $request)
    {
        $status = isset($request->status) ? $request->status : 'Active';

        $all_employees = DB::table('employees')
                            ->select('employees.*', 'departments.name as department_name', 'user_roles.user_role as role_name')
                            ->leftJoin('departments', 'departments.id', 'employees.department_id')
                            ->leftJoin('user_roles', 'user_roles.id', 'employees.role_id')
                            ->whereNotIn('employees.id', [1,2,3,4,5]); // not use employees
                            if ($status != 'All') {
        $all_employees = $all_employees->where('employees.status', $status);
                        }
        $all_employees = $all_employees->get();

        return view('hr.report.employeeReport', compact('all_employees', 'status'));

    }

    // epf & etf report
    public function epfEtfReport(Request $request)
    {
        
        if($request->get('date_from') && $request->get('date_to')){
            $date_from = date('Y-m', strtotime($request->get('date_from')));
            $date_to = date('Y-m', strtotime($request->get('date_to')));
        }else{
            $now = Carbon::now()->toDateString();
            $date_from = date("Y-m", strtotime ( '-1 month' , strtotime ($now) ));
            $date_to = date('Y-m', strtotime($now));
        }


        $epf_etf = DB::table('hr_salary_payables')
                    ->select('hr_salary_payables.*','employees.f_name','employees.emp_code')
                    ->leftJoin('employees','employees.id','hr_salary_payables.emp_id')
                    ->where('salary_month','>=',$date_from)
                    ->where('salary_month','<=',$date_to)
                    ->get();
        
        
        return view('hr.report.epf&etfReport', compact('epf_etf', 'date_from', 'date_to'));
                    
    }

    // salary payable report
    public function salaryPayableReport(Request $request)
    {
        if ($request->get('month')) {
            $month = $request->get('month');
        } else {
            $month = date("Y-m");
        }

        $salary_payable = DB::table('hr_salary_payables')
            ->select('hr_salary_payables.*', 'employees.f_name as emp_name', 'employees.emp_code')
            ->leftJoin('employees', 'employees.id', 'hr_salary_payables.emp_id')
            ->where('salary_month', $month)
            ->get();

        return view('hr.report.salaryPayableReport', compact('salary_payable', 'month'));
    }

    // basic salary report
    public function basicSalaryReport(Request $request)
    {
        $status = isset($request->status) ? $request->status : 'Active';

        $basic_salary = DB::table('hr_employee_basic_salaries as hebs')
                        ->select('hebs.*', 'employees.f_name as emp_name', 'employees.emp_code')
                        ->leftJoin('employees', 'employees.id', 'hebs.emp_id');
                        if ($status != 'All') {
        $basic_salary = $basic_salary->where('status', $status);
                        }
        $basic_salary = $basic_salary->get();

        return view('hr.report.basicSalaryReport', compact('basic_salary', 'status'));
    }
}
