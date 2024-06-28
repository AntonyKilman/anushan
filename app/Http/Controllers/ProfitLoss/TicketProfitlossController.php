<?php

namespace App\Http\Controllers\ProfitLoss;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TicketProfitlossController extends Controller
{
    function account_profit_loss_ticket(Request $request, $type)
    {

        $to  = Carbon::now()->format('Y-m-d');
        $from  = Carbon::now()->subMonth(3)->format('Y-m-d');

        if ($request->from) {
            $from  = $request->from;
        }

        if ($request->to) {
            $to  = $request->to;
        }


          $datas = DB::table('account_profit_loss')
          ->Where('account_profit_loss.department_id', 6)
          ->where('account_profit_loss.date', '<=', $to)  
          ->where('account_profit_loss.date', '>=', $from)
          ->where('account_profit_loss.is_delete', 0)
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
                  // return $datas;

                  $cash = DB::table('account_profit_loss')
                  ->where('department_id', 3)
                  ->where('is_delete', 0)
                  ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
                  ->select(
                      DB::raw('SUM(account_profit_loss.cash_in) TICK_CASH_IN'),
                      DB::raw('SUM(account_profit_loss.cash_out) TICK_CASH_OUT')
                  )
                  ->first();
              $cash = $cash-> TICK_CASH_IN -$cash-> TICK_CASH_OUT;
              // return $cash;

                $TOTAL_PURCHASE = 0;
                $PURCHASE_RETURN = 0;
                $FINAL_PURCHASE = 0;
                $SALES = 0;
                $GROSS_PROFIT = 0;
                $TOTAL_CREDIT = 0;

                foreach ($datas as $data) {

                    $TOTAL_CREDIT += $data->TOTAL_CREDIT;
        
                    if ($data->type == "Ticket Purchase") {
                        $TOTAL_PURCHASE = $data->TOTAL_PURCHASE;
                    }
                    if ($data->type == "Ticket Purchase Reurn") {
                        $PURCHASE_RETURN = $data->TOTAL_PURCHASE;
                    }
                    if ($data->type == "Ticket Billing") {
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
                    ->where('account_dept_service_expense.departments_id', 6)
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
        
        
                if ($type == "profitLoss") {
                    return view('profit_loss.acc_ticket_profit_loss', compact('from', 'to', 'datas', 'TOTAL_PURCHASE', 'PURCHASE_RETURN', 'FINAL_PURCHASE', 'SALES', 'GROSS_PROFIT', 'otherexpenses', 'NET_PROFIT'));
                } else {
                    return view('financial_position.acc_ticket_financial', compact('from', 'to', 'NET_PROFIT', 'TOTAL_CREDIT','cash','TOTALASSETS','charges','TotalEquality','chargePaid'));
                }
            }

}

