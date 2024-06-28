<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\inventory_purchase_request_items;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryProduct;
use App\Models\inventory_purchase_order_new;


class newInventoryPurchaseOrder extends Controller
{
    function newPurchaseOrderShow(){
        $sellers = DB::table('inventory_sellers')->get();

        $datas = inventory_purchase_order_new::join('inventory_products','inventory_products.id','=','inventory_purchase_order_new.product_id')
        ->join('inventory_sellers','inventory_sellers.id','=','inventory_purchase_order_new.seller_id')
        ->select('inventory_purchase_order_new.*','inventory_products.product_name','inventory_sellers.seller_name')
        ->orderBy('id', 'DESC')
        ->get();
        // return $datas;

        $groups = DB::table('inventory_purchase_order_new')
        ->groupBy('inventory_purchase_order_new.purchase_order_id')
        ->select('inventory_purchase_order_new.purchase_order_id')
        ->orderBy('inventory_purchase_order_new.purchase_order_id', 'DESC')
        ->get();
        // return $groups;
        // return $datas;
        return view('newpurchaseOrder/purchaseOrder',compact('datas','groups','sellers'));
    }

    function newPurchaseOrderAdd(){

        // get last purchase order id
        $last = DB::table('inventory_purchase_order_new')->latest('id')->first();

        if($last){
            $last = $last->purchase_order_id+1;
        }
        else{
            $last = 1;
        }

        $sellers = DB::table('inventory_sellers')->get();
        $products = InventoryProduct::all();


        // collect all purchase order request ids and save into the array
        $collections = DB::table('inventory_purchase_order_new')->get();
        $arrIds = array();

        foreach($collections as $collection){

            $check =  (explode(",",$collection ->reqiest_items_id));

            for($x=0; $x<count($check); $x++){
                array_push($arrIds, $check[$x]);
            }
        }

        // get request product by groupby
        $groupProducts = DB::table('inventory_purchase_request_items')
        ->join('inventory_products','inventory_products.id','=','inventory_purchase_request_items.product')
        ->where('inventory_purchase_request_items.is_delete',0)
        ->where('inventory_purchase_request_items.inv_man_status','=','Stock Not Available')
        ->where('inventory_purchase_request_items.acc_man_status','=','Accepted')
        ->where('inventory_purchase_request_items.man_dir_status','=','Accepted')
        ->whereNotIn('inventory_purchase_request_items.id',$arrIds)
        ->groupBy('inventory_purchase_request_items.product','inventory_purchase_request_items.quantity_type','inventory_products.product_name')
        ->select('inventory_purchase_request_items.product','inventory_purchase_request_items.quantity_type','inventory_products.product_name', DB::raw("SUM(inventory_purchase_request_items.approved_quantiy) as totalQty"))
        ->get();
        // return $groupProducts;


        foreach($groupProducts as $groupProduct){

            $reqId = DB::table('inventory_purchase_request_items')
            ->where('inventory_purchase_request_items.is_delete',0)
            ->where('inventory_purchase_request_items.inv_man_status','=','Stock Not Available')
            ->where('inventory_purchase_request_items.acc_man_status','=','Accepted')
            ->where('inventory_purchase_request_items.man_dir_status','=','Accepted')
            ->where('inventory_purchase_request_items.product',$groupProduct->product)
            ->get();

            $arr= $groupProduct->product;
            $arr .= $groupProduct->quantity_type;
            $arr = array();

            $arr2= $groupProduct->product;
            $arr2 .= $groupProduct->quantity_type;
            $arr2 = array();


            foreach($reqId as $no){

                if($groupProduct -> product == $no -> product && $groupProduct -> quantity_type == $no -> quantity_type){
                    array_push($arr, $no->inventory_purchase_request_new_id);
                    array_push($arr2, $no->id);
                    $groupProduct->reqiestIds = $arr;
                    $groupProduct->reqiest_items_id = $arr2;
                }
            }
        }

        // return $groupProducts ;
        return view('newpurchaseOrder/purchaseOrderAdd',compact('groupProducts','products','last','sellers'));
    }


    function newPurchaseOrderStore(Request $request){
        // return $request;


        $count = count($request->product);

        for($i=0; $i<$count; $i++){
            $order = new inventory_purchase_order_new();
            $order -> purchase_order_id = $request -> purchase_order_id;
            $order -> product_id = $request -> product[$i];
            $order -> quantity_type = $request -> qty_type[$i];
            $order -> quantity = $request -> quantity[$i];
            $order -> exp_quantity = $request -> exp_quantity[$i];
            $order -> seller_id = $request -> seller[$i];
            $order -> purchase_request_ids = $request -> purchase_request_id[$i];
            $order -> reqiest_items_id = $request -> purchase_request_items_id[$i];
            $order -> save();

        }
        return redirect(('/newPurchaseOrderShow'))->with('success','Successfully Recorded');


    }

    function newPurchaseOrderView($id){

        $datas = DB::table('inventory_purchase_order_new')
        ->join('inventory_products','inventory_products.id','=','inventory_purchase_order_new.product_id')
        ->join('inventory_sellers','inventory_sellers.id','=','inventory_purchase_order_new.seller_id')
        ->select('inventory_purchase_order_new.*','inventory_products.product_name','inventory_sellers.seller_name')
        ->where('inventory_purchase_order_new.purchase_order_id',$id)
        ->get();
        // return "hello";
        return $datas;
        // return response()->json($datas);

    }

    function newPurchaseOrderUpdate(Request $request){
        
        $items = inventory_purchase_order_new::find($request->id);
        $items -> quantity = $request -> quantity;
        $items -> seller_id = $request -> seller;
        $items -> save();
        return redirect('/newPurchaseOrderShow')->with('success','Successfully Updated');
    }
}
