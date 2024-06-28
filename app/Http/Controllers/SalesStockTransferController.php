<?php

namespace App\Http\Controllers;

use App\Models\SalesStockTransferModel;
use App\Models\foodCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesStockTransferController extends Controller
{
    public function stocktransferview()
    {
        $stocktransfer=DB::table('sales_stock_transfer')
                ->select('sales_stock_transfer.*','inventory_products.product_name','inventory_products.product_code','food_city_products.name as food_city_product_name')
                ->leftJoin('inventory_products', 'inventory_products.id','=', 'sales_stock_transfer.inventory_product_id')
                ->leftJoin('food_city_products', 'food_city_products.id','=', 'sales_stock_transfer.foodcity_product_id')
                ->orderByDesc('sales_stock_transfer.id')
                ->get();


        return view('sales.stocktransferview',compact('stocktransfer'));
    }

    // search inventory products
    public function searchProducts(Request $request)
    {
        $key = $request->q;

        $Productdetails=DB::table('inventory_products')
                            ->select('id', 'product_name', 'product_code')
                            ->where('product_name', 'like', '%' . $key . '%')
                            ->orWhere('product_code', 'like', '%' . $key . '%')
                            ->orderByDesc('id')
                            ->limit(30)
                            ->get();

        return $Productdetails;
    }

    // search inventory products
    public function searhfoodcityProducts(Request $request)
    {
        $key = $request->c;

        $products = DB::table('food_city_products')
                    ->select('id', 'name', 'quantity', 'sales_price')
                    ->where('name', 'like', '%' . $key . '%')
                    ->where('food_city_products.status', '=', 1)
                    ->where('food_city_products.is_out_of_stock', '=', 0); // have stock
                    if ($key == "") {
        $products = $products->limit(30);
                    }
        $products = $products->get();


        $arry = [];
        //get availalbe quantity
        foreach ($products as $product) {

            $returnsQuantity = 0;
            $salesQuantity = 0;
            $cancelQuantity = 0;
            $stockTransferQuantity = 0;
            $foodcity_company_return = 0;

            $return_product_count = DB::table('foodcity_product_returns')
                            ->where('product_id', $product->id)
                            ->value(DB::raw('SUM(return_qty) as returns_quantity'));
            if ($return_product_count) {
                $product->returns_quantity = $returnsQuantity = (double) $return_product_count;
            } else {
                $product->returns_quantity = 0;
            }

            $sales_product_count = DB::table('foodcity_product_sales')
                            ->where('product_id', $product->id)
                            ->value(DB::raw('SUM(quantity) as sales_quantity'));
            if ($sales_product_count) {
                $product->sales_quantity = $salesQuantity = (double) $sales_product_count;
            } else {
                $product->sales_quantity = 0;
            }

            $cancel_product_count = DB::table('foodcity_sales_returns')
                            ->where('product_id', $product->id)
                            ->value(DB::raw('SUM(return_quantity) as cancel_quantity'));
            if ($cancel_product_count) {
                $product->cancel_quantity = $cancelQuantity = (double) $cancel_product_count;
            } else {
                $product->cancel_quantity = 0;
            }

            $stock_transfer_count = DB::table('sales_stock_transfer')
                            ->where('foodcity_product_id', $product->id)
                            ->where('status', 'out')
                            ->value(DB::raw('SUM(quantity)'));
                            
            if ($stock_transfer_count) {
                $product->stockTransferQuantity = $stockTransferQuantity = (double) $stock_transfer_count;
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
            } 
        }

        return $arry;
    }

    public function stocktransferAdd(Request $request)
    {

        $id = $request->id; // id

        if ($id == 0) {

            $request-> validate([
                'foodcity_product_id' => 'required',
                'inventory_product_id' => 'required',
            ]);

            $product = DB::table('inventory_products')->where('id', $request->inventory_product_id)->first();
            $product_name = $product->product_name;
            $product_code = $product->product_code;
            $product_sub_cat_id = $product->product_sub_cat_id;
            $type = $product->type;

            $foodCityProduct = DB::table('food_city_products')->where('id', $request->foodcity_product_id)->first();
            $purchase_order_id = $foodCityProduct->purchase_order_id;
            $transection_id = $foodCityProduct->transection_id;
            $purchase_price = $foodCityProduct->purchase_price;
            $expire_date = $foodCityProduct->expire_date;
            $scale_id = $foodCityProduct->scale_id;
    
            $stock_transfer_foodcity = new SalesStockTransferModel();
            $stock_transfer_foodcity->foodcity_product_id = $request->foodcity_product_id;
            $stock_transfer_foodcity->quantity = $request->foodcity_productquantity;
            $stock_transfer_foodcity->status = 'out';
            $stock_transfer_foodcity->quantity_type = $type;
            $stock_transfer_foodcity->save();

            $stock_transfer_inve = new SalesStockTransferModel();
            $stock_transfer_inve->inventory_product_id = $request->inventory_product_id;
            $stock_transfer_inve->quantity = $request->inventory_changequantity;
            $stock_transfer_inve->status = 'in';
            $stock_transfer_inve->quantity_type = $scale_id;
            $stock_transfer_inve->save();

            

            $foodCity = new foodCity();
            $foodCity->name = $product_name;
            $foodCity->product_code = $product_code;
            $foodCity->quantity = $request->inventory_changequantity;
            $foodCity->status = 0;
            $foodCity->sub_category_id = $product_sub_cat_id;
            $foodCity->purchase_order_id = $purchase_order_id;
            $foodCity->purchase_price = $purchase_price / $request->divion_by;
            $foodCity->scale_id = $type;
            $foodCity->expire_date = $expire_date;
            $foodCity->user_id = Auth::user()->emp_id;
            $foodCity->transection_id = $transection_id;
            $foodCity->item_id  = $request->inventory_product_id;  //inventory product table id
            $foodCity->save();

            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("stock transfer Add");
            } catch (\Throwable $th) {
                return back()->with('error', 'Something  wrong');
            }

    
            return redirect()->route('sales.stocktransferview')
                        ->with('success', 'stock transfer Add successfully!!');

        } else {

            $request-> validate([
                // 'sales_id' => 'required',
                // 'commision_cust_id' => 'required',
            ]);
    
            $stock_transfer_foodcity = SalesStockTransferModel::find($id);
            $stock_transfer_foodcity->product_id = $request->product_id;
            $stock_transfer_foodcity->quantity = $request->productquantity;
            $stock_transfer_foodcity->foodcity_product_id = $request->changeproduct_id;
            $stock_transfer_foodcity->quantity_type = $request->changequantity;
            
            $stock_transfer_foodcity->save();
            return redirect()->route('sales.stocktransferview')
            ->with('success', 'Details successfully Updated!!');
        }
    }
}
