<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryProductSubCategory;
use App\Models\InventoryBrand;
use App\Models\InventoryBrandSubCategory;

class InventoryBrandController extends Controller{

    public function brandsDetails($id)
    {
        $brand_by_subcategory = InventoryBrandSubCategory::join('inventory_brands','inventory_brands.id','=','inventory_brand_sub_categories.brand_id')
        ->select('inventory_brands.brand_name','inventory_brand_sub_categories.*')
        ->where('inventory_brand_sub_categories.product_sub_cat_id','=',$id)
        ->get();

        return $brand_by_subcategory;
    }

    function brandAdd(Request $req){

        $req-> validate([
            'brand_name' => 'required',
        ]);

        $InventoryBrand = new InventoryBrand();
        $InventoryBrand -> brand_name = $req -> brand_name;
        $InventoryBrand -> brand_des = $req -> brand_des;
        $InventoryBrand -> save();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Create Product Brand");
        } catch (\Throwable $th) {
        }
        
        $length=count($req->product_sub_cat_name);
        for ($i=0; $i < $length; $i++) { 
            $InventoryBrandSubCategory = new InventoryBrandSubCategory();
            $InventoryBrandSubCategory -> brand_id = $InventoryBrand->id;
            $InventoryBrandSubCategory -> product_sub_cat_id = $req -> product_sub_cat_name[$i];
            $InventoryBrandSubCategory -> save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Product Brand");
            } catch (\Throwable $th) {
            }
        } 
        return redirect('brandShow')->with('success','Successfully Recorded');

    }

    function brandShow(){
        $prosubs = InventoryProductSubCategory::all();
        $datas = InventoryBrandSubCategory::join('inventory_product_sub_categories','inventory_product_sub_categories.id','=','inventory_brand_sub_categories.product_sub_cat_id')
        ->join('inventory_brands','inventory_brands.id','=','inventory_brand_sub_categories.brand_id')
        ->select('inventory_brand_sub_categories.*','inventory_brands.*','inventory_product_sub_categories.product_sub_cat_name','inventory_brands.id as sub_unigue')
        ->get(); 
        return view('brandShowAll',compact('datas','prosubs'));
    }

    function brandEdit($id){
         $dataBrandSub = InventoryBrandSubCategory::join('inventory_product_sub_categories','inventory_product_sub_categories.id','=','inventory_brand_sub_categories.product_sub_cat_id')
                        ->select('inventory_brand_sub_categories.*','inventory_product_sub_categories.product_sub_cat_name')
                        ->where('brand_id',$id)
                        ->get();
                        return $dataBrandSub; 
    }

    function brandUpdate(Request $req){

        $req-> validate([
            'brand_name' => 'required',
        ]);

        $InventoryBrand = InventoryBrand::find($req->id);
        $InventoryBrand -> brand_name = $req -> brand_name;
        $InventoryBrand -> brand_des = $req -> brand_des;
        $InventoryBrand -> save();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Update Product Brand");
        } catch (\Throwable $th) {
        }
        $subCat = InventoryBrandSubCategory::where('brand_id',$req->id)->get();
      
        foreach($subCat as $sub){
            $delete= InventoryBrandSubCategory::find($sub->id);
            $delete->delete();
        }

        $length=count($req->Editproduct_sub_cat_name);
        for ($i=0; $i < $length; $i++) { 
            $InventoryBrandSubCategory = new InventoryBrandSubCategory();
            $InventoryBrandSubCategory -> brand_id = $req->id;
            $InventoryBrandSubCategory -> product_sub_cat_id = $req -> Editproduct_sub_cat_name[$i];
            $InventoryBrandSubCategory -> save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Product Brand");
            } catch (\Throwable $th) {
            }
        } 
        return redirect('brandShow')->with('success','Successfully Updated');

    }
}
