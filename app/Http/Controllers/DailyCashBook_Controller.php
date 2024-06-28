<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;



class DailyCashBook_Controller extends Controller
{
  public function ShowDailyCash(Request $req)
  {
    $from = isset($req->from) ? $req->from : Carbon::now()->format('Y-m-d');
    $to = isset($req->to) ? $req->to : Carbon::now()->format('Y-m-d');

    $startDate = date('Y-m-d', strtotime('-1 day', strtotime( $from)));

    $Closed_Balance = DB::table('closed_balance')
      ->whereDate('closed_balance.date', $startDate)
      ->sum('closed_balance.adjustment_amount');

    //INCOME///
    $Cash_sale = DB::table('foodcity_sales')
      ->whereDate('foodcity_sales.billing_date', '>=',  $from)
      ->whereDate('foodcity_sales.billing_date', '<=', $to)
      ->value(DB::raw('SUM(foodcity_sales.cash_payment) as sales_cash'));

    $Card_sale = DB::table('foodcity_sales')
      ->whereDate('foodcity_sales.billing_date', '>=',  $from)
      ->whereDate('foodcity_sales.billing_date', '<=', $to)
      ->value(DB::raw('SUM(foodcity_sales.card_payment) as sales_card'));

    $Cheque_sale = DB::table('foodcity_sales')
      ->whereDate('foodcity_sales.billing_date', '>=',  $from)
      ->whereDate('foodcity_sales.billing_date', '<=', $to)
      ->value(DB::raw('SUM(foodcity_sales.cheque_payment) as sales_cheque'));

    $Card_Commission = DB::table('account_bank_transections')
      ->select('account_bank_transections.*', 'account_banks.account_no', 'account_banks.name')
      ->leftJoin('account_banks', 'account_banks.id', 'account_bank_transections.bank_id')
      ->whereDate('account_bank_transections.date', '>=',  $from)
      ->whereDate('account_bank_transections.date', '<=', $to)
      ->where('type', '=', 'Commission')
      ->get();

    $Credit_Payment = DB::table('foodcity_credit_payments')
      ->select(
        'foodcity_credit_payments.cash',
        'customers.name',
        'customers.id as customer_id',
        (DB::raw('SUM(foodcity_credit_payments.cash) as credit_cash'))
      )
      ->leftJoin('customers', 'customers.id', 'foodcity_credit_payments.customer_id')
      ->groupBy('foodcity_credit_payments.customer_id')
      ->where('foodcity_credit_payments.cash', '>', 0)
      ->whereDate('foodcity_credit_payments.date', '>=',  $from)
      ->whereDate('foodcity_credit_payments.date', '<=', $to)
      ->get();

    $Bank_Withdraw = DB::table('account_bank_transections')
      ->select('account_bank_transections.*', 'account_banks.account_no', 'account_banks.name')
      ->leftJoin('account_banks', 'account_banks.id', 'account_bank_transections.bank_id')
      ->whereDate('account_bank_transections.date', '>=',  $from)
      ->whereDate('account_bank_transections.date', '<=', $to)
      ->where('type', '=', 'Withdraw')
      ->get();

    $Advance_Amount = DB::table('advance_payments')
      ->select('advance_payments.*', 'customers.name')
      ->leftJoin('customers', 'customers.id', 'advance_payments.coustomer_id')
      ->whereDate('advance_payments.date', '>=',  $from)
      ->whereDate('advance_payments.date', '<=', $to)
      ->get();

    $Cheque_Income = DB::table('account_bank_transections')
    ->select('account_bank_transections.*', 'account_banks.account_no', 'account_banks.name')
      ->leftJoin('account_banks', 'account_banks.id', 'account_bank_transections.bank_id')
      ->whereDate('account_bank_transections.date', '>=',  $from)
      ->whereDate('account_bank_transections.date', '<=', $to)
      ->where('type', '=', 'Cheque Deposit')
      ->get();

    $other_income_details = DB::table('accounts_other_incomes')
      ->select('accounts_other_incomes.*','accounts_other_incomes_categeory.categeory_name')
      ->leftJoin('accounts_other_incomes_categeory','accounts_other_incomes_categeory.id','accounts_other_incomes.categeory_id')
      ->whereDate('accounts_other_incomes.date', '>=',  $from)
      ->whereDate('accounts_other_incomes.date', '<=', $to)
      ->get();

    //EXPENSE
    $Bank_Deposit = DB::table('account_bank_transections')
      ->select('account_bank_transections.*', 'account_banks.account_no', 'account_banks.name')
      ->leftJoin('account_banks', 'account_banks.id', 'account_bank_transections.bank_id')
      ->whereDate('account_bank_transections.date', '>=',  $from)
      ->whereDate('account_bank_transections.date', '<=', $to)
      ->where('type', '=', 'Deposit')
      ->get();

    $Purchase_Amount = DB::table('inventory_purchase_orders')
      ->select('inventory_purchase_orders.pur_ord_cash', 'inventory_sellers.seller_name', 'inventory_purchase_orders.pur_ord_bill_no')
      ->leftJoin('inventory_sellers', 'inventory_sellers.id', 'inventory_purchase_orders.seller_id')
      ->where('inventory_purchase_orders.pur_ord_cash','>', 0)
      ->whereDate('inventory_purchase_orders.date', '>=',  $from)
      ->whereDate('inventory_purchase_orders.date', '<=', $to)
      ->get();

    $Gr_Credit_Payment_Cash = DB::table('inventory_purchase_order_credit_payment')
      ->select('inventory_purchase_order_credit_payment.*', 'inventory_purchase_orders.pur_ord_bill_no')
      ->leftJoin('inventory_purchase_orders', 'inventory_purchase_orders.id', 'inventory_purchase_order_credit_payment.purchase_order_id')
      ->whereDate('inventory_purchase_order_credit_payment.payment_date', '>=',  $from)
      ->whereDate('inventory_purchase_order_credit_payment.payment_date', '<=', $to)
      ->get();

    $Gr_Credit_Payment_Cheque = DB::table('inventory_purchase_order_credit_payment')
      ->select('inventory_purchase_order_credit_payment.*', 'inventory_purchase_orders.pur_ord_bill_no')
      ->leftJoin('inventory_purchase_orders', 'inventory_purchase_orders.id', 'inventory_purchase_order_credit_payment.purchase_order_id')
      ->where('inventory_purchase_order_credit_payment.cheque_amount', '>', 0)
      ->whereDate('inventory_purchase_order_credit_payment.payment_date', '>=',  $from)
      ->whereDate('inventory_purchase_order_credit_payment.payment_date', '<=', $to)
      ->get();

    $Other_Expence_Cash = DB::table('accounts_other_expenses')
      ->select('accounts_other_expenses.oth_exp_cash', 'add_sub_categeory.name as sub_categeory_name')
      ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', 'accounts_other_expenses.sub_categeory_id')
      ->whereDate('accounts_other_expenses.date', '>=',  $from)
      ->whereDate('accounts_other_expenses.date', '<=', $to)
      ->get();

    $Other_Expence__Cheque = DB::table('accounts_other_expenses')
      ->select('accounts_other_expenses.oth_exp_cheque', 'add_categeory.name as categeory_name')
      ->leftJoin('add_categeory', 'add_categeory.id', 'accounts_other_expenses.categeory_id')
      ->where('accounts_other_expenses.oth_exp_cheque', '>', 0)
      ->whereDate('accounts_other_expenses.date', '>=',  $from)
      ->whereDate('accounts_other_expenses.date', '<=', $to)
      ->get();

    $Return_Amount = DB::table('foodcity_sales_return_details')
      ->select('foodcity_sales_return_details.*', 'foodcity_sales.invoice_no')
      ->leftJoin('foodcity_sales', 'foodcity_sales.id', 'foodcity_sales_return_details.sales_id')
      ->whereDate('foodcity_sales_return_details.return_date', '>=',  $from)
      ->whereDate('foodcity_sales_return_details.return_date', '<=', $to)
      ->get();

    $mukunthan = DB::table('mukunthan_collection')
      ->select('mukunthan_collection.*')
      ->whereDate('mukunthan_collection.date', '>=',  $from)
      ->whereDate('mukunthan_collection.date', '<=', $to)
      ->get();

    $cheque_return = DB::table('account_cheque_payments')
      ->select('account_cheque_payments.*', (DB::raw('SUM(account_cheque_payments.credit) as cheque_return_credit')))
      ->leftJoin('account_banks', 'account_banks.id', 'account_cheque_payments.bank_id')
      ->groupBy('account_cheque_payments.bank_id')
      ->where('account_cheque_payments.status', '2')
      ->whereDate('account_cheque_payments.date', '>=',  $from)
      ->whereDate('account_cheque_payments.date', '<=', $to)
      ->get();

    $owner_transaction_debit = DB::table('owners_transaction')
      ->select('owners_transaction.*','owners_list.owner_name',(DB::raw('SUM(owners_transaction.amount) as owner_tran_debit_amount')))
      ->leftJoin('owners_list','owners_list.id','owners_transaction.owners_list_id')
      ->groupBy('owners_transaction.owners_list_id')
      ->whereNotIn('owners_transaction.option',['Credit'])
      ->whereDate('owners_transaction.date', '>=', $from)
      ->whereDate('owners_transaction.date', '<=', $to)
      ->get();

    $owner_transaction_credit = DB::table('owners_transaction')
      ->select('owners_transaction.*','owners_list.owner_name',(DB::raw('SUM(owners_transaction.amount) as owner_tran_credit_amount')))
      ->leftJoin('owners_list','owners_list.id','owners_transaction.owners_list_id')
      ->groupBy('owners_transaction.owners_list_id')
      ->whereNotIn('owners_transaction.option',['Debit'])
      ->whereDate('owners_transaction.date', '>=', $from)
      ->whereDate('owners_transaction.date', '<=', $to)
      ->get();

      
      if (isset($req->finaltrialBlance)) {
        $totalIncome = $Closed_Balance + $Advance_Amount->sum('amount') + $Cash_sale + $Card_sale + $Cheque_sale + $Card_Commission->sum('amount') + $Credit_Payment->sum('credit_cash') + $Bank_Withdraw->sum('amount') + $other_income_details->sum('amount') + $owner_transaction_credit->sum('owner_tran_credit_amount');
        $totalExpence = $cheque_return->sum('cheque_return_credit') + $mukunthan->sum('amount') + $Bank_Deposit->sum('amount') + $Purchase_Amount->sum('pur_ord_cash') + $Gr_Credit_Payment_Cash->sum('cash_amount') + $Gr_Credit_Payment_Cheque->sum('cheque_amount') + $Other_Expence_Cash->sum('oth_exp_cash') + $Other_Expence__Cheque->sum('oth_exp_cheque') + $Return_Amount->sum('amount') + $owner_transaction_debit->sum('owner_tran_debit_amount');
        $NetAmount = $totalIncome - $totalExpence;

        return $NetAmount;
      }

    return view('accountspages.DailyCashBook', compact(
      'to',
      'from',
      'from',
      'to',
      'Cash_sale',
      'Card_sale',
      'Cheque_sale',
      'Card_Commission',
      'Credit_Payment',
      'Bank_Withdraw',
      'Cheque_Income',
      'Bank_Deposit',
      'Purchase_Amount',
      'Advance_Amount',
      'Closed_Balance',
      'other_income_details',
      'Other_Expence__Cheque',
      'Other_Expence_Cash',
      'Return_Amount',
      'Gr_Credit_Payment_Cheque',
      'Gr_Credit_Payment_Cash',
      'mukunthan',
      'cheque_return',
      'owner_transaction_debit',
      'owner_transaction_credit'
    ));
  }
}
