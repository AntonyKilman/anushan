<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Facade\FlareClient\View;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //All Income view page
    public function index()
    {
        //***************************** FoodCity *****************************//
            //monthly foodcity sales
            $foodCitySalesMonth = DB::table('foodcity_sales')
                ->select(DB::raw('SUM(foodcity_sales.amount) - SUM(foodcity_sales.discount_amount) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
                ->where('is_cancelled', '=', 0)
                ->whereYear('foodcity_sales.created_at', Carbon::now()->year)
                ->groupBy('month_name')
                ->orderBy('month_name', 'DESC')
                ->get();

            //if there are no record in a month, add 0 value
            $foodcityArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
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

            //weekly foodcity sales
            $foodCitySalesWeek = DB::table('foodcity_sales')
                ->select(DB::raw('SUM(foodcity_sales.amount) - SUM(foodcity_sales.discount_amount) as totalSaleAmount'))
                ->where('is_cancelled', '=', 0)
                ->whereBetween('foodcity_sales.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->first();

            //today foodcity sales
            $foodCitySalesToday = DB::table('foodcity_sales')
                ->select(DB::raw('SUM(foodcity_sales.amount) - SUM(foodcity_sales.discount_amount) as totalSaleAmount'))
                ->where('is_cancelled', '=', 0)
                ->whereDate('foodcity_sales.created_at', Carbon::today())
                ->first();

        //***************************** Restaurant *****************************//

            //monthly restaurant onside sales
            //https://stackoverflow.com/questions/45565434/laravel-join-two-table
            // $restaurantSalesMonth = DB::table('restaurant_food_orders')
            // ->select(DB::raw('SUM(restaurant_food_orders.grand_total) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
            // ->whereYear('restaurant_food_orders.created_at', Carbon::now()->year)
            // ->groupBy('month_name')
            // ->orderBy('month_name', 'DESC')
            // ->get();
            // //monthly restaurant online sales
            // $restaurantOnlineSalesMonth = DB::table('restaurant_online_food_orders')
            //     ->select(DB::raw('SUM(restaurant_online_food_orders.total_paid) as totalSaleAmount'), DB::raw("MONTHNAME(delivery_date) as month_name"))
            //     ->whereYear('restaurant_online_food_orders.delivery_date', Carbon::now()->year)
            //     ->whereIn('restaurant_online_food_orders.status', [5, 6])
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            // //sum online sales and offline sales by month
            // foreach ($restaurantSalesMonth as $restaurant) {
            //     foreach ($restaurantOnlineSalesMonth as $restaurantOnline) {
            //         if ($restaurant->month_name == $restaurantOnline->month_name) {
            //             $restaurant->totalSaleAmount += $restaurantOnline->totalSaleAmount;
            //         }
            //     }
            // }

            // //if there are no record in a month, add 0 value
            // $resArr = [];
            // for ($i = 0; $i < Carbon::now()->month; $i++) {
            //     if (!empty($restaurantSalesMonth)) {
            //         $resArr[$i]['totalSaleAmount'] =0;
            //         foreach ($restaurantSalesMonth as $item){
            //             if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
            //                 $resArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
            //             }
            //         }
            //     } else {
            //         $resArr[$i]['totalSaleAmount'] = 0;
            //     }
            //     $resArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            // }
            // $restaurantSalesMonth = collect(array_values($resArr));

            // //weekly restaurant onside sales
            // $restaurantSalesWeek = DB::table('restaurant_food_orders')
            //     ->select(DB::raw('SUM(restaurant_food_orders.grand_total) as totalSaleAmount'))
            //     ->whereBetween('restaurant_food_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //     ->first();
            // //weekly restaurant online sales
            // $restaurantOnlineSalesWeek = DB::table('restaurant_online_food_orders')
            //     ->select(DB::raw('SUM(restaurant_online_food_orders.total_paid) as totalSaleAmount'))
            //     ->whereBetween('restaurant_online_food_orders.delivery_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //     ->whereIn('restaurant_online_food_orders.status', [5, 6])
            //     ->first();
            // //sum online sales and offline sales by week 
            // $restaurantSalesWeek->totalSaleAmount += $restaurantOnlineSalesWeek->totalSaleAmount;

            // //today restaurant onside sales
            // $restaurantSalesToday = DB::table('restaurant_food_orders')
            //     ->select(DB::raw('SUM(restaurant_food_orders.grand_total) as totalSaleAmount'))
            //     ->whereDate('restaurant_food_orders.created_at', Carbon::today())
            //     ->first();
            // //today restaurant online sales
            // $restaurantOnlineSalesToday = DB::table('restaurant_online_food_orders')
            //     ->select(DB::raw('SUM(restaurant_online_food_orders.total_paid) as totalSaleAmount'))
            //     ->whereDate('restaurant_online_food_orders.delivery_date', Carbon::today())
            //     ->whereIn('restaurant_online_food_orders.status', [5, 6])
            //     ->first();
            // //sum online sales and offline sales by today
            // $restaurantSalesToday->totalSaleAmount += $restaurantOnlineSalesToday->totalSaleAmount;


        //***************************** Reecha Main *****************************//
            //monthly reecha main sales
            // $reechaMainSalesMonth = DB::table('main_orders')
            //     ->select(DB::raw('SUM(main_orders.total_amount) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
            //     ->whereYear('main_orders.created_at', Carbon::now()->year)
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            // //if there are no record in a month, add 0 value
            // $mainArr = [];
            // for ($i = 0; $i < Carbon::now()->month; $i++) {
            //     if (!empty($reechaMainSalesMonth)) {
            //         $mainArr[$i]['totalSaleAmount'] =0;
            //         foreach ($reechaMainSalesMonth as $item){
            //             if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
            //                 $mainArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
            //             }
            //         }
            //     } else {
            //         $mainArr[$i]['totalSaleAmount'] = 0;
            //     }
            //     $mainArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            // }
            // $reechaMainSalesMonth = collect(array_values($mainArr));

            // //weekly reecha main sales
            // $reechaMainSalesWeek = DB::table('main_orders')
            //     ->select(DB::raw('SUM(main_orders.total_amount) as totalSaleAmount'))
            //     ->whereBetween('main_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //     ->first();

            // //today reecha main sales
            // $reechaMainSalesToday = DB::table('main_orders')
            //     ->select(DB::raw('SUM(main_orders.total_amount) as totalSaleAmount'))
            //     ->whereDate('main_orders.created_at', Carbon::today())
            //     ->first();

        //***************************** Hotel *****************************//
            //monthly hotel sales
            // $hotelSalesMonth = DB::table('holel_bookings')
            //     ->select(DB::raw('SUM(holel_bookings.income) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
            //     ->whereYear('holel_bookings.created_at', Carbon::now()->year)
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            // //if there are no record in a month, add 0 value
            // $hotelArr = [];
            // for ($i = 0; $i < Carbon::now()->month; $i++) {
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

            // //weekly hotel sales
            // $hotelSalesWeek = DB::table('holel_bookings')
            //     ->select(DB::raw('SUM(holel_bookings.income) as totalSaleAmount'))
            //     ->whereBetween('holel_bookings.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //     ->first();

            // //today hotel sales
            // $hotelSalesToday = DB::table('holel_bookings')
            //     ->select(DB::raw('SUM(holel_bookings.income) as totalSaleAmount'))
            //     ->whereDate('holel_bookings.created_at', Carbon::today())
            //     ->first();


        //***************************** Farm *****************************//
            //monthly farm onside sales
            // $farmSalesMonth = DB::table('farm_orders_food_orders')
            //     ->select(DB::raw('SUM(farm_orders_food_orders.grand_total) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
            //     ->whereYear('farm_orders_food_orders.created_at', Carbon::now()->year)
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            // //monthly farm online sales
            // $farmOnlineSalesMonth = DB::table('farm_orders_online_food_orders')
            //     ->select(DB::raw('SUM(farm_orders_online_food_orders.total_paid) as totalSaleAmount'), DB::raw("MONTHNAME(delivery_date) as month_name"))
            //     ->whereYear('farm_orders_online_food_orders.delivery_date', Carbon::now()->year)
            //     ->whereIn('farm_orders_online_food_orders.status', [5, 6])
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            // //sum online sales and offline sales by month
            // foreach ($farmSalesMonth as $farm) {
            //     foreach ($farmOnlineSalesMonth as $farmOnline) {
            //         if ($farm->month_name == $farmOnline->month_name) {
            //             $farm->totalSaleAmount += $farmOnline->totalSaleAmount;
            //         }
            //     }
            // }

            // //if there are no record in a month, add 0 value
            // $farmArr = [];
            // for ($i = 0; $i < Carbon::now()->month; $i++) {
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

            // //weekly farm onside sales
            // $farmSalesWeek = DB::table('farm_orders_food_orders')
            //     ->select(DB::raw('SUM(farm_orders_food_orders.grand_total) as totalSaleAmount'))
            //     ->whereBetween('farm_orders_food_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //     ->first();
            // //weekly farm online sales
            // $farmOnlineSalesWeek = DB::table('farm_orders_online_food_orders')
            //     ->select(DB::raw('SUM(farm_orders_online_food_orders.total_paid) as totalSaleAmount'))
            //     ->whereBetween('farm_orders_online_food_orders.delivery_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //     ->whereIn('farm_orders_online_food_orders.status', [5, 6])
            //     ->first();
            // //sum online sales and offline sales by week 
            // $farmSalesWeek->totalSaleAmount += $farmOnlineSalesWeek->totalSaleAmount;

            // //today farm onside sales
            // $farmSalesToday = DB::table('farm_orders_food_orders')
            //     ->select(DB::raw('SUM(farm_orders_food_orders.grand_total) as totalSaleAmount'))
            //     ->whereDate('farm_orders_food_orders.created_at', Carbon::today())
            //     ->first();
            // //today farm online sales
            // $farmOnlineSalesToday = DB::table('farm_orders_online_food_orders')
            //     ->select(DB::raw('SUM(farm_orders_online_food_orders.total_paid) as totalSaleAmount'))
            //     ->whereDate('farm_orders_online_food_orders.delivery_date', Carbon::today())
            //     ->whereIn('farm_orders_online_food_orders.status', [5, 6])
            //     ->first();
            // //sum online sales and offline sales by today
            // $farmSalesToday->totalSaleAmount += $farmOnlineSalesToday->totalSaleAmount;
        
        //***************************** Bank *****************************//
            //monthly bank sales
        //     $bankSalesMonth = DB::table('account_bank_balances')
        //         ->select(DB::raw('SUM(account_bank_balances.credit) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
        //         ->whereYear('account_bank_balances.date', Carbon::now()->year)
        //         ->groupBy('month_name')
        //         ->orderBy('month_name', 'DESC')
        //         ->get();

        //     //if there are no record in a month, add 0 value
        //     $bankArr = [];
        //     for ($i = 0; $i < Carbon::now()->month; $i++) {
        //         if (!empty($bankSalesMonth)) {
        //             $bankArr[$i]['totalSaleAmount'] =0;
        //             foreach ($bankSalesMonth as $item){
        //                 if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
        //                     $bankArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
        //                 }
        //             }
        //         } else {
        //             $bankArr[$i]['totalSaleAmount'] = 0;
        //         }
        //         $bankArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
        //     }
        //     $bankSalesMonth = collect(array_values($bankArr));

        //     //weekly bank sales
        //     $bankSalesWeek = DB::table('account_bank_balances')
        //         ->select(DB::raw('SUM(account_bank_balances.credit) as totalSaleAmount'))
        //         ->whereBetween('account_bank_balances.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        //         ->first();

        //     //today bank sales
        //     $bankSalesToday = DB::table('account_bank_balances')
        //         ->select(DB::raw('SUM(account_bank_balances.credit) as totalSaleAmount'))
        //         ->whereDate('account_bank_balances.date', Carbon::today())
        //         ->first();

        // //***************************** other income *****************************//
        //     //monthly other sales
        //     $otherSalesMonth = DB::table('accounts_other_incomes')
        //         ->select(DB::raw('SUM(accounts_other_incomes.amount) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
        //         ->whereYear('accounts_other_incomes.date', Carbon::now()->year)
        //         ->groupBy('month_name')
        //         ->orderBy('month_name', 'DESC')
        //         ->get();

        //     //if there are no record in a month, add 0 value
        //     $otherArr = [];
        //     for ($i = 0; $i < Carbon::now()->month; $i++) {
        //         if (!empty($otherSalesMonth)) {
        //             $otherArr[$i]['totalSaleAmount'] =0;
        //             foreach ($otherSalesMonth as $item){
        //                 if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
        //                     $otherArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
        //                 }
        //             }
        //         } else {
        //             $otherArr[$i]['totalSaleAmount'] = 0;
        //         }
        //         $otherArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
        //     }
        //     $otherSalesMonth = collect(array_values($otherArr));

        //     //weekly other sales
        //     $otherSalesWeek = DB::table('accounts_other_incomes')
        //         ->select(DB::raw('SUM(accounts_other_incomes.amount) as totalSaleAmount'))
        //         ->whereBetween('accounts_other_incomes.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        //         ->first();

        //     //today other sales
        //     $otherSalesToday = DB::table('accounts_other_incomes')
        //         ->select(DB::raw('SUM(accounts_other_incomes.amount) as totalSaleAmount'))
        //         ->whereDate('accounts_other_incomes.date', Carbon::today())
        //         ->first();

        //***************************** Total Income *****************************//
            $totalSalesMonth=[];
                for($i=0; $i< Carbon::now()->month; $i++){
                        $totalSalesMonth[$i]['totalSaleAmount'] = $foodCitySalesMonth[$i]['totalSaleAmount'];
                        $totalSalesMonth[$i]['month_name'] = $foodCitySalesMonth[$i]['month_name'];
                }
                
            $totalSalesMonth = collect(array_values($totalSalesMonth));


        //***************************** Calculate sale percentage to pie chart *****************************//
            $foodcityAnualIncome = $foodCitySalesMonth->sum('totalSaleAmount');  
            // $restaurantAnualIncome = $restaurantSalesMonth->sum('totalSaleAmount');
            // $reechaMainAnualIncome = $reechaMainSalesMonth->sum('totalSaleAmount');
            // $hotelAnualIncome = $hotelSalesMonth->sum('totalSaleAmount');
            // $farmAnualIncome = $farmSalesMonth->sum('totalSaleAmount');
            // $bankAnualIncome = $bankSalesMonth->sum('totalSaleAmount');
            // $otherAnualIncome = $bankSalesMonth->sum('totalSaleAmount');

            $totalAnualIncome = [number_format($foodcityAnualIncome,2,'.', '')];
            //$totalAnualIncome = [1,57,2,10,15,5,10];
            //curent year
            $yearIncome = now()->format('Y');
            

        return view('accountspages.income', compact(
            'foodCitySalesMonth',
            'foodCitySalesWeek',
            'foodCitySalesToday',
            
        ));
    }

    //All Income view filter by year page
    public function indexFilter(Request $request)
    {
        $yearIncome = $request->year;
        $currentYear = now()->format('Y');

        //***************************** FoodCity *****************************//
            //monthly foodcity sales
            $foodCitySalesMonth = DB::table('foodcity_sales')
                ->select(DB::raw('SUM(foodcity_sales.amount) - SUM(foodcity_sales.discount_amount) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
                ->where('is_cancelled', '=', 0)
                ->whereYear('foodcity_sales.created_at', $yearIncome)
                ->groupBy('month_name')
                ->orderBy('month_name', 'DESC')
                ->get();

            //if there are no record in a month, add 0 value
            $foodcityArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
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

            //weekly foodcity sales
            if($currentYear == $yearIncome){
                $foodCitySalesWeek = DB::table('foodcity_sales')
                    ->select(DB::raw('SUM(foodcity_sales.amount) - SUM(foodcity_sales.discount_amount) as totalSaleAmount'))
                    ->where('is_cancelled', '=', 0)
                    ->whereBetween('foodcity_sales.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->first();
            }else{
                $foodCitySalesWeek = (object)['totalSaleAmount'=>'0'];
            }
            //today foodcity sales
            if($currentYear == $yearIncome){
                $foodCitySalesToday = DB::table('foodcity_sales')
                    ->select(DB::raw('SUM(foodcity_sales.amount) - SUM(foodcity_sales.discount_amount) as totalSaleAmount'))
                    ->where('is_cancelled', '=', 0)
                    ->whereDate('foodcity_sales.created_at', Carbon::today())
                    ->first();
            }else{
                $foodCitySalesToday = (object)['totalSaleAmount'=>'0'];
            }

        //***************************** Restaurant *****************************//
            //monthly restaurant onside sales
            // $restaurantSalesMonth = DB::table('restaurant_food_orders')
            //     ->select(DB::raw('SUM(restaurant_food_orders.grand_total) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
            //     ->whereYear('restaurant_food_orders.created_at', $yearIncome)
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            //monthly restaurant online sales
            // $restaurantOnlineSalesMonth = DB::table('restaurant_online_food_orders')
            //     ->select(DB::raw('SUM(restaurant_online_food_orders.total_paid) as totalSaleAmount'), DB::raw("MONTHNAME(delivery_date) as month_name"))
            //     ->whereYear('restaurant_online_food_orders.delivery_date', $yearIncome)
            //     ->whereIn('restaurant_online_food_orders.status', [5, 6])
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            //sum online sales and offline sales by month
            // foreach ($restaurantSalesMonth as $restaurant) {
            //     foreach ($restaurantOnlineSalesMonth as $restaurantOnline) {
            //         if ($restaurant->month_name == $restaurantOnline->month_name) {
            //             $restaurant->totalSaleAmount += $restaurantOnline->totalSaleAmount;
            //         }
            //     }
            // }

            //if there are no record in a month, add 0 value
            // $resArr = [];
            // for ($i = 0; $i < Carbon::now()->month; $i++) {
            //     if (!empty($restaurantSalesMonth)) {
            //         $resArr[$i]['totalSaleAmount'] =0;
            //         foreach ($restaurantSalesMonth as $item){
            //             if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
            //                 $resArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
            //             }
            //         }
            //     } else {
            //         $resArr[$i]['totalSaleAmount'] = 0;
            //     }
            //     $resArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            // }
            // $restaurantSalesMonth = collect(array_values($resArr));

            // if($currentYear == $yearIncome){
            //     //weekly restaurant onside sales
            //     $restaurantSalesWeek = DB::table('restaurant_food_orders')
            //         ->select(DB::raw('SUM(restaurant_food_orders.grand_total) as totalSaleAmount'))
            //         ->whereBetween('restaurant_food_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //         ->first();
            //     //weekly restaurant online sales
            //     $restaurantOnlineSalesWeek = DB::table('restaurant_online_food_orders')
            //         ->select(DB::raw('SUM(restaurant_online_food_orders.total_paid) as totalSaleAmount'))
            //         ->whereBetween('restaurant_online_food_orders.delivery_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //         ->whereIn('restaurant_online_food_orders.status', [5, 6])
            //         ->first();
            // }else{
            //     $restaurantSalesWeek = (object)['totalSaleAmount'=>'0'];
            //     $restaurantOnlineSalesWeek = (object)['totalSaleAmount'=>'0'];
            // }
            // //sum online sales and offline sales by week 
            // $restaurantSalesWeek->totalSaleAmount += $restaurantOnlineSalesWeek->totalSaleAmount;

            // if($currentYear == $yearIncome){
            //     //today restaurant onside sales
            //     $restaurantSalesToday = DB::table('restaurant_food_orders')
            //         ->select(DB::raw('SUM(restaurant_food_orders.grand_total) as totalSaleAmount'))
            //         ->whereDate('restaurant_food_orders.created_at', Carbon::today())
            //         ->first();
            //     //today restaurant online sales
            //     $restaurantOnlineSalesToday = DB::table('restaurant_online_food_orders')
            //         ->select(DB::raw('SUM(restaurant_online_food_orders.total_paid) as totalSaleAmount'))
            //         ->whereDate('restaurant_online_food_orders.delivery_date', Carbon::today())
            //         ->whereIn('restaurant_online_food_orders.status', [5, 6])
            //         ->first();
            // }else{
            //     $restaurantSalesToday = (object)['totalSaleAmount'=>'0'];
            //     $restaurantOnlineSalesToday = (object)['totalSaleAmount'=>'0'];
            // }
            // //sum online sales and offline sales by today
            // $restaurantSalesToday->totalSaleAmount += $restaurantOnlineSalesToday->totalSaleAmount;


        //***************************** Reecha Main *****************************//
            //monthly reecha main sales
            // $reechaMainSalesMonth = DB::table('main_orders')
            //     ->select(DB::raw('SUM(main_orders.total_amount) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
            //     ->whereYear('main_orders.created_at', $yearIncome)
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            //if there are no record in a month, add 0 value
            // $mainArr = [];
            // for ($i = 0; $i < Carbon::now()->month; $i++) {
            //     if (!empty($reechaMainSalesMonth)) {
            //         $mainArr[$i]['totalSaleAmount'] =0;
            //         foreach ($reechaMainSalesMonth as $item){
            //             if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
            //                 $mainArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
            //             }
            //         }
            //     } else {
            //         $mainArr[$i]['totalSaleAmount'] = 0;
            //     }
            //     $mainArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            // }
            // $reechaMainSalesMonth = collect(array_values($mainArr));

            // if($currentYear == $yearIncome){
            //     //weekly reecha main sales
            //     $reechaMainSalesWeek = DB::table('main_orders')
            //         ->select(DB::raw('SUM(main_orders.total_amount) as totalSaleAmount'))
            //         ->whereBetween('main_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //         ->first();

            //     //today reecha main sales
            //     $reechaMainSalesToday = DB::table('main_orders')
            //         ->select(DB::raw('SUM(main_orders.total_amount) as totalSaleAmount'))
            //         ->whereDate('main_orders.created_at', Carbon::today())
            //         ->first();
            // }else{
            //     $reechaMainSalesWeek = (object)['totalSaleAmount'=>'0'];
            //     $reechaMainSalesToday = (object)['totalSaleAmount'=>'0'];
            // }
        //***************************** Hotel *****************************//
            //monthly hotel sales
            // $hotelSalesMonth = DB::table('holel_bookings')
            //     ->select(DB::raw('SUM(holel_bookings.income) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
            //     ->whereYear('holel_bookings.created_at', $yearIncome)
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            // //if there are no record in a month, add 0 value
            // $hotelArr = [];
            // for ($i = 0; $i < Carbon::now()->month; $i++) {
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

            // if($currentYear == $yearIncome){
            // //weekly hotel sales
            // $hotelSalesWeek = DB::table('holel_bookings')
            //     ->select(DB::raw('SUM(holel_bookings.income) as totalSaleAmount'))
            //     ->whereBetween('holel_bookings.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //     ->first();

            // //today hotel sales
            // $hotelSalesToday = DB::table('holel_bookings')
            //     ->select(DB::raw('SUM(holel_bookings.income) as totalSaleAmount'))
            //     ->whereDate('holel_bookings.created_at', Carbon::today())
            //     ->first();
            // }else{
            //     $hotelSalesWeek = (object)['totalSaleAmount'=>'0'];
            //     $hotelSalesToday = (object)['totalSaleAmount'=>'0'];
            // }

        //***************************** Farm *****************************//
            //monthly farm onside sales
            // $farmSalesMonth = DB::table('farm_orders_food_orders')
            //     ->select(DB::raw('SUM(farm_orders_food_orders.grand_total) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
            //     ->whereYear('farm_orders_food_orders.created_at', $yearIncome)
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            // //monthly farm online sales
            // $farmOnlineSalesMonth = DB::table('farm_orders_online_food_orders')
            //     ->select(DB::raw('SUM(farm_orders_online_food_orders.total_paid) as totalSaleAmount'), DB::raw("MONTHNAME(delivery_date) as month_name"))
            //     ->whereYear('farm_orders_online_food_orders.delivery_date', $yearIncome)
            //     ->whereIn('farm_orders_online_food_orders.status', [5, 6])
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();
            // //sum online sales and offline sales by month
            // foreach ($farmSalesMonth as $farm) {
            //     foreach ($farmOnlineSalesMonth as $farmOnline) {
            //         if ($farm->month_name == $farmOnline->month_name) {
            //             $farm->totalSaleAmount += $farmOnline->totalSaleAmount;
            //         }
            //     }
            // }

            // //if there are no record in a month, add 0 value
            // $farmArr = [];
            // for ($i = 0; $i < Carbon::now()->month; $i++) {
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

            // if($currentYear == $yearIncome){
            //     //weekly farm onside sales
            //     $farmSalesWeek = DB::table('farm_orders_food_orders')
            //         ->select(DB::raw('SUM(farm_orders_food_orders.grand_total) as totalSaleAmount'))
            //         ->whereBetween('farm_orders_food_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //         ->first();
            //     //weekly farm online sales
            //     $farmOnlineSalesWeek = DB::table('farm_orders_online_food_orders')
            //         ->select(DB::raw('SUM(farm_orders_online_food_orders.total_paid) as totalSaleAmount'))
            //         ->whereBetween('farm_orders_online_food_orders.delivery_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //         ->whereIn('farm_orders_online_food_orders.status', [5, 6])
            //         ->first();
            //     //sum online sales and offline sales by week 
            // }else{
            //     $farmSalesWeek = (object)['totalSaleAmount'=>'0'];
            //     $farmOnlineSalesWeek = (object)['totalSaleAmount'=>'0'];
            // }
            // $farmSalesWeek->totalSaleAmount += $farmOnlineSalesWeek->totalSaleAmount;

            // if($currentYear == $yearIncome){
            //     //today farm onside sales
            //     $farmSalesToday = DB::table('farm_orders_food_orders')
            //         ->select(DB::raw('SUM(farm_orders_food_orders.grand_total) as totalSaleAmount'))
            //         ->whereDate('farm_orders_food_orders.created_at', Carbon::today())
            //         ->first();
            //     //today farm online sales
            //     $farmOnlineSalesToday = DB::table('farm_orders_online_food_orders')
            //         ->select(DB::raw('SUM(farm_orders_online_food_orders.total_paid) as totalSaleAmount'))
            //         ->whereDate('farm_orders_online_food_orders.delivery_date', Carbon::today())
            //         ->whereIn('farm_orders_online_food_orders.status', [5, 6])
            //         ->first();
            // }else{
            //     $farmSalesToday = (object)['totalSaleAmount'=>'0'];
            //     $farmOnlineSalesToday = (object)['totalSaleAmount'=>'0'];
            // }
            // //sum online sales and offline sales by today
            // $farmSalesToday->totalSaleAmount += $farmOnlineSalesToday->totalSaleAmount;
        
        //***************************** Bank *****************************//
            //monthly bank sales
            // $bankSalesMonth = DB::table('account_bank_balances')
            //     ->select(DB::raw('SUM(account_bank_balances.credit) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
            //     ->whereYear('account_bank_balances.date', $yearIncome)
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();

            // //if there are no record in a month, add 0 value
            // $bankArr = [];
            // for ($i = 0; $i < Carbon::now()->month; $i++) {
            //     if (!empty($bankSalesMonth)) {
            //         $bankArr[$i]['totalSaleAmount'] =0;
            //         foreach ($bankSalesMonth as $item){
            //             if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
            //                 $bankArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
            //             }
            //         }
            //     } else {
            //         $bankArr[$i]['totalSaleAmount'] = 0;
            //     }
            //     $bankArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            // }
            // $bankSalesMonth = collect(array_values($bankArr));

            // if($currentYear == $yearIncome){
            //     //weekly bank sales
            //     $bankSalesWeek = DB::table('account_bank_balances')
            //         ->select(DB::raw('SUM(account_bank_balances.credit) as totalSaleAmount'))
            //         ->whereBetween('account_bank_balances.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //         ->first();

            //     //today bank sales
            //     $bankSalesToday = DB::table('account_bank_balances')
            //         ->select(DB::raw('SUM(account_bank_balances.credit) as totalSaleAmount'))
            //         ->whereDate('account_bank_balances.date', Carbon::today())
            //         ->first();
            // }else{
            //     $bankSalesWeek = (object)['totalSaleAmount'=>'0'];
            //     $bankSalesToday = (object)['totalSaleAmount'=>'0'];
            // }

        //***************************** other income *****************************//
            //monthly other sales
            // $otherSalesMonth = DB::table('accounts_other_incomes')
            //     ->select(DB::raw('SUM(accounts_other_incomes.amount) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
            //     ->whereYear('accounts_other_incomes.date', $yearIncome)
            //     ->groupBy('month_name')
            //     ->orderBy('month_name', 'DESC')
            //     ->get();

            // //if there are no record in a month, add 0 value
            // $otherArr = [];
            // for ($i = 0; $i < Carbon::now()->month; $i++) {
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

            // if($currentYear == $yearIncome){
            //     //weekly other sales
            //     $otherSalesWeek = DB::table('accounts_other_incomes')
            //         ->select(DB::raw('SUM(accounts_other_incomes.amount) as totalSaleAmount'))
            //         ->whereBetween('accounts_other_incomes.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            //         ->first();

            //     //today other sales
            //     $otherSalesToday = DB::table('accounts_other_incomes')
            //         ->select(DB::raw('SUM(accounts_other_incomes.amount) as totalSaleAmount'))
            //         ->whereDate('accounts_other_incomes.date', Carbon::today())
            //         ->first();
            // }else{
            //     $otherSalesWeek = (object)['totalSaleAmount'=>'0'];
            //     $otherSalesToday = (object)['totalSaleAmount'=>'0'];
            // }

        //***************************** Total Income *****************************//
            $totalSalesMonth=[];
                for($i=0; $i< Carbon::now()->month; $i++){
                        $totalSalesMonth[$i]['totalSaleAmount'] = $foodCitySalesMonth[$i]['totalSaleAmount'] ;
                        $totalSalesMonth[$i]['month_name'] = $foodCitySalesMonth[$i]['month_name'];
                }
                
            $totalSalesMonth = collect(array_values($totalSalesMonth));


        //***************************** Calculate sale percentage to pie chart *****************************//
            $foodcityAnualIncome = $foodCitySalesMonth->sum('totalSaleAmount');  

            $totalAnualIncome = [number_format($foodcityAnualIncome,2,'.', '') ];


        return view('accountspages.income', compact(
            'foodCitySalesMonth',
            'foodCitySalesWeek',
            'foodCitySalesToday',
            'totalSalesMonth',
            'totalAnualIncome',
            'yearIncome',
            'bankSalesMonth',
            'bankSalesWeek',
            'bankSalesToday',
            'otherSalesMonth',
            'otherSalesWeek',
            'otherSalesToday',
        ));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function otherIncomeStore(Request $request)
    {

        $request-> validate([

            'employee_id'=> 'required',
            'date'=> 'required',
            'amount'=>'required',
            'reason'=>'required',
            'description'=>'required',

        ]);

        $other_incomes= new Income();
        $other_incomes->employee_id=$request->employee_id;
        $other_incomes->date=$request->date;
        $other_incomes->amount=$request->amount;
        $other_incomes->reason=$request->reason;
        $other_incomes->description=$request->description;
        $other_incomes->save();    

        return redirect('accountspages.OtherIncome')->with('success','Record Successfully');

    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function otherIncomeShow()
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function otherIncomeEdit($id)
    {
     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function otherIncomeUpdate(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        //
    }
}
