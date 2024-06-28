<?php

namespace App\Http\Controllers\ProfitLoss;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HrProfitlossController extends Controller
{
    function account_profit_loss_hr(Request $request, $type)
    {

        $to  = Carbon::now()->format('Y-m-d');
        $from  = Carbon::now()->subMonth(3)->format('Y-m-d');

        if ($request->from) {
            $from  = $request->from;
        }

        if ($request->to) {
            $to  = $request->to;
        }

        // calculate salary and advance amount
        $datas = DB::table('account_profit_loss')
            ->Where('account_profit_loss.department_id', 5)
            ->where('account_profit_loss.date', '<=', $to)  //to
            ->where('account_profit_loss.date', '>=', $from) //from
            ->whereIn('account_profit_loss.type', ['HR_SALARY', 'HR_ADVANCE_SALARY'])
            ->where('account_profit_loss.is_delete', 0)
            ->groupBy('account_profit_loss.type')
            ->select(
                'account_profit_loss.type',
                DB::raw('SUM(account_profit_loss.cash_out) as TOTAL_CASH_OUT'),
                DB::raw('SUM(account_profit_loss.cheque_out) as TOTAL_CHEQUE_OUT'),
                DB::raw('SUM(account_profit_loss.card_out) as TOTAL_CARD_OUT')
            )
            ->get();
        // return $datas;
        // calculate total employee expense
        $TOTAL_EMP_EXPENSE = 0;
        foreach ($datas as $data) {
            $TOTAL_EMP_EXPENSE += $data->TOTAL_CASH_OUT + $data->TOTAL_CHEQUE_OUT + $data->TOTAL_CARD_OUT;
        }


        // service expense Details
        $service_expenses = DB::table('account_service_type')->get();

        $deptCharges = DB::table('account_dept_service_charge')
            ->join('account_service_type', 'account_service_type.id', '=', 'account_dept_service_charge.service_type_id')
            ->where(DB::raw('DATE_FORMAT( month, "%Y-%m")'), '<=', $to)
            ->where(DB::raw('DATE_FORMAT( month, "%Y-%m")'), '>=', $from)
            ->where('dept_id', 5)
            ->groupBy('service_type_id')
            ->select(
                'account_dept_service_charge.service_type_id',
                'account_service_type.name',
                DB::raw('SUM(account_dept_service_charge.charge) as TotalCharge')
            )
            ->get();



        foreach ($service_expenses  as $expenses) {
            $a = true;

            if (count($deptCharges) == 0) {
                $expenses->TotalCharge = 0.00;
            } else {

                foreach ($deptCharges  as $deptCharge) {

                    if ($expenses->name ==  $deptCharge->name) {
                        $a = false;
                        $expenses->TotalCharge = $deptCharge->TotalCharge;
                    } elseif ($a) {
                        $expenses->TotalCharge = 0.00;
                    }
                }
            }
        }

        // return $service_expenses;

        $allServiceCharge = 0;
        foreach ($service_expenses as $value) {
            $allServiceCharge += $value->TotalCharge;
        }

        // Total expenses for profit loss page
        $total_profitloss_exp = 0;
        $total_profitloss_exp = $allServiceCharge + $TOTAL_EMP_EXPENSE;


        // calculate service expense charge
        $charges = DB::table('account_dept_service_expense')
            ->join('account_service_expense_new', 'account_service_expense_new.id', '=', 'account_dept_service_expense.account_service_expense_new_id')
            ->join('account_service_type', 'account_service_type.id', '=', 'account_service_expense_new.service_type_id')
            ->where('account_service_expense_new.month', '>=', $from)
            ->where('account_service_expense_new.month', '<=', $to)
            ->where('account_dept_service_expense.departments_id', 5)

            ->groupBy('account_service_expense_new.service_type_id')
            ->select(
                'account_service_expense_new.service_type_id',
                'account_service_type.name',
                DB::raw('SUM(account_dept_service_expense.dept_pay) as TotalPaymemt')
            )
            ->get();
        // return $charges;

        // get data for finance statement
        foreach ($deptCharges as $deptCharge) {
            $a = true;

            if (count($charges) == 0) {
                $deptCharge->Accured_amount = $deptCharge->TotalCharge;
                $deptCharge->paidAmount = 0;
            } else {


                foreach ($charges as $charge) {

                    if ($deptCharge->service_type_id == $charge->service_type_id) {
                        $a = false;
                        $deptCharge->Accured_amount = $deptCharge->TotalCharge - $charge->TotalPaymemt;
                        $deptCharge->paidAmount = $charge->TotalPaymemt;
                    } elseif ($a) {
                        $deptCharge->Accured_amount = $deptCharge->TotalCharge;
                        $deptCharge->paidAmount = 0;
                    }
                }
            }
        }

        // return  $deptCharges;

        $chargePaid = 0;
        $TOTAL_PAID_AMOUNT = 0;

        foreach ($deptCharges as $deptCharge) {
            $chargePaid += $deptCharge->Accured_amount;
            $TOTAL_PAID_AMOUNT += $deptCharge->paidAmount;
        }


        // get data for other expenses
        // $other_expenses = DB::table('accounts_other_expenses')
        // ->join('account__other_expenses_types','account__other_expenses_types.id','=','accounts_other_expenses.oth_dep_id')
        // ->select('accounts_other_expenses.*')
        // ->get();
        // return  $other_expenses;






        if ($type == "profitLoss") {
            return view('profit_loss.acc_hr_profitloss', compact('from', 'to', 'service_expenses', 'allServiceCharge', 'datas', 'TOTAL_EMP_EXPENSE', 'total_profitloss_exp'));
        } else {
            return view('financial_position.acc_hr_financial', compact('from', 'to', 'deptCharges', 'total_profitloss_exp', 'TOTAL_EMP_EXPENSE', 'chargePaid', 'TOTAL_PAID_AMOUNT'));
        }
    }
}
