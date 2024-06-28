<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\MainAccount;
use App\Models\account_profit_loss;

use App\Models\FoodcitySalesReturnDetails;

use Carbon\Carbon;

class SalesReturnController extends Controller
{
    public function index(Request $request)
    {
        if ($request->from) {
            $from = $request->from;
        } else {
            $from = date('Y-m-d');
        }

        $salesReport = DB::table('foodcity_product_sales')
            ->select(DB::raw('SUM(food_city_products.purchase_price * foodcity_product_sales.quantity ) as totalPurchasePrice'), 'foodcity_sales.*', 'foodcity_sales.amount as totalAmount', DB::raw('SUM(foodcity_product_sales.quantity) as productQuantity'))
            ->leftJoin('foodcity_sales', 'foodcity_sales.id', '=', 'foodcity_product_sales.sales_id')
            ->leftJoin('food_city_products', 'food_city_products.id', '=', 'foodcity_product_sales.product_id')
            ->groupBy('foodcity_product_sales.sales_id')
            ->whereDate('foodcity_sales.created_at', $from)
            ->orderBy('foodcity_sales.id', 'DESC')
            ->get();

        return view('sales.sales_return', compact('salesReport', 'from'));
    }

    public function salesReturn($id)
    {
        $salesReportView = DB::table('foodcity_product_sales')
            ->select('foodcity_product_sales.*', 'food_city_products.name', 'foodcity_product_sales.quantity as sale_quantity')
            ->where('foodcity_product_sales.sales_id', '=', $id)
            ->join('food_city_products', 'food_city_products.id', 'foodcity_product_sales.product_id')
            ->get();

        $finalSales = [];
        foreach ($salesReportView as $value) {
            $salesProductId = $value->product_id;

            $haveReturnThisProduct = DB::table('foodcity_sales_returns')
                ->select('id', 'return_quantity')
                ->where('sales_id', $id)
                ->where('product_id', $salesProductId)
                ->first();
            if (!$haveReturnThisProduct) {
                array_push($finalSales, $value);
            } else {
                $buyingQry = $value->sale_quantity;
                $returnQty = $haveReturnThisProduct->return_quantity;
                if ((double)$buyingQry > (double)$returnQty) {
                    $value->sale_quantity = $value->sale_quantity - $returnQty;
                    array_push($finalSales, $value);
                }
            }
        }

        $invoiceNo = DB::table('foodcity_sales')
            ->select('id', 'foodcity_sales.invoice_no', DB::raw('DATE(created_at) AS date'), DB::raw('TIME(created_at) AS time'))
            ->where('foodcity_sales.id', '=', $id)
            ->first();

        return view('sales.sales_return_view', compact('finalSales', 'invoiceNo'));
    }

    public function store(Request $request)
    {
        $sales_id = $request->sales_id;
        $cancel_itemsArry = $request->cancel_items;
        $reason = $request->reason;

        //save return products to sales return table
        $amountTotal = 0;
        $printArry = [];
        foreach ($cancel_itemsArry as $item) {

            $item = json_decode(json_encode($item));

            $foodcity_product_sales = DB::table('foodcity_product_sales')->where('id', $item->foodcity_product_sales_id)->first();

            $amount = round(($foodcity_product_sales->amount - $foodcity_product_sales->discount)  * $item->this_product_qty, 2);
            $return_discount = round($foodcity_product_sales->discount * $item->this_product_qty, 2);

            $salesReturn = new SalesReturn();
            $salesReturn->sales_id = $sales_id;
            $salesReturn->product_id = $foodcity_product_sales->product_id;
            $salesReturn->return_amount = $amount;
            $salesReturn->return_discount = $return_discount;
            $salesReturn->return_quantity = $item->this_product_qty;
            $salesReturn->bar_code = $foodcity_product_sales->bar_code;
            $salesReturn->return_reson = $reason;
            $salesReturn->save();

            $amountTotal += $amount;

            DB::table('food_city_products')
                ->where('id', $foodcity_product_sales->product_id)
                ->update(['is_out_of_stock' => 0, 'updated_at' => now()]);

            $product = DB::table('foodcity_product_sales')
                ->select('food_city_products.sales_price', 'food_city_products.name')
                ->leftJoin('food_city_products', 'food_city_products.id', 'foodcity_product_sales.product_id')
                ->where('food_city_products.id', $foodcity_product_sales->product_id)
                ->first();

            $item->amount = $amount;
            $item->quantity = $item->this_product_qty;
            $item->product_name = $product->name;
            $item->rate = $product->sales_price;
            $item->discount = $return_discount;

            array_push($printArry, $item);
        }

        
        //for foodcity sales return details
        $sales_return_details = new FoodcitySalesReturnDetails();
        $sales_return_details->sales_id = $sales_id;
        $sales_return_details->amount = $amountTotal;
        $sales_return_details->return_resaon = $reason;
        $sales_return_details->return_date = date('Y-m-d');
        $sales_return_details->save();


        // $amount = DB::table('foodcity_sales_returns')
        //     ->where('foodcity_sales_returns.sales_id', $sales_id)
        //     ->SUM('foodcity_sales_returns.return_amount');

        // $foodcity_product_sales_id = DB::table('foodcity_product_sales')
        // ->select('id')
        // ->where('foodcity_product_sales.sales_id', $sales_id)
        // ->get();

        $details = DB::table('foodcity_sales')
            ->select('employees.f_name as billUser', 'customers.name as customerName')
            ->leftJoin('users', 'users.id', 'foodcity_sales.user_id')
            ->leftJoin('employees', 'employees.id', 'users.emp_id')
            ->leftJoin('customers', 'customers.id', 'foodcity_sales.customer_id')
            ->where('foodcity_sales.id', $sales_id)
            ->first();


        $salesCancel = Sales::find($sales_id);
        $edit_data = MainAccount::where('dept_id', 1)->where('connected_id', $sales_id)->first();

        $SalesReturn = new MainAccount();
        $SalesReturn->debit = $amountTotal;
        $SalesReturn->dept_id = 1; //food city
        $SalesReturn->date = Carbon::now()->format('Y-m-d');
        $SalesReturn->account_type = 7000; //need to change
        $SalesReturn->category = 7;
        $SalesReturn->description = isset($edit_data) ? $edit_data->description : null;
        $SalesReturn->connected_id = $salesCancel->id;
        // $SalesReturn->cash = $salesCancel->cash_payment;
        // $SalesReturn->card = $salesCancel->card_payment;
        // $SalesReturn->credit_amount = $salesCancel->credit_payment;
        $SalesReturn->purchase_amount = isset($edit_data) ? $edit_data->purchase_amount : null;
        $SalesReturn->customer_id = $salesCancel->customer_id;
        $SalesReturn->table_id = 3; //sales return account
        $SalesReturn->save();


        $CashAccount = new MainAccount();
        $CashAccount->credit = floatval($salesCancel->cash_payment) + floatval($salesCancel->card_payment) + +floatval($salesCancel->cheque_payment);
        $CashAccount->dept_id = 1; //food city
        $CashAccount->date = Carbon::now()->format('Y-m-d');
        $CashAccount->account_type = 5000;
        $CashAccount->category = 7;
        $CashAccount->description = isset($edit_data) ? $edit_data->description : null;
        $CashAccount->connected_id = $salesCancel->id;
        $CashAccount->customer_id = $salesCancel->customer_id;
        $CashAccount->cash = $salesCancel->cash_payment;
        $CashAccount->card = $salesCancel->card_payment;
        $CashAccount->credit_amount = $salesCancel->credit_payment;
        $CashAccount->table_id = 1; //cash account
        $CashAccount->save();

        if ($salesCancel->credit_payment > 0) {
            $credit_account = new MainAccount();
            $credit_account->credit = $salesCancel->credit_payment;
            $credit_account->dept_id = 1; //food city
            $credit_account->date = Carbon::now()->format('Y-m-d');
            $credit_account->account_type = 5000;
            $credit_account->category = 7;
            $credit_account->description = isset($edit_data) ? $edit_data->description : null;
            $credit_account->connected_id = $salesCancel->id;
            $credit_account->customer_id = $salesCancel->customer_id;
            $credit_account->table_id = 4; //credit account
            $credit_account->save();
        }

        // store account_profit_loss table created by sivakaran----------------------------------

        // sales cancel update the account profit loss table

        // $cancel = DB::table('account_profit_loss')
        //     ->where('account_profit_loss.connected_id', $sales_id)
        //     ->where('account_profit_loss.type', "Foodcity Sales")
        //     ->where("account_profit_loss.department_id", 1)
        //     ->get()
        //     ->first();

        // $account_profit_loss = new  account_profit_loss();
        // $account_profit_loss->customer_id = $cancel->customer_id;
        // $account_profit_loss->total_purchase_price = $cancel->total_purchase_price;
        // $account_profit_loss->total_selling_price = $cancel->total_selling_price;
        // $account_profit_loss->total_discount_out = -$cancel->total_discount_out;
        // $account_profit_loss->total_sales_price = $cancel->total_sales_price;
        // $account_profit_loss->cheque_out = $cancel->cheque_in;
        // $account_profit_loss->cheque_no = $cancel->cheque_no;
        // $account_profit_loss->cheque_date = $cancel->cheque_date;
        // $account_profit_loss->credit = -$cancel->credit;
        // $account_profit_loss->department_id = 1;
        // $account_profit_loss->type = "Foodcity Sales Return";
        // $account_profit_loss->cash_out = $cancel->cash_in;
        // $account_profit_loss->card_out = $cancel->card_in;
        // $account_profit_loss->connected_id = $cancel->connected_id;
        // $account_profit_loss->date = Carbon::now();
        // $account_profit_loss->is_delete =  0;
        // $account_profit_loss->save();


        return response()->json(['success' => true, 'printArry' => $printArry, 'details' => $details]);
    }

}