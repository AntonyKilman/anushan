<?php

namespace App\Http\Controllers\ProfitLoss;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FoodcityProfitlossController extends Controller
{

    function account_profit_loss_foodcity(Request $request,$type)
    {

        $to  = Carbon::now()->format('Y-m-d');
        $from  = Carbon::now()->subMonth(3)->format('Y-m-d');

        if ($request->from) {
            $from  = $request->from;
        }

        if ($request->to) {
            $to  = $request->to;
        }

        // sales amount-------------------------------------------------------
        $sales = DB::table('account_profit_loss')
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.type', "Foodcity Sales")
            ->where('account_profit_loss.department_id', 1)
            ->select( DB::raw('SUM(total_sales_price) as totalSales'))
            ->get()
            ->first();

        if ($sales->totalSales == null) {
            $sales->totalSales = 0.00;
        }
        $sales = $sales->totalSales;


        //sales return amount------------------------------------------------------------
        $salesReturn = DB::table('account_profit_loss')
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.type', "Foodcity Sales Return")
            ->where('account_profit_loss.department_id', 1)
            ->select( DB::raw('SUM(total_sales_price) as totalSalesReturn'))
            ->get()
            ->first();

        if ($salesReturn->totalSalesReturn == null) {
            $salesReturn->totalSalesReturn = 0.00;
        }
        $salesReturn = $salesReturn->totalSalesReturn;


        // calculate basic stock amount---------------------------------------------
        $basicInStocks = DB::table('account_profit_loss')
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id', 1)
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $from) //from
            ->whereIn('account_profit_loss.type', ["Foodcity Sales Return", "Foodcity purchase Product"])
            ->select(DB::raw('SUM(total_purchase_price) as basicInStocks'))
            ->get()
            ->first();

        if ($basicInStocks->basicInStocks == null) {
            $basicInStocks->basicInStocks = 0.00;
        }


        $basicOutStocks = DB::table('account_profit_loss')
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id', 1)
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $from) //from
            ->whereIn('account_profit_loss.type', ['Foodcity Sales', 'Foodcity Product Return','Stock damage','Stock drawing'])
            ->select('account_profit_loss.*', DB::raw('SUM(total_purchase_price) as basicOutStocks'))
            ->get()
            ->first();

        if ($basicOutStocks->basicOutStocks == null) {
            $basicOutStocks->basicOutStocks = 0.00;
        }
        $basicStocks = $basicInStocks->basicInStocks - $basicOutStocks->basicOutStocks;


        // purchase amount-------------------------------------------------------
        $purchases = DB::table('account_profit_loss')
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id', 1)
            ->where('account_profit_loss.type', 'Foodcity purchase Product')
            ->select(DB::raw('SUM(total_purchase_price) as purchases'))
            ->get()
            ->first();

        if ($purchases->purchases == null) {
            $purchases->purchases = 0.00;
        }
        $purchases = $purchases->purchases;


        //carriage inwards-----------------------------------------------------
        $carriageinwards = DB::table('account_profit_loss')
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id', 1)
            ->where('account_profit_loss.type', 'carriage inwards')
            ->select(DB::raw('SUM(card_out) as carriageinwards'))
            ->get()
            ->first();

        if ($carriageinwards->carriageinwards == null) {
            $carriageinwards->carriageinwards = 0.00;
        }
        $carriageinwards = $carriageinwards->carriageinwards;


        //Stock Damage----------------------------------------------------
        $stockDamage = DB::table('account_profit_loss')
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id', 1)
            ->where('account_profit_loss.type', 'Stock damage')
            ->select(DB::raw('SUM(total_purchase_price) as stockDamage'))
            ->get()
            ->first();
            $stockDamage = $stockDamage->stockDamage;

        if ($stockDamage == null) {
            $stockDamage = 0;
        }

        //Stock Drawings----------------------------------------------------
        $stockDrawings = DB::table('account_profit_loss')
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id', 1)
            ->where('account_profit_loss.type', 'Stock drawing')
            ->select(DB::raw('SUM(total_purchase_price) as stockDrawing'))
            ->get()
            ->first();
            $stockDrawings = $stockDrawings->stockDrawing;

        if ($stockDrawings == null) {
            $stockDrawings = 0;
        }


        // calculate final stock amount---------------------------------------------
        $finalInStocks = DB::table('account_profit_loss')
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id', 1)
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)
            ->whereIn('account_profit_loss.type', ["Foodcity Sales Return", "Foodcity purchase Product"])
            ->select(DB::raw('SUM(total_purchase_price) as finalInStocks'))
            ->get()
            ->first();

        if ($finalInStocks->finalInStocks == null) {
            $finalInStocks->finalInStocks = 0.00;
        }


        $finalOutStocks = DB::table('account_profit_loss')
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id', 1)
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)
            ->whereIn('account_profit_loss.type', ['Foodcity Sales', 'Foodcity Product Return','Stock damage','Stock drawing'])
            ->select(DB::raw('SUM(total_purchase_price) as finalOutStocks'))
            ->get()
            ->first();

        if ($finalOutStocks->finalOutStocks == null) {
            $finalOutStocks->finalOutStocks = 0.00;
        }
        $finalStocks = $finalInStocks->finalInStocks - $finalOutStocks->finalOutStocks;


        // get other expense data
        $otherexpenses = DB::table('account_service_type')->get();

        // get other expense charges amount--------------------------------------------------------
        $deptCharges = DB::table('account_dept_service_charge')
            ->join('account_service_type', 'account_service_type.id', '=', 'account_dept_service_charge.service_type_id')
            ->where(DB::raw('DATE_FORMAT( month, "%Y-%m")'), '<=', $to)
            ->where(DB::raw('DATE_FORMAT( month, "%Y-%m")'), '>=', $from)
            ->where('dept_id', 1)
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


        // Purchase return amount-------------------------------------------------------
        $purchasesReturn = DB::table('account_profit_loss')
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id', 1)
            ->where('account_profit_loss.type', 'Foodcity Product Return')
            ->select( DB::raw('SUM(total_purchase_price) as returns'))
            ->get()
            ->first();

        if ($purchasesReturn->returns == null) {
            $purchasesReturn->returns = 0.00;
        }
        $purchasesReturn = $purchasesReturn->returns;

        // set variable for blade page calculation
        $salesReturn4 =  $sales - $salesReturn;
        $CarriageDown = $carriageinwards + $purchases;
        $stockDrawing3 = $CarriageDown - ($stockDamage + $stockDrawings + $purchasesReturn);
        $costOfSales = $stockDrawing3 + $basicStocks;
        $stockat4 = $costOfSales - $finalStocks;
        $grossProfit =  $salesReturn4  - $stockat4;

        // get all service charge amount
        $allServiceCharge = 0;
        foreach ($otherexpenses as $value) {
            $allServiceCharge+= $value->TotalCharge;
        }



        //  Calculate Debtors
        $Debtors = DB::table('account_profit_loss')
            ->where('account_profit_loss.is_delete', 0)
            ->where('account_profit_loss.department_id', 1)
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
            ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
            ->select(DB::raw('SUM(credit) as totalCredit'))
            ->get()
            ->first();
        $Debtors = $Debtors->totalCredit;

         //  Calculate Cash
         $Cash = DB::table('account_profit_loss')
         ->where('account_profit_loss.is_delete', 0)
         ->where('account_profit_loss.department_id', 1)
         ->whereIn('account_profit_loss.type',['Foodcity Sales','Foodcity Sales Return','Foodcity purchase Product','Foodcity Product Return'])
         ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $to)  //to
         ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=', $from) //from
         ->select(
             DB::raw('SUM(cash_in) as totalCashIn'),
             DB::raw('SUM(cash_out) as totalCashOut'),
         )
         ->get()
         ->first();



          // calculate service expense charge
          $charges = DB::table('account_dept_service_expense')
          ->join('account_service_expense_new', 'account_service_expense_new.id', '=', 'account_dept_service_expense.account_service_expense_new_id')
          ->join('account_service_type', 'account_service_type.id', '=', 'account_service_expense_new.service_type_id')
          ->where('account_dept_service_expense.departments_id', 1)
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


        //   calculate other expense amount
        $newOtherExpenseTypes = DB::table('account__other_expenses_types')->select('account__other_expenses_types.name','account__other_expenses_types.id')->get();

        $newOtherExpensesAmounts = DB::table('accounts_other_expenses')
          ->join('account__other_expenses_types','account__other_expenses_types.id','=','accounts_other_expenses.oth_exp_type_id')
          ->groupBy('account__other_expenses_types.name')
          ->select('account__other_expenses_types.name',
                DB::raw('SUM(accounts_other_expenses.oth_exp_amount) as TotalExpAmount'))
          ->where('accounts_other_expenses.oth_dep_id', 1)
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


          // Assign Values
          $netProfit = $grossProfit - $allServiceCharge -$finalOtherExpense;
          $Cash = $Cash->totalCashIn - $Cash->totalCashOut-$finalOtherExpense;
          $cash4 = $finalStocks + $Debtors + $Cash;
          $drawings4 = $netProfit + $stockDrawings;
          $TotalEquality = $drawings4 + $chargePaid;

        if ($type == "profitLoss") {
            return view('profit_loss.acc_food_profit_loss', compact( 'to', 'from', 'sales','salesReturn','salesReturn4','basicStocks','purchases','carriageinwards', 'CarriageDown','purchasesReturn', 'stockDamage', 'stockDrawings', 'stockDrawing3','costOfSales','finalStocks','stockat4','grossProfit','otherexpenses','netProfit','newOtherExpenseTypes','finalOtherExpense'));
        }
        else{
            return view('financial_position.acc_food_financial', compact( 'to','from','netProfit','finalStocks','Debtors','Cash','stockDrawings','charges','chargePaid','cash4','drawings4','TotalEquality','sales','salesReturn','purchases','purchasesReturn','finalOtherExpense'));
        }

    }
}
