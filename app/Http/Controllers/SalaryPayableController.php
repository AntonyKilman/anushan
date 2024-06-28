<?php

namespace App\Http\Controllers;

use App\Models\account_profit_loss;
use App\Models\AllowanceType;
use App\Models\FoodOrder;
use App\Models\FoodOrderPayment;
use App\Models\SalaryPayable;
use App\Models\SalaryPayableDummy;
use App\Models\SalaryPayablePart;
use App\Models\SalaryPayablePartDummy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;



class SalaryPayableController extends Controller
{

    // show
    public function index(Request $request)
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

        return view('hr.salaryPayable.salaryPayableIndex', compact('salary_payable', 'month'));
    }

    // create page
    public function create()
    {
        $allowance = AllowanceType::all();

        return view('hr.salaryPayable.salaryPayableAdd', compact('allowance'));
    }

    // add employee salary
    public function store(Request $request)
    {

        $rules = array(
            'emp_id' => 'required',
            'salary_month' => 'required'
        );

        $customMessages = [
            'emp_id.required' => 'Select One Employee',
            'salary_month.required' => 'Required'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {  // check back end validator

            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {

            $emp_id = $request->emp_id;
            $salary_month = $request->salary_month;

            $this_month_salary_Paid = DB::table('hr_salary_payables')
                ->where('emp_id', $emp_id)
                ->where('salary_month', $salary_month)
                ->value('id');

            if ($this_month_salary_Paid) {  // this month salary already paid
                return back()->with('error', 'This Month this Employee Salary Already Paid!!');
            }

            $salary_payable = new SalaryPayable();
            $salary_payable->date = $request->date;
            $salary_payable->salary_month = $request->salary_month;
            $salary_payable->emp_id = $request->emp_id;
            $salary_payable->epf = $request->epf;
            $salary_payable->basic_salary = $request->basic_salary;
            $salary_payable->over_time_work = $request->over_time_work;
            $salary_payable->gross_salary = $request->gross_salary;
            $salary_payable->company_epf = $request->company_epf;
            $salary_payable->company_etf = $request->company_etf;
            $salary_payable->others = $request->others;
            $salary_payable->net_salary = $request->net_salary;
            $salary_payable->advance_payment_amount = $request->advance_payment_amount;
            $salary_payable->save();

            $allowance_type_id = $request->allowance_type_id;
            $allowance_type_val = $request->allowance_type_val;
            $total_allowance = 0;
            foreach ($allowance_type_id as $key => $allow_ty_id) {
                $salary_payable_part = new SalaryPayablePart();
                $salary_payable_part->emp_id = $request->emp_id;
                $salary_payable_part->salary_payable_id = $salary_payable->id;
                $salary_payable_part->allowance_type_id = $allow_ty_id;
                $salary_payable_part->amount = $allowance_type_val[$key];

                $total_allowance += $allowance_type_val[$key];

                $salary_payable_part->save();
            }

            $salary_payable->total_allowance = $total_allowance;
            $salary_payable->save();


            // store account profit loss table code by sivakaran
            $account_profit_loss = new account_profit_loss();
            $account_profit_loss->customer_id = $request->emp_id;
            $account_profit_loss->department_id = 5;
            $account_profit_loss->type = "HR_SALARY";
            $account_profit_loss->cash_out = $request->gross_salary - $request->advance_payment_amount;
            $account_profit_loss->connected_id = $salary_payable->id;
            $account_profit_loss->date = $request->date;
            $account_profit_loss->is_delete = 0;
            $account_profit_loss->save();

        }

        $actvity = 'New Employee Salary Payable Create  Emp_id - ' . $request->emp_id;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

        return redirect()->route('hr.salaryPayable.index')
            ->with('success', 'Employee Salary Payable successfully Create!!');
    }

    // employee details for salary
    public function getDetailForSalary(Request $request)
    {

        $emp_id = $request->emp_id;
        $month = $request->salary_month;  // salary month

       
        $salaryDateStart = '01';
        // salary month start date
        $salary_month_start_date = date("Y-m-" . $salaryDateStart, strtotime($month));
        // salary month end date
        $salary_month_end_date = date("Y-m-d", strtotime('+1 month -1 day', strtotime($salary_month_start_date)));


        $basic_salary = DB::table('hr_employee_basic_salaries')
            ->where('emp_id', $emp_id)
            ->value('basic_salary');
        if (!$basic_salary) {
            $basic_salary = 0;
        }

        $allowance = DB::table('hr_employee_salary_parts')
            ->select('hr_employee_salary_parts.*', 'hr_allowance_types.name')
            ->leftJoin('hr_allowance_types', 'hr_allowance_types.id', '=', 'hr_employee_salary_parts.allowance_type_id')
            ->where('hr_employee_salary_parts.emp_id', $emp_id)
            ->get();

        $over_time_work_amount = 0;

        $advance_payment_amount = DB::table('hr_advance_payments')
            ->where('emp_id', $emp_id)
            ->whereDate('month', '>=', $salary_month_start_date)
            ->whereDate('month', '<=', $salary_month_end_date)
            ->value(DB::raw('IFNULL(sum(amount),0)'));


        $data = array([
            'basic_salary' => $basic_salary,
            'allowance' => $allowance,
            'over_time_work_amount' => $over_time_work_amount,
            'advance_payment_amount' => $advance_payment_amount
        ]);

        return $data;
    }

    // view
    public function view($id)
    {

        $salary_payable_id = $id;  // salary payable id

        $salarypayable = DB::table('hr_salary_payables')
            ->select(
                'hr_salary_payables.*',
                'employees.f_name',
                'employees.l_name',
                'employees.emp_code',
                'bank_account_number',
                'bank_branch_name',
                'user_roles.user_role as role_name',
                'departments.name as department_name'
            )
            ->leftJoin('employees', 'hr_salary_payables.emp_id', '=', 'employees.id')
            ->leftJoin('user_roles', 'user_roles.id', '=', 'employees.role_id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->where('hr_salary_payables.id', $salary_payable_id)
            ->first();

        $allowance = DB::table('hr_salary_payable_parts')
            ->select('hr_salary_payable_parts.*', 'hr_allowance_types.name as allowance_type_name')
            ->leftJoin('hr_allowance_types', 'hr_allowance_types.id', '=', 'hr_salary_payable_parts.allowance_type_id')
            ->where('hr_salary_payable_parts.salary_payable_id', $salary_payable_id)
            ->get();

        return view('hr.salaryPayable.salaryPayableView', compact('salarypayable', 'allowance'));
    }

}
