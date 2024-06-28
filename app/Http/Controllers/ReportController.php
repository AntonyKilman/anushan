<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //view sales report
    public function sales(Request $request)
    {
        if ($request->from) {
            $from = $request->from;
        } else {
            $from = now()->subDays(30)->format('Y-m-d');
        }
        if ($request->to) {
            $to = $request->to;
        } else {
            $to = now()->format('Y-m-d');
        }

        $bill_type = isset($request->bill_type) ? $request->bill_type : 'All';

        //default filter by one moth

        $salesReport = DB::table('foodcity_product_sales')
            ->select(DB::raw('SUM(food_city_products.purchase_price * foodcity_product_sales.quantity ) as totalPurchasePrice'), 'foodcity_sales.*', 'foodcity_sales.amount as totalAmount', DB::raw('SUM(foodcity_product_sales.quantity) as productQuantity'))
            ->join('foodcity_sales', 'foodcity_sales.id', '=', 'foodcity_product_sales.sales_id')
            ->join('food_city_products', 'food_city_products.id', '=', 'foodcity_product_sales.product_id')
            ->groupBy('foodcity_product_sales.sales_id')
            ->where('is_cancelled', '=', 0)
            ->whereBetween(DB::raw('DATE_FORMAT(foodcity_sales.created_at, "%Y-%m-%d")'), [$from, $to])
            ->orderBy('foodcity_sales.id', 'DESC');
            if ($bill_type != 'All') {
                $salesReport = $salesReport->where('foodcity_sales.customer_type', $bill_type);
                                }
                $salesReport = $salesReport->get();

        return view('sales.sales_report', compact('salesReport', 'from', 'to','bill_type'));
    }
    //view sales report
    public function temp_sales(Request $request)
    {

        $temp_salesReport = DB::table('foodcity_sales_tempeory')
            ->select(DB::raw('SUM(food_city_products.purchase_price * foodcity_product_sales.quantity ) as totalPurchasePrice'), 'foodcity_sales.*', 'foodcity_sales.amount as totalAmount', DB::raw('SUM(foodcity_product_sales.quantity) as productQuantity'))
            ->leftJoin('foodcity_sales', 'foodcity_sales.id', '=', 'foodcity_sales_tempeory.sales_id')
            ->leftJoin('foodcity_product_sales','foodcity_product_sales.sales_id', '=', 'foodcity_sales.id')
            ->leftJoin('food_city_products', 'food_city_products.id', '=', 'foodcity_product_sales.product_id')
            ->groupBy('foodcity_product_sales.sales_id')
            ->get();

        $startDateTime = DB::table('foodcity_sales_tempeory')->first('created_at');
        if ($startDateTime) {
            $startDateTime = $startDateTime->created_at;
        } 
        $endDateTime = DB::table('foodcity_sales_tempeory')->orderByDesc('id')->first('created_at');
        if ($endDateTime) {
            $endDateTime = $endDateTime->created_at;
        } 
        
        $lastId = DB::table('foodcity_sales_tempeory')->orderByDesc('id')->first('id');
        if ($lastId) {
            $lastId = $lastId->id;
        } 

        return view('sales.temp_sales_report', compact('temp_salesReport', 'startDateTime', 'endDateTime', 'lastId'));
    }

    public function temp_sales_delete($id)
    {
        DB::table('foodcity_sales_tempeory')
        ->where('id', '<=', $id)
        ->delete();

        return 'done';
    }

    //view individual sales by bill
    public function salesView($id)
    {
        $salesReportView = DB::table('foodcity_product_sales')
            ->select('foodcity_product_sales.*', 'food_city_products.*', 'foodcity_product_sales.quantity as sale_quantity')
            ->where('foodcity_product_sales.sales_id', '=', $id)
            ->join('food_city_products', 'food_city_products.id', 'foodcity_product_sales.product_id')
            ->get();

        $invoiceNo = DB::table('foodcity_sales')
            ->select('foodcity_sales.is_cancelled', 'foodcity_sales.invoice_no', DB::raw('DATE(created_at) AS date'), DB::raw('TIME(created_at) AS time'))
            ->where('foodcity_sales.id', '=', $id)
            ->first();
        return view('sales.view_sales_report', compact('salesReportView', 'invoiceNo'));
    }

    //view return products report
    public function return(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
        } else {
            $from = now()->format('Y-m-d');
            $to = now()->format('Y-m-d');
        }

        $returnReport = DB::table('foodcity_product_returns')
            ->select('foodcity_product_returns.*', 'food_city_products.id', 'food_city_products.name', 'food_city_products.expire_date', 'food_city_products.transection_id', 'food_city_products.product_code')
            ->join('food_city_products', 'food_city_products.id', '=', 'foodcity_product_returns.product_id')
            ->whereBetween(DB::raw('DATE_FORMAT(foodcity_product_returns.created_at, "%Y-%m-%d")'), [$from, $to])
            ->orderBy('foodcity_product_returns.id', 'DESC')
            ->get();

        return view('sales.return_report', compact('returnReport', 'from', 'to'));
    }

    //stock report
    public function stock(Request $request)
    {

        $sub_cat_id = $request->product_sub_cat_id;

        $product_sub_categories = DB::table('inventory_product_sub_categories as ipsc')
        ->select('ipsc.*')
        ->get();

    $products = DB::table('food_city_products')
                ->select('food_city_products.*','inventory_product_sub_categories.product_sub_cat_name')
                ->leftJoin('inventory_product_sub_categories','inventory_product_sub_categories.id','food_city_products.sub_category_id')
                ->where('food_city_products.is_out_of_stock', '=', 0);// have stock
                if ($sub_cat_id) {
    $products = $products->where('food_city_products.sub_category_id',$sub_cat_id);
                }
    $products = $products->get();
                

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

        return view('sales.stock_report', compact('arry','product_sub_categories','sub_cat_id'));

    }

    //view sales cancel report
    public function salesCancel(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
        } else {
            $from = now()->format('Y-m-d');
            $to = now()->format('Y-m-d');
        }

        $salesCancelReport = DB::table('foodcity_sales_returns')
            ->select('foodcity_sales_returns.*', 'foodcity_sales.invoice_no', 'food_city_products.name as product_name')
            ->leftJoin('foodcity_sales', 'foodcity_sales.id', '=', 'foodcity_sales_returns.sales_id')
            ->leftJoin('food_city_products', 'food_city_products.id', '=', 'foodcity_sales_returns.product_id')
            ->whereBetween(DB::raw('DATE_FORMAT(foodcity_sales.created_at, "%Y-%m-%d")'), [$from, $to])
            ->get();
            
        return view('sales.sales_cancel', compact('salesCancelReport', 'from', 'to'));
    }

    //view individual sales cancel by bill
    public function salesCancelView($id)
    {
        $salesReportView = DB::table('foodcity_product_sales')
            ->select('foodcity_product_sales.*', 'food_city_products.*', 'foodcity_product_sales.quantity as sale_quantity')
            ->where('foodcity_product_sales.sales_id', '=', $id)
            ->join('food_city_products', 'food_city_products.id', 'foodcity_product_sales.product_id')
            ->get();

        $invoiceNo = DB::table('foodcity_sales')
            ->select('foodcity_sales.is_cancelled', 'foodcity_sales.invoice_no', DB::raw('DATE(created_at) AS date'), DB::raw('TIME(created_at) AS time'))
            ->where('foodcity_sales.id', '=', $id)
            ->first();
        return view('sales.sales_cancel_view', compact('salesReportView', 'invoiceNo'));
    }

    //view individual sales product report
    public function salesProduct(Request $request)
    {
        # code...
        $from_date = isset($request->from_date) ? $request->from_date : date('Y-m-d');
        $to_date = isset($request->to_date) ? $request->to_date : date('Y-m-d');

        $salesReportView = DB::table('food_city_products')
            ->selectRaw('
            inventory_products.product_name,
            food_city_products.purchase_price,
            sum(foodcity_product_sales.quantity) as quantity_sum,
            min(foodcity_product_sales.amount) as min_amount,
            max(foodcity_product_sales.amount) as max_amount,
            min(foodcity_product_sales.discount) as min_discount,
            max(foodcity_product_sales.discount) as max_discount,
            sum((foodcity_product_sales.amount - foodcity_product_sales.discount) * foodcity_product_sales.quantity) as sub_total
            
        ')
            ->join('foodcity_product_sales', 'foodcity_product_sales.product_id', 'food_city_products.id')
            ->join('inventory_products', 'inventory_products.id', 'food_city_products.item_id')
            ->groupBy('food_city_products.item_id')
            ->whereDate('foodcity_product_sales.created_at', '>=',  $from_date)
            ->whereDate('foodcity_product_sales.created_at', '<=', $to_date)
            ->get();


        // return $salesReportView; 

        return view('sales.sales_product_report')->with('salesReportView', $salesReportView)
            ->with('from', $from_date)
            ->with('to', $to_date);
    }

    // credit report
    public function creditReport(Request $request)
    {
        $from_date = isset($request->from_date) ? $request->from_date : date('Y-m-d');
        $to_date = isset($request->to_date) ? $request->to_date : date('Y-m-d');

        $data = DB::table('foodcity_sales as fcs')
            ->select('fcs.*', 'c.name as customer_name', 'c.phone_number')
            ->leftJoin('customers as c', 'c.id', 'fcs.customer_id')
            ->where('fcs.payment_status', 0)  // not fenish
            ->where('fcs.is_cancelled', 0)  // not cancell
            ->whereDate('fcs.billing_date', '>=', $from_date)
            ->whereDate('fcs.billing_date', '<=', $to_date)
            ->orderByDesc('fcs.id')
            ->get();

        return view('sales.report.creditReport', compact('data', 'from_date', 'to_date'));
    }
}
