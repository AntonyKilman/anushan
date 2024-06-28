<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalaryPayPrintController extends Controller
{
    // salary pay by bank
    public function salaryPayByBank(Request $request)
    {
        if($request->get('month')){
            $month = date('Y-m', strtotime($request->get('month')));

        }else{
            $now = Carbon::now()->toDateString();
            $month = date("Y-m", strtotime('-1 month', strtotime($now)));
        }
        
        $salary_pay_by_bank = DB::table('hr_salary_payables as hsp')
                            ->select(
                                'e.f_name as emp_name',
                                'e.nic as emp_nic',
                                'e.bank_account_number as emp_account_number',
                                'hsp.net_salary'
                            )
                            ->leftJoin('employees as e', 'e.id', 'hsp.emp_id')
                            ->where('salary_month', $month)
                            ->where('hsp.is_salary_pay_by_bank', 1)
                            ->get();

        return view('hr.salaryPrint.salaryPayByBank', compact('month', 'salary_pay_by_bank'));


    }

    // salary pay by hand
    public function salaryPayByHand(Request $request)
    {
        if($request->get('month')){
            $month = date('Y-m', strtotime($request->get('month')));

        }else{
            $now = Carbon::now()->toDateString();
            $month = date("Y-m", strtotime('-1 month', strtotime($now)));
        }
        
        $salary_pay_by_hand = DB::table('hr_salary_payables as hsp')
                            ->select(
                                'e.f_name as emp_name',
                                'e.nic as emp_nic',
                                'hsp.net_salary'
                            )
                            ->leftJoin('employees as e', 'e.id', 'hsp.emp_id')
                            ->where('salary_month', $month)
                            ->where('hsp.is_salary_pay_by_bank', 0)
                            ->get();

        return view('hr.salaryPrint.salaryPayByHand', compact('month', 'salary_pay_by_hand'));


                            
    }
    
}
