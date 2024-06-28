<?php

namespace App\Http\Controllers;

use App\Models\inventory_purchase_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\inventory_stock_adjustment;
use App\Models\InventoryProduct;
use Illuminate\Support\Collection;
use \stdClass;

class inventoryNewStockController extends Controller
{
    
    public function productPrice(Request $request)
    {
        $supplier = $request->supplier;
        $products = $request->products;

        $purchased_Items = DB::table('inventory_purchase_items')
            ->leftJoin('inventory_products', 'inventory_products.id', 'inventory_purchase_items.product_id')
            ->leftJoin('inventory_purchase_orders', 'inventory_purchase_orders.id', 'inventory_purchase_items.purchase_order_id')
            ->leftJoin('inventory_sellers', 'inventory_sellers.id', 'inventory_purchase_orders.seller_id')
            ->select(
                'inventory_sellers.seller_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_products.id as product_id',
                'inventory_purchase_orders.seller_id'
            );
        if ($supplier) {
            $purchased_Items = $purchased_Items->where('inventory_purchase_orders.seller_id', $supplier);
        }

        if ($products) {
            $purchased_Items = $purchased_Items->where('product_id', $products);
        }

        $purchased_Items = $purchased_Items->groupBy(
            'product_id',
            'inventory_purchase_orders.seller_id',
            'inventory_sellers.seller_name',
            'inventory_products.product_name',
            'inventory_products.product_code',
        )
            ->get();
        return $purchased_Items;
        $created_array = [];


        foreach ($purchased_Items as $row) {

            $data = DB::table('inventory_purchase_items')
                ->where('inventory_purchase_items.product_id', $row->product_id)
                ->leftJoin('inventory_purchase_orders', 'inventory_purchase_orders.id', 'inventory_purchase_items.purchase_order_id')
                ->leftJoin('inventory_sellers', 'inventory_sellers.id', 'inventory_purchase_orders.seller_id')
                ->leftJoin('inventory_products', 'inventory_products.id', 'inventory_purchase_items.product_id')
                ->where('seller_id', $row->seller_id)
                ->orderBy('purchase_order_id', "desc")
                ->select(
                    'inventory_products.product_code',
                    'inventory_sellers.seller_name',
                    'inventory_products.product_name',
                    'inventory_purchase_items.pur_item_amount',
                    'inventory_purchase_items.pur_item_qty'
                )
                ->first();


            array_push($created_array, json_decode(json_encode($data)));
            // array_push($created_array, json_decode(json_encode($data), true));

        }
        $inventory_sellers = DB::table('inventory_sellers')->get();
        $inventory_products = DB::table('inventory_products')->get();

    
        // $created_array = json_decode(json_encode($created_array));



        // return view('reports.ProductPriceReport', compact('created_array', 'supplier', 'products', 'purchased_Items', 'inventory_sellers', 'inventory_products'));
        return view('reports.ProductPriceReport', ['purchased_Items' => $purchased_Items]);
    }

    public function newproductPrice(Request $request)
    {
        $sub_cat = $request->sub_category;
        $purchase_product = inventory_purchase_item::groupBy('inventory_purchase_items.product_id')
            ->join('inventory_products', 'inventory_products.id', 'inventory_purchase_items.product_id')
            ->join('inventory_product_sub_categories', 'inventory_product_sub_categories.id', 'inventory_products.product_sub_cat_id')
            ->select('inventory_purchase_items.product_id', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_product_sub_categories.product_sub_cat_name', 'inventory_purchase_items.product_id', 'inventory_product_sub_categories.id as sub_cat_id')
            ->orderBy('inventory_products.product_code', 'asc');
        if ($sub_cat) {
            $purchase_product = $purchase_product->where('inventory_product_sub_categories.id', $sub_cat);
        }
        $purchase_product = $purchase_product->get();
        $subCategories = DB::table('inventory_product_sub_categories')->get();

        return view('reports.newProductPrice', compact('purchase_product', 'subCategories', 'sub_cat'));
    }

    public function productLastPrice($product)
    {
        $datas = inventory_purchase_item::where('inventory_purchase_items.product_id', $product)
            ->join('inventory_purchase_orders', 'inventory_purchase_orders.id', 'inventory_purchase_items.purchase_order_id')
            ->select('inventory_purchase_items.id as pur_item_id')
            ->groupBy('inventory_purchase_orders.seller_id')
            ->select('inventory_purchase_orders.seller_id')
            ->get();
        // return $datas;

        $created_array = [];
        foreach ($datas as $row) {

            $get_obj = inventory_purchase_item::where('inventory_purchase_items.product_id', $product)
                ->join('inventory_products', 'inventory_products.id', 'inventory_purchase_items.product_id')
                ->join('inventory_purchase_orders', 'inventory_purchase_orders.id', 'inventory_purchase_items.purchase_order_id')
                ->join('inventory_sellers', 'inventory_sellers.id', 'inventory_purchase_orders.seller_id')
                ->where('inventory_purchase_orders.seller_id', $row->seller_id)
                ->orderBy('inventory_purchase_items.id', 'desc')
                ->select(
                    'inventory_sellers.seller_name',
                    'inventory_purchase_orders.id as pur_order_id',
                    'inventory_products.product_name',
                    'inventory_products.product_code',
                    'inventory_purchase_items.pur_item_amount',
                    'inventory_purchase_items.pur_item_qty',
                    'inventory_purchase_items.pur_item_qty_type',
                    'inventory_purchase_orders.date'
                )
                ->first();
            array_push($created_array, $get_obj);
        }
        return $created_array;
    }

    public function stockByDate(Request $request)
    {

        if ($request->to) {
            $to  = $request->to;
        } else {
            $to = Carbon::now();
            $to = $to->format('Y-m-d');
        }

        if ($request->from) {
            $from  = $request->from;
        } else {
            $from = Carbon::now()->subMonth(1);
            $from = $from->format('Y-m-d');
        }


        $product_id = $request->product_id;
        $product_type = $request->pur_item_qty_type;
        $sub_cat_id = $request->product_sub_cat_id;

        $new_product = DB::table('inventory_purchase_items')
            ->leftJoin('inventory_purchase_orders', 'inventory_purchase_orders.id', 'inventory_purchase_items.purchase_order_id')
            ->leftJoin('inventory_products', 'inventory_products.id', 'inventory_purchase_items.product_id')
            ->where('inventory_purchase_orders.date', '<=', $to)
            ->where('inventory_purchase_orders.date', '>=', $from);
        if ($sub_cat_id) {
            $new_product = $new_product->where('inventory_products.product_sub_cat_id', $sub_cat_id);
        }
        $new_product = $new_product->groupBy('inventory_purchase_items.product_id')
            ->select(
                'inventory_purchase_items.product_id',
                'inventory_products.product_sub_cat_id',
                DB::raw('SUM(inventory_purchase_items.pur_item_qty) as PUR_QUANTITYS'),
                DB::raw('SUM(inventory_purchase_items.pur_item_amount) as PUR_AMOUNTS')
            )
            ->get();

        $indoors_product = DB::table('inventory_indoor_transfer')
            ->leftJoin('inventory_products', 'inventory_products.id', 'inventory_indoor_transfer.product_id')
            ->where(DB::raw('DATE_FORMAT(inventory_indoor_transfer.updated_at, "%Y-%m-%d")'), '>=', $from)
            ->where(DB::raw('DATE_FORMAT(inventory_indoor_transfer.updated_at, "%Y-%m-%d")'), '<=', $to)
            ->groupBy('inventory_indoor_transfer.product_id');

        if ($sub_cat_id) {
            $indoors_product = $indoors_product->where('inventory_products.product_sub_cat_id', $sub_cat_id);
        }
        $indoors_product = $indoors_product->select(
            'inventory_indoor_transfer.product_id',
            'inventory_products.product_sub_cat_id',
            DB::raw('SUM(inventory_indoor_transfer.transfer_quantity) as INDOOR_QUANTITY'),
            DB::raw('SUM(inventory_indoor_transfer.transfer_quantity*inventory_indoor_transfer.purchase_unit_price) as INDOOR_AMOUNT')
        )
            ->get();


        $purchasedItems = DB::table('inventory_purchase_items')
            ->leftJoin('inventory_purchase_orders', 'inventory_purchase_orders.id', 'inventory_purchase_items.purchase_order_id')
            ->leftJoin('inventory_products', 'inventory_products.id', 'inventory_purchase_items.product_id')
            ->leftJoin('inventory_product_sub_categories', 'inventory_product_sub_categories.id', 'inventory_products.product_sub_cat_id')
            ->where('inventory_purchase_orders.date', '<=', $from);
        if ($product_id) {
            $purchasedItems = $purchasedItems->where('inventory_purchase_items.product_id', $product_id);
        }
        if ($product_type) {
            $purchasedItems = $purchasedItems->where('inventory_purchase_items.pur_item_qty_type', $product_type);
        }

        if ($sub_cat_id) {
            $purchasedItems = $purchasedItems->where('inventory_product_sub_categories.id', $sub_cat_id);
        }
        $purchasedItems = $purchasedItems->groupBy('inventory_purchase_items.product_id', 'inventory_purchase_items.pur_item_qty_type', 'inventory_purchase_items.purchase_order_id')
            ->select(
                'inventory_products.product_name',
                'inventory_products.product_sub_cat_id',
                'inventory_products.product_code',
                'inventory_product_sub_categories.product_sub_cat_name',
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.product_id',
                'inventory_purchase_items.purchase_order_id',
                DB::raw('SUM(inventory_purchase_items.pur_item_qty) as TOATAL_PURCHASE_QTY'),
                DB::raw('SUM(inventory_purchase_items.pur_item_amount) as TOATAL_PURCHASE_AMOUNT')
            )
            ->get();

        $collection1 = collect();
        foreach ($purchasedItems as $row) {

            $row->UNIT_PRICE = $row->TOATAL_PURCHASE_AMOUNT / $row->TOATAL_PURCHASE_QTY;

            // insert indoor transfer qty
            $indoor_transfer = DB::table('inventory_indoor_transfer')
                ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $from)
                ->where('product_id', $row->product_id)
                ->where('purchase_id', $row->purchase_order_id)
                ->where('Qty_type', $row->pur_item_qty_type)
                ->SUM('transfer_quantity');

            // insert indoor return qty
            $indoor_return = DB::table('inventory_indoor_returns')
                ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $from)
                ->where('product_id', $row->product_id)
                ->where('purchase_order_id', $row->purchase_order_id)
                ->where('Qty_type', $row->pur_item_qty_type)
                ->SUM('return_qty');

            // insert outdoor return qty
            $outdoor_return = DB::table('inventory_outdoor_returns')
                ->where(DB::raw('DATE_FORMAT(updated_at, "%Y-%m-%d")'), '<=', $from)
                ->where('product_id', $row->product_id)
                ->where('purchase_order_id', $row->purchase_order_id)
                ->where('Qty_type', $row->pur_item_qty_type)
                ->SUM('return_qty');

            // insert stock adjustment qty
            $stock_adjustments = DB::table('inventory_stock_adjustment')
                ->where('date', '<=', $from)
                ->where('product_id', $row->product_id)
                ->where('purchase_id', $row->purchase_order_id)
                ->where('qty_type', $row->pur_item_qty_type)
                ->SUM('quantity');

            $row->FINAL_STOCK = $row->TOATAL_PURCHASE_QTY - $indoor_transfer + $indoor_return - $outdoor_return + $stock_adjustments;
            $row->FINAL_STOCK_copy = $row->TOATAL_PURCHASE_QTY - $indoor_transfer + $indoor_return - $outdoor_return + $stock_adjustments;
            $row->FINAL_STOCK_AMOUNT = floatval($row->TOATAL_PURCHASE_AMOUNT) - (floatval($indoor_transfer + $outdoor_return) * floatval($row->UNIT_PRICE)) + (floatval($row->UNIT_PRICE) * (floatval($indoor_return + $stock_adjustments)));
            $row->FINAL_STOCK_AMOUNT_copy = floatval($row->TOATAL_PURCHASE_AMOUNT) - (floatval($indoor_transfer + $outdoor_return) * floatval($row->UNIT_PRICE)) + (floatval($row->UNIT_PRICE) * (floatval($indoor_return + $stock_adjustments)));
            $row->FINAL_STOCK_AMOUNT = number_format((float)$row->FINAL_STOCK_AMOUNT, 2, '.', '');
            $row->FINAL_STOCK = number_format((float)$row->FINAL_STOCK, 2, '.', '');
            $row->FINAL_STOCK_copy = number_format((float)$row->FINAL_STOCK_copy, 2, '.', '');
            $row->FINAL_STOCK_AMOUNT_copy = number_format((float)$row->FINAL_STOCK_AMOUNT_copy, 2, '.', '');



            $check_PUR = $new_product->where('product_id', $row->product_id)->first();

            if (!($check_PUR)) {
                $row->PUR_QUANTITYS = 0.00;
                $row->PUR_AMOUNTS = 0.00;
            }

            $check_INDOOR = $indoors_product->where('product_id', $row->product_id)->first();

            if (!($check_INDOOR)) {
                $row->INDOOR_QUANTITY = 0.00;
                $row->INDOOR_AMOUNT = 0.00;
            }

            // set array for group by product and type
            if ($purchasedItems[0] == $row) {
                $collection1->push($row);
            } else {
                $find_data = $collection1->where('product_id', $row->product_id)
                    ->where('pur_item_qty_type', $row->pur_item_qty_type)
                    ->first();

                if ($find_data) {
                    $find_data->FINAL_STOCK = floatval($find_data->FINAL_STOCK) + floatval($row->FINAL_STOCK);
                    $find_data->FINAL_STOCK_AMOUNT = floatval($find_data->FINAL_STOCK_AMOUNT) + floatval($row->FINAL_STOCK_AMOUNT);
                } else {
                    $collection1->push($row);
                }
            }
        }

        foreach ($new_product as $row) {
            $check =  $collection1->where('product_id', $row->product_id)->first();

            if ($check) {
                $check->PUR_QUANTITYS = $row->PUR_QUANTITYS;
                $check->PUR_AMOUNTS = $row->PUR_AMOUNTS;
            } else {

                $obj = new stdClass();

                $product = InventoryProduct::where('inventory_products.id', $row->product_id)
                    ->join('inventory_product_sub_categories', 'inventory_product_sub_categories.id', 'inventory_products.product_sub_cat_id')
                    ->select(
                        'inventory_products.product_name',
                        'inventory_products.product_code',
                        'inventory_products.type',
                        'inventory_products.type',
                        'inventory_products.id as product_id',
                        'inventory_product_sub_categories.product_sub_cat_name'
                    )
                    ->first();

                $obj->product_id = $product->product_id;
                $obj->product_name = $product->product_name;
                $obj->product_sub_cat_id = $product->product_sub_cat_id;
                $obj->product_code = $product->product_code;
                $obj->product_sub_cat_name = $product->product_sub_cat_name;
                $obj->pur_item_qty_type = $product->type;
                $obj->FINAL_STOCK = 0.00;
                $obj->FINAL_STOCK_AMOUNT = 0.00;
                $obj->PUR_QUANTITYS = $row->PUR_QUANTITYS;
                $obj->PUR_AMOUNTS = $row->PUR_AMOUNTS;
                $obj->INDOOR_QUANTITY = 0.00;
                $obj->INDOOR_AMOUNT = 0.00;
                $collection1->push($obj);
            }
        }


        foreach ($indoors_product as $row) {
            $check2 =  $collection1->where('product_id', $row->product_id)->first();

            if ($check2) {
                $check2->INDOOR_QUANTITY = $row->INDOOR_QUANTITY;
                $check2->INDOOR_AMOUNT = $row->INDOOR_AMOUNT;
            } else {

                $obj = new stdClass();

                $product = InventoryProduct::where('inventory_products.id', $row->product_id)
                    ->join('inventory_product_sub_categories', 'inventory_product_sub_categories.id', 'inventory_products.product_sub_cat_id')
                    ->select(
                        'inventory_products.product_name',
                        'inventory_products.product_code',
                        'inventory_products.type',
                        'inventory_products.type',
                        'inventory_products.id as product_id',
                        'inventory_product_sub_categories.product_sub_cat_name'
                    )
                    ->first();

                $obj->product_id = $product->product_id;
                $obj->product_name = $product->product_name;
                $obj->product_sub_cat_id = $product->product_sub_cat_id;
                $obj->product_code = $product->product_code;
                $obj->product_sub_cat_name = $product->product_sub_cat_name;
                $obj->pur_item_qty_type = $product->type;
                $obj->FINAL_STOCK = 0.00;
                $obj->FINAL_STOCK_AMOUNT = 0.00;
                $obj->PUR_QUANTITYS = 0.00;
                $obj->PUR_AMOUNTS = 0.00;
                $obj->INDOOR_QUANTITY = $row->INDOOR_QUANTITY;
                $obj->INDOOR_AMOUNT = $row->INDOOR_AMOUNT;
                $collection1->push($obj);
            }
        }
        // return $collection1;

        $sub_categories = DB::table('inventory_product_sub_categories')->get();
        return view('reports.stockByDate', compact('collection1', 'to', 'from', 'sub_categories', 'sub_cat_id'));
    }

    public function stockByDateProduct(Request $request)
    {
        $to = $request->to;
        $from = $request->from;
        $product_id = $request->product_id;

        $datas = DB::table('inventory_purchase_items')
            ->leftJoin('inventory_purchase_orders', 'inventory_purchase_orders.id', 'inventory_purchase_items.purchase_order_id')
            ->leftJoin('inventory_products', 'inventory_products.id', 'inventory_purchase_items.product_id')
            ->leftJoin('inventory_sellers', 'inventory_sellers.id', 'inventory_purchase_orders.seller_id')
            ->where('inventory_purchase_orders.date', '<=', $to)
            ->where('inventory_purchase_orders.date', '>=', $from)
            ->where('inventory_purchase_items.product_id', $product_id)
            ->select(
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_products.type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_purchase_items.purchase_order_id',
                'inventory_sellers.seller_name'
            )
            ->get();
        return $datas;
    }

    public function stockByDateTransfer(Request $request)
    {

        $to = $request->to;
        $from = $request->from;
        $product_id = $request->product_id;

        $datas = DB::table('inventory_indoor_transfer')
            ->leftJoin('inventory_products', 'inventory_products.id', 'inventory_indoor_transfer.product_id')
            ->leftJoin('inventory_departments','inventory_departments.id', 'inventory_indoor_transfer.department_id')
            ->leftJoin('inventory_purchase_orders','inventory_purchase_orders.id','inventory_indoor_transfer.purchase_id')
            ->leftJoin('inventory_sellers','inventory_sellers.id','inventory_purchase_orders.seller_id')
            ->where(DB::raw('DATE_FORMAT(inventory_indoor_transfer.updated_at, "%Y-%m-%d")'), '>=', $from)
            ->where(DB::raw('DATE_FORMAT(inventory_indoor_transfer.updated_at, "%Y-%m-%d")'), '<=', $to)
            ->where('inventory_indoor_transfer.product_id',$product_id)
            ->select(
                'inventory_indoor_transfer.product_id',
                'inventory_products.product_sub_cat_id',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_products.type',
                'inventory_indoor_transfer.transfer_quantity',
                'inventory_indoor_transfer.purchase_unit_price',
                'inventory_indoor_transfer.purchase_id',
                'inventory_departments.dept_name',
                'inventory_sellers.seller_name'
            )
            ->get();
        return $datas;
    }
}
