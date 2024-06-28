<?php

namespace App\Http\Controllers;

use App\Models\InventoryPermanentAssets;
use App\Models\InventoryAssetStatusType;
use App\Models\InventoryAssetStatus;

use Illuminate\Http\Request;

class InventoryPermanentAssetsController extends Controller
{
    public function permanentAssetsShowAll()
    {
        $permanent_assets=InventoryPermanentAssets::join('inventory_products','inventory_products.id','=','inventory_permanent_assets.product_id')
        ->select('inventory_permanent_assets.*','inventory_products.product_name','inventory_products.product_code')
        ->get();
        return view('purchased_item.PermanentAssets',['permanent_assets'=>$permanent_assets]);
    }

    public function permanentAssetsEdit($id)
    {
        $permanent_assets=InventoryPermanentAssets::join('inventory_products','inventory_products.id','=','inventory_permanent_assets.product_id')
        ->where('inventory_permanent_assets.id','=',$id)
        ->select('inventory_permanent_assets.*','inventory_products.product_name','inventory_products.product_code')
        ->first();
        $status=InventoryAssetStatus::where('purchased_product_id','=',$id)->orderBy('id', 'ASC')->get();
        $length=count($status);
        foreach ($status as $key => $value) {
            if ($key==$length-1) {
                $status_id=$value->status_id;
            }
        }
        $assets_status=InventoryAssetStatusType::all();
        try {
            if($status_id){
            }
        } catch (\Throwable $th) {
            $status_id='';
        }
        return view('purchased_item.PermanentAssetEdit',['permanent_assets'=>$permanent_assets,'assets_status'=>$assets_status,'status_id'=>$status_id]);
        // return $permanent_assets;
    }

    public function permanentAssetUpdateProcess(Request $request)
    {
        // return $request;
        $purchase_item=InventoryPermanentAssets::find($request->id);
        $purchase_item->product_id=$request->product_id;
        $purchase_item->pur_item_qty=$request->pur_item_qty;
        $purchase_item->pur_item_qty_type=$request->pur_item_qty_type;
        $purchase_item->pur_item_amount=$request->pur_item_amount;
        $purchase_item->purchase_order_id=$request->purchase_order_id;
        $purchase_item->warranty=$request->warranty;
        $purchase_item->serial_number=$request->serial_number;
        $purchase_item->description=$request->description;
        $purchase_item->save();

        $asset_status=new InventoryAssetStatus();
        $asset_status->purchased_product_id=$request->id;
        $asset_status->status_id=$request->status;
        $asset_status->save();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Create Permanent Assets");
        } catch (\Throwable $th) {
        }
        return redirect('permanent-assets-show-all')->with('success','Successfully Updated');
    }
}
