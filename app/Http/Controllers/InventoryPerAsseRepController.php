<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryPermanentAssets;
use App\Models\InventoryProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryPerAsseRepController extends Controller
{
    function permanantAssetsReportMonth(Request $req){
        
        $from=substr(Carbon::now()->subDays(30),0,10);
        $to=substr(Carbon::now(),0,10);
        $datas =InventoryPermanentAssets::join('inventory_products','inventory_products.id','=','inventory_permanent_assets.product_id')
                ->groupBy('inventory_products.product_name','inventory_products.product_code','inventory_permanent_assets.pur_item_qty_type','inventory_products.id')
                ->select('inventory_products.product_name','inventory_products.product_code','inventory_permanent_assets.pur_item_qty_type','inventory_products.id as pro_id',
                DB::raw("SUM(inventory_permanent_assets.pur_item_qty) as qty"),
                DB::raw("SUM(inventory_permanent_assets.pur_item_amount) as amount")
                )
                ->whereBetween('inventory_permanent_assets.created_at',[$from,$to])
                ->get();
         
                // return $datas;
        return view('reports.permanantAssetsReport',compact('datas','from','to',));
    }


    function permanantAssetsReportShow(Request $req){
        // return $req;
        $from=$req->from;
        $to=$req->to;
        $datas =InventoryPermanentAssets::join('inventory_products','inventory_products.id','=','inventory_permanent_assets.product_id')
                ->groupBy('inventory_products.product_name','inventory_products.product_code','inventory_permanent_assets.pur_item_qty_type','inventory_products.id')
                ->select('inventory_products.product_name','inventory_products.product_code','inventory_permanent_assets.pur_item_qty_type','inventory_products.id as pro_id',
                DB::raw("SUM(inventory_permanent_assets.pur_item_qty) as qty"),
                DB::raw("SUM(inventory_permanent_assets.pur_item_amount) as amount"));

                if($from=="null" && $to=="null"){
                 $datas= $datas ->whereBetween('inventory_permanent_assets.created_at',[$from,$to]);
                }

                 if($from && $to){
                    $datas= $datas ->whereBetween('inventory_permanent_assets.created_at',[$from,$to]);
                }

                 if($req->from && $req->to==""){
                    $datas= $datas ->where('inventory_permanent_assets.created_at','>=',$from);
                }

                if($req->to && $req->from=="" ){
                    $datas= $datas ->where('inventory_permanent_assets.created_at','<=',$to);
                }

                
                $datas= $datas->get();
         
                // return $datas;
        return view('reports.permanantAssetsReport',compact('datas','from','to',));
    }

    function permanantAssetsReportModal(Request $req){
           
        $from=$req->from;
        $to=$req->to;
        $datas =InventoryPermanentAssets::join('inventory_products','inventory_products.id','=','inventory_permanent_assets.product_id')
                ->select('inventory_products.product_name','inventory_permanent_assets.*')
                ->where('product_id',$req->pro_id)
                ->where('pur_item_qty_type',$req->qty_type);

                if($from=="null" && $to=="null"){
                 $datas= $datas ->whereBetween('inventory_permanent_assets.created_at',[$from,$to]);
                }

                 if($from && $to){
                    $datas= $datas ->whereBetween('inventory_permanent_assets.created_at',[$from,$to]);
                }

                 if($req->from && $req->to==""){
                    $datas= $datas ->where('inventory_permanent_assets.created_at','>=',$from);
                }

                if($req->to && $req->from=="" ){
                    $datas= $datas ->where('inventory_permanent_assets.created_at','<=',$to);
                }

                
                $datas= $datas->get();
         
                return $datas;
        


    }


    

}
