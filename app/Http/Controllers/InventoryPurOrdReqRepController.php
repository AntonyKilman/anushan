<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryPurchaseOrderRequest;
use App\Models\InventoryDepartment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class InventoryPurOrdReqRepController extends Controller{

    function purchaseOrderRequestRepShow(Request $request){
        // return $request;
       
        
        $from = $request->from;
        $to = $request->to;
        $status=$request->status;
        $departmentId =$request->departmentId;
      
        $Departments = InventoryDepartment::all();
        $datas = InventoryPurchaseOrderRequest::join('inventory_products','inventory_products.id','=','inventory_purchase_order_requests.product_id')
        ->join('inventory_departments','inventory_departments.id','=','inventory_purchase_order_requests.department_id')
        ->groupBy('inventory_products.product_name','inventory_products.product_code','inventory_purchase_order_requests.pur_req_status','inventory_departments.dept_name','inventory_purchase_order_requests.pur_req_qty_type','inventory_products.id','inventory_departments.id')
        ->select('inventory_products.product_name','inventory_products.product_code','inventory_purchase_order_requests.pur_req_status as status','inventory_departments.dept_name','inventory_purchase_order_requests.pur_req_qty_type','inventory_products.id as pro_id','inventory_departments.id as dept_id',
        DB::raw("SUM(inventory_purchase_order_requests.pur_req_qty) as qty"));
        // ->where('pur_req_status',1);

        // Date and departments
         if( $from && $to && $departmentId){
            $datas =$datas ->whereBetween('inventory_purchase_order_requests.created_at',[$from,$to])
                            ->where('department_id',$departmentId);
        }
 
        // only dates
        else if ($to &&  $from ) {
            $datas =$datas ->whereBetween('inventory_purchase_order_requests.created_at',[$from,$to]);
        }

        // only departments
        else if($departmentId && $from="null" && $to="null"){
            $datas =$datas ->where('department_id',$departmentId);
        }

        // only from"
        else if($from && $to="null"){
            $datas= $datas ->where('inventory_purchase_order_requests.created_at','>=',$from);
        }

         // only to
        else if($to && $from=""){
            $datas= $datas ->where('inventory_purchase_order_requests.created_at','<=',$to);

        }

         // from and departments
         else if($from  && $to="null" && $departmentId){
            $datas =$datas ->where('inventory_purchase_order_requests.created_at','>=',$from)
                            ->where('department_id',$departmentId);
        }

        // to and departments
        else if( $to && $departmentId && $from="null"){
            $datas =$datas ->where('inventory_purchase_order_requests.created_at','<=',$to)
                            ->where('department_id',$departmentId);
        }

        if ($status != "") {
            $datas =$datas->where('pur_req_status',$status);
        }
       
        $datas =$datas->get();
        // return $datas;
       
        return view('reports.purOrdReqReport',compact('datas','Departments', 'from', 'to','departmentId','status',));
    } 
    


    function purchaseOrderRequestRepMonth(){
        $from=substr(Carbon::now()->subDays(30),0,10);
        $to=substr(Carbon::now(),0,10);
        $departmentId ="";
        $status="";
        $Departments = InventoryDepartment::all();
        $datas = InventoryPurchaseOrderRequest::join('inventory_products','inventory_products.id','=','inventory_purchase_order_requests.product_id')
        ->join('inventory_departments','inventory_departments.id','=','inventory_purchase_order_requests.department_id')
        ->groupBy('inventory_products.product_name','inventory_products.product_code','inventory_departments.dept_name','inventory_purchase_order_requests.pur_req_qty_type','inventory_purchase_order_requests.pur_req_status','inventory_products.id','inventory_departments.id')
        ->select('inventory_products.product_name','inventory_products.product_code','inventory_purchase_order_requests.pur_req_status as status','inventory_departments.dept_name','inventory_purchase_order_requests.pur_req_qty_type','inventory_products.id as pro_id','inventory_departments.id as dept_id',
        DB::raw("SUM(inventory_purchase_order_requests.pur_req_qty) as qty"))
        ->where('pur_req_status',1)
        ->whereBetween('inventory_purchase_order_requests.created_at',[$from,$to])
        ->get();
        return view('reports.purOrdReqReport',compact('datas','Departments', 'from', 'to','departmentId','status',));
    }
   
    function purchaseOrderRequestRepModal(Request $request){
        
        $from = $request->from;
        $to = $request->to;
     
        $departmentId =$request->dept_id;
        $productId =$request->pro_id;
        $qty_type=$request->qty_type;
      
        $datas = InventoryPurchaseOrderRequest::join('inventory_products','inventory_products.id','=','inventory_purchase_order_requests.product_id')
        ->join('inventory_departments','inventory_departments.id','=','inventory_purchase_order_requests.department_id')
        ->select('inventory_products.product_name','inventory_departments.dept_name','inventory_purchase_order_requests.*')  
        ->where('department_id',$departmentId)
        ->where('product_id',$productId)
        ->where('pur_req_qty_type',$qty_type);
        // ->where('pur_req_status',1);
        if ($request->status != "") {
            $datas =$datas->where('pur_req_status',$request->status);
        }
        if ($from && $to) {
            $datas =$datas ->whereBetween('inventory_purchase_order_requests.created_at',[$from,$to]);
        }

        $datas =$datas->get();
     
        
        return $datas;
         

    }



    
}
