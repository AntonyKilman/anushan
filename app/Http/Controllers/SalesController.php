<?php

namespace App\Http\Controllers;

use App\Models\CustomerLoyality;
use App\Models\Loyality;
use App\Models\ProductSales;
use App\Models\account_profit_loss;
use App\Models\Sales;
use App\Models\TempoerySalesModel;
use App\Models\Customer;
use App\Models\MainAccount;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{

    public function index()
    {
        
        $invoice = 'RFC-N' . rand(10, 99) . date('ymd');
        
        $data = $this->billingDetails();
        $credit_customer = $data['credit_customer'];
        $sales_return_reference_no = $data['sales_return_reference_no'];
        $advance_payment_details = $data['advance_payment_details'];

        return view('sales.sales', compact('invoice', 'credit_customer', 'sales_return_reference_no', 'advance_payment_details'));
        // return view('sales.example_print');
    }

    //credit sales billpng
    public function creditindex()
    {
        
        $invoice = 'RFC-C' . rand(10, 99) . date('ymd');

        $data = $this->billingDetails();
        $credit_customer = $data['credit_customer'];
        $sales_return_reference_no = $data['sales_return_reference_no'];
        $advance_payment_details = $data['advance_payment_details'];

        return view('sales.credit_sales', compact('invoice', 'credit_customer', 'sales_return_reference_no','advance_payment_details'));
    }

    public function billingDetails()
    {

        $credit_customer = DB::table('credit_customers')
            ->select('customers.*')
            ->leftJoin('customers', 'customers.id', 'credit_customers.customer_id')
            ->get();


        $sales_return_reference_no = DB::table('foodcity_sales_return_details')
            ->select('foodcity_sales.invoice_no', 'foodcity_sales_return_details.sales_id', 'foodcity_sales_return_details.amount as total', 'foodcity_sales_return_details.id as foodcity_sales_return_details_id')
            ->leftJoin('foodcity_sales', 'foodcity_sales.id', 'foodcity_sales_return_details.sales_id')
            ->where('foodcity_sales_return_details.cash', 'UnPaid')
            ->orderByDesc('foodcity_sales_return_details.id')
            ->get();

        $advance_payment_details = DB::table('advance_payments')
            ->select('advance_payments.*','customers.name','customers.phone_number')
            ->leftJoin('customers', 'customers.id','advance_payments.coustomer_id')
            ->where('advance_payments.buying_status', 'NotBuy')
            ->orderByDesc('advance_payments.id')
            ->get();

        $arry = [
            'credit_customer' => $credit_customer,
            'sales_return_reference_no' => $sales_return_reference_no,
            'advance_payment_details' => $advance_payment_details,
        ];

        return $arry;
    }

    public function search(Request $request)
    {
        $searchType = $request->searchType;
        $barcode = $request->barcode;
        $search = $request->search;

        //search by bardcode
        if ($searchType == 1) {
            $products = DB::table('food_city_products')
                ->select('food_city_products.*')
                ->groupBy('food_city_products.bar_code')
                ->where('product_code', 'like', '%' . $barcode . '%')
                ->where('food_city_products.status', '=', 1)
                ->where('food_city_products.is_out_of_stock', '=', 0) // have stock
                ->limit(100)
                ->get();

            //search by name, product code or categorey
        } elseif ($searchType == 2) {
            $products = DB::table('food_city_products')
                ->select('food_city_products.*')
                ->groupBy('food_city_products.bar_code')
                ->where('name', 'like', '%' . $search . '%')
                ->where('food_city_products.status', '=', 1)
                ->where('food_city_products.is_out_of_stock', '=', 0) // have stock
                ->limit(100)
                ->get();
        }

        $arry = [];
        //get availalbe quantity
        foreach ($products as $product) {

            $returnsQuantity = 0;
            $salesQuantity = 0;
            $cancelQuantity = 0;
            $stockTransferQuantity = 0;
            $foodcity_stock_adjustment = 0;
            $foodcity_company_return = 0;

            $return_product_count = DB::table('foodcity_product_returns')
                ->where('product_id', $product->id)
                ->value(DB::raw('SUM(return_qty) as returns_quantity'));
            if ($return_product_count) {
                $product->returns_quantity = $returnsQuantity = (float) $return_product_count;
            } else {
                $product->returns_quantity = 0;
            }

            $sales_product_count = DB::table('foodcity_product_sales')
                ->where('product_id', $product->id)
                ->value(DB::raw('SUM(quantity) as sales_quantity'));
            if ($sales_product_count) {
                $product->sales_quantity = $salesQuantity = (float) $sales_product_count;
            } else {
                $product->sales_quantity = 0;
            }

            $cancel_product_count = DB::table('foodcity_sales_returns')
                ->where('product_id', $product->id)
                ->value(DB::raw('SUM(return_quantity) as cancel_quantity'));
            if ($cancel_product_count) {
                $product->cancel_quantity = $cancelQuantity = (float) $cancel_product_count;
            } else {
                $product->cancel_quantity = 0;
            }

            $stock_transfer_count = DB::table('sales_stock_transfer')
                ->where('foodcity_product_id', $product->id)
                ->where('status', 'out') // foodcity product transfer
                ->value(DB::raw('SUM(quantity)'));
            if ($stock_transfer_count) {
                $product->stockTransferQuantity = $stockTransferQuantity = (float) $stock_transfer_count;
            } else {
                $product->stockTransferQuantity = 0;
            }

            $foodcity_stock_adjustment = DB::table('foodcity_stock_adjustment')
                ->where('foodcity_product_id', $product->id)
                ->SUM('quantity');

            $foodcity_company_return = DB::table('foodcity_company_return')
                ->where('foodcity_product_id', $product->id)
                ->SUM('return_qty');

            if (($product->quantity - $returnsQuantity - $salesQuantity + $cancelQuantity - $stockTransferQuantity + $foodcity_stock_adjustment - $foodcity_company_return) > 0) { // product have
                $product->now_have_quantity = $product->quantity - $returnsQuantity - $salesQuantity + $cancelQuantity - $stockTransferQuantity + $foodcity_stock_adjustment - $foodcity_company_return;

                array_push($arry, $product);
            } else {
                DB::table('food_city_products')
                    ->where('id', $product->id)
                    ->update(['is_out_of_stock' => 1, 'updated_at' => now()]);
            }
        }

        return response()->json(['success' => $arry]);
    }


    public function store(Request $request)
    {
        try {

            //get login user id
            $userId = Auth::user()->id;

            //get data from form sumbision
            $cash = $request->payments['cashPayment'];
            $card = $request->payments['cardPayment'];
            $credit = $request->payments['creditPayment'];
            $cheque = $request->payments['chequePayment'];
            $chequeNo = $request->payments['chequeNo'];
            $chequeDate = $request->payments['chequeDate'];
            $onlinePayment = $request->payments['onlinePayment'];
            $return_amount = isset($request->payments['return_amount']) ? $request->payments['return_amount'] : 0;
            $sales_id = isset($request->payments['sales_id']) ? $request->payments['sales_id'] : null;
            $advance_amount = isset($request->payments['advance_amount']) ? $request->payments['advance_amount'] : 0;
            $advance_payment_id = isset($request->payments['advance_payment_id']) ? $request->payments['advance_payment_id'] : null;
            $foodcity_sales_return_details_id = isset($request->payments['foodcity_sales_return_details_id']) ? $request->payments['foodcity_sales_return_details_id'] : null;

            $items = $request->items;
            $invoiceNo = $request->invoiceNo;
            $customerName = $request->customer['name'];
            $customerPhone = $request->customer['phone'];

            $customer_type = $request->customerType['customer_type'];
            $credit_customer_id = $request->customerType['credit_customer_id'];

            $total = $request->amount['total'];
            $totalPayableDiscount = $request->amount['totalPayableDiscount'];
            $totalPayable = $request->amount['totalPayable'];

            if ($customer_type == 'Customer') {
                //check customer name if null
                $customerName = $customerName ? $customerName : 'null';

                if ($customerPhone && (8 < strlen($customerPhone)) && (strlen($customerPhone) < 11)) {
                    //find customer id
                    $customer = DB::table('customers')->where('phone_number', '=', $customerPhone)->first();
                    //if customer not already exsiting
                    if (!$customer) {
                        DB::table('customers')->insert([
                            'phone_number' => $customerPhone,
                            'name' => $customerName,
                        ]);
                    }
                    $customers = DB::table('customers')->where('phone_number', '=', $customerPhone)->first();

                    $customerType = $customer_type;
                    $customerId = $customers->id;
                } else {
                    $customerType = $customer_type;
                    $customerId = null;
                }
            } else { // credit customer
                $customerType = $customer_type;
                $customerId = $credit_customer_id;
            }

            $paymentStatus = 1;
            //check if payment by credit or cheque
            if ($credit > 0 || $cheque > 0) {
                $paymentStatus = 0;
            }

            $maxBillId = (int) DB::table('foodcity_sales')->max('id') + 1;
            $padStr = str_pad(substr($maxBillId, -4), 4, 0, STR_PAD_LEFT);
            $invoiceNo = $invoiceNo . $padStr;

            DB::beginTransaction();
            //store data to foodcity sales table //
            $sales = new Sales();
            $sales->customer_type = $customerType;
            $sales->billing_date = date('Y-m-d');
            $sales->cash_payment = $cash;
            $sales->card_payment = $card;
            $sales->loyality_payment = 0;
            $sales->credit_payment = $credit;
            $sales->cheque_payment = $cheque;
            $sales->cheque_number = $chequeNo;
            $sales->cheque_date = $chequeDate;
            $sales->user_id = $userId;
            $sales->customer_id = $customerId;
            $sales->payment_status = $paymentStatus;
            $sales->sub_total = $total;
            $sales->discount_amount = 0;
            $sales->total_bill_discount = $totalPayableDiscount;
            $sales->amount = $total;
            $sales->invoice_no = $invoiceNo;
            $sales->is_cancelled = 0;
            $sales->sales_id = $sales_id;
            $sales->sales_return_amount = $return_amount;
            $sales->advance_payment_amount = $advance_amount;
            $sales->advance_payment_id = $advance_payment_id;
            $sales->online_payment = $onlinePayment;
            $sales->save();

            $temp_sales = new TempoerySalesModel();
            $temp_sales->sales_id = $sales->id;
            $temp_sales->save();

            $totalDiscount = 0;
            //store items data to product sales table
            foreach ($items as $item) {
                $salesProduct = new ProductSales();
                $salesProduct->sales_id = $sales->id;
                $salesProduct->product_id = $item['id'];
                $salesProduct->amount = $item['price'];
                $salesProduct->discount = $item['discount'];
                $salesProduct->quantity = $item['qty'];
                $salesProduct->bar_code = $item['barCode'];
                $salesProduct->save();

                $totalDiscount += (float)$item['qty'] * (float)$item['discount'];
            }

            $sales->discount_amount = $totalDiscount;
            $sales->save();

            // status change
            if ($foodcity_sales_return_details_id != null) {
                DB::table('foodcity_sales_return_details')
                    ->where('id', $foodcity_sales_return_details_id)
                    ->update(['cash' => 'ReturnThing', 'updated_at' => now()]);
            }
            // status change
            if ($advance_payment_id != null) {
                DB::table('advance_payments')
                    ->where('id', $advance_payment_id)
                    ->update(['buying_status' => 'Buy', 'sales_id' => $sales->id, 'updated_at' => now()]);
            }

            $details = DB::table('foodcity_sales')
                ->select('employees.f_name as billUser', 'customers.name as customerName', 'foodcity_sales.invoice_no')
                ->leftJoin('users', 'users.id', 'foodcity_sales.user_id')
                ->leftJoin('employees', 'employees.id', 'users.emp_id')
                ->leftJoin('customers', 'customers.id', 'foodcity_sales.customer_id')
                ->where('foodcity_sales.id', $sales->id)
                ->first();

            DB::commit();
            return response()->json(['success' => 'success', 'details' => $details]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th]);
        }
    }

    public function printDocs()
    {
        return view('sales.billing_print');
    }

    
}
