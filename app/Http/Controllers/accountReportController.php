<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class accountReportController extends Controller
{

    function monthlyReport(Request $request){


        if($request->year){
            
            if($request->year==Carbon::now()->year){
                $year =Carbon::now()->year;
                $month =Carbon::now()->month;
            }
            else{
            $year = $request->year;
            $month = 12;
            }
        }
       
        else{
            $year =Carbon::now()->year;
            $month =Carbon::now()->month;
        }

       

        //monthly foodcity sales
        $foodCitySalesMonth = DB::table('foodcity_sales')
        ->select(DB::raw('SUM(foodcity_sales.amount) - SUM(foodcity_sales.discount_amount) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
        ->where('is_cancelled', '=', 0)
        ->whereYear('foodcity_sales.created_at', $year)
        ->groupBy('month_name')
        ->orderBy('month_name', 'DESC')
        ->get();
        // return  $foodCitySalesMonth;

        //if there are no record in a month, add 0 value
        $foodcityArr = [];
        for ($i = 0; $i < $month; $i++) {
            if (!empty($foodCitySalesMonth)) {
                $foodcityArr[$i]['totalSaleAmount'] =0;
                foreach ($foodCitySalesMonth as $item){
                    if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                        $foodcityArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                    }
                }
            } else {
                $foodcityArr[$i]['totalSaleAmount'] = 0;
            }
            $foodcityArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
        }
        $foodCitySalesMonth = collect(array_values($foodcityArr));
        // return  $foodCitySalesMonth;

        //monthly restaurant onside sales
        // $restaurantSalesMonth = DB::table('restaurant_food_orders')
        // ->select(DB::raw('SUM(restaurant_food_orders.grand_total) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
        // ->whereYear('restaurant_food_orders.created_at', $year)
        // ->groupBy('month_name')
        // ->orderBy('month_name', 'DESC')
        // ->get(); 
        
        //monthly restaurant online sales
        // $restaurantOnlineSalesMonth = DB::table('restaurant_online_food_orders')
        // ->select(DB::raw('SUM(restaurant_online_food_orders.total_paid) as totalSaleAmount'), DB::raw("MONTHNAME(delivery_date) as month_name"))
        // ->whereYear('restaurant_online_food_orders.delivery_date', $year)
        // ->whereIn('restaurant_online_food_orders.status', [5, 6])
        // ->groupBy('month_name')
        // ->orderBy('month_name', 'DESC')
        // ->get();

        //sum online sales and offline sales by month
        //  foreach ($restaurantSalesMonth as $restaurant) {
        //     foreach ($restaurantOnlineSalesMonth as $restaurantOnline) {
        //         if ($restaurant->month_name == $restaurantOnline->month_name) {
        //             $restaurant->totalSaleAmount += $restaurantOnline->totalSaleAmount;
        //         }
        //     }
        // }


        // $resArr = [];
        //     for ($i = 0; $i < $month; $i++) {
        //         if (!empty($restaurantSalesMonth)) {
        //             $resArr[$i]['totalSaleAmount'] =0;
        //             foreach ($restaurantSalesMonth as $item){
        //                 if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
        //                     $resArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
        //                 }
        //             }
        //         } else {
        //             $resArr[$i]['totalSaleAmount'] = 0;
        //         }
        //         $resArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
        //     }
        //     $restaurantSalesMonth = collect(array_values($resArr));
            // return $restaurantSalesMonth;

         //monthly reecha main sales
        // $reechaMainSalesMonth = DB::table('main_orders')
        // ->select(DB::raw('SUM(main_orders.total_amount) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
        // ->whereYear('main_orders.created_at', $year)
        // ->groupBy('month_name')
        // ->orderBy('month_name', 'DESC')
        // ->get();

         //if there are no record in a month, add 0 value
        //  $mainArr = [];
        //  for ($i = 0; $i < $month; $i++) {
        //      if (!empty($reechaMainSalesMonth)) {
        //          $mainArr[$i]['totalSaleAmount'] =0;
        //          foreach ($reechaMainSalesMonth as $item){
        //              if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
        //                  $mainArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
        //              }
        //          }
        //      } else {
        //          $mainArr[$i]['totalSaleAmount'] = 0;
        //      }
        //      $mainArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
        //  }
        //  $reechaMainSalesMonth = collect(array_values($mainArr));
        //  return $reechaMainSalesMonth;

          //monthly hotel sales
        // $hotelSalesMonth = DB::table('holel_bookings')
        // ->select(DB::raw('SUM(holel_bookings.income) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
        // ->whereYear('holel_bookings.created_at', $year)
        // ->groupBy('month_name')
        // ->orderBy('month_name', 'DESC')
        // ->get();

        //if there are no record in a month, add 0 value
        // $hotelArr = [];
        // for ($i = 0; $i < $month; $i++) {
        //     if (!empty($hotelSalesMonth)) {
        //         $hotelArr[$i]['totalSaleAmount'] =0;
        //         foreach ($hotelSalesMonth as $item){
        //             if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
        //                 $hotelArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
        //             }
        //         }
        //     } else {
        //         $hotelArr[$i]['totalSaleAmount'] = 0;
        //     }
        //     $hotelArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
        // }
        // $hotelSalesMonth = collect(array_values($hotelArr));
        // return  $hotelSalesMonth;

        //monthly farm onside sales
        // $farmSalesMonth = DB::table('farm_orders_food_orders')
        // ->select(DB::raw('SUM(farm_orders_food_orders.grand_total) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
        // ->whereYear('farm_orders_food_orders.created_at', $year)
        // ->groupBy('month_name')
        // ->orderBy('month_name', 'DESC')
        // ->get();

        //monthly farm online sales
        // $farmOnlineSalesMonth = DB::table('farm_orders_online_food_orders')
        // ->select(DB::raw('SUM(farm_orders_online_food_orders.total_paid) as totalSaleAmount'), DB::raw("MONTHNAME(delivery_date) as month_name"))
        // ->whereYear('farm_orders_online_food_orders.delivery_date', $year)
        // ->whereIn('farm_orders_online_food_orders.status', [5, 6])
        // ->groupBy('month_name')
        // ->orderBy('month_name', 'DESC')
        // ->get();
        //sum online sales and offline sales by month
        //  foreach ($farmSalesMonth as $farm) {
        //     foreach ($farmOnlineSalesMonth as $farmOnline) {
        //         if ($farm->month_name == $farmOnline->month_name) {
        //             $farm->totalSaleAmount += $farmOnline->totalSaleAmount;
        //         }
        //     }
        // }

        //if there are no record in a month, add 0 value
        // $farmArr = [];
        // for ($i = 0; $i < $month; $i++) {
        //     if (!empty($farmSalesMonth)) {
        //         $farmArr[$i]['totalSaleAmount'] =0;
        //         foreach ($farmSalesMonth as $item){
        //             if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
        //                 $farmArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
        //             }
        //         }
        //     } else {
        //         $farmArr[$i]['totalSaleAmount'] = 0;
        //     }
        //     $farmArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
        // }
        // $farmSalesMonth = collect(array_values($farmArr));
        // return $farmSalesMonth;

         //monthly bank sales
        // $bankSalesMonth = DB::table('account_bank_balances')
        // ->select(DB::raw('SUM(account_bank_balances.credit) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
        // ->whereYear('account_bank_balances.date', $year)
        // ->groupBy('month_name')
        // ->orderBy('month_name', 'DESC')
        // ->get();


          //if there are no record in a month, add 0 value
        //   $bankArr = [];
        //   for ($i = 0; $i < $month; $i++) {
        //       if (!empty($bankSalesMonth)) {
        //           $bankArr[$i]['totalSaleAmount'] =0;
        //           foreach ($bankSalesMonth as $item){
        //               if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
        //                   $bankArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
        //               }
        //           }
        //       } else {
        //           $bankArr[$i]['totalSaleAmount'] = 0;
        //       }
        //       $bankArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
        //   }
        //   $bankSalesMonth = collect(array_values($bankArr));
        // //   return $bankSalesMonth;
       

        // $otherSalesMonth = DB::table('accounts_other_incomes')
        // ->select(DB::raw('SUM(accounts_other_incomes.amount) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
        // ->whereYear('accounts_other_incomes.date', $year)
        // ->groupBy('month_name')
        // ->orderBy('month_name', 'DESC')
        // ->get();

        // //if there are no record in a month, add 0 value
        // $otherArr = [];
        // for ($i = 0; $i < $month; $i++) {
        //     if (!empty($otherSalesMonth)) {
        //         $otherArr[$i]['totalSaleAmount'] =0;
        //         foreach ($otherSalesMonth as $item){
        //             if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
        //                 $otherArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
        //             }
        //         }
        //     } else {
        //         $otherArr[$i]['totalSaleAmount'] = 0;
        //     }
        //     $otherArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
        // }
        // $otherSalesMonth = collect(array_values($otherArr));
        // return  $otherSalesMonth;
           
        // ---------------------------income end----------------------------------------


        // ---------------------------expense  start-------------------------------------

         //monthly Inventery expense
        $inventeryExpenseMonth = DB::table('inventory_purchase_orders')
                ->select(DB::raw('SUM(inventory_purchase_orders.pur_ord_amount) as totalExpenseAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
                ->whereYear('inventory_purchase_orders.created_at', $year)
                ->groupBy('month_name')
                ->orderBy('month_name', 'DESC')
                ->get();
                // return $inventeryExpenseMonth;

            //if there are no record in a month, add 0 value
        $inventeryArr = [];
            for ($i = 0; $i < $month; $i++) {
                if (!empty($inventeryExpenseMonth)) {
                    $inventeryArr[$i]['totalExpenseAmount'] =0;
                    foreach ($inventeryExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $inventeryArr[$i]['totalExpenseAmount'] = $item->totalExpenseAmount;
                        }
                    }
                } else {
                    $inventeryArr[$i]['totalExpenseAmount'] = 0;
                }
                $inventeryArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
        $inventeryExpenseMonth = collect(array_values($inventeryArr));
        // return  $inventeryExpenseMonth;
        
        
        
               //monthly HR expense
               $hrExpenseMonth = DB::table('hr_salary_payables')
               ->select(DB::raw('SUM(hr_salary_payables.net_salary) as totalExpenseAmount'), DB::raw("MONTHNAME(date) as month_name"))
               ->whereYear('hr_salary_payables.date', $year)
               ->groupBy('month_name')
               ->orderBy('month_name', 'DESC')
               ->get();

           //if there are no record in a month, add 0 value
           $hrArr = [];
           for ($i = 0; $i < $month; $i++) {
               if (!empty($hrExpenseMonth)) {
                   $hrArr[$i]['totalExpenseAmount'] =0;
                   foreach ($hrExpenseMonth as $item){
                       if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                           $hrArr[$i]['totalExpenseAmount'] = $item->totalExpenseAmount;
                       }
                   }
               } else {
                   $hrArr[$i]['totalExpenseAmount'] = 0;
               }
               $hrArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
           }
           $hrExpenseMonth = collect(array_values($hrArr)); 
        //    return  $hrExpenseMonth;
           
           
           $otherExpenseMonth = DB::table('accounts_other_expenses')
           ->select(DB::raw('SUM(accounts_other_expenses.oth_exp_amount) as totalExpenseAmount'), DB::raw("MONTHNAME(date) as month_name"))
           ->whereYear('accounts_other_expenses.date', $year)
           ->groupBy('month_name')
           ->orderBy('month_name', 'DESC')
           ->get();

           //if there are no record in a month, add 0 value
           $otherArr = [];
           for ($i = 0; $i < $month; $i++) {
               if (!empty($otherExpenseMonth)) {
                   $otherArr[$i]['totalExpenseAmount'] =0;
                   foreach ($otherExpenseMonth as $item){
                       if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                           $otherArr[$i]['totalExpenseAmount'] = $item->totalExpenseAmount;
                       }
                   }
               } else {
                   $otherArr[$i]['totalExpenseAmount'] = 0;
               }
               $otherArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
           }
           $otherExpenseMonth = collect(array_values($otherArr));
        //    return   $otherExpenseMonth;


           $bankExpenseMonth = DB::table('account_bank_balances')
           ->select(DB::raw('SUM(account_bank_balances.debit) as totalExpenseAmount'), DB::raw("MONTHNAME(date) as month_name"))
           ->whereYear('account_bank_balances.date', $year)
           ->groupBy('month_name')
           ->orderBy('month_name', 'DESC')
           ->get();

           //if there are no record in a month, add 0 value
           $bankArr = [];
           for ($i = 0; $i < $month; $i++) {
               if (!empty($bankExpenseMonth)) {
                   $bankArr[$i]['totalExpenseAmount'] =0;
                   foreach ($bankExpenseMonth as $item){
                       if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                           $bankArr[$i]['totalExpenseAmount'] = $item->totalExpenseAmount;
                       }
                   }
               } else {
                   $bankArr[$i]['totalExpenseAmount'] = 0;
               }
               $bankArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
           }
           $bankExpenseMonth = collect(array_values($bankArr));
        //    return $bankExpenseMonth;


           $serviceExpenseMonth = DB::table('accounts_service_expenses')
           ->select(DB::raw('SUM(accounts_service_expenses.amount) as totalExpenseAmount'), DB::raw("MONTHNAME(date) as month_name"))
           ->whereYear('accounts_service_expenses.date', $year)
           ->groupBy('month_name')
           ->orderBy('month_name', 'DESC')
           ->get();

       //if there are no record in a month, add 0 value
       $serviceArr = [];
       for ($i = 0; $i < $month; $i++) {
           if (!empty($serviceExpenseMonth)) {
               $serviceArr[$i]['totalExpenseAmount'] =0;
               foreach ($serviceExpenseMonth as $item){
                   if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                       $serviceArr[$i]['totalExpenseAmount'] = $item->totalExpenseAmount;
                   }
               }
           } else {
               $serviceArr[$i]['totalExpenseAmount'] = 0;
           }
           $serviceArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
       }
       $serviceExpenseMonth = collect(array_values($serviceArr));
    //    return $serviceExpenseMonth;


       $totalExpenseMonth=[];
       for($i=0; $i<$month; $i++){
               $totalExpenseMonth[$i]['totalExpenseAmount'] = $inventeryExpenseMonth[$i]['totalExpenseAmount'] + $hrExpenseMonth[$i]['totalExpenseAmount'] + $otherExpenseMonth[$i]['totalExpenseAmount'] + $bankExpenseMonth[$i]['totalExpenseAmount'] + $serviceExpenseMonth[$i]['totalExpenseAmount'];
               $totalExpenseMonth[$i]['totalSaleAmount'] = $foodCitySalesMonth[$i]['totalSaleAmount'];
               $totalExpenseMonth[$i]['month_name'] = $inventeryExpenseMonth[$i]['month_name'];
       }
       
        $totalExpenseMonth = collect(array_values($totalExpenseMonth));
        // return $totalExpenseMonth;
        $totalExpenseMonth = json_decode(json_encode($totalExpenseMonth)); 
        // return $totalExpenseMonth;

        return view('accountspages.accountReport',compact('totalExpenseMonth','year'));

    }
}
