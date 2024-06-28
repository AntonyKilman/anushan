<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\account_profit_loss;
use App\Models\InventoryEquipment;
use App\Models\InventoryProduct;
use App\Models\InventoryDepartment;
use App\Models\InventoryReturnReason;
use App\Models\department;
use App\Models\employee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;



class InventoryEquipmentController extends Controller
{
    function EquProduct()
    {
        $products =  DB::table("inventory_purchase_items")
        ->join("inventory_products","inventory_products.id","=","inventory_purchase_items.product_id")
        ->groupBy("inventory_purchase_items.product_id","inventory_products.product_name")
        ->select("inventory_purchase_items.product_id","inventory_products.product_name",)
        ->get();
        $departments = department::all();
        $employees = employee::all();
        return view('EquipmentTransfer.equipmentTransferAdd', compact('products', 'departments', 'employees'));
    }

    function equipmentTransferAdd(Request $req)
    {
        // return $req;

        $length = count($req->purchase_id);
        $userPurchase = $req->employee_id;
        $reason = $req->reason;
        $department = $req->department_id;
        $TOTAL_AMOUNT = 0;


        for ($i = 0; $i < $length; $i++) {

            $check = $req->purchase_id[$i] . '-' . $req->pro_id[$i];

            if ($req->$check) {

                // Assign variable for get total transfer amount
                $TOTAL_AMOUNT = $req->transfer_qty[$i] * $req->purchase_amount[$i];

                $InventoryEquipment = new InventoryEquipment();
                $InventoryEquipment->purchase_id  = $req->purchase_id[$i];
                $InventoryEquipment->product_id  = $req->pro_id[$i];
                $InventoryEquipment->quantity  = $req->transfer_qty[$i];
                $InventoryEquipment->Qty_type  = $req->qtyType[$i];
                $InventoryEquipment->purchaseDate  = $req->purDate[$i];
                $InventoryEquipment->noOfDays  = $req->no_of_days[$i];
                $InventoryEquipment->reason  = $reason;
                $InventoryEquipment->userEnter  =  Auth::user()->emp_id;
                $InventoryEquipment->employee_id = $userPurchase;
                $InventoryEquipment->status  = 1;
                $InventoryEquipment->department_id  = $department;
                $InventoryEquipment->purchase_unit_price  = $req->purchase_amount[$i];
                $InventoryEquipment->save();
                try {
                    $a = app('App\Http\Controllers\ActivityLogController')->index("Create EquipmentTransfer");
                } catch (\Throwable $th) {
                }
            } else {
            }
        }

        // store account profit loss table code by karan
        $account_profit_loss = new account_profit_loss();
        $account_profit_loss->total_purchase_price = $TOTAL_AMOUNT;
        $account_profit_loss->department_id =  $department;
        $account_profit_loss->type = "Inventory Transfer";
        $account_profit_loss->cash_in = $TOTAL_AMOUNT;
        $account_profit_loss->is_delete = 0;
        $account_profit_loss->save();
        return redirect('equipmentTransferShow')->with('success', 'Record successfully');
    }

    function equipmentTransferShow()
    {
        $equipments = InventoryEquipment::join('inventory_products', 'inventory_products.id', '=', 'inventory_equipment_transfer.product_id')
            ->join('departments', 'departments.id', '=', 'inventory_equipment_transfer.department_id')
            ->join('employees', 'employees.id', '=', 'inventory_equipment_transfer.employee_id')
            ->select('inventory_equipment_transfer.*', 'inventory_products.product_name','inventory_products.product_code', 'inventory_equipment_transfer.id as equTransId', 'departments.name as mainDeptName', 'employees.f_name')
            ->where('inventory_equipment_transfer.status', 1)
            ->get();
        //  return $equipments;
        return  view('EquipmentTransfer.equipmentTransferShow', compact('equipments'));
    }

    function equipmentTransferEdit($id)
    {

        $equipments = InventoryEquipment::join('inventory_products', 'inventory_products.id', '=', 'inventory_equipment_transfer.product_id')
            ->join('employees', 'employees.id', '=', 'inventory_equipment_transfer.employee_id')
            ->select('inventory_equipment_transfer.*', 'inventory_products.product_name', 'inventory_equipment_transfer.id as equTransId', 'employees.emp_code')
            ->where('inventory_equipment_transfer.id', $id)
            ->get();
        // return $equipments;
        return  view('EquipmentTransfer.equipmentTransferEdit', compact('equipments'));
    }

    function equipmentTransferUpdate(Request $req)
    {
        // return $req;

        $InventoryEquipment = InventoryEquipment::find($req->id);
        $InventoryEquipment->purchase_id  = $req->purchase_id;
        $InventoryEquipment->product_id  = $req->product_id;
        $InventoryEquipment->quantity  = $req->quantity;
        $InventoryEquipment->purchaseDate  = $req->purchase_date;
        $InventoryEquipment->noOfDays  = $req->no_days;
        $InventoryEquipment->reason  = $req->reason;
        // $InventoryEquipment -> userEnter  = "enter";
        $InventoryEquipment->employee_id  = $req->employee_id;
        $InventoryEquipment->department_id  = $req->department_id;
        $InventoryEquipment->status  = $req->status;
        $InventoryEquipment->discription  = $req->discription;
        $InventoryEquipment->save();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Update EquipmentTransfer");
        } catch (\Throwable $th) {
        }

        // store account profit loss table code by karan
        if ($req->status == 0) {
            $account_profit_loss = new account_profit_loss();
            $account_profit_loss->total_purchase_price = $req->quantity * $req->purchase_unit_price;
            $account_profit_loss->department_id =  $req->department_id;
            $account_profit_loss->type = "Inventory Return";
            $account_profit_loss->cash_out = $req->quantity * $req->purchase_unit_price;
            $account_profit_loss->is_delete = 0;
            $account_profit_loss->connected_id = $InventoryEquipment->id;
            $account_profit_loss->save();
        }


        return redirect('equipmentTransferShow')->with('success', 'Updated successfully');
    }

    function equipmentTransferDelete($id)
    {
        $InventoryEquipment = InventoryEquipment::find($id);
        $InventoryEquipment->delete();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Delete EquipmentTransfer");
        } catch (\Throwable $th) {
        }
        return redirect('equipmentTransferShow')->with('success', 'deleted successfully');
    }

    public function equipmentTransferReport()
    {
        $status = "";
        $from = substr(Carbon::now()->subDays(30), 0, 10);
        $to = substr(Carbon::now(), 0, 10);
        $filter_by = '';
        // $equipment_item_transfer=[];
        $equipment_item_transfer = InventoryEquipment::join('inventory_departments', 'inventory_departments.id', '=', 'inventory_equipment_transfer.department_id')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_equipment_transfer.product_id')
            ->join('employees', 'employees.id', '=', 'inventory_equipment_transfer.employee_id')
            ->select(
                'inventory_products.product_name',
                'inventory_equipment_transfer.purchaseDate',
                'inventory_equipment_transfer.noOfDays',
                'inventory_equipment_transfer.reason',
                'inventory_equipment_transfer.userEnter',
                'inventory_equipment_transfer.employee_id',
                'employees.f_name',
                'inventory_equipment_transfer.status',
                DB::raw('SUM(inventory_equipment_transfer.quantity) as qty')
            )
            ->groupBy('inventory_products.product_name', 'inventory_equipment_transfer.purchaseDate', 'inventory_equipment_transfer.noOfDays', 'inventory_equipment_transfer.reason', 'inventory_equipment_transfer.userEnter', 'inventory_equipment_transfer.employee_id', 'employees.f_name', 'inventory_equipment_transfer.status')
            ->where('inventory_equipment_transfer.created_at', '>=', $from)
            ->where('inventory_equipment_transfer.created_at', '<=', $to)
            ->get();

        // return $equipment_item_transfer;
        return view('reports.EquipmentTransferReport', ['filter_by' => $filter_by, 'from' => $from, 'to' => $to, 'equipment_item_transfer' => $equipment_item_transfer, 'name' => '', 'status' => $status]);
    }

    public function equipmentTransferReportView(Request $request)
    {
        $status = $request->status;
        $startDate = Carbon::parse($request->from)->toDateTimeString();
        $endDate = Carbon::parse($request->to)->toDateTimeString();

        // if($request->filter_by==1){
        //     $equipment_item_transfer = InventoryEquipment::join('inventory_products', 'inventory_products.id', '=', 'inventory_equipment_transfer.product_id')
        //     ->select('inventory_products.product_name','inventory_equipment_transfer.purchaseDate','inventory_equipment_transfer.noOfDays','inventory_equipment_transfer.reason','inventory_equipment_transfer.userEnter','inventory_equipment_transfer.userPurchase','inventory_equipment_transfer.status',
        //     DB::raw('SUM(inventory_equipment_transfer.quantity) as qty'))
        //     ->groupBy('inventory_products.product_name','inventory_equipment_transfer.purchaseDate','inventory_equipment_transfer.noOfDays','inventory_equipment_transfer.reason','inventory_equipment_transfer.userEnter','inventory_equipment_transfer.userPurchase','inventory_equipment_transfer.status')
        //     ->whereBetween('inventory_equipment_transfer.created_at', [$request->from, $request->to])->get();
        //       // return $equipment_item_transfer;
        //      return view('reports.EquipmentTransferReport',['filter_by'=>$request->filter_by,'from'=>$request->from,'to'=>$request->to,'equipment_item_transfer'=>$equipment_item_transfer,'name'=>'Product']);
        // }


        $equipment_item_transfer = InventoryEquipment::join('inventory_departments', 'inventory_departments.id', '=', 'inventory_equipment_transfer.department_id')
            ->join('employees', 'employees.id', '=', 'inventory_equipment_transfer.employee_id')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_equipment_transfer.product_id');

        if ($request->filter_by == 1) {
            $equipment_item_transfer = $equipment_item_transfer->select(
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_equipment_transfer.purchaseDate',
                'inventory_equipment_transfer.noOfDays',
                'inventory_equipment_transfer.reason',
                'inventory_equipment_transfer.userEnter',
                'inventory_equipment_transfer.employee_id',
                'employees.f_name',
                'inventory_equipment_transfer.status',
                DB::raw('SUM(inventory_equipment_transfer.quantity) as qty')
            )
                ->groupBy('inventory_products.product_name','inventory_products.product_code', 'inventory_equipment_transfer.purchaseDate', 'inventory_equipment_transfer.noOfDays', 'inventory_equipment_transfer.reason', 'inventory_equipment_transfer.userEnter', 'inventory_equipment_transfer.employee_id', 'employees.f_name', 'inventory_equipment_transfer.status');
        }

        if ($request->filter_by == 2) {
            $equipment_item_transfer = $equipment_item_transfer->select(
                'inventory_departments.dept_name',
                'inventory_equipment_transfer.purchaseDate',
                'inventory_equipment_transfer.noOfDays',
                'inventory_equipment_transfer.reason',
                'inventory_equipment_transfer.userEnter',
                'inventory_equipment_transfer.employee_id',
                'employees.f_name',
                'inventory_equipment_transfer.status',
                DB::raw('SUM(inventory_equipment_transfer.quantity) as qty')
            )
                ->groupBy('inventory_departments.dept_name', 'inventory_equipment_transfer.purchaseDate', 'inventory_equipment_transfer.noOfDays', 'inventory_equipment_transfer.reason', 'inventory_equipment_transfer.userEnter', 'inventory_equipment_transfer.employee_id', 'employees.f_name', 'inventory_equipment_transfer.status');
        }

        if ($request->filter_by == null) {
            $equipment_item_transfer = $equipment_item_transfer->select(
                'inventory_products.product_name',
                'inventory_equipment_transfer.purchaseDate',
                'inventory_equipment_transfer.noOfDays',
                'inventory_equipment_transfer.reason',
                'inventory_equipment_transfer.userEnter',
                'inventory_equipment_transfer.employee_id',
                'employees.f_name',
                'inventory_equipment_transfer.status',
                DB::raw('SUM(inventory_equipment_transfer.quantity) as qty')
            )
                ->groupBy('inventory_products.product_name', 'inventory_equipment_transfer.purchaseDate', 'inventory_equipment_transfer.noOfDays', 'inventory_equipment_transfer.reason', 'inventory_equipment_transfer.userEnter', 'inventory_equipment_transfer.employee_id', 'employees.f_name', 'inventory_equipment_transfer.status');
        }

        if ($request->from) {
            $equipment_item_transfer = $equipment_item_transfer->where('inventory_equipment_transfer.created_at', '>=', $request->from);
        }
        if ($request->to) {
            $equipment_item_transfer = $equipment_item_transfer->where('inventory_equipment_transfer.created_at', '<=', $request->to);
        }
        if ($status != "") {
            $equipment_item_transfer = $equipment_item_transfer->where('inventory_equipment_transfer.status', '=', $status);
        }

        $equipment_item_transfer = $equipment_item_transfer->get();
        // return $equipment_item_transfer;
        if ($request->filter_by == 1) {
            return view('reports.EquipmentTransferReport', ['filter_by' => $request->filter_by, 'from' => $request->from, 'to' => $request->to, 'equipment_item_transfer' => $equipment_item_transfer, 'name' => 'Product', 'status' => $status]);
        }
        if ($request->filter_by == 2) {
            return view('reports.EquipmentTransferReport', ['filter_by' => $request->filter_by, 'from' => $request->from, 'to' => $request->to, 'equipment_item_transfer' => $equipment_item_transfer, 'name' => 'Department', 'status' => $status]);
        }
        if ($request->filter_by == null) {
            return view('reports.EquipmentTransferReport', ['filter_by' => $request->filter_by, 'from' => $request->from, 'to' => $request->to, 'equipment_item_transfer' => $equipment_item_transfer, 'name' => '', 'status' => $status]);
        }
    }
}
