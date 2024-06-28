<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryProductSubCategory;
use App\Models\InventoryProductType;
use App\Models\InventoryBrand;
use App\Models\InventoryProduct;
use App\Models\InventoryProductCategory;
use App\Models\InventoryBrandSubCategory;
use App\Models\InventoryIndoorTransfer;
use App\Models\inventory_purchase_item;
use App\Models\inventory_stock_adjustment;


use Illuminate\Support\Facades\DB;

class InventoryProductController extends Controller
{
    function productGet()
    {
        $proTypes = InventoryProductType::all();
        $subCats = InventoryProductSubCategory::all();
        $proCats = InventoryProductCategory::all();
        $brands = InventoryBrand::all();

        // return $subCats;

        $categories = InventoryProductCategory::all();
        return view('product.productAdd', compact('proTypes', 'brands', 'subCats', 'categories', 'proCats'));
    }

    function getBrandId($id)
    {
        $InventoryBrandSubCategory = InventoryBrandSubCategory::where('product_sub_cat_id', $id)->get();
        return $InventoryBrandSubCategory;
    }


    function productAdd(Request $req)
    {
        if (!($req->id)) {

            $req->validate([
                'product_name' => 'required|unique:inventory_products,product_name',
                'product_code' => 'required',
                'product_type_id' => 'required',
                'product_sub_cat_id' => 'required',
                'brand_id' => 'required',
            ]);

            $InventoryProduct = new InventoryProduct();
            $InventoryProduct->product_name = $req->product_name;
            $InventoryProduct->product_code = $req->product_code;
            $InventoryProduct->product_des = $req->product_des;
            $InventoryProduct->product_type_id = $req->product_type_id;
            $InventoryProduct->product_sub_cat_id = $req->product_sub_cat_id;
            $InventoryProduct->brand_id = $req->brand_id;
            $InventoryProduct->min_qty = $req->min_qty;
            $InventoryProduct->danger_qty = $req->danger_qty;
            $InventoryProduct->max_qty = $req->max_qty;
            $InventoryProduct->type = $req->type;

            try {
                $InventoryProduct->save();
                try {
                    $a = app('App\Http\Controllers\ActivityLogController')->index("Create Product");
                } catch (\Throwable $th) {
                }
                return redirect('productShow')->with('success', 'Successfully Recorded');
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Something wrong try again');
            }
        } else {

            // return "update";

            $InventoryProduct = InventoryProduct::find($req->id);

            $req->validate([
                'product_name' => 'required|unique:inventory_products,product_name,' . $InventoryProduct->id,
                'product_code' => 'required',
                'product_type_id' => 'required',
                'product_sub_cat_id' => 'required',
                'brand_id' => 'required',

            ]);

            $InventoryProduct->product_name = $req->product_name;
            $InventoryProduct->product_code = $req->product_code;
            $InventoryProduct->product_des = $req->product_des;
            $InventoryProduct->product_type_id = $req->product_type_id;
            $InventoryProduct->product_sub_cat_id = $req->product_sub_cat_id;
            $InventoryProduct->brand_id = $req->brand_id;
            $InventoryProduct->min_qty = $req->min_qty;
            $InventoryProduct->danger_qty = $req->danger_qty;
            $InventoryProduct->max_qty = $req->max_qty;
            $InventoryProduct->type = $req->type;

            try {
                $InventoryProduct->save();
                try {
                    $a = app('App\Http\Controllers\ActivityLogController')->index("Update Product");
                } catch (\Throwable $th) {
                }
                return redirect('productShow')->with('success', 'Successfully Updated');
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Something wrong try again');
            }
        }
    }

    function productShow()
    {
        $proTypes = InventoryProductType::all();
        $qty_types = DB::table('inventory_qty_types')->get();
        $brands = InventoryBrand::all();
        $subCats = InventoryProductSubCategory::all();
        $categories = InventoryProductCategory::all();
        $datas = InventoryProduct::join('inventory_product_types', 'inventory_product_types.id', '=', 'inventory_products.product_type_id')
            ->join('inventory_brands', 'inventory_brands.id', '=', 'inventory_products.brand_id')
            ->orderBy('id', 'desc')
            ->join('inventory_product_sub_categories', 'inventory_product_sub_categories.id', '=', 'inventory_products.product_sub_cat_id')
            ->select('inventory_products.*', 'inventory_product_types.product_type_name', 'inventory_brands.brand_name', 'inventory_product_sub_categories.product_sub_cat_name', 'inventory_product_sub_categories.product_cat_id')
            ->get();
        return view('productShowAll', compact('datas', 'proTypes', 'brands', 'subCats', 'categories', 'qty_types'));
    }

    function productEdit($id)
    {
        $product = InventoryProduct::find($id);
        $proTypes = InventoryProductType::all();
        $brands = InventoryBrand::all();
        $subCats = InventoryProductSubCategory::all();
        return  view('product.productEdit', compact('product', 'proTypes', 'brands', 'subCats'));
    }

    // function productUpdate(Request $req){

    //     $InventoryProduct = InventoryProduct::find($req->id);

    //     $req->validate([
    //         'product_name' => 'required|unique:inventory_products,product_name,'.$InventoryProduct->id,
    //         'product_code' => 'required',
    //         'product_type_id' => 'required',
    //         'product_sub_cat_id' => 'required',
    //         'brand_id' => 'required',

    //     ]);

    //     $InventoryProduct->product_name=$req->product_name;
    //     $InventoryProduct -> product_code = $req -> product_code;
    //     $InventoryProduct -> product_des = $req -> product_des;
    //     $InventoryProduct -> product_type_id = $req -> product_type_id;
    //     $InventoryProduct -> product_sub_cat_id = $req -> product_sub_cat_id;
    //     $InventoryProduct -> brand_id = $req -> brand_id;


    //     try{
    //         $InventoryProduct -> save();
    //         try {
    //             $a = app('App\Http\Controllers\ActivityLogController')->index("Update Product");
    //         } catch (\Throwable $th) {
    //         }
    //         return redirect('productShow')->with('success','Successfully Updated');
    //     }
    //     catch (\Throwable $e) {
    //        return redirect()->back()->with('error','Something wrong try again');
    //     }
    // }


    // qty alert
    public function productQtyAlertShowall(Request $request)
    {
        $filter_by = $request->id;

        $batches = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            // ->where('product_id','=',$request->id)
            // ->groupBy('inventory_purchase_items.purchase_order_id','inventory_products.product_name','inventory_products.product_code','inventory_purchase_items.product_id')
            ->groupBy('inventory_purchase_items.pur_item_qty_type', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_purchase_items.product_id')
            // ->select('inventory_purchase_items.purchase_order_id','inventory_purchase_items.product_id',
            // 'inventory_products.product_name','inventory_products.product_code',
            //  DB::raw("SUM(inventory_purchase_items.pur_item_qty) as qty")
            // )
            ->select(
                'inventory_purchase_items.product_id',
                'inventory_purchase_items.pur_item_qty_type as qty_type',
                'inventory_products.product_name',
                'inventory_products.product_code',
                DB::raw("SUM(inventory_purchase_items.pur_item_qty) as qty")
            )
            ->orderBy('inventory_purchase_items.product_id', 'ASC')
            ->get();

        foreach ($batches as $batch) {

            $batch->alert = false;
            $alert_qty = DB::table('inventory_products')->select('danger_qty', 'min_qty', 'max_qty')->where('id', '=', $batch->product_id)->first();

            $outdoor_return = DB::table('inventory_outdoor_returns')
                ->select('return_qty', 'id')
                ->where('product_id', '=', $batch->product_id)
                // ->where('purchase_order_id','=',$batch->purchase_order_id)
                ->where('status', '=', 1)
                ->get();
            $outdoor_return_qty = 0;
            foreach ($outdoor_return as $value) {
                $outdoor_return_qty += $value->return_qty;
            }

            $indoor_return = DB::table('inventory_indoor_returns')
                ->select('return_qty', 'id')
                ->where('product_id', '=', $batch->product_id)
                // ->where('purchase_order_id','=',$batch->purchase_order_id)
                ->where('status', '=', 1)
                ->get();
            $indoor_return_qty = 0;
            foreach ($indoor_return as $value) {
                $indoor_return_qty += $value->return_qty;
            }

            $indoor_transfer = DB::table('inventory_indoor_transfer')
                ->select('transfer_quantity', 'id')
                ->where('product_id', '=', $batch->product_id)
                // ->where('purchase_id','=',$batch->purchase_order_id)
                ->where('status', "=", 1)
                ->get();
            $indoor_transfer_qty = 0;
            foreach ($indoor_transfer as $value) {
                $indoor_transfer_qty += $value->transfer_quantity;
            }

            $indoor_foodcity_return = DB::table('foodcity_product_returns')
                ->select('return_qty', 'id')
                ->where('item_id', '=', $batch->product_id)
                // ->where('purchase_order_id','=',$batch->purchase_order_id)
                ->where('status', '=', 1)
                ->get();
            $indoor_foodcity_return_qty = 0;
            foreach ($indoor_foodcity_return as $value) {
                $indoor_foodcity_return_qty += $value->return_qty;
            }

            $equipment_transfer = DB::table('inventory_equipment_transfer')
                ->select('quantity', 'id')
                ->where('product_id', '=', $batch->product_id)
                // ->where('purchase_id', '=', $batch->purchase_order_id)
                ->where('status', "=", 1)
                ->get();
            $equipment_transfer_qty = 0;

            foreach ($equipment_transfer as $value) {
                $equipment_transfer_qty += $value->quantity;
            }

            $batch->qty = $batch->qty + $indoor_return_qty + $indoor_foodcity_return_qty - $outdoor_return_qty - $indoor_transfer_qty - $equipment_transfer_qty;

            if ($filter_by == 1) {
                if ($alert_qty->danger_qty >= $batch->qty) {
                    $batch->alert = true;
                }
                if ($alert_qty->danger_qty == null) {
                    $batch->alert = false;
                }
            }
            if ($filter_by == 2) {
                if ($alert_qty->danger_qty < $batch->qty && $alert_qty->min_qty >= $batch->qty) {
                    $batch->alert = true;
                }
                if ($alert_qty->min_qty == null) {
                    $batch->alert = false;
                }
            }
            if ($filter_by == 3) {
                if ($alert_qty->max_qty < $batch->qty) {
                    $batch->alert = true;
                }
                if ($alert_qty->max_qty == null) {
                    $batch->alert = false;
                }
            }
        }

        return view('reports.QtyAlertReport', compact('filter_by', 'batches'));
    }


    public function productSearch(Request $request)
    {
        $products = InventoryProduct::query()
            ->where('inventory_products.product_name', 'LIKE', "%" . $request->search . "%")
            ->orWhere('inventory_products.product_code', 'LIKE', "%" . $request->search . "%")
            ->limit(10)
            ->get();
        return $products;
    }


    public function productCode($id)
    {
        $sub_cat_code = InventoryProductSubCategory::where('id', $id)->first()->product_sub_cat_code;
        $count = InventoryProduct::where('product_sub_cat_id', $id)->count();
        $new_code = $sub_cat_code + $count + 1;
        return  $new_code;
    }

    public function productMovingCount(Request $request)
    {
        $sub_cat = $request->sub_category;
        $getDate = $request->date;

        $product_counts = InventoryIndoorTransfer::join('inventory_products', 'inventory_products.id', 'inventory_indoor_transfer.product_id')
            ->join('inventory_product_sub_categories', 'inventory_product_sub_categories.id', 'inventory_products.product_sub_cat_id')
            ->groupBy('inventory_products.id', 'inventory_indoor_transfer.Qty_type');
        if ($getDate) {
            $date = explode('-', $getDate);
            $product_counts = $product_counts->whereYear('inventory_indoor_transfer.updated_at', $date[0])
                ->whereMonth('inventory_indoor_transfer.updated_at', $date[1]);
        }
        if ($sub_cat) {
            $product_counts = $product_counts->where('inventory_product_sub_categories.id', $sub_cat);
        }
        $product_counts = $product_counts->select(
            'inventory_products.product_name',
            'inventory_products.product_code',
            'inventory_indoor_transfer.Qty_type',
            DB::raw('SUM(inventory_indoor_transfer.transfer_quantity) as TOTAL_TRANSFER')
        )
            ->orderBy('TOTAL_TRANSFER', 'desc')
            ->get();
        $InventoryProductSubCategory = InventoryProductSubCategory::all();
        return view('reports.movingProduct', compact('product_counts', 'InventoryProductSubCategory', 'sub_cat', 'getDate'));
    }


    public function productPurchase($product)
    {
        $purchases = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', 'inventory_purchase_items.product_id')
            ->join('inventory_purchase_orders','inventory_purchase_orders.id','inventory_purchase_items.purchase_order_id')
            ->join('inventory_sellers','inventory_sellers.id','inventory_purchase_orders.seller_id')
            // ->join('inventory_purchase_items','product_id.id','inventory_purchase_orders.seller_id')
            ->where('product_id', $product)
            ->select(
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_expery_date',
                'inventory_purchase_items.purchase_order_id',
                'inventory_purchase_items.pur_item_qty',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_products.type',
                'inventory_purchase_items.pur_item_qty',
                'inventory_sellers.seller_name',
                'inventory_purchase_orders.pur_ord_bill_no',
                'inventory_purchase_orders.date',
                'inventory_purchase_orders.pur_ord_cash',
                'inventory_purchase_orders.pur_ord_credit'
            )
            ->get();
        return $purchases;
    }

    public function pro_type()
    {
        // $products =   InventoryProduct::where('type', "No's")->get();
        $products =   InventoryProduct::where('type', "No")->get();

        foreach ($products as $row) {
            $edit = InventoryProduct::find($row->id);
            $edit->type = "No's";
            // $edit->type = "No";
            $edit->save();
        }

        $pur_items = DB::table('inventory_purchase_items')->get();

        foreach ($pur_items as $pur) {
            $type = InventoryProduct::find($pur->product_id);

            if (!($type == null)) {
                $update = inventory_purchase_item::find($pur->id);
                $update->pur_item_qty_type = $type->type;
                $update->save();
            }
        }
        // return "success";
        $transfers = InventoryIndoorTransfer::all();

        foreach ($transfers as $tran) {
            $type = InventoryProduct::find($tran->product_id);

            if (!($type == null)) {
                $updateTran = InventoryIndoorTransfer::find($tran->id);
                $updateTran->Qty_type = $type->type;
                $updateTran->save();
            }
        }
        // return "success";

        $adjustments = inventory_stock_adjustment::all();
        // return $adjustments;
        foreach ($adjustments as $adju) {
            $type = InventoryProduct::find($adju->product_id);

            if (!($type == null)) {
                $updateAdj = inventory_stock_adjustment::find($adju->id);
                $updateAdj->qty_type = $type->type;
                $updateAdj->save();
            }
        }
        return "success";
    }
}
