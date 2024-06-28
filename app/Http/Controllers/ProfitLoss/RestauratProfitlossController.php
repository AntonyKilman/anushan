<?php

namespace App\Http\Controllers\ProfitLoss;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RestauratProfitlossController extends Controller
{

    function account_profit_loss_restaurants(Request $request, $type)
    {

        // $check = $datas = DB::table('account_profit_loss')
        // ->where('account_profit_loss.department_id', 3)
        // ->groupBy('account_profit_loss.type')
        // ->select('account_profit_loss.type',
        //     DB::raw('SUM(account_profit_loss.total_sales_price) RES_SALES'),
        //     DB::raw('SUM(account_profit_loss.cash_in) RES_CASH'),
        //     DB::raw('SUM(account_profit_loss.card_in) RES_CARD'),
        //     DB::raw('SUM(account_profit_loss.credit) RES_CREDIT'),
        //     )
        // ->get();
        // return $check;

        $check = $datas = DB::table('account_profit_loss')
        ->where('account_profit_loss.department_id', 3)
        ->where('account_profit_loss.type',"Resturant Billing")
        ->get();
        // return $check;

        $emptyArray = [];
        foreach ($check as $raw) {
           $income = $raw->cash_in+$raw->card_in+$raw->credit;

           if(!($income == $raw->total_sales_price )){
            array_push($emptyArray,$raw);
           }

        }

        // return $emptyArray;








        $to  = Carbon::now()->format('Y-m-d');
        $from  = Carbon::now()->subMonth(3)->format('Y-m-d');

        if ($request->from) {
            $from  = $request->from;
        }

        if ($request->to) {
            $to  = $request->to;
        }


        $datas = DB::table('account_profit_loss')
            ->where('department_id', 3)
            ->where('is_delete', 0)
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->groupBy('account_profit_loss.type')
            ->select(
                'account_profit_loss.type',
                DB::raw('SUM(account_profit_loss.total_purchase_price) as TOTAL_PURCHASE'),
                DB::raw('SUM(account_profit_loss.total_sales_price) as TOTAL_SALES'),
                DB::raw('SUM(account_profit_loss.credit) as TOTAL_CREDIT'),
                DB::raw('SUM(account_profit_loss.cash_in) as TOTAL_CASHIN'),
                DB::raw('SUM(account_profit_loss.cash_out) as TOTAL_CASHOUT'),
                DB::raw('SUM(account_profit_loss.card_in) as TOTAL_CARDIN'),
                DB::raw('SUM(account_profit_loss.card_out) as TOTAL_CARDOUT'),
            )
            ->get();
        // return $datas ;

        $cash = DB::table('account_profit_loss')
            ->where('department_id', 3)
            ->where('is_delete', 0)
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->select(
                DB::raw('SUM(account_profit_loss.cash_in) RES_CASH_IN'),
                DB::raw('SUM(account_profit_loss.cash_out) RES_CASH_OUT'),
                DB::raw('SUM(account_profit_loss.card_in) RES_CARD_IN'),
                DB::raw('SUM(account_profit_loss.card_out) RES_CARD_OUT'),
            )
            ->first();
            // return $cash;
        $cash = $cash-> RES_CASH_IN + $cash-> RES_CARD_IN - $cash-> RES_CASH_OUT - $cash-> RES_CARD_OUT;


        $TOTAL_PURCHASE = 0;
        $PURCHASE_RETURN = 0;
        $FINAL_PURCHASE = 0;
        $SALES = 0;
        $GROSS_PROFIT = 0;
        $TOTAL_CREDIT = 0;
        // return $datas;

        foreach ($datas as $data) {

            $TOTAL_CREDIT += $data->TOTAL_CREDIT;

            if ($data->type == "Inventory Transfer") {
                $TOTAL_PURCHASE = $data->TOTAL_PURCHASE;
            }
            if ($data->type == "Resturant Purchase Reurn") {
                $PURCHASE_RETURN = $data->TOTAL_PURCHASE;
            }
            if ($data->type == "Resturant Billing") {
                $SALES = $data->TOTAL_SALES;
            }
        }

        $FINAL_PURCHASE = $TOTAL_PURCHASE - $PURCHASE_RETURN;
        $GROSS_PROFIT = $SALES - $FINAL_PURCHASE;



        // Other expense Details
        $otherexpenses = DB::table('account_service_type')->get();

        $deptCharges = DB::table('account_dept_service_charge')
            ->join('account_service_type', 'account_service_type.id', '=', 'account_dept_service_charge.service_type_id')
            ->where(DB::raw('DATE_FORMAT( month, "%Y-%m")'), '<=', $to)
            ->where(DB::raw('DATE_FORMAT( month, "%Y-%m")'), '>=', $from)
            ->where('dept_id', 3)
            ->groupBy('service_type_id')
            ->select(
                'account_dept_service_charge.service_type_id',
                'account_service_type.name',
                DB::raw('SUM(account_dept_service_charge.charge) as TotalCharge')
            )
            ->get();

        foreach ($otherexpenses  as $expenses) {
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

        $allServiceCharge = 0;
        foreach ($otherexpenses as $value) {
            $allServiceCharge += $value->TotalCharge;
        }

        $NET_PROFIT = 0;
        $NET_PROFIT = $GROSS_PROFIT - $allServiceCharge;
        $TOTALASSETS = $TOTAL_CREDIT + $cash;

        $charges = DB::table('account_dept_service_expense')
            ->join('account_service_expense_new', 'account_service_expense_new.id', '=', 'account_dept_service_expense.account_service_expense_new_id')
            ->join('account_service_type', 'account_service_type.id', '=', 'account_service_expense_new.service_type_id')
            ->where('account_dept_service_expense.departments_id', 3)
            ->groupBy('account_service_expense_new.service_type_id')
            ->select(
                'account_service_type.name',
                DB::raw('SUM(account_dept_service_expense.dept_pay) as TotalPaymemt')
            )
            ->get();


        $chargePaid = 0;
        foreach ($charges as $charge) {
            $chargePaid += $charge->TotalPaymemt;
        }

        $TotalEquality= $chargePaid + $NET_PROFIT;

          //   calculate other expense amount
          $newOtherExpenseTypes = DB::table('account__other_expenses_types')->select('account__other_expenses_types.name','account__other_expenses_types.id')->get();

          $newOtherExpensesAmounts = DB::table('accounts_other_expenses')
            ->join('account__other_expenses_types','account__other_expenses_types.id','=','accounts_other_expenses.oth_exp_type_id')
            ->groupBy('account__other_expenses_types.name')
            ->select('account__other_expenses_types.name',
                  DB::raw('SUM(accounts_other_expenses.oth_exp_amount) as TotalExpAmount'))
            ->where('accounts_other_expenses.oth_dep_id', 3)
            ->where(DB::raw('DATE_FORMAT(accounts_other_expenses.date, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(accounts_other_expenses.date, "%Y-%m-%d")'), '>=', $from) //from
            ->get();

            $finalOtherExpense = 0;
            foreach($newOtherExpenseTypes as $expType){

              if(count($newOtherExpensesAmounts)>0){

                  $check = $newOtherExpensesAmounts->where('name',$expType->name)->first();

                  if( $check){
                   $expType ->TotalExpAmount = $check ->TotalExpAmount;
                   $finalOtherExpense+=$check ->TotalExpAmount;
                  }

                  else{
                   $expType ->TotalExpAmount = 0.00;
                  }
              }

              else{
                  $expType ->TotalExpAmount = 0.00;
              }
            }


        if ($type == "profitLoss") {
            return view('profit_loss.acc_restaurant_profit_loss', compact('from', 'to', 'datas', 'TOTAL_PURCHASE', 'PURCHASE_RETURN', 'FINAL_PURCHASE', 'SALES', 'GROSS_PROFIT', 'otherexpenses', 'NET_PROFIT','newOtherExpenseTypes','finalOtherExpense'));
        } else {
            return view('financial_position.acc_restaurant_financial', compact('from', 'to', 'NET_PROFIT', 'TOTAL_CREDIT','cash','TOTALASSETS','charges','TotalEquality','chargePaid'));
        }
    }
}
