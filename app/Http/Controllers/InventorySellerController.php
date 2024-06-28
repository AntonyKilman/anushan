<?php

namespace App\Http\Controllers;

use App\Models\inventory_purchase_item;
use Illuminate\Http\Request;
use App\Models\InventorySeller;
use App\Models\InventorySellerType;
use Illuminate\Support\Facades\DB;
// use Image;
use Intervention\Image\Facades\Image;


class InventorySellerController extends Controller
{
    public function sellerAdd()
    {
        $seller_types = InventorySellerType::all();
        return view('seller.SellerAdd', ['seller_types' => $seller_types]);
    }

    public function sellerAddProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:inventory_sellers,seller_name',
            'address' => 'required',
            'seller_type_id' => 'required',
            'mobile_no' => 'nullable|unique:inventory_sellers,mobile_no',
            'office_no' => 'nullable|unique:inventory_sellers,contact_no',
            'image_1' => 'mimes:jpeg,bmp,png|max:2048',
            'image_2' => 'mimes:jpeg,bmp,png|max:2048',
            'image_3' => 'mimes:jpeg,bmp,png|max:2048',
        ]);

        $seller = new InventorySeller();
        $seller->seller_name = $request->name;
        $seller->seller_reg_no = $request->reg_no;
        $seller->seller_address = $request->address;
        $seller->contact_no = $request->office_no;
        $seller->mobile_no = $request->mobile_no;
        $seller->seller_type_id = $request->seller_type_id;
        $seller->save();
        $a = app('App\Http\Controllers\ActivityLogController')->index("Create Seller");
        try {

            try {
            } catch (\Throwable $th) {
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something Wrong Try Again');
        }

        $seller_update = InventorySeller::find($seller->id);
        if ($request->image_1 != null) {
            // if ($request->image[0]->extension()=="jpg,jpeg,png,gif,svg") {
            //     # code...
            // }
            $image_name = $seller->id . '-' . "1" . '.' . $request->image_1->extension();
            $seller_update->seller_img_1 = $image_name;
            // $img = Image::make($request->image_1);
            // $img->save(public_path('images/'.$image_name),50);
            $request->image_1->move('images', $image_name);
        }
        if ($request->image_2 != null) {
            $image_name = $seller->id . '-' . "2" . '.' . $request->image_2->extension();
            $seller_update->seller_img_2 = $image_name;
            // $img = Image::make($request->image_2);
            // $img->save(public_path('images/'.$image_name),50);
            $request->image_2->move('images', $image_name);
        }
        if ($request->image_3 != null) {
            $image_name = $seller->id . '-' . "3" . '.' . $request->image_3->extension();
            $seller_update->seller_img_3 = $image_name;
            // $img = Image::make($request->image_3);
            // $img->save(public_path('images/'.$image_name),50);
            $request->image_3->move('images', $image_name);
        }

        $seller_update->save();
        return redirect('/seller-show-all')->with('success', 'Successfully Recorded');

        try {
            // try {
            //     $length=count($request->image);
            // } catch (\Throwable $th) {
            //     $length=0;
            // }

            // for ($i=0; $i <$length ; $i++) { 
            //     if ($i<3) {
            //         if ($i==0) {
            //             // if ($request->image[0]->extension()=="jpg,jpeg,png,gif,svg") {
            //             //     # code...
            //             // }
            //             $image_name=$seller->id.'-'.$i.'.'.$request->image[0]->extension();
            //             $seller_update->seller_img_1=$image_name;
            //             $img = Image::make($request->image[0]);
            //             $img->save(public_path('images/'.$image_name),50);
            //             // $request->image[0]->move(public_path('images'),$image_name);
            //         }elseif ($i==1) {
            //             $image_name=$seller->id.'-'.$i.'.'.$request->image[1]->extension();
            //             $seller_update->seller_img_2=$image_name;
            //             $img = Image::make($request->image[1]);
            //             $img->save(public_path('images/'.$image_name),50);
            //             // $request->image[1]->move(public_path('images'),$image_name);
            //         }else {
            //             $image_name=$seller->id.'-'.$i.'.'.$request->image[2]->extension();
            //             $seller_update->seller_img_3=$image_name;
            //             $img = Image::make($request->image[2]);
            //             $img->save(public_path('images/'.$image_name),50);
            //             // $request->image[2]->move(public_path('images'),$image_name);
            //         }
            //     }
            // } 

            // $seller_update->save();
            // return redirect('/seller-show-all')->with('success','Successfully Recorded');
        } catch (\Throwable $th) {
        }
    }

    public function sellerShowAll()
    {
        $sellers = DB::table('inventory_sellers')
            ->join('inventory_seller_types', 'inventory_seller_types.id', '=', 'inventory_sellers.seller_type_id')
            ->select('inventory_sellers.*', 'inventory_seller_types.seller_type_name')
            ->get();

        return view('seller.SellerShowAll', ['sellers' => $sellers]);
    }

    public function sellerView($id)
    {
        $seller = DB::table('inventory_sellers')
            ->join('inventory_seller_types', 'inventory_seller_types.id', '=', 'inventory_sellers.seller_type_id')
            ->select('inventory_sellers.*', 'inventory_seller_types.seller_type_name')
            ->where('inventory_sellers.id', '=', $id)
            ->first();
        return view('seller.SellerView', ['seller' => $seller]);
    }

    public function sellerEdit($id)
    {
        $seller = InventorySeller::find($id);
        $seller_types = InventorySellerType::all();
        return view('seller.SellerEdit', ['seller_types' => $seller_types, 'seller' => $seller]);
    }

    public function sellerUpdateProcess(Request $request)
    {
        $seller = InventorySeller::find($request->id);
        $request->validate([
            'name' => 'required|unique:inventory_sellers,seller_name,' . $seller->id,
            'address' => 'required',
            'seller_type_id' => 'required',
            'mobile_no' => 'nullable|unique:inventory_sellers,mobile_no,' . $seller->id,
            'office_no' => 'nullable|unique:inventory_sellers,contact_no,' . $seller->id,
            'image_1' => 'mimes:jpeg,bmp,png|max:2048',
            'image_2' => 'mimes:jpeg,bmp,png|max:2048',
            'image_3' => 'mimes:jpeg,bmp,png|max:2048',
        ]);

        $seller->seller_name = $request->name;
        $seller->seller_reg_no = $request->reg_no;
        $seller->seller_address = $request->address;
        $seller->contact_no = $request->office_no;
        $seller->mobile_no = $request->mobile_no;
        $seller->seller_type_id = $request->seller_type_id;
        if ($request->image_1 != null) {
            // if ($request->image[0]->extension()=="jpg,jpeg,png,gif,svg") {
            //     # code...
            // }
            $image_name = $seller->id . '-' . "1" . '.' . $request->image_1->extension();
            $seller->seller_img_1 = $image_name;
            // $img = Image::make($request->image_1);
            // $img->save(public_path('images/'.$image_name),50);
            $request->image_1->move('images', $image_name);
        }
        if ($request->image_2 != null) {
            $image_name = $seller->id . '-' . "2" . '.' . $request->image_2->extension();
            $seller->seller_img_2 = $image_name;
            // $img = Image::make($request->image_2);
            // $img->save(public_path('images/'.$image_name),50);
            $request->image_2->move('images', $image_name);
        }
        if ($request->image_3 != null) {
            $image_name = $seller->id . '-' . "3" . '.' . $request->image_3->extension();
            $seller->seller_img_3 = $image_name;
            // $img = Image::make($request->image_3);
            // $img->save(public_path('images/'.$image_name),50);
            $request->image_3->move('images', $image_name);
        }

        // try {
        //     $length=count($request->image);
        //     for ($i=0; $i <$length ; $i++) { 
        //         if ($i<3) {
        //             if ($i==0) {
        //                 $image_name=$request->id.'-'.$i.'.'.$request->image[0]->extension();;
        //                 $seller->seller_img_1=$image_name;
        //                 $img = Image::make($request->image[0]);
        //                 $img->save(public_path('images/'.$image_name),50);
        //                 // $request->image[0]->move(public_path('images'),$image_name);
        //             }elseif ($i==1) {
        //                 $image_name=$request->id.'-'.$i.'.'.$request->image[1]->extension();;
        //                 $seller->seller_img_2=$image_name;
        //                 $img = Image::make($request->image[1]);
        //                 $img->save(public_path('images/'.$image_name),50);
        //                 // $request->image[1]->move(public_path('images'),$image_name);
        //             }else {
        //                 $image_name=$request->id.'-'.$i.'.'.$request->image[2]->extension();;
        //                 $seller->seller_img_3=$image_name;
        //                 $img = Image::make($request->image[2]);
        //                 $img->save(public_path('images/'.$image_name),50);
        //                 // $request->image[2]->move(public_path('images'),$image_name);
        //             }
        //         }
        //     } 
        // } catch (\Throwable $th) {

        // }

        try {
            $seller->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Seller");
            } catch (\Throwable $th) {
            }
            return redirect('/seller-show-all')->with('success', 'Successfully Updated');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something Wrong Try Again');
        }
    }

    public function sellerHistroy($seller)
    {
        $purchases = DB::table('inventory_purchase_orders')->where('seller_id', $seller)
            ->join('inventory_sellers', 'inventory_sellers.id', 'inventory_purchase_orders.seller_id')
            ->select(
                'inventory_sellers.seller_name',
                'inventory_purchase_orders.id as GR_No',
                'inventory_purchase_orders.pur_ord_bill_no',
                'inventory_purchase_orders.date',
                'inventory_purchase_orders.pur_ord_amount',
                'inventory_purchase_orders.pur_ord_cash',
                'inventory_purchase_orders.pur_ord_credit'
            )
            ->get();
        return $purchases;
    }
}
