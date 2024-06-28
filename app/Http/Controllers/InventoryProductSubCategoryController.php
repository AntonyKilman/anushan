<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryProductSubCategory;
use App\Models\InventoryProductCategory;

class InventoryProductSubCategoryController extends Controller{

    function productSubCatAdd(Request $req){

        $req-> validate([
            
            'product_sub_cat_name' => 'required|unique:inventory_product_sub_categories,product_sub_cat_name',
            'product_cat_id' => 'required',
            'product_sub_cat_name' => 'required|unique:inventory_product_sub_categories,product_sub_cat_code',

        ]);

        $InventoryProductSubCategory = new InventoryProductSubCategory();
        $InventoryProductSubCategory -> product_sub_cat_name = $req -> product_sub_cat_name;
        $InventoryProductSubCategory -> product_sub_cat_des = $req -> product_sub_cat_des;
        $InventoryProductSubCategory -> product_cat_id = $req -> product_cat_id;
        $InventoryProductSubCategory -> product_sub_cat_code = $req -> product_sub_cat_code;

        
        try{
            $InventoryProductSubCategory -> save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Product SubCategory");
            } catch (\Throwable $th) {
            }
            return redirect('productSubCatShow')->with('success','Successfully Recorded');
        }
        catch (\Throwable $e) {
           return redirect()->back()->with('error','Something wrong try again');
        }
        
        
    }

    function productSubCatShow(){
        $proCats = InventoryProductCategory::all();
        $datas = InventoryProductSubCategory::join('inventory_product_categories','inventory_product_categories.id','=','inventory_product_sub_categories.product_cat_id')
        ->select('inventory_product_sub_categories.*','inventory_product_categories.product_cat_name')
        ->get();
        return view('ProductSubCatShowAll',compact('datas','proCats'));
    }

    function productSubCatUpdate(Request $req){

       
        $InventoryProductSubCategory = InventoryProductSubCategory::find($req ->id);
        $req-> validate([

            'product_sub_cat_name' => 'required|unique:inventory_product_sub_categories,product_sub_cat_name,'.$InventoryProductSubCategory->id,
            'product_cat_id' => 'required',
            'product_sub_cat_name' => 'required|unique:inventory_product_sub_categories,product_sub_cat_code,'.$InventoryProductSubCategory->id,

        ]);
        
        $InventoryProductSubCategory -> product_sub_cat_name = $req -> product_sub_cat_name;
        $InventoryProductSubCategory -> product_sub_cat_des = $req -> product_sub_cat_des;
        $InventoryProductSubCategory -> product_cat_id = $req -> product_cat_id;
        $InventoryProductSubCategory -> product_sub_cat_code = $req -> product_sub_cat_code;

        
        try{
            $InventoryProductSubCategory -> save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Product SubCategory");
            } catch (\Throwable $th) {
            }
            return redirect('productSubCatShow')->with('success','Successfully Updated');
        }
        catch (\Throwable $e) {
           return redirect()->back()->with('error','Something wrong try again');
        }

    }
}