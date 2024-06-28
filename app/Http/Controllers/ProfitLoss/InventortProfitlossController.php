<?php

namespace App\Http\Controllers\ProfitLoss;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventortProfitlossController extends Controller
{
    function account_profit_loss_inventory(Request $request, $type)
    {

        $to  = Carbon::now()->format('Y-m-d');
        $from  = Carbon::now()->subMonth(3)->format('Y-m-d');

        if ($request->from) {
            $from  = $request->from;
        }

        if ($request->to) {
            $to  = $request->to;
        }

        $purchases = DB::table('account_profit_loss')
            ->whereIn('account_profit_loss.type', ['Inventory Purchase', 'Inventory Transfer', 'Inventory Indoor Return', 'Inventory Outdoor Return'])
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id',4)
            ->groupBy('account_profit_loss.type')
            ->select(
                'account_profit_loss.type',
                DB::raw('SUM(account_profit_loss.total_purchase_price) as TOTAL_PURCHASE'),
                DB::raw('SUM(account_profit_loss.cheque_in) as TOTAL_CHEQUE_IN'),
                DB::raw('SUM(account_profit_loss.cheque_out) as TOTAL_CHEQUE_OUT'),
                DB::raw('SUM(account_profit_loss.cash_in) as TOTAL_CASH_IN'),
                DB::raw('SUM(account_profit_loss.cash_out) as TOTAL_CASH_OUT'),
                DB::raw('SUM(account_profit_loss.card_in) as TOTAL_CARD_IN'),
                DB::raw('SUM(account_profit_loss.card_out) as TOTAL_CARD_OUT'),
            )
            ->get();
        // return $purchases;
        $PURCHASE_RETURN = 0;
        $PURCHASE = 0;
        $FINAL_CASH = 0;


        foreach ($purchases as $purchase) {

            $FINAL_CASH+= $purchase->TOTAL_CHEQUE_IN + $purchase->TOTAL_CASH_IN + $purchase->TOTAL_CARD_IN - $purchase->TOTAL_CHEQUE_OUT - $purchase->TOTAL_CASH_OUT - $purchase->TOTAL_CARD_OUT;

            if ($purchase->type == "Inventory Outdoor Return") {
                $PURCHASE_RETURN = $purchase->TOTAL_PURCHASE;
            }
            if ($purchase->type == "Inventory Purchase") {
                $PURCHASE = $purchase->TOTAL_PURCHASE;
            }
        }

        $FINAL_PURCHASE = $PURCHASE - $PURCHASE_RETURN;


        // Calculate basic stock
        $basic_stocks = DB::table('account_profit_loss')
            ->whereIn('account_profit_loss.type', ['Inventory Purchase', 'Inventory Transfer', 'Inventory Indoor Return', 'Inventory Outdoor Return'])
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $from) //from
            ->where('account_profit_loss.is_delete', 0)
            ->groupBy('account_profit_loss.type')
            ->where('account_profit_loss.department_id',4)
            ->select(
                'account_profit_loss.type',
                DB::raw('SUM(account_profit_loss.total_purchase_price) as TOTAL_PURCHASE'),
            )
            ->get();

        $TOTAL_BASICS = 0;

        foreach ($basic_stocks as $basic_stock) {
            if ($basic_stock->type == "Inventory Purchase" || $basic_stock->type == "Inventory Indoor Return") {
                $TOTAL_BASICS += $basic_stock->TOTAL_PURCHASE;
            }

            if ($basic_stock->type == "Inventory Outdoor Return" || $basic_stock->type == "Inventory Transfer") {
                $TOTAL_BASICS -= $basic_stock->TOTAL_PURCHASE;
            }
        }

        $STOCK_DOWN = $TOTAL_BASICS +   $FINAL_PURCHASE;

        // Calculate final stock
        $final_stocks = DB::table('account_profit_loss')
            ->whereIn('account_profit_loss.type', ['Inventory Purchase', 'Inventory Transfer', 'Inventory Indoor Return', 'Inventory Outdoor Return'])
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to) //from
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id',4)
            ->groupBy('account_profit_loss.type')
            ->select(
                'account_profit_loss.type',
                DB::raw('SUM(account_profit_loss.total_purchase_price) as TOTAL_PURCHASE'),
            )
            ->get();

        $TOTAL_FINAL = 0;

        foreach ($final_stocks as $final_stock) {
            if ($final_stock->type == "Inventory Purchase" || $final_stock->type == "Inventory Indoor Return") {
                $TOTAL_FINAL += $final_stock->TOTAL_PURCHASE;
            }

            if ($final_stock->type == "Inventory Outdoor Return" || $final_stock->type == "Inventory Transfer") {
                $TOTAL_FINAL -= $final_stock->TOTAL_PURCHASE;
            }
        }

        $STOCK4 = $STOCK_DOWN - $TOTAL_FINAL;
        $GROSS_PROFIT = 0 - $STOCK4;
        $TOTAL_ASSETS = $TOTAL_FINAL + $FINAL_CASH;

        // Other expense Details
        $otherexpenses = DB::table('account_service_type')->get();

        $deptCharges = DB::table('account_dept_service_charge')
            ->join('account_service_type', 'account_service_type.id', '=', 'account_dept_service_charge.service_type_id')
            ->where(DB::raw('DATE_FORMAT( month, "%Y-%m")'), '<=', $to)
            ->where(DB::raw('DATE_FORMAT( month, "%Y-%m")'), '>=', $from)
            ->where('dept_id', 4)
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

        // calculate service expense charge
        $charges = DB::table('account_dept_service_expense')
            ->join('account_service_expense_new', 'account_service_expense_new.id', '=', 'account_dept_service_expense.account_service_expense_new_id')
            ->join('account_service_type', 'account_service_type.id', '=', 'account_service_expense_new.service_type_id')
            ->where('account_dept_service_expense.departments_id', 4)
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
        $TotalEquality = $NET_PROFIT + $chargePaid;

        if ($type == "profitLoss") {
            return view('profit_loss.acc_inventory_profitloss', compact('from', 'to', 'PURCHASE', 'PURCHASE_RETURN', "TOTAL_BASICS", 'FINAL_PURCHASE', 'STOCK_DOWN', 'TOTAL_FINAL', 'GROSS_PROFIT', 'STOCK4','otherexpenses','NET_PROFIT'));
        }

        else{
            return view('financial_position.acc-inventory-financial',compact('from', 'to','TOTAL_FINAL','FINAL_CASH','TOTAL_ASSETS','NET_PROFIT','charges','TotalEquality','chargePaid'));
        }
    }
}
