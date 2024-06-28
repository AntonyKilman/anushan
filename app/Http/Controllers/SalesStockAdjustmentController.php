<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\inventory_stock_adjustment;
use App\Models\InventoryProduct;
use Illuminate\Support\Collection;
use \stdClass;

class SalesStockAdjustmentController extends Controller
{
    public function sales_stock(Request $request, $type)
    {
        if ($request->month) {
            $month  = $request->month;
        } else {
            $month = date('Y-m');
        }

        $product_id = $request->product_id;
        $product_type = $request->pur_item_qty_type;

        $product_sub_categories = DB::table('inventory_product_sub_categories')
        ->select('inventory_product_sub_categories.id', 'inventory_product_sub_categories.product_sub_cat_name')
        ->get();

        if (!isset($request->product_sub_cat_id) && $type == 'adjustment') {
            $sub_cat_id = DB::table('inventory_product_sub_categories')->first('id')->id;
        } else {
            $sub_cat_id = $request->product_sub_cat_id;
        }

        $foodcityitems = DB::table('food_city_products')
            ->select(
                'food_city_products.id as food_city_product_id',
                'food_city_products.name',
                'food_city_products.item_id',
                'inventory_products.product_sub_cat_id',
                'inventory_products.product_code',
                'inventory_product_types.product_type_name',
                'inventory_product_sub_categories.product_sub_cat_name',
                'food_city_products.purchase_order_id',
                'food_city_products.scale_id',
                DB::raw('SUM(food_city_products.quantity) as TOATAL_FOODCITY_QTY'),
                DB::raw('SUM(food_city_products.purchase_price * food_city_products.quantity) as TOATAL_PURCHASE_AMOUNT')
            )
            ->leftJoin('inventory_products', 'inventory_products.id', 'food_city_products.item_id')
            ->leftJoin('inventory_product_sub_categories', 'inventory_product_sub_categories.id', 'food_city_products.sub_category_id')
            ->leftJoin('inventory_product_types', 'inventory_product_types.id', 'inventory_products.product_type_id')
            ->where(DB::raw('DATE_FORMAT(food_city_products.created_at, "%Y-%m")'), $month)
            ->where('food_city_products.status', '=', 1);
            if ($sub_cat_id) {
                $foodcityitems = $foodcityitems->where('inventory_product_sub_categories.id', $sub_cat_id);
            }
            if ($product_id) {
                $foodcityitems = $foodcityitems->where('food_city_products.item_id', $product_id);
            }
            if ($product_type) {
                $foodcityitems = $foodcityitems->where('food_city_products.scale_id', $product_type);
            }
            $foodcityitems = $foodcityitems->groupBy('food_city_products.item_id', 'food_city_products.purchase_order_id', 'food_city_products.scale_id')
            ->orderByDesc('food_city_products.id')
            ->get();

            $collection1 = collect();
            foreach ($foodcityitems as $item) {
                $SalesQuantity = DB::table('foodcity_product_sales')
                    ->leftJoin('food_city_products', 'food_city_products.id', 'foodcity_product_sales.product_id')
                    ->where('product_id', $item->food_city_product_id)
                    ->where('food_city_products.scale_id', $item->scale_id)
                    ->where('food_city_products.purchase_order_id', $item->purchase_order_id)
                    ->SUM('foodcity_product_sales.quantity');

                $Return_Quqntity = DB::table('foodcity_sales_returns')
                    ->leftJoin('food_city_products', 'food_city_products.id', 'foodcity_sales_returns.product_id')
                    ->where('food_city_products.id', $item->food_city_product_id)
                    ->where('food_city_products.scale_id', $item->scale_id)
                    ->where('food_city_products.purchase_order_id', $item->purchase_order_id)
                    ->SUM('foodcity_sales_returns.return_quantity');

                // $Product_Return_Quqntity = DB::table('foodcity_product_returns')
                //     ->where('product_id', $item->item_id)
                //     ->SUM('foodcity_product_returns.return_qty');
                $Product_Return_Quqntity = 0;

                $stock_transfer_Quqntity = DB::table('sales_stock_transfer')
                    ->leftJoin('food_city_products', 'food_city_products.id', 'sales_stock_transfer.foodcity_product_id')
                    ->where('sales_stock_transfer.foodcity_product_id', $item->food_city_product_id)
                    ->where('food_city_products.scale_id', $item->scale_id)
                    ->where('food_city_products.purchase_order_id', $item->purchase_order_id)
                    ->where('sales_stock_transfer.status', 'out') // foodcity product transfer
                    ->SUM('sales_stock_transfer.quantity');

                $foodcity_stock_adjustment = DB::table('foodcity_stock_adjustment')
                                ->where('foodcity_product_id', $item->food_city_product_id)
                                ->where('product_id', $item->item_id)
                                ->where('purchase_id', $item->purchase_order_id)
                                ->SUM('quantity');

                    
                $item->UNIT_PRICE = $item->TOATAL_PURCHASE_AMOUNT / $item->TOATAL_FOODCITY_QTY;
                    
                $item->FINAL_STOCK = $item->TOATAL_FOODCITY_QTY  + $Return_Quqntity + $foodcity_stock_adjustment - $SalesQuantity - $Product_Return_Quqntity - $stock_transfer_Quqntity;
                $item->FINAL_STOCK_copy = $item->TOATAL_FOODCITY_QTY - $SalesQuantity + $Return_Quqntity + $foodcity_stock_adjustment - $Product_Return_Quqntity - $stock_transfer_Quqntity;
                $item->FINAL_STOCK_AMOUNT = floatval($item->TOATAL_PURCHASE_AMOUNT) - (floatval($SalesQuantity + $Product_Return_Quqntity + $stock_transfer_Quqntity) * floatval($item->UNIT_PRICE)) + (floatval($item->UNIT_PRICE) * (floatval($Return_Quqntity + $foodcity_stock_adjustment)));
                $item->FINAL_STOCK_AMOUNT_copy = floatval($item->TOATAL_PURCHASE_AMOUNT) - (floatval($SalesQuantity + $Product_Return_Quqntity + $stock_transfer_Quqntity) * floatval($item->UNIT_PRICE)) + (floatval($item->UNIT_PRICE) * (floatval($Return_Quqntity + $foodcity_stock_adjustment)));
                $item->FINAL_STOCK_AMOUNT = number_format((float)$item->FINAL_STOCK_AMOUNT, 2, '.', '');
                $item->FINAL_STOCK = number_format((float)$item->FINAL_STOCK, 2, '.', '');
                $item->FINAL_STOCK_copy = number_format((float)$item->FINAL_STOCK_copy, 2, '.', '');
                $item->FINAL_STOCK_AMOUNT_copy = number_format((float)$item->FINAL_STOCK_AMOUNT_copy, 2, '.', '');

                // set array for group by product and type
                if ($foodcityitems[0] == $item) {
                    $collection1->push($item);
                } else {
                    $find_data = $collection1->where('item_id', $item->item_id)
                        ->where('scale_id', $item->scale_id)
                        ->first();

                    if ($find_data) {
                        $find_data->FINAL_STOCK = floatval($find_data->FINAL_STOCK) + floatval($item->FINAL_STOCK);
                        $find_data->FINAL_STOCK_AMOUNT = floatval($find_data->FINAL_STOCK_AMOUNT) + floatval($item->FINAL_STOCK_AMOUNT);
                    } else {
                        $collection1->push($item);
                    }
                }
            } 

        switch ($type) {
            // this condition use to get product stock by purchase order(call in ajax method)
        case 'stockByProduct':
            return $foodcityitems;
            break;
        case 'adjustment':
            return view('sales.SalesStockAdjustment', compact('month', 'collection1', 'product_sub_categories', 'sub_cat_id'));
            break;
        case 'report':
            $total = $collection1->sum('FINAL_STOCK_AMOUNT');
            $collection1 = $collection1->where('FINAL_STOCK', '>', 0);
            return view('sales.report.newStockReport', compact('month', 'collection1', 'total', 'product_sub_categories', 'sub_cat_id'));
            break;
        }

    }

    public function salesstockAdjustmentStore(Request $request)
    {
        $length = count($request->product_id);

        for ($i = 0; $i < $length; $i++) {

            $check = $request->product_id[$i] . '-' . $request->purchase_id[$i];

            if ($request->$check) {
                $data = new inventory_stock_adjustment();
                $data->foodcity_product_id = $request->food_city_product_id[$i];
                $data->product_id = $request->product_id[$i];
                $data->purchase_id = $request->purchase_id[$i];
                $data->qty_type = $request->qty_type[$i];
                // $data->quantity = number_format($request->change_qty[$i], 2)  - number_format($request->final_stock[$i], 2);
                $data->quantity = number_format((float)$request->change_qty[$i], 2, '.', '')  - number_format((float)$request->final_stock[$i], 2, '.', '');
                $data->date = $request->date;
                try {
                    $data->save();

                    DB::table('food_city_products')
                    ->where('id', $request->food_city_product_id[$i])
                    ->update(['is_out_of_stock' => 0, 'updated_at' => now()]);

                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', 'Something wrong try again');
                }
            }
        }

        return redirect('/sales_stock_view/adjustment')->with('success', 'Successfully Updated');
    }

}
