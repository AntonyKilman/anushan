<?php

namespace App\Http\Controllers\ProfitLoss;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class foodcitAccountController extends Controller
{

    // show the foodcity sales data
    public function foodcitySales(Request $request){
        $sales = DB::table('foodcity_sales')
        ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=',$request->to)  //to
        ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=',$request->from) //from
        ->get();
        return $sales;
    }

    // show the foodcity sales data
    public function foodcitySalesReturn(Request $request){
        $salesReturn = DB::table('foodcity_sales_returns')
        ->join('inventory_products','inventory_products.id','=','foodcity_sales_returns.product_id')
        ->select('foodcity_sales_returns.*','inventory_products.product_name')
        ->where(DB::raw('DATE_FORMAT(foodcity_sales_returns.updated_at, "%Y-%m-%d")'), '<=',$request->to)  //to
        ->where(DB::raw('DATE_FORMAT(foodcity_sales_returns.updated_at, "%Y-%m-%d")'), '>=',$request->from) //from
        ->get();
        return $salesReturn;
    }

     // show the foodcity sales data
     public function foodcityPurchases(Request $request){
        $purchases = DB::table('food_city_products')
        ->where('food_city_products.status',1)
        ->where(DB::raw('DATE_FORMAT(food_city_products.updated_at, "%Y-%m-%d")'), '<=',$request->to)  //to
        ->where(DB::raw('DATE_FORMAT(food_city_products.updated_at, "%Y-%m-%d")'), '>=',$request->from) //from
        ->get();
        return $purchases;
    }

     // show the foodcity purchase return  data
    public function foodcityPurchasesReturn(Request $request){

        $purchasesReturns = DB::table('foodcity_product_returns')
        ->join('inventory_products','inventory_products.id','=','foodcity_product_returns.product_id')
        ->select('foodcity_product_returns.*','inventory_products.product_name')
        ->where('foodcity_product_returns.status',1)
        ->where(DB::raw('DATE_FORMAT(foodcity_product_returns.updated_at, "%Y-%m-%d")'), '<=',$request->to)  //to
        ->where(DB::raw('DATE_FORMAT(foodcity_product_returns.updated_at, "%Y-%m-%d")'), '>=',$request->from) //from
        ->get();

        foreach ($purchasesReturns as $purchasesReturn) {

            $purchasesReturn->unit_price = DB::table('inventory_purchase_items')
                ->where('product_id',$purchasesReturn->product_id)
                ->where('purchase_order_id',$purchasesReturn->purchase_order_id)
                        ->value(DB::raw('pur_item_amount/pur_item_qty'));
        }
        return $purchasesReturns;
    }

    // show the foodcity other expense  data
    public function foodcityotherExpense(Request $request){

        $otherExpenses = DB::table('accounts_other_expenses')
        ->where('accounts_other_expenses.oth_dep_id',1)
        ->where('accounts_other_expenses.oth_exp_type_id',$request->id)
        ->where(DB::raw('DATE_FORMAT(accounts_other_expenses.date, "%Y-%m-%d")'), '<=',$request->to)  //to
        ->where(DB::raw('DATE_FORMAT(accounts_other_expenses.date, "%Y-%m-%d")'), '>=',$request->from) //from
        ->get();
        return $otherExpenses;
    }

    // show the foodcity debtors  data
    public function foodcityDebtors(Request $request){

        $debtors = DB::table('foodcity_sales')
        ->join('customers','customers.id','=','foodcity_sales.customer_id')
        ->select('foodcity_sales.*','customers.name')
        ->where('foodcity_sales.credit_payment','>',0)
        ->where(DB::raw('DATE_FORMAT(foodcity_sales.updated_at, "%Y-%m-%d")'), '<=',$request->to)  //to
        ->where(DB::raw('DATE_FORMAT(foodcity_sales.updated_at, "%Y-%m-%d")'), '>=',$request->from) //from
        ->get();
        return $debtors;
    }


}
