<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //***************************** Inventery *****************************//
            //monthly Inventery expense
            $inventeryExpenseMonth = DB::table('inventory_purchase_orders')
                ->select(DB::raw('SUM(inventory_purchase_orders.pur_ord_amount) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
                ->whereYear('inventory_purchase_orders.created_at', Carbon::now()->year)
                ->groupBy('month_name')
                ->orderBy('month_name', 'DESC')
                ->get();

            //if there are no record in a month, add 0 value
            $inventeryArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
                if (!empty($inventeryExpenseMonth)) {
                    $inventeryArr[$i]['totalSaleAmount'] =0;
                    foreach ($inventeryExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $inventeryArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                        }
                    }
                } else {
                    $inventeryArr[$i]['totalSaleAmount'] = 0;
                }
                $inventeryArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
            $inventeryExpenseMonth = collect(array_values($inventeryArr));

            //weekly Inventery expense
            $inventeryExpenseWeek = DB::table('inventory_purchase_orders')
                ->select(DB::raw('SUM(inventory_purchase_orders.pur_ord_amount) as totalSaleAmount'))
                ->whereBetween('inventory_purchase_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->first();

            //today Inventery expense
            $inventeryExpenseToday = DB::table('inventory_purchase_orders')
                ->select(DB::raw('SUM(inventory_purchase_orders.pur_ord_amount) as totalSaleAmount'))
                ->whereDate('inventory_purchase_orders.created_at', Carbon::today())
                ->first();
        //***************************** HR *****************************//
            //monthly HR expense
            $hrExpenseMonth = DB::table('hr_salary_payables')
                ->select(DB::raw('SUM(hr_salary_payables.net_salary) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
                ->whereYear('hr_salary_payables.date', Carbon::now()->year)
                ->groupBy('month_name')
                ->orderBy('month_name', 'DESC')
                ->get();

            //if there are no record in a month, add 0 value
            $hrArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
                if (!empty($hrExpenseMonth)) {
                    $hrArr[$i]['totalSaleAmount'] =0;
                    foreach ($hrExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $hrArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                        }
                    }
                } else {
                    $hrArr[$i]['totalSaleAmount'] = 0;
                }
                $hrArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
            $hrExpenseMonth = collect(array_values($hrArr));

            //weekly HR expense
            $hrExpenseWeek = DB::table('hr_salary_payables')
                ->select(DB::raw('SUM(hr_salary_payables.net_salary) as totalSaleAmount'))
                ->whereBetween('hr_salary_payables.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->first();

            //today HR expense
            $hrExpenseToday = DB::table('hr_salary_payables')
                ->select(DB::raw('SUM(hr_salary_payables.net_salary) as totalSaleAmount'))
                ->whereDate('hr_salary_payables.date', Carbon::today())
                ->first();
        //***************************** Other Expense *****************************//
            $otherExpenseMonth = DB::table('accounts_other_expenses')
            ->select(DB::raw('SUM(accounts_other_expenses.oth_exp_amount) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
            ->whereYear('accounts_other_expenses.date', Carbon::now()->year)
            ->groupBy('month_name')
            ->orderBy('month_name', 'DESC')
            ->get();

            //if there are no record in a month, add 0 value
            $otherArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
                if (!empty($otherExpenseMonth)) {
                    $otherArr[$i]['totalSaleAmount'] =0;
                    foreach ($otherExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $otherArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                        }
                    }
                } else {
                    $otherArr[$i]['totalSaleAmount'] = 0;
                }
                $otherArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
            $otherExpenseMonth = collect(array_values($otherArr));

            //weekly Other expense
            $otherExpenseWeek = DB::table('accounts_other_expenses')
                ->select(DB::raw('SUM(accounts_other_expenses.oth_exp_amount) as totalSaleAmount'))
                ->whereBetween('accounts_other_expenses.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->first();
        
            //today Other expense
            $otherExpenseToday = DB::table('accounts_other_expenses')
                ->select(DB::raw('SUM(accounts_other_expenses.oth_exp_amount) as totalSaleAmount'))
                ->whereDate('accounts_other_expenses.date', Carbon::today())
                ->first();

        //***************************** Bank Expense *****************************//
            $bankExpenseMonth = DB::table('account_bank_balances')
            ->select(DB::raw('SUM(account_bank_balances.debit) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
            ->whereYear('account_bank_balances.date', Carbon::now()->year)
            ->groupBy('month_name')
            ->orderBy('month_name', 'DESC')
            ->get();

            //if there are no record in a month, add 0 value
            $bankArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
                if (!empty($bankExpenseMonth)) {
                    $bankArr[$i]['totalSaleAmount'] =0;
                    foreach ($bankExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $bankArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                        }
                    }
                } else {
                    $bankArr[$i]['totalSaleAmount'] = 0;
                }
                $bankArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
            $bankExpenseMonth = collect(array_values($bankArr));

            //weekly Bank expense
            $bankExpenseWeek = DB::table('account_bank_balances')
                ->select(DB::raw('SUM(account_bank_balances.debit) as totalSaleAmount'))
                ->whereBetween('account_bank_balances.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->first();
        
            //today Bank expense
            $bankExpenseToday = DB::table('account_bank_balances')
                ->select(DB::raw('SUM(account_bank_balances.debit) as totalSaleAmount'))
                ->whereDate('account_bank_balances.date', Carbon::today())
                ->first();

        //***************************** Service Charge Expense *****************************//
            $serviceExpenseMonth = DB::table('accounts_service_expenses')
                ->select(DB::raw('SUM(accounts_service_expenses.amount) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
                ->whereYear('accounts_service_expenses.date', Carbon::now()->year)
                ->groupBy('month_name')
                ->orderBy('month_name', 'DESC')
                ->get();
    
            //if there are no record in a month, add 0 value
            $serviceArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
                if (!empty($serviceExpenseMonth)) {
                    $serviceArr[$i]['totalSaleAmount'] =0;
                    foreach ($serviceExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $serviceArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                        }
                    }
                } else {
                    $serviceArr[$i]['totalSaleAmount'] = 0;
                }
                $serviceArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
            $serviceExpenseMonth = collect(array_values($serviceArr));
    
            //weekly Service Charge expense
            $serviceExpenseWeek = DB::table('accounts_service_expenses')
                ->select(DB::raw('SUM(accounts_service_expenses.amount) as totalSaleAmount'))
                ->whereBetween('accounts_service_expenses.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->first();
        
            //today Service Charge expense
            $serviceExpenseToday = DB::table('accounts_service_expenses')
                ->select(DB::raw('SUM(accounts_service_expenses.amount) as totalSaleAmount'))
                ->whereDate('accounts_service_expenses.date', Carbon::today())
                ->first();

        //***************************** Total Expense *****************************//
            $totalExpenseMonth=[];
                for($i=0; $i< Carbon::now()->month; $i++){
                        $totalExpenseMonth[$i]['totalSaleAmount'] = $inventeryExpenseMonth[$i]['totalSaleAmount'] + $hrExpenseMonth[$i]['totalSaleAmount'] + $otherExpenseMonth[$i]['totalSaleAmount'] + $bankExpenseMonth[$i]['totalSaleAmount'] + $serviceExpenseMonth[$i]['totalSaleAmount'];
                        $totalExpenseMonth[$i]['month_name'] = $inventeryExpenseMonth[$i]['month_name'];
                }
                
            $totalExpenseMonth = collect(array_values($totalExpenseMonth));

        //***************************** Calculate expense percentage to pie chart *****************************//
            $inventeryAnualIncome = $inventeryExpenseMonth->sum('totalSaleAmount');  
            $otherAnualIncome = $otherExpenseMonth->sum('totalSaleAmount');
            $bankAnualIncome = $bankExpenseMonth->sum('totalSaleAmount');
            $serviceAnualIncome = $serviceExpenseMonth->sum('totalSaleAmount');
            $hrAnualIncome = $hrExpenseMonth->sum('totalSaleAmount');

            $totalAnualExpense = [number_format($inventeryAnualIncome,2,'.', '') , number_format($hrAnualIncome,2,'.', ''), number_format($otherAnualIncome,2,'.', '') , number_format($serviceAnualIncome,2,'.', '') , number_format($bankAnualIncome,2,'.', '')];
            
            //current year
            $yearExpense = Carbon::now()->year;

        return view('accountspages.expense', compact(
            'inventeryExpenseMonth',
            'inventeryExpenseWeek',
            'inventeryExpenseToday',
            'yearExpense',
            'otherExpenseMonth',
            'otherExpenseToday',
            'otherExpenseWeek',
            'bankExpenseMonth',
            'bankExpenseToday',
            'bankExpenseWeek',
            'serviceExpenseMonth',
            'serviceExpenseToday',
            'serviceExpenseWeek',
            'hrExpenseMonth',
            'hrExpenseToday',
            'hrExpenseWeek',
            'totalExpenseMonth',
            'totalAnualExpense',
        ));
    }

    public function indexFilter(Request $request)
    {
        $yearExpense = $request->year;
        $currentYear = now()->format('Y');
        //***************************** Inventery *****************************//
            //monthly Inventery expense
            $inventeryExpenseMonth = DB::table('inventory_purchase_orders')
                ->select(DB::raw('SUM(inventory_purchase_orders.pur_ord_amount) as totalSaleAmount'), DB::raw("MONTHNAME(created_at) as month_name"))
                ->whereYear('inventory_purchase_orders.created_at', $yearExpense)
                ->groupBy('month_name')
                ->orderBy('month_name', 'DESC')
                ->get();

            //if there are no record in a month, add 0 value
            $inventeryArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
                if (!empty($inventeryExpenseMonth)) {
                    $inventeryArr[$i]['totalSaleAmount'] =0;
                    foreach ($inventeryExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $inventeryArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                        }
                    }
                } else {
                    $inventeryArr[$i]['totalSaleAmount'] = 0;
                }
                $inventeryArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
            $inventeryExpenseMonth = collect(array_values($inventeryArr));

            if($currentYear == $yearExpense){
                //weekly Inventery expense
                $inventeryExpenseWeek = DB::table('inventory_purchase_orders')
                    ->select(DB::raw('SUM(inventory_purchase_orders.pur_ord_amount) as totalSaleAmount'))
                    ->whereBetween('inventory_purchase_orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->first();

                //today Inventery expense
                $inventeryExpenseToday = DB::table('inventory_purchase_orders')
                    ->select(DB::raw('SUM(inventory_purchase_orders.pur_ord_amount) as totalSaleAmount'))
                    ->whereDate('inventory_purchase_orders.created_at', Carbon::today())
                    ->first();
            }else{
                $inventeryExpenseWeek = (object)['totalSaleAmount'=>'0'];
                $inventeryExpenseToday = (object)['totalSaleAmount'=>'0'];
            }
        //***************************** HR *****************************//
            //monthly HR expense
            $hrExpenseMonth = DB::table('hr_salary_payables')
                ->select(DB::raw('SUM(hr_salary_payables.net_salary) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
                ->whereYear('hr_salary_payables.date', $yearExpense)
                ->groupBy('month_name')
                ->orderBy('month_name', 'DESC')
                ->get();

            //if there are no record in a month, add 0 value
            $hrArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
                if (!empty($hrExpenseMonth)) {
                    $hrArr[$i]['totalSaleAmount'] =0;
                    foreach ($hrExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $hrArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                        }
                    }
                } else {
                    $hrArr[$i]['totalSaleAmount'] = 0;
                }
                $hrArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
            $hrExpenseMonth = collect(array_values($hrArr));

            if($currentYear == $yearExpense){
                //weekly HR expense
                $hrExpenseWeek = DB::table('hr_salary_payables')
                    ->select(DB::raw('SUM(hr_salary_payables.net_salary) as totalSaleAmount'))
                    ->whereBetween('hr_salary_payables.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->first();

                //today HR expense
                $hrExpenseToday = DB::table('hr_salary_payables')
                    ->select(DB::raw('SUM(hr_salary_payables.net_salary) as totalSaleAmount'))
                    ->whereDate('hr_salary_payables.date', Carbon::today())
                    ->first();
            }else{
                $hrExpenseWeek = (object)['totalSaleAmount'=>'0'];
                $hrExpenseToday = (object)['totalSaleAmount'=>'0'];
            }
        //***************************** Other Expense *****************************//
            $otherExpenseMonth = DB::table('accounts_other_expenses')
            ->select(DB::raw('SUM(accounts_other_expenses.oth_exp_amount) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
            ->whereYear('accounts_other_expenses.date', $yearExpense)
            ->groupBy('month_name')
            ->orderBy('month_name', 'DESC')
            ->get();

            //if there are no record in a month, add 0 value
            $otherArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
                if (!empty($otherExpenseMonth)) {
                    $otherArr[$i]['totalSaleAmount'] =0;
                    foreach ($otherExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $otherArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                        }
                    }
                } else {
                    $otherArr[$i]['totalSaleAmount'] = 0;
                }
                $otherArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
            $otherExpenseMonth = collect(array_values($otherArr));

            if($currentYear == $yearExpense){
                //weekly Other expense
                $otherExpenseWeek = DB::table('accounts_other_expenses')
                    ->select(DB::raw('SUM(accounts_other_expenses.oth_exp_amount) as totalSaleAmount'))
                    ->whereBetween('accounts_other_expenses.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->first();
            
                //today Other expense
                $otherExpenseToday = DB::table('accounts_other_expenses')
                    ->select(DB::raw('SUM(accounts_other_expenses.oth_exp_amount) as totalSaleAmount'))
                    ->whereDate('accounts_other_expenses.date', Carbon::today())
                    ->first();
            }else{
                $otherExpenseWeek = (object)['totalSaleAmount'=>'0'];
                $otherExpenseToday = (object)['totalSaleAmount'=>'0'];
            }
        //***************************** Bank Expense *****************************//
            $bankExpenseMonth = DB::table('account_bank_balances')
            ->select(DB::raw('SUM(account_bank_balances.debit) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
            ->whereYear('account_bank_balances.date', $yearExpense)
            ->groupBy('month_name')
            ->orderBy('month_name', 'DESC')
            ->get();

            //if there are no record in a month, add 0 value
            $bankArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
                if (!empty($bankExpenseMonth)) {
                    $bankArr[$i]['totalSaleAmount'] =0;
                    foreach ($bankExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $bankArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                        }
                    }
                } else {
                    $bankArr[$i]['totalSaleAmount'] = 0;
                }
                $bankArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
            $bankExpenseMonth = collect(array_values($bankArr));

            if($currentYear == $yearExpense){
                //weekly Bank expense
                $bankExpenseWeek = DB::table('account_bank_balances')
                    ->select(DB::raw('SUM(account_bank_balances.debit) as totalSaleAmount'))
                    ->whereBetween('account_bank_balances.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->first();
            
                //today Bank expense
                $bankExpenseToday = DB::table('account_bank_balances')
                    ->select(DB::raw('SUM(account_bank_balances.debit) as totalSaleAmount'))
                    ->whereDate('account_bank_balances.date', Carbon::today())
                    ->first();
            }else{
                $bankExpenseWeek = (object)['totalSaleAmount'=>'0'];
                $bankExpenseToday = (object)['totalSaleAmount'=>'0'];
            }
        //***************************** Service Charge Expense *****************************//
            $serviceExpenseMonth = DB::table('accounts_service_expenses')
                ->select(DB::raw('SUM(accounts_service_expenses.amount) as totalSaleAmount'), DB::raw("MONTHNAME(date) as month_name"))
                ->whereYear('accounts_service_expenses.date', $yearExpense)
                ->groupBy('month_name')
                ->orderBy('month_name', 'DESC')
                ->get();
    
            //if there are no record in a month, add 0 value
            $serviceArr = [];
            for ($i = 0; $i < Carbon::now()->month; $i++) {
                if (!empty($serviceExpenseMonth)) {
                    $serviceArr[$i]['totalSaleAmount'] =0;
                    foreach ($serviceExpenseMonth as $item){
                        if($item->month_name == date("F", mktime(0, 0, 0, $i+1, 1))){
                            $serviceArr[$i]['totalSaleAmount'] = $item->totalSaleAmount;
                        }
                    }
                } else {
                    $serviceArr[$i]['totalSaleAmount'] = 0;
                }
                $serviceArr[$i]['month_name'] = date("F", mktime(0, 0, 0, $i+1, 1));
            }
            $serviceExpenseMonth = collect(array_values($serviceArr));
    
            if($currentYear == $yearExpense){
                //weekly Service Charge expense
                $serviceExpenseWeek = DB::table('accounts_service_expenses')
                    ->select(DB::raw('SUM(accounts_service_expenses.amount) as totalSaleAmount'))
                    ->whereBetween('accounts_service_expenses.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->first();
            
                //today Service Charge expense
                $serviceExpenseToday = DB::table('accounts_service_expenses')
                    ->select(DB::raw('SUM(accounts_service_expenses.amount) as totalSaleAmount'))
                    ->whereDate('accounts_service_expenses.date', Carbon::today())
                    ->first();
            }else{
                $serviceExpenseWeek = (object)['totalSaleAmount'=>'0'];
                $serviceExpenseToday = (object)['totalSaleAmount'=>'0'];
            }

        //***************************** Total Expense *****************************//
            $totalExpenseMonth=[];
                for($i=0; $i< Carbon::now()->month; $i++){
                        $totalExpenseMonth[$i]['totalSaleAmount'] = $inventeryExpenseMonth[$i]['totalSaleAmount'] + $hrExpenseMonth[$i]['totalSaleAmount'] + $otherExpenseMonth[$i]['totalSaleAmount'] + $bankExpenseMonth[$i]['totalSaleAmount'] + $serviceExpenseMonth[$i]['totalSaleAmount'];
                        $totalExpenseMonth[$i]['month_name'] = $inventeryExpenseMonth[$i]['month_name'];
                }
                
            $totalExpenseMonth = collect(array_values($totalExpenseMonth));

        //***************************** Calculate expense percentage to pie chart *****************************//
            $inventeryAnualIncome = $inventeryExpenseMonth->sum('totalSaleAmount');  
            $otherAnualIncome = $otherExpenseMonth->sum('totalSaleAmount');
            $bankAnualIncome = $bankExpenseMonth->sum('totalSaleAmount');
            $serviceAnualIncome = $serviceExpenseMonth->sum('totalSaleAmount');
            $hrAnualIncome = $hrExpenseMonth->sum('totalSaleAmount');

            $totalAnualExpense = [number_format($inventeryAnualIncome,2,'.', '') , number_format($hrAnualIncome,2,'.', ''), number_format($otherAnualIncome,2,'.', '') , number_format($serviceAnualIncome,2,'.', '') , number_format($bankAnualIncome,2,'.', '')];
            

        return view('accountspages.expense', compact(
            'inventeryExpenseMonth',
            'inventeryExpenseWeek',
            'inventeryExpenseToday',
            'yearExpense',
            'otherExpenseMonth',
            'otherExpenseToday',
            'otherExpenseWeek',
            'bankExpenseMonth',
            'bankExpenseToday',
            'bankExpenseWeek',
            'serviceExpenseMonth',
            'serviceExpenseToday',
            'serviceExpenseWeek',
            'hrExpenseMonth',
            'hrExpenseToday',
            'hrExpenseWeek',
            'totalExpenseMonth',
            'totalAnualExpense',
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
