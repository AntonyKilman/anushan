<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrialBalanceController extends Controller
{
    public function FinalTrialBalanceReport(Request $request)
    {
        $year = isset($request->year) ? $request->year : date('Y');

        $year_start_date =  date('Y-m-d', strtotime('-1 year', strtotime($year . "-04-01")));
        $year_end_date =  date('Y-m-d', strtotime($year . "-03-31"));

        $details = $this->trialDetails($year_start_date, $year_end_date);
        $Property_Plants_Equipments = $details['Property_Plants_Equipments'];
        $Fixed_Deposits = $details['Fixed_Deposits'];
        $Stock = $details['Stock'];
        $Debtors = $details['Debtors'];
        $Post_Date_Cheque = $details['Post_Date_Cheque'];
        $Bank_Loan = $details['Bank_Loan'];
        $Leasing = $details['Leasing'];
        $Bank = $details['Bank'];
        $cash_in_hand = $details['cash_in_hand'];
        $mukunthan = $details['mukunthan'];
        $Creditors = $details['Creditors'];
        $Sales = $details['Sales'];
        $Sales_return = $details['Sales_return'];
        $excess = $details['excess'];
        $other_income = $details['other_income'];
        $Purchase = $details['Purchase'];
        $Purchase_return = $details['Purchase_return'];
        $Selling_Distribution_Expences = $details['Selling_Distribution_Expences'];
        $Salary_staff = $details['Salary_staff'];
        $Epf = $details['Epf'];
        $Etf = $details['Etf'];
        $Administrative_Expences = $details['Administrative_Expences'];
        $Discount_allowed = $details['Discount_allowed'];
        $Common_expences = $details['Common_expences'];
        $Bank_charges = $details['Bank_charges'];
        $Bank_interest = $details['Bank_interest'];
        $Accounting_Exp = $details['Accounting_Exp'];
        $total_debit = $details['total_debit'];
        $total_credit = $details['total_credit'];

        return view('accountspages.final_trial_balance', compact(
            'year',
            'year_end_date',
            'year_start_date',
            'Property_Plants_Equipments',
            'Fixed_Deposits',
            'Stock',
            'Debtors',
            'Post_Date_Cheque',
            'Bank_Loan',
            'Leasing',
            'Bank',
            'cash_in_hand',
            'mukunthan',
            'Creditors',
            'Sales',
            'Sales_return',
            'excess',
            'other_income',
            'Purchase',
            'Purchase_return',
            'Selling_Distribution_Expences',
            'Salary_staff',
            'Epf',
            'Etf',
            'Administrative_Expences',
            'Discount_allowed',
            'Common_expences',
            'Bank_charges',
            'Bank_interest',
            'Accounting_Exp',
            'total_debit',
            'total_credit',

        ));
    }

    public function FinalTrialBalanceMonth(Request $request)
    {
        $month = isset($request->month) ? $request->month : date('Y-m');

        $month_start_date =  date('Y-m-01', strtotime($month));
        $month_end_date =  date('Y-m-d', strtotime('+1 month -1 day', strtotime($month_start_date)));

        $details = $this->trialDetails($month_start_date, $month_end_date);
        $Property_Plants_Equipments = $details['Property_Plants_Equipments'];
        $Fixed_Deposits = $details['Fixed_Deposits'];
        $Stock = $details['Stock'];
        $Debtors = $details['Debtors'];
        $Post_Date_Cheque = $details['Post_Date_Cheque'];
        $Bank_Loan = $details['Bank_Loan'];
        $Leasing = $details['Leasing'];
        $Bank = $details['Bank'];
        $cash_in_hand = $details['cash_in_hand'];
        $mukunthan = $details['mukunthan'];
        $Creditors = $details['Creditors'];
        $Sales = $details['Sales'];
        $Sales_return = $details['Sales_return'];
        $excess = $details['excess'];
        $other_income = $details['other_income'];
        $Purchase = $details['Purchase'];
        $Purchase_return = $details['Purchase_return'];
        $Selling_Distribution_Expences = $details['Selling_Distribution_Expences'];
        $Salary_staff = $details['Salary_staff'];
        $Epf = $details['Epf'];
        $Etf = $details['Etf'];
        $Administrative_Expences = $details['Administrative_Expences'];
        $Discount_allowed = $details['Discount_allowed'];
        $Common_expences = $details['Common_expences'];
        $Bank_charges = $details['Bank_charges'];
        $Bank_interest = $details['Bank_interest'];
        $Accounting_Exp = $details['Accounting_Exp'];
        $total_debit = $details['total_debit'];
        $total_credit = $details['total_credit'];


        return view('accountspages.final_trial_month', compact(
            'month',
            'month_end_date',
            'month_start_date',
            'Property_Plants_Equipments',
            'Fixed_Deposits',
            'Stock',
            'Debtors',
            'Post_Date_Cheque',
            'Bank_Loan',
            'Leasing',
            'Bank',
            'cash_in_hand',
            'mukunthan',
            'Creditors',
            'Sales',
            'Sales_return',
            'excess',
            'other_income',
            'Purchase',
            'Purchase_return',
            'Selling_Distribution_Expences',
            'Salary_staff',
            'Epf',
            'Etf',
            'Administrative_Expences',
            'Discount_allowed',
            'Common_expences',
            'Bank_charges',
            'Bank_interest',
            'Accounting_Exp',
            'total_debit',
            'total_credit',
        ));
    }

    public function trialDetails($start_date, $end_date)
    {

        $Property_Plants_Equipments = DB::table('accounts_details')
            ->select('accounts_details.*', 'add_sub_categeory.name')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', 'accounts_details.sub_categeory_id')
            ->whereDate('accounts_details.date', '>=', $start_date)
            ->whereDate('accounts_details.date', '<=', $end_date)
            ->where('accounts_details.Categeory_id', 1)
            ->get();

        $Property_Plants_Equipments_Sum = $Property_Plants_Equipments->sum('amount');

        $Fixed_Deposits = DB::table('accounts_details')
            ->select('accounts_details.*', 'add_sub_categeory.name')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', 'accounts_details.sub_categeory_id')
            ->whereDate('accounts_details.date', '>=', $start_date)
            ->whereDate('accounts_details.date', '<=', $end_date)
            ->where('accounts_details.Categeory_id', 2)
            ->get();

        $Fixed_Deposits_Sum = $Fixed_Deposits->sum('amount');


        $Stock = DB::table('food_city_products')
            ->whereDate('food_city_products.created_at', '>=', $start_date)
            ->whereDate('food_city_products.updated_at', '<=', $end_date)
            ->value(DB::raw('SUM(food_city_products.purchase_price) as Stock_amount'));

        $foodcity_sales_credit_amount = DB::table('foodcity_sales')
            ->whereDate('foodcity_sales.billing_date', '>=', $start_date)
            ->whereDate('foodcity_sales.billing_date', '<=', $end_date)
            ->value(DB::raw('SUM(foodcity_sales.credit_payment) as total_foodcity_sales_credit_amount'));

        $foodcity_credit_payment = DB::table('foodcity_credit_payments')
            ->whereDate('foodcity_credit_payments.date', '>=', $start_date)
            ->whereDate('foodcity_credit_payments.date', '<=', $end_date)
            ->value(DB::raw('SUM(cash + card + check_+ discount_amount) as total_credit_payment'));

        $Debtors = $foodcity_sales_credit_amount - $foodcity_credit_payment;

        $Post_Date_Cheque = DB::table('account_cheque_payments')
            ->whereDate('account_cheque_payments.date', '>=', $start_date)
            ->whereDate('account_cheque_payments.date', '<=', $end_date)
            ->value(DB::raw('SUM(debit) as Post_Date_Cheque_payment'));

        $Bank_Loan = DB::table('accounts_details')
            ->select('accounts_details.*', 'add_sub_categeory.name')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', 'accounts_details.sub_categeory_id')
            ->whereDate('accounts_details.date', '>=', $start_date)
            ->whereDate('accounts_details.date', '<=', $end_date)
            ->where('accounts_details.Categeory_id', 6)
            ->get();

        $Bank_Loan_Sum = $Bank_Loan->sum('amount');

        $Leasing = DB::table('accounts_details')
            ->select('accounts_details.*', 'add_sub_categeory.name')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', 'accounts_details.sub_categeory_id')
            ->whereDate('accounts_details.date', '>=', $start_date)
            ->whereDate('accounts_details.date', '<=', $end_date)
            ->where('accounts_details.Categeory_id', 7)
            ->get();

        $Leasing_Sum = $Leasing->sum('amount');


        $Bank = DB::table('accounts_details')
            ->select('accounts_details.*', 'add_sub_categeory.name')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', 'accounts_details.sub_categeory_id')
            ->whereDate('accounts_details.date', '>=', $start_date)
            ->whereDate('accounts_details.date', '<=', $end_date)
            ->where('accounts_details.Categeory_id', 4)
            ->get();

        $Bank_Sum = $Bank->sum('amount');


        $body = [
            'from' => $start_date,
            'to' => $end_date,
            'finaltrialBlance' => true,
        ];
        $body2 = new Request($body);
        $cash_in_hand = app('App\Http\Controllers\DailyCashBook_Controller')->ShowDailyCash($body2);

        $mukunthan = DB::table('accounts_details')
            ->select('accounts_details.*', 'add_sub_categeory.name')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', 'accounts_details.sub_categeory_id')
            ->whereDate('accounts_details.date', '>=', $start_date)
            ->whereDate('accounts_details.date', '<=', $end_date)
            ->where('accounts_details.Categeory_id', 5)
            ->get();

        $mukunthan_Sum = $mukunthan->sum('amount');


        $Creditors = DB::table('inventory_purchase_orders')
            ->whereDate('inventory_purchase_orders.date', '>=', $start_date)
            ->whereDate('inventory_purchase_orders.date', '<=', $end_date)
            ->value(DB::raw('SUM(inventory_purchase_orders.pur_ord_credit) as company_purchase_credit_amount'));

        $Sales = DB::table('foodcity_sales')
            ->whereDate('foodcity_sales.billing_date', '>=', $start_date)
            ->whereDate('foodcity_sales.billing_date', '<=', $end_date)
            ->value(DB::raw('SUM(foodcity_sales.amount) as Sales_amount'));

        $Sales_return = DB::table('foodcity_sales_returns')
            ->whereDate('foodcity_sales_returns.created_at', '>=', $start_date)
            ->whereDate('foodcity_sales_returns.updated_at', '<=', $end_date)
            ->value(DB::raw('SUM(foodcity_sales_returns.return_amount) as Sales_amount'));

        $body = [
            'from' => $start_date,
            'to' => $end_date,
            'dailyadjustmentamount' => true,
        ];
        $body2 = new Request($body);
        $excess = app('App\Http\Controllers\DailyAdjustmentReportController')->ShowDailyCashReport($body2);

        $other_income = DB::table('accounts_other_incomes')
            ->whereDate('accounts_other_incomes.date', '>=', $start_date)
            ->whereDate('accounts_other_incomes.date', '<=', $end_date)
            ->value(DB::raw('SUM(accounts_other_incomes.amount) as other_income_amount'));

        $Purchase = DB::table('inventory_purchase_items')
            ->whereDate('inventory_purchase_items.created_at', '>=', $start_date)
            ->whereDate('inventory_purchase_items.created_at', '<=', $end_date)
            ->value(DB::raw('SUM(inventory_purchase_items.pur_item_amount) as Purchase_amount'));

        $Purchase_return = DB::table('foodcity_company_return')
            ->leftJoin('food_city_products', 'food_city_products.id', 'foodcity_company_return.foodcity_product_id')
            ->whereDate('foodcity_company_return.date', '>=', $start_date)
            ->whereDate('foodcity_company_return.date', '<=', $end_date)
            ->value(DB::raw('IFNULL(SUM(food_city_products.purchase_price * foodcity_company_return.return_qty),0)'));

        $Selling_Distribution_Expences = DB::table('accounts_details')
            ->select('accounts_details.*', 'add_sub_categeory.name')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', 'accounts_details.sub_categeory_id')
            ->whereDate('accounts_details.date', '>=', $start_date)
            ->whereDate('accounts_details.date', '<=', $end_date)
            ->where('accounts_details.Categeory_id', 14)
            ->get();

        $Selling_Distribution_Expences_Sum = $Selling_Distribution_Expences->sum('amount');

        $Salary_staff = DB::table('hr_salary_payables')
            ->whereDate('hr_salary_payables.date', '>=', $start_date)
            ->whereDate('hr_salary_payables.date', '<=', $end_date)
            ->value(DB::raw('IFNULL(SUM(hr_salary_payables.basic_salary - hr_salary_payables.epf),0)'));

        $Epf = DB::table('hr_salary_payables')
            ->whereDate('hr_salary_payables.date', '>=', $start_date)
            ->whereDate('hr_salary_payables.date', '<=', $end_date)
            ->value(DB::raw('SUM(hr_salary_payables.company_epf) as company_epf_amount'));

        $Etf = DB::table('hr_salary_payables')
            ->whereDate('hr_salary_payables.date', '>=', $start_date)
            ->whereDate('hr_salary_payables.date', '<=', $end_date)
            ->value(DB::raw('SUM(hr_salary_payables.company_etf) as company_etf_amount'));

        $Administrative_Expences = DB::table('accounts_details')
            ->select('accounts_details.*', 'add_sub_categeory.name')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', 'accounts_details.sub_categeory_id')
            ->whereDate('accounts_details.date', '>=', $start_date)
            ->whereDate('accounts_details.date', '<=', $end_date)
            ->where('accounts_details.Categeory_id', 13)
            ->get();

        $Administrative_Expences_Sum = $Administrative_Expences->sum('amount');

        $Discount_allowed = DB::table('accounts_other_incomes')
            ->whereDate('accounts_other_incomes.date', '>=', $start_date)
            ->whereDate('accounts_other_incomes.date', '<=', $end_date)
            ->value(DB::raw('SUM(accounts_other_incomes.amount) as Discount_allowed_amount'));

        $Common_expences = DB::table('accounts_details')
            ->select('accounts_details.*', 'add_sub_categeory.name')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', 'accounts_details.sub_categeory_id')
            ->whereDate('accounts_details.date', '>=', $start_date)
            ->whereDate('accounts_details.date', '<=', $end_date)
            ->where('accounts_details.Categeory_id', 24)
            ->get();

        $Common_expences_Sum = $Common_expences->sum('amount');

        $Bank_charges = DB::table('accounts_bank_charges')
            ->whereDate('accounts_bank_charges.date', '>=', $start_date)
            ->whereDate('accounts_bank_charges.date', '<=', $end_date)
            ->value(DB::raw('SUM(accounts_bank_charges.amount) as Bank_charges_amount'));

        $Bank_interest = DB::table('account_bank_intrests')
            ->whereDate('account_bank_intrests.date', '>=', $start_date)
            ->whereDate('account_bank_intrests.date', '<=', $end_date)
            ->value(DB::raw('SUM(account_bank_intrests.intrest_amount) as Bank_interest_amount'));

        $Accounting_Exp = DB::table('accounts_other_expenses')
            ->whereDate('accounts_other_expenses.date', '>=', $start_date)
            ->whereDate('accounts_other_expenses.date', '<=', $end_date)
            ->value(DB::raw('SUM(accounts_other_expenses.oth_exp_amount) as Accounting_Exp_amount'));


        $total_debit = $Property_Plants_Equipments_Sum + $Fixed_Deposits_Sum + $Stock + $Debtors + $Post_Date_Cheque + $Bank_Sum + $cash_in_hand +
                        $mukunthan_Sum + $Sales_return + $Purchase + $Selling_Distribution_Expences_Sum + $Salary_staff + $Epf + $Etf + $Administrative_Expences_Sum +
                        $Discount_allowed + $Common_expences_Sum + $Bank_charges + $Bank_interest + $Accounting_Exp;

        $total_credit = $Bank_Loan_Sum + $Leasing_Sum + $Creditors + $Sales + $excess + $other_income + $Purchase_return;

        $arry = [
            'Property_Plants_Equipments' => $Property_Plants_Equipments,
            'Fixed_Deposits' => $Fixed_Deposits,
            'Stock' => $Stock,
            'Debtors' => $Debtors,
            'Post_Date_Cheque' => $Post_Date_Cheque,
            'Bank_Loan' => $Bank_Loan,
            'Leasing' => $Leasing,
            'Bank' => $Bank,
            'cash_in_hand' => $cash_in_hand,
            'mukunthan' => $mukunthan,
            'Creditors' => $Creditors,
            'Sales' => $Sales,
            'Sales_return' => $Sales_return,
            'excess' => $excess,
            'other_income' => $other_income,
            'Purchase' => $Purchase,
            'Purchase_return' => $Purchase_return,
            'Selling_Distribution_Expences' => $Selling_Distribution_Expences,
            'Salary_staff' => $Salary_staff,
            'Epf' => $Epf,
            'Etf' => $Etf,
            'Administrative_Expences' => $Administrative_Expences,
            'Discount_allowed' => $Discount_allowed,
            'Common_expences' => $Common_expences,
            'Bank_charges' => $Bank_charges,
            'Bank_interest' => $Bank_interest,
            'Accounting_Exp' => $Accounting_Exp,
            'total_debit' => $total_debit,
            'total_credit' => $total_credit,
        ];

        return $arry;
    }

    public function ShowTrialBalanceReport(Request $req)
    {

        $to = isset($req->to) ? $req->to : Carbon::now()->format('Y-m-d');
        $from = isset($req->from) ? $req->from : Carbon::now()->format('Y-m-d');

        $property_plants_equipment = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 1)
            ->value(DB::raw('SUM(accounts_details.amount) as property_plants_equipment_amount'));

        $fixdeposits = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 2)
            ->value(DB::raw('SUM(accounts_details.amount) as fixdeposits_amount'));

        $debtors = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 3)
            ->value(DB::raw('SUM(accounts_details.amount) as debtors_amount'));

        $cash_at_bank = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 4)
            ->value(DB::raw('SUM(accounts_details.amount) as bank_amount'));

        $cash_in_hand = DB::table('closed_balance')
            ->whereDate('closed_balance.date', '>=', $from)
            ->whereDate('closed_balance.date', '<=', $to)
            ->value(DB::raw('SUM(closed_balance.adjustment_amount) as cash_amount'));

        $food_city_sales = DB::table('foodcity_sales')
            ->whereDate('foodcity_sales.billing_date', '>=', $from)
            ->whereDate('foodcity_sales.billing_date', '<=', $to)
            ->value(DB::raw('SUM(foodcity_sales.amount) as food_city_sales_amount'));

        $inventory_purchace_order = DB::table('inventory_purchase_orders')
            ->whereDate('inventory_purchase_orders.date', '>=', $from)
            ->whereDate('inventory_purchase_orders.date', '<=', $to)
            ->value(DB::raw('SUM(inventory_purchase_orders.pur_ord_amount) as inventory_purchace_order_amount'));

        $stock_amount = ($inventory_purchace_order - $food_city_sales);

        $mugunthan = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 5)
            ->value(DB::raw('SUM(accounts_details.amount) as mugunthan_amount'));

        $bank_loan = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 6)
            ->value(DB::raw('SUM(accounts_details.amount) as bank_loan_amount'));

        $leasing = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 7)
            ->value(DB::raw('SUM(accounts_details.amount) as leasing_amount'));

        $sales = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 10)
            ->value(DB::raw('SUM(accounts_details.amount) as sales_amount'));

        $purchase = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 11)
            ->value(DB::raw('SUM(accounts_details.amount) as purchase_amount'));

        $other_income = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 12)
            ->value(DB::raw('SUM(accounts_details.amount) as other_income_amount'));

        $administrative_expenses = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 13)
            ->value(DB::raw('SUM(accounts_details.amount) as administrative_expenses_amount'));

        $selling_distribution_expenses = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 14)
            ->value(DB::raw('SUM(accounts_details.amount) as selling_distribution_expenses_amount'));

        $financial_expenses = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 15)
            ->value(DB::raw('SUM(accounts_details.amount) as financial_expenses_amount'));

        $creditors = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 8)
            ->value(DB::raw('SUM(accounts_details.amount) as creditors_amount'));

        $accrued_expenses = DB::table('accounts_details')
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->where('accounts_details.Categeory_id', 9)
            ->value(DB::raw('SUM(accounts_details.amount) as accrued_expenses_amount'));

        $total_income = ($property_plants_equipment + $fixdeposits + $debtors + $cash_at_bank + $cash_in_hand + $stock_amount + $purchase + $administrative_expenses + $selling_distribution_expenses + $financial_expenses);

        $total_expense = ($mugunthan + $bank_loan + $leasing + $sales + $other_income + $creditors + $accrued_expenses);

        return view(
            'accountspages.trial_balance_report',
            compact(
                'to',
                'from',
                'property_plants_equipment',
                'fixdeposits',
                'debtors',
                'cash_at_bank',
                'cash_in_hand',
                'stock_amount',
                'mugunthan',
                'bank_loan',
                'leasing',
                'sales',
                'purchase',
                'other_income',
                'administrative_expenses',
                'selling_distribution_expenses',
                'financial_expenses',
                'creditors',
                'accrued_expenses',
                'total_income',
                'total_expense'
            )
        );
    }

}
