<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventorySellerType;


class InventorySellerTypeController extends Controller
{
    // seller type add
    public function sellerTypeAdd()
    {
        return view('seller_type.SellerTypeAdd');
    }

    // seller type add process
    public function sellerTypeAddProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:inventory_seller_types,seller_type_name',
        ]);

        $seller_type=new InventorySellerType();
        $seller_type->seller_type_name=$request->name;
        $seller_type->seller_type_des=$request->description;

        try {
            $seller_type->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Seller Type");
            } catch (\Throwable $th) {
            }
            return redirect('/seller-type-show-all')->with('success','Successfully Recorded');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','Something Wrong Try Again');
        }

    }

    // seller type showall
    public function sellerTypeShowAll()
    {
        $seller_types=InventorySellerType::all();
        return view('seller_type.SellerTypeShowAll',['seller_types'=>$seller_types]);
    }

    // seller type edit
    public function sellerTypeEdit($id)
    {
        $seller_type=InventorySellerType::find($id);
        return view('seller_type.SellerTypeEdit',['seller_type'=>$seller_type]);
    }

    // seller update process
    public function sellerTypeUpdateProcess(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required|unique:inventory_seller_types,seller_type_name,'.$request->id,
        ]);

        $seller_type=InventorySellerType::find($request->id);

        

        $seller_type->seller_type_name=$request->name;
        $seller_type->seller_type_des=$request->description;

        try {
            $seller_type->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Seller Type");
            } catch (\Throwable $th) {
            }
            return redirect('/seller-type-show-all')->with('success','Successfully Updated');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','Something Wrong Try Again');
        }

    }

}
