<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryProduct;
use App\Models\InventoryDepartment;
use App\Models\inventory_purchase_request_news;
use Illuminate\Support\Facades\DB;
use App\Models\inventory_purchase_request_items;
use Facade\FlareClient\View;
use PHPUnit\Framework\Constraint\Count;
use Illuminate\Support\Facades\Auth;


class InventoryPurchaseRequestController extends Controller
{
    function PurchaseRequestShowAdd(){

        $products = InventoryProduct::all();
        $departments = InventoryDepartment::all();
        $latest = DB::table('inventory_purchase_request_new')->latest('id')->first();
    
        if(!$latest){
            $RequestNo = 1;
        }
        else{
            $RequestNo = $latest->id+1;
        }
        return view('purchaseOrderRequest.purchaseOrderRequestAdd',compact('products','departments','RequestNo'));
    }

    function PurchaseRequestAdd(Request $request){

        $inventory_purchase_request_news = new inventory_purchase_request_news();
        $inventory_purchase_request_news -> department_id = $request ->department;
        $inventory_purchase_request_news -> reason = $request -> reason;

        try{
            $inventory_purchase_request_news -> save();
            $a = app('App\Http\Controllers\ActivityLogController')->index("Add Purchase Order Request");
        }
        catch (\Throwable $th) {
            return redirect()->back()->with('error','Something wrong try again');
        }


        $length =Count($request -> product);

        for($i=0; $i<$length; $i++){
            $inventory_purchase_request_items = new inventory_purchase_request_items();
            $inventory_purchase_request_items -> inventory_purchase_request_new_id = $inventory_purchase_request_news -> id;
            $inventory_purchase_request_items -> product = $request -> product[$i];
            $inventory_purchase_request_items -> quantity = $request -> quantity[$i];
            $inventory_purchase_request_items -> quantity_type = $request -> qty_type[$i];
            $inventory_purchase_request_items -> approved_quantiy = 0;
            $inventory_purchase_request_items -> is_delete = 0;
            $inventory_purchase_request_items -> inv_man_status = "Pending";
            $inventory_purchase_request_items -> acc_man_status = "Pending";
            $inventory_purchase_request_items -> man_dir_status = "Pending";
          
            try{
                $inventory_purchase_request_items -> save();
                $a = app('App\Http\Controllers\ActivityLogController')->index("Add Purchase Order Request Items");
                
            }
            catch (\Throwable $th) {
                return redirect()->back()->with('error','Something wrong try again');
            }
        }

        return redirect('/PurchaseRequestShow')->with('success','Successfully Recorded');
    }

    function PurchaseRequestShow(){

        $datas = inventory_purchase_request_news::join('inventory_departments','inventory_departments.id','=','inventory_purchase_request_new.department_id')
        ->select('inventory_purchase_request_new.*','inventory_departments.dept_name')
        ->orderBy('id', 'DESC')
        ->get();
        return view('purchaseOrderRequest.purchaseOrderRequest',compact('datas'));
    }

    function PurchaseRequestEdit($id){

        $a = session()->get('Access');
        $storeKeeper = false;

        if (in_array('store_keeper_edit', $a)) {
            $storeKeeper = true;
        } 

        $Accountant = false;
        if (in_array('accountant_edit', $a)) {
            $Accountant = true;
        }

        $ManagingDirector = false;
        if (in_array('managing_director_edit', $a)) {
            $ManagingDirector = true;
        }

        $datas = inventory_purchase_request_news::find($id);
        $department = InventoryDepartment::find($datas->department_id);
        $department = $department->dept_name;

        $collections = DB::table('inventory_purchase_order_new')->get();
        $arrIds = array();

        foreach($collections as $collection){

            $check =  (explode(",",$collection ->reqiest_items_id));

            for($x=0; $x<count($check); $x++){
                array_push($arrIds, $check[$x]);
            }  
        }

        $items = inventory_purchase_request_items::where('inventory_purchase_request_items.inventory_purchase_request_new_id',$id)
        ->where('inventory_purchase_request_items.is_delete',0)
        ->whereNotIn('inventory_purchase_request_items.id',$arrIds)
        ->join('inventory_products','inventory_products.id','=','inventory_purchase_request_items.product')
        ->select('inventory_purchase_request_items.*','inventory_products.product_name')
        ->get();
       
        return view('purchaseOrderRequest.purchaseOrderRequestEdit',compact('datas','department','items','storeKeeper','Accountant','ManagingDirector'));
    }

    function PurchaseRequestUpdate(Request $request){


        $collections = DB::table('inventory_purchase_order_new')->get();
        $arrIds = array();

        foreach($collections as $collection){

            $check =  (explode(",",$collection ->reqiest_items_id));

            for($x=0; $x<count($check); $x++){
                array_push($arrIds, $check[$x]);
            }  
        }

        $datas = inventory_purchase_request_items::where('inventory_purchase_request_items.inventory_purchase_request_new_id',$request->request_no)
        ->where('inventory_purchase_request_items.is_delete',0)
        ->whereNotIn('inventory_purchase_request_items.id',$arrIds)
        ->get();
        $count = count($datas);
     

        for($i=0; $i<$count; $i++){
            $datas[$i]-> approved_quantiy = $request -> approved_quantiy[$i];
            $datas[$i]-> inv_man_status = $request -> inv_man_status[$i];
            $datas[$i]-> acc_man_status = $request -> acc_man_status[$i];
            $datas[$i]-> man_dir_status = $request -> man_dir_status[$i];
            
            try{
                $datas[$i]-> save();
                $a = app('App\Http\Controllers\ActivityLogController')->index("Approve Purchase Order Request"); 
            }

            catch (\Throwable $th) {
                return redirect()->back()->with('error','Something wrong try again');
            }

        }

        return redirect('/PurchaseRequestShow')->with('success','Successfully Updated');    
        
    }

    function PurchaseRequestView($id){
        $datas = inventory_purchase_request_news::find($id);
        $department = InventoryDepartment::find($datas->department_id);
        $department = $department->dept_name;
        $items = inventory_purchase_request_items::where('inventory_purchase_request_items.inventory_purchase_request_new_id',$id)
        ->where('inventory_purchase_request_items.is_delete',0)
        ->join('inventory_products','inventory_products.id','=','inventory_purchase_request_items.product')
        ->select('inventory_purchase_request_items.*','inventory_products.product_name')
        ->get();
        return View('purchaseOrderRequest.purchaseOrderRequestView',compact('datas','department','items'));

    }

    function PurchaseRequestChange($id){
        $products = InventoryProduct::all();
        $datas = inventory_purchase_request_news::find($id);
        $department = InventoryDepartment::find($datas->department_id);
        $department = $department->dept_name;

        $items = inventory_purchase_request_items::where('inventory_purchase_request_items.inventory_purchase_request_new_id',$id)
        ->where('inventory_purchase_request_items.inv_man_status',"Pending")
        ->where('inventory_purchase_request_items.acc_man_status',"Pending")
        ->where('inventory_purchase_request_items.man_dir_status',"Pending")
        ->where('inventory_purchase_request_items.is_delete',0)
        ->join('inventory_products','inventory_products.id','=','inventory_purchase_request_items.product')
        ->select('inventory_purchase_request_items.*','inventory_products.product_name')
        ->get();
        
        return view('purchaseOrderRequest.purchaseOrderRequestChange',compact('datas','department','items','products'));

    }

    function PurchaseRequestChangeUpdate(Request $request){
        
        $drops = $request->dropId;
        $drops= $drops[0];
        $drops = explode(',', $drops);
        
        if(!($drops==[""])){

            foreach($drops as $drop){
                $items = inventory_purchase_request_items::find($drop);
                $items -> is_delete = 1;
                $items -> save(); 
            }

        }

        $datas = inventory_purchase_request_news::find($request ->request_no);
        $datas -> reason = $request -> reason;
        $datas -> save();

        if($request->id){

            $countId = count($request->id);
            $countProduct = count($request->product);

            for($i=0; $i<$countId; $i++){
                $items = inventory_purchase_request_items::find($request->id[$i]);
                $items -> product = $request -> product[$i];
                $items -> quantity = $request -> quantity[$i];
                $items -> approved_quantiy = $request -> quantity[$i];
                $items -> quantity_type = $request -> qty_type[$i];

                try{
                    $items-> save();
                    $a = app('App\Http\Controllers\ActivityLogController')->index("Approval Purchase Order Request"); 
                }
                catch (\Throwable $th) {
                    return redirect()->back()->with('error','Something wrong try again');
                }
            }
    
            for($i=$countId; $i<$countProduct; $i++){
                $inventory_purchase_request_items = new inventory_purchase_request_items();
                $inventory_purchase_request_items -> inventory_purchase_request_new_id = $request -> request_no;
                $inventory_purchase_request_items -> product = $request -> product[$i];
                $inventory_purchase_request_items -> quantity = $request -> quantity[$i];
                $inventory_purchase_request_items -> quantity_type = $request -> qty_type[$i];
                $inventory_purchase_request_items -> approved_quantiy = 0;
                $inventory_purchase_request_items -> is_delete = 0;
                $inventory_purchase_request_items -> inv_man_status = "Pending";
                $inventory_purchase_request_items -> acc_man_status = "Pending";
                $inventory_purchase_request_items -> man_dir_status = "Pending";
               
                try{
                    $inventory_purchase_request_items -> save();
                    $a = app('App\Http\Controllers\ActivityLogController')->index("Update Purchase Order Request"); 
                }
                catch (\Throwable $th) {
                    return redirect()->back()->with('error','Something wrong try again');
                }
            }

        }

        else{

            for($i=0; $i<count($request->product); $i++){
                $inventory_purchase_request_items = new inventory_purchase_request_items();
                $inventory_purchase_request_items -> inventory_purchase_request_new_id = $request -> request_no;
                $inventory_purchase_request_items -> product = $request -> product[$i];
                $inventory_purchase_request_items -> quantity = $request -> quantity[$i];
                $inventory_purchase_request_items -> quantity_type = $request -> qty_type[$i];
                $inventory_purchase_request_items -> approved_quantiy = 0;
                $inventory_purchase_request_items -> is_delete = 0;
                $inventory_purchase_request_items -> inv_man_status = "Pending";
                $inventory_purchase_request_items -> acc_man_status = "Pending";
                $inventory_purchase_request_items -> man_dir_status = "Pending";
                $inventory_purchase_request_items -> save();
                try{
                    $inventory_purchase_request_items -> save();
                    $a = app('App\Http\Controllers\ActivityLogController')->index("Create new product in Update Purchase Order Request section"); 
                }
                catch (\Throwable $th) {
                    return redirect()->back()->with('error','Something wrong try again');
                }
            }
            
        }

        return redirect('/PurchaseRequestShow')->with('success','Successfully Updated');
    }
}
