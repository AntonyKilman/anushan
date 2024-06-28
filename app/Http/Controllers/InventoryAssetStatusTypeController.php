<?php

namespace App\Http\Controllers;

use App\Models\InventoryAssetStatusType;
use Illuminate\Http\Request;

class InventoryAssetStatusTypeController extends Controller
{
    public function assetStatusTypeShowAll()
    {
        $statuses= InventoryAssetStatusType::all(); 
        return view('asset_status_type.AssetStatusTypeShowAll',['statuses'=>$statuses]);
    }

    public function assetStatusTypeAddProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:inventory_asset_status_types,status_name',
        ]);

        $status=new InventoryAssetStatusType();
        $status->status_name=$request->name;
        $status->description=$request->description;
        
        try {
            $status->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index( "Create Assets Status Type");
            } catch (\Throwable $th) {
            }
            return redirect('/asset-status-type-show-all')->with('success','Successfully Recorded');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','Something Wrong Please Try Again');
        }
        // return $request;
    }

    public function assetStatusTypeUpdateProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:inventory_asset_status_types,status_name,'.$request->id,
        ]);

        $status=InventoryAssetStatusType::find($request->id);
        $status->status_name=$request->name;
        $status->description=$request->description;
        try {
            $status->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index( "Update Assets Status Type");
            } catch (\Throwable $th) {
            }
            return redirect('/asset-status-type-show-all')->with('success','Successfully Updated');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','Something Wrong Please Try Again');
        }
    }
}
