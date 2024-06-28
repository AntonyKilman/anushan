<?php

namespace App\Http\Controllers;

use App\Models\InventoryProductType;
use Illuminate\Http\Request;

class InventoryProductTypeController extends Controller
{
    // product type add
    public function productTypeAdd()
    {
        return view('product_type.ProductTypeAdd');
    }

    // product type add process
    public function productTypeAddProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:inventory_product_types,product_type_name',
        ]);

        $product_type=new InventoryProductType();
        $product_type->product_type_name=$request->name;
        $product_type->product_type_des=$request->description;

        try {
            $product_type->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Product Type");
            } catch (\Throwable $th) {
            }
            return redirect('/product-type-show-all')->with('success','Successfully Recorded');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','Something Wrong Try Again');
        }

    }


    public function productTypeShowAll()
    {
        $product_types=InventoryProductType::all();
        return view('product_type.ProductTypeShowAll',['product_types'=>$product_types]);
    }

    // product type edit
    public function productTypeEdit($id)
    {
        $product_type=InventoryProductType::find($id);
        return view('product_type.ProductTypeEdit',['product_type'=>$product_type]);
    }

    public function productTypeUpdateProcess(Request $request)
    {
        $product_type=InventoryProductType::find($request->id);

        $request->validate([
            'name' => 'required|unique:inventory_product_types,product_type_name,'.$product_type->id,
        ]);

        $product_type->product_type_name=$request->name;
        $product_type->product_type_des=$request->description;

        try {
            $product_type->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Product Type");
            } catch (\Throwable $th) {
            }
            return redirect('/product-type-show-all')->with('success','Successfully Updated');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','Something Wrong Try Again');
        }

    }
}
