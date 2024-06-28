<?php

namespace App\Http\Controllers\ProfitLoss;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class restaurantAccountController extends Controller
{
    public function sales(Request $request){
        $sales = DB::table('restaurant_food_order_payments')
        ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=',$request->to)  //to
        ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '>=',$request->from) //from
        ->orderBy('restaurant_food_order_payments.updated_at','desc')
        ->get();
        return $sales;
    }

    public function purchase(Request $request){
        $purchases = DB::table('inventory_indoor_transfer')
        ->leftJoin('inventory_products','inventory_products.id','inventory_indoor_transfer.product_id')
        ->where('inventory_indoor_transfer.status',1)
        ->where('inventory_indoor_transfer.department_id',3)
        ->where(DB::raw('DATE_FORMAT(inventory_indoor_transfer.updated_at, "%Y-%m-%d")'), '<=',$request->to)  //to
        ->where(DB::raw('DATE_FORMAT(inventory_indoor_transfer.updated_at, "%Y-%m-%d")'), '>=',$request->from) //from
        ->select('inventory_indoor_transfer.*','inventory_products.product_name')
        ->orderBy('inventory_indoor_transfer.updated_at','desc')
        ->get();
        return $purchases;
    }

    public function purchaseReturn(Request $request){
        $purchaseReturn = DB::table('inventory_indoor_returns')
        ->leftJoin('inventory_products','inventory_products.id','inventory_indoor_returns.product_id')
        ->where('status',1)
        ->where('department_id',3)
        ->where(DB::raw('DATE_FORMAT(inventory_indoor_returns.updated_at, "%Y-%m-%d")'), '<=',$request->to)  //to
        ->where(DB::raw('DATE_FORMAT(inventory_indoor_returns.updated_at, "%Y-%m-%d")'), '>=',$request->from) //from
        ->select('inventory_indoor_returns.*','inventory_products.product_name')
        ->orderBy('inventory_indoor_returns.updated_at','desc')
        ->get();
        return $purchaseReturn;
    }


    public function debtors(Request $request){
        $debtors = DB::table('restaurant_food_order_payments')
        ->leftJoin('restaurant_food_orders','restaurant_food_orders.id','restaurant_food_order_payments.food_order_id')
        ->where(DB::raw('DATE_FORMAT(restaurant_food_orders.billing_date, "%Y-%m-%d")'), '<=',$request->to)  //to
        ->where(DB::raw('DATE_FORMAT(restaurant_food_orders.billing_date, "%Y-%m-%d")'), '>=',$request->from) //from
        ->where('credit_payment','>',0)
        ->select('restaurant_food_order_payments.food_order_id')
        ->get();
        // return $debtors;

        // foreach($debtors as $row){
        //     $row->TOTAL_PAID =DB::table('restaurant_food_order_payments')
        //     ->where('restaurant_food_order_payments.food_order_id',$row->food_order_id)
        //     ->where(DB::raw('DATE_FORMAT(restaurant_food_order_payments.updated_at, "%Y-%m-%d")'), '<=',$request->to)  //to
        //     ->where(DB::raw('DATE_FORMAT(restaurant_food_order_payments.updated_at, "%Y-%m-%d")'), '>=',$request->from) //from
        //     ->groupBy('restaurant_food_order_payments.food_order_id')
        //     ->select(DB::raw('SUM(restaurant_food_order_payments.total_amount) as TOTAL'))
        //     ->first()
        //     ->TOTAL;
        //     $row->TOTAL_PAID = $row->TOTAL_PAID - $row->total_amount;

        // }

        $created_array = [];
        foreach($debtors as $row){
            $records = DB::table('restaurant_food_order_payments')
            ->leftJoin('restaurant_food_orders','restaurant_food_orders.id','restaurant_food_order_payments.food_order_id')
            ->leftJoin('customers','customers.id','restaurant_food_order_payments.credit_customer_id')
            ->where('restaurant_food_order_payments.food_order_id',$row->food_order_id)
            ->where(DB::raw('DATE_FORMAT(restaurant_food_order_payments.updated_at, "%Y-%m-%d")'), '<=',$request->to)  //to
            ->where(DB::raw('DATE_FORMAT(restaurant_food_order_payments.updated_at, "%Y-%m-%d")'), '>=',$request->from) //from
            ->select('restaurant_food_order_payments.*','restaurant_food_orders.billing_date','customers.')
            ->get();

            foreach($records as $record){
                array_push($created_array,$record);
            }

        }
        return $created_array;

    }
}
