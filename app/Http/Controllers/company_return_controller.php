<?php

namespace App\Http\Controllers;

use App\Models\CompanyReturnModel;
use App\Models\InventoryProduct;
use App\Models\InventoryPurchaseOrder;
use App\Models\InventoryReturnReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class company_return_controller extends Controller
{
    public function company_return_view(Request $request)
    {
        $month = isset($request->month) ? $request->month : date('Y-m');

        $company_returns = DB::table('foodcity_company_return')
            ->select('foodcity_company_return.*','food_city_products.product_code','food_city_products.name',
            'inventory_purchase_orders.pur_ord_bill_no','inventory_sellers.seller_name',)
            ->leftJoin('food_city_products', 'food_city_products.id', 'foodcity_company_return.foodcity_product_id')
            ->leftJoin('inventory_purchase_orders', 'inventory_purchase_orders.id', 'foodcity_company_return.purchase_order_id')
            ->leftJoin('inventory_sellers', 'inventory_sellers.id', 'inventory_purchase_orders.seller_id')
            ->leftJoin('employees', 'employees.id', 'foodcity_company_return.emp_id')
            ->where(DB::raw('DATE_FORMAT(foodcity_company_return.date, "%Y-%m")'), $month)
            ->get();

        return view('company_return', compact('company_returns','month'));
    }

    public function companyReturnAdd()
    {
        $reasons = InventoryReturnReason::all();
        return view('company_return_Add',compact('reasons'));
    }

    public function companyReturnAddProcess(Request $request)
    {

        foreach ($request->foodcity_produt_id as $key => $value) {
            if (in_array($value, $request->check_id)) {
                $company_return = new CompanyReturnModel();
                $company_return->foodcity_product_id = $value;
                $company_return->purchase_order_id = $request->pur_ord_id[$key];
                $company_return->reason = $request->reason_id[$key];
                $company_return->qty = $request->qty[$key];
                $company_return->return_qty = $request->return_qty[$key];
                $company_return->emp_id = Auth::user()->emp_id;
                $company_return->date = date('Y-m-d');
                $company_return->save();
            }
        }

       
        return redirect('/company-return')->with('success', 'successfully recorded');
    }

    public function PurchaseItemview(Request $request)
    {
        $products = DB::table('food_city_products')
                ->select('food_city_products.*')
                ->where('food_city_products.item_id', '=', $request->product_id)
                ->where('food_city_products.is_out_of_stock', '=', 0) // have stock
                ->get();

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
}
