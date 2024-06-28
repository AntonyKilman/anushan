<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryProductCategory;
use App\Models\inventory_stock_adjustment;

use Illuminate\Support\Facades\DB;


class InventoryProductCategoryController extends Controller{

    function productCatAdd(Request $req){

        $req-> validate([
            'product_cat_name' => 'required|unique:inventory_product_categories,product_cat_name',
            'product_cat_code' => 'required|unique:inventory_product_categories,product_cat_code',

        ]);

        $InventoryProductCategory = new InventoryProductCategory();
        $InventoryProductCategory -> product_cat_name = $req -> product_cat_name;
        $InventoryProductCategory -> product_cat_code = $req -> product_cat_code;
        $InventoryProductCategory -> product_cat_des = $req -> product_cat_des;

       
        
        try{
            $InventoryProductCategory -> save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Product Category");
            } catch (\Throwable $th) {
            }
            return redirect('productCatShow')->with('success','Successfully Recorded');
        }
        catch (\Throwable $e) {
            
           return redirect()->back()->with('error','Something wrong try again');
        }
        
    }

    function productCatShow(){
        $datas = InventoryProductCategory::all();
        return view('ProductCatShowAll',compact('datas'));
    }

    function productCatUpdate(Request $req){

       
        
        $InventoryProductCategory = InventoryProductCategory::find($req->id);
        $req-> validate([
            'product_cat_name' => 'required|unique:inventory_product_categories,product_cat_name,'.$InventoryProductCategory->id,
            'product_cat_code' => 'required|unique:inventory_product_categories,product_cat_code,'.$InventoryProductCategory->id,

        ]);

        $InventoryProductCategory -> product_cat_name = $req -> product_cat_name;
        $InventoryProductCategory -> product_cat_code = $req -> product_cat_code;
        $InventoryProductCategory -> product_cat_des = $req -> product_cat_des;
        
        try{
            $InventoryProductCategory -> save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Product Category");
            } catch (\Throwable $th) {
            }
            return redirect('productCatShow')->with('success','Successfully Updated');
        }
        catch (\Throwable $e) {   
            return redirect()->back()->with('error','Something wrong try again');
        }
    }







    public function clear(){


        $purchasedItems = DB::table('inventory_purchase_items')
            ->leftJoin('inventory_purchase_orders', 'inventory_purchase_orders.id', 'inventory_purchase_items.purchase_order_id')
            ->leftJoin('inventory_products', 'inventory_products.id', 'inventory_purchase_items.product_id')
            ->groupBy('inventory_purchase_items.product_id', 'inventory_purchase_items.pur_item_qty_type', 'inventory_purchase_items.purchase_order_id')
            ->select(
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.product_id',
                'inventory_purchase_items.purchase_order_id',
                DB::raw('SUM(inventory_purchase_items.pur_item_qty) as TOATAL_PURCHASE_QTY'),
                DB::raw('SUM(inventory_purchase_items.pur_item_amount) as TOATAL_PURCHASE_AMOUNT')
            )
            ->get();
        // return $purchasedItems ;


     
        foreach ($purchasedItems as $row) {

            // $row->UNIT_PRICE = $row->TOATAL_PURCHASE_AMOUNT / $row->TOATAL_PURCHASE_QTY;

            // insert indoor transfer qty
            $indoor_transfer = DB::table('inventory_indoor_transfer')
               
                ->where('product_id', $row->product_id)
                ->where('purchase_id', $row->purchase_order_id)
                ->where('Qty_type', $row->pur_item_qty_type)
                ->SUM('transfer_quantity');
            // return $indoor_transfer;


            // insert indoor return qty
            $indoor_return = DB::table('inventory_indoor_returns')
               
                ->where('product_id', $row->product_id)
                ->where('purchase_order_id', $row->purchase_order_id)
                ->where('Qty_type', $row->pur_item_qty_type)
                ->SUM('return_qty');
            // return $indoor_return;

            // insert outdoor return qty
            $outdoor_return = DB::table('inventory_outdoor_returns')
             
                ->where('product_id', $row->product_id)
                ->where('purchase_order_id', $row->purchase_order_id)
                ->where('Qty_type', $row->pur_item_qty_type)
                ->SUM('return_qty');
            // return $outdoor_return;

            // insert stock adjustment qty
            $stock_adjustments = DB::table('inventory_stock_adjustment')
         
                ->where('product_id', $row->product_id)
                ->where('purchase_id', $row->purchase_order_id)
                ->where('qty_type', $row->pur_item_qty_type)
                ->SUM('quantity');
            // return $stock_adjustments;

            $row->FINAL_STOCK = $row->TOATAL_PURCHASE_QTY - $indoor_transfer + $indoor_return - $outdoor_return + $stock_adjustments;
          
        }

        $purchasedItems = $purchasedItems->whereNotIn('FINAL_STOCK',[0]);

        foreach($purchasedItems as $item){

            
                $aa = new inventory_stock_adjustment();
                $aa->product_id = $item->product_id;
                $aa->qty_type = $item->pur_item_qty_type;
                if($item->FINAL_STOCK>0){
                $aa->quantity = (-$item->FINAL_STOCK);
                }
                else{
                    $aa->quantity = abs($item->FINAL_STOCK); 
                }
                $aa->date = "2022-10-23";
                $aa->purchase_id = $item->purchase_order_id;
                $aa->save();
            

           
        }

         return "success" ;
    }

}
