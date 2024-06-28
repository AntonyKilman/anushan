<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryPurchaseOrderRequest;
use App\Models\InventoryProduct;
use App\Models\InventoryDepartment;
use Illuminate\Support\Facades\Auth;

class InventoryPurchaseOrderRequestController extends Controller{

    function PurchaseOrderRequestAdd(Request $req){
        // return $req;
        // return Auth::user()->emp_id;

       $InventoryPurchaseOrderRequest = new InventoryPurchaseOrderRequest();
       $InventoryPurchaseOrderRequest -> product_id = $req -> product_id;
       $InventoryPurchaseOrderRequest -> department_id = $req -> department_id;
       $InventoryPurchaseOrderRequest -> pur_req_qty = $req -> pur_req_qty;
       $InventoryPurchaseOrderRequest -> pur_req_qty_type = $req -> pur_req_qty_type;
       $InventoryPurchaseOrderRequest -> pur_req_des = $req -> pur_req_des;
       $InventoryPurchaseOrderRequest -> pur_req_reason = $req -> pur_req_reason;
       $InventoryPurchaseOrderRequest -> user_id = Auth::user()->emp_id;
    //    $InventoryPurchaseOrderRequest -> pur_req_approved_by = $req -> pur_req_approved_by;
       $status = "0";
       $InventoryPurchaseOrderRequest -> pur_req_status =  $status;
     
       try {
            $InventoryPurchaseOrderRequest -> save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Purchase Order Request");
            } catch (\Throwable $th) {
            }
            return redirect('/purchaseOrderRequestShow')->with('success','Successfully Recorded');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','something wrong try again');
        }
    
    }

    function purchaseOrderRequestShow(){
        $products = InventoryProduct::all();
        $departments = InventoryDepartment::all();
        $datas = InventoryPurchaseOrderRequest::join('inventory_products','inventory_products.id','=','inventory_purchase_order_requests.product_id')
        ->join('inventory_departments','inventory_departments.id','=','inventory_purchase_order_requests.department_id')
        ->select('inventory_purchase_order_requests.*','inventory_products.product_name','inventory_departments.dept_name')
        ->where('pur_req_status',0)
        ->get();
       
        return view('purchaseorderRequestShowAll',compact('datas','products','departments'));
    }

    function purchaseorderRequestEdit($id){
        $data = InventoryPurchaseOrderRequest::find($id);
        $products = InventoryProduct::all();
        return view('PurchaseOrderRequest.purchaseorderRequestEdit',compact('data','products'));
    }

    function purchaseorderRequestUpdate(Request $req){
      
        $InventoryPurchaseOrderRequest = InventoryPurchaseOrderRequest::find($req->id);
        $InventoryPurchaseOrderRequest -> product_id = $req -> product_id;
        $InventoryPurchaseOrderRequest -> department_id = $req -> department_id;
        $InventoryPurchaseOrderRequest -> pur_req_qty = $req -> pur_req_qty;
        $InventoryPurchaseOrderRequest -> pur_req_qty_type = $req -> pur_req_qty_type;
        $InventoryPurchaseOrderRequest -> pur_req_des = $req -> pur_req_des;
        $InventoryPurchaseOrderRequest -> pur_req_reason = $req -> pur_req_reason;
        if ( $InventoryPurchaseOrderRequest -> pur_req_status != $req -> pur_req_status) {
            $InventoryPurchaseOrderRequest -> pur_req_status = $req -> pur_req_status;
            $InventoryPurchaseOrderRequest -> pur_req_approved_by = Auth::user()->emp_id;
        }
       
        try {
                $InventoryPurchaseOrderRequest -> save();
                try {
                    $a = app('App\Http\Controllers\ActivityLogController')->index("Update Purchase Order Request");
                } catch (\Throwable $th) {
                }
                return redirect('/purchaseOrderRequestShow')->with('success','Successfully Updated');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error','something wrong try again');
            }
        
    }
}
