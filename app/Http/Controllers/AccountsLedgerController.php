<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AccountsLedgerController extends Controller
{
    public function salesDetailsShowall(Request $request)
    {
        $month = $request->month;

       
        
        // restaurent food orders cash calculations
        $restaurant_food_orders_cash = DB::table('restaurant_food_orders')
            ->select('cash', 'cash_balance');
        if ($month != '') {
            $restaurant_food_orders_cash = $restaurant_food_orders_cash->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $restaurant_food_orders_cash = $restaurant_food_orders_cash->get();

        $restaurant_food_orders_cash_total = 0;
        foreach ($restaurant_food_orders_cash as $value) {
            $value->cash = $value->cash - $value->cash_balance;
            $restaurant_food_orders_cash_total += $value->cash;
        }

        // restaurant_online_food_orders_cash_total calculations 
        $restaurant_online_food_orders_cash = DB::table('restaurant_online_food_orders')
            ->select('total_paid')
            ->where('restaurant_online_food_orders.payment_mode', '=', "CASH ON");
        if ($month != '') {
            $restaurant_online_food_orders_cash = $restaurant_online_food_orders_cash->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $restaurant_online_food_orders_cash = $restaurant_online_food_orders_cash->get();

        $restaurant_online_food_orders_cash_total = 0;
        foreach ($restaurant_online_food_orders_cash as $value) {
            $restaurant_online_food_orders_cash_total += $value->total_paid;
        }

        // hotel booking cash calculations
        $hotel_booking_cash = DB::table('holel_bookings')
            ->select('income')
            ->whereIn('payment_method_id', [1, 2]);
        if ($month != '') {
            $hotel_booking_cash = $hotel_booking_cash->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $hotel_booking_cash = $hotel_booking_cash->get();

        $hotel_booking_cash_total = 0;
        foreach ($hotel_booking_cash as $value) {
            $hotel_booking_cash_total += $value->income;
        }

        // farm online food orders cash calculations
        $farm_online_food_order_cash = DB::table('farm_orders_online_food_orders')
            ->select('total_paid');
        if ($month != '') {
            $farm_online_food_order_cash = $farm_online_food_order_cash->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $farm_online_food_order_cash = $farm_online_food_order_cash->get();

        $farm_online_food_order_cash_total = 0;
        foreach ($farm_online_food_order_cash as $value) {
            $farm_online_food_order_cash_total += $value->total_paid;
        }

        //foodcity_cash
        $foodcity_cash = DB::table('foodcity_sales')
            ->select('foodcity_sales.cash_payment as cash');
        if ($month != '') {
            $foodcity_cash = $foodcity_cash->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $foodcity_cash = $foodcity_cash->get();

        $foodcity_cash_total = 0;
        foreach ($foodcity_cash as $value) {
            $foodcity_cash_total += $value->cash;
        }

        //foodcity_cheque
        $foodcity_cheque = DB::table('foodcity_sales')
            ->select('foodcity_sales.cheque_payment as cheque');
        if ($month != '') {
            $foodcity_cheque = $foodcity_cheque->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $foodcity_cheque = $foodcity_cheque->get();

        $foodcity_cheque_total = 0;
        foreach ($foodcity_cheque as $value) {
            $foodcity_cheque_total += $value->cheque;
        }

        //Main_Orders
        $main_orders_cash = DB::table('main_orders')
            ->select('total_amount');
        if ($month != '') {
            $main_orders_cash = $main_orders_cash->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $main_orders_cash = $main_orders_cash->get();
        $main_orders_cash_total = 0;
        foreach ($main_orders_cash as $value) {
            $main_orders_cash_total += $value->total_amount;
        }

        //farm_orders_food_orders cash
        $farm_orders_cash = DB::table('farm_orders_food_orders')
            ->select('cash', 'cash_balance');
        if ($month != '') {
            $farm_orders_cash = $farm_orders_cash->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $farm_orders_cash = $farm_orders_cash->get();
        $farm_orders_cash_total = 0;
        foreach ($farm_orders_cash as $value) {
            $farm_orders_cash_total += $value->cash_total;
        }

        $cash_total = $foodcity_cash_total + $foodcity_cheque_total + $restaurant_food_orders_cash_total + $restaurant_online_food_orders_cash_total + $main_orders_cash_total + $hotel_booking_cash_total + $farm_orders_cash_total + $farm_online_food_order_cash_total;
        $cash = [
            "Foodcity Sales" => $foodcity_cash_total + $foodcity_cheque_total,
            "Restaurant Food Orders" => $restaurant_food_orders_cash_total,
            "Restaurant Online Food Orders" => $restaurant_online_food_orders_cash_total,
            "Main Orders" => $main_orders_cash_total,
            "Hotel Booking" => $hotel_booking_cash_total,
            "Farm Orders Food Orders" => $farm_orders_cash_total,
            "Farm Orders Online Food Orders" => $farm_online_food_order_cash_total
        ];

       
    


        // hotel booking online calculations
        $hotel_booking_online = DB::table('holel_bookings')
            ->select('income')
            ->whereIn('payment_method_id', [5, 6]);
        if ($month != '') {
            $hotel_booking_online = $hotel_booking_online->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $hotel_booking_online = $hotel_booking_online->get();

        $hotel_booking_online_total = 0;
        foreach ($hotel_booking_online as $value) {
            $hotel_booking_online_total += $value->income;
        }

        $online_total = $hotel_booking_online_total;
        $online = [
            "Foodcity Sales" => 0,
            "Restaurant Food Orders" => 0,
            "Restaurant Online Food Orders" => 0,
            "Main Orders" => 0,
            "Hotel Booking" => $hotel_booking_online_total,
            "Farm Orders Food Orders" => 0,
            "Farm Orders Online Food Orders" => 0
        ];
        ////////////////online start////////////////

        // restaurent food orders card calculations
        $restaurant_food_orders_card = DB::table('restaurant_food_orders')
            ->select('card', 'card_balance');
        if ($month != '') {
            $restaurant_food_orders_card = $restaurant_food_orders_card->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $restaurant_food_orders_card = $restaurant_food_orders_card->get();

        $restaurant_food_orders_card_total = 0;
        foreach ($restaurant_food_orders_card as $value) {
            $value->card = $value->card - $value->card_balance;
            $restaurant_food_orders_card_total += $value->card;
        }

        // hotel booking card calculations
        $hotel_booking_card = DB::table('holel_bookings')
            ->select('income')
            ->whereIn('payment_method_id', [3, 4]);
        if ($month != '') {
            $hotel_booking_card = $hotel_booking_card->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $hotel_booking_card = $hotel_booking_card->get();

        $hotel_booking_card_total = 0;
        foreach ($hotel_booking_card as $value) {
            $hotel_booking_card_total += $value->income;
        }

        //foodcity_card
        $foodcity_card = DB::table('foodcity_sales')
            ->select('foodcity_sales.card_payment as card');
        if ($month != '') {
            $foodcity_card = $foodcity_card->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $foodcity_card = $foodcity_card->get();
        $foodcity_card_total = 0;
        foreach ($foodcity_card as $value) {
            $foodcity_card_total += $value->card;
        }

        //farm_orders_food_orders card
        $farm_orders_card = DB::table('farm_orders_food_orders')
            ->select('card', 'card_balance');
        if ($month != '') {
            $farm_orders_card = $farm_orders_card->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
        }
        $farm_orders_card = $farm_orders_card->get();

        $farm_orders_card_total = 0;
        foreach ($farm_orders_card as $value) {
            $farm_orders_card_total += $value->card_total;
        }

        $card_total = $foodcity_card_total + $restaurant_food_orders_card_total + $farm_orders_card_total + $hotel_booking_card_total;
        $card = [
            "Foodcity Sales" => $foodcity_card_total,
            "Restaurant Food Orders" => $restaurant_food_orders_card_total,
            "Restaurant Online Food Orders" => 0,
            "Main Orders" => 0,
            "Hotel Booking" => $hotel_booking_card_total,
            "Farm Orders Food Orders" => $farm_orders_card_total,
            "Farm Orders Online Food Orders" => 0
        ];

        ////////////////online end////////////////

        //foodcity_credit
        $foodcity_credit = DB::table('foodcity_sales')
            ->select('foodcity_sales.credit_payment as credit');
            if ($month != '') {
                $foodcity_credit = $foodcity_credit->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $month);
            }
            $foodcity_credit = $foodcity_credit->get();
        $foodcity_credit_total = 0;
        foreach ($foodcity_credit as $value) {
            $foodcity_credit_total += $value->credit;
        }

        $credit_total = $foodcity_credit_total;
        $credit = [
            "Foodcity Sales" => $foodcity_credit_total,
            "Restaurant Food Orders" => 0,
            "Restaurant Online Food Orders" => 0,
            "Main Orders" => 0,
            "Hotel Booking" => 0,
            "Farm Orders Food Orders" => 0,
            "Farm Orders Online Food Orders" => 0
        ];

        return view('accountspages.Ledgers.sales_ledgers', compact('cash', 'online', 'card', 'credit', 'cash_total', 'online_total', 'credit_total', 'card_total', 'month'));
    }

    public function chequeDetailsShowall()
    {
        // customers
        $foodcity_cheques = DB::table('foodcity_sales')
            ->join('customers', 'customers.id', '=', 'foodcity_sales.customer_id')
            ->where('cheque_payment', '!=', '0')
            ->select('foodcity_sales.invoice_no', 'foodcity_sales.cheque_payment', 'foodcity_sales.cheque_number', 'foodcity_sales.cheque_date', 'foodcity_sales.customer_id', 'customers.name', 'customers.phone_number')
            ->get();
        // return $foodcity_cheque;
        return view('accountspages.Ledgers.cheque_details', compact('foodcity_cheques'));
    }
}
