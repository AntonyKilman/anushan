<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryDepartment;
use App\Models\account_profit_loss;
use App\Models\InventoryIndoorTransfer;
use App\Models\InventoryHotelTransaction;
use App\Models\foodCity;
use App\Models\InventoryProduct;
use App\Models\InventoryPurchaseOrder;
use App\Models\InventoryPurchaseItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\employee;
use App\Models\account_main_account;



class InventoryIndoorTransferController extends Controller
{

    function GetProDept()
    {
        $dept_id = 1;                        
        $dept_name = 'Sales Department';                        

        $products = DB::table("inventory_purchase_items")
            ->join("inventory_products", "inventory_products.id", "=", "inventory_purchase_items.product_id")
            ->groupBy("inventory_purchase_items.product_id", "inventory_products.product_name")
            ->select("inventory_purchase_items.product_id", "inventory_products.product_name")
            ->get();

        $departments = InventoryDepartment::all();
        $products = InventoryProduct::all();
        $employees = employee::all();


        return view('IndoorTransfer.IndoorTransferAdd', compact('products', 'departments', 'employees', 'products', 'dept_id', 'dept_name'));
    }

    function GetPurchaseId($id)
    {

        $batches = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->where('product_id', $id)
            ->groupBy('inventory_purchase_items.purchase_order_id', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_purchase_items.product_id', 'inventory_purchase_items.pur_item_expery_date', 'inventory_products.product_sub_cat_id', 'inventory_purchase_items.pur_item_amount', 'inventory_purchase_items.pur_item_qty', 'inventory_purchase_items.pur_item_qty_type')
            ->select(
                'inventory_purchase_items.purchase_order_id',
                'inventory_purchase_items.product_id',
                'inventory_purchase_items.pur_item_expery_date',
                'inventory_purchase_items.pur_item_amount',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_products.product_sub_cat_id',
                'inventory_purchase_items.pur_item_qty',
                'inventory_purchase_items.pur_item_qty_type'
            )
            ->get();
            // return $batches;


        foreach ($batches as $batch) {

            $outdoor_return = DB::table('inventory_outdoor_returns')
                ->select('return_qty', 'id')
                ->where('product_id', '=', $batch->product_id)
                ->where('purchase_order_id', '=', $batch->purchase_order_id)
                ->where('status', "=", "1")
                ->get();


            $outdoor_return_qty = 0;
            foreach ($outdoor_return as $value) {
                $outdoor_return_qty += $value->return_qty;
            }



            $indoor_return = DB::table('inventory_indoor_returns')
                ->select('return_qty', 'id')
                ->where('product_id', '=', $batch->product_id)
                ->where('purchase_order_id', '=', $batch->purchase_order_id)
                ->where('status', "=", "1")
                ->get();

            $indoor_return_qty = 0;
            foreach ($indoor_return as $value) {
                $indoor_return_qty += $value->return_qty;
            }



            $indoor_transfer = DB::table('inventory_indoor_transfer')
                ->select('transfer_quantity', 'id')
                ->where('product_id', '=', $batch->product_id)
                ->where('purchase_id', '=', $batch->purchase_order_id)
                ->where('status', "=", "1")
                ->get();

            $indoor_transfer_qty = 0;
            foreach ($indoor_transfer as $value) {
                $indoor_transfer_qty += $value->transfer_quantity;
            }

            // return $indoor_transfer_qty;

            // $equipment_transfer = DB::table('inventory_equipment_transfer')
            //     ->select('quantity', 'id')
            //     ->where('product_id', '=', $batch->product_id)
            //     ->where('purchase_id', '=', $batch->purchase_order_id)
            //     ->where('status', "=", "1")
            //     ->get();
            // $equipment_transfer_qty = 0;

            // foreach ($equipment_transfer as $value) {
            //     $equipment_transfer_qty += $value->quantity;
            // }

            $stock_adjustment = DB::table('inventory_stock_adjustment')
                ->select('quantity', 'id')
                ->where('product_id', '=', $batch->product_id)
                ->where('purchase_id', '=', $batch->purchase_order_id)
                ->get();
            $stock_adjustment_qty = 0;
            foreach ($stock_adjustment as $value) {
                $stock_adjustment_qty += $value->quantity;
            }

            $batch->pur_item_amount = $batch->pur_item_amount / $batch->pur_item_qty;
            // $batch->pur_item_qty = $batch->pur_item_qty - $outdoor_return_qty + $indoor_return_qty - $indoor_transfer_qty - $equipment_transfer_qty;
            $batch->pur_item_qty = $batch->pur_item_qty - $outdoor_return_qty + $indoor_return_qty - $indoor_transfer_qty + $stock_adjustment_qty;
        }
        // return $indoor_transfer_qty;

        return $batches;
    }



    function indoorTransferAdd(Request $req)
    {
        $length = count($req->purchase_id);
        $depts = explode(",", $req->dept_id);
        $department = $depts[0];
        $department_name = $depts[1];
        $acc_dept_id = $depts[2];

        $employee_id = $req->employee_id;

        for ($i = 0; $i < $length; $i++) {

            $check = $req->purchase_id[$i] . '-' . $req->pro_id[$i];

            if ($req->$check) {


                $InventoryIndoorTransfer = new InventoryIndoorTransfer();
                $InventoryIndoorTransfer->purchase_id = $req->purchase_id[$i];
                $InventoryIndoorTransfer->product_id = $req->pro_id[$i];
                $InventoryIndoorTransfer->quantity = $req->qty[$i];
                $InventoryIndoorTransfer->transfer_quantity = $req->transfer_qty[$i];
                $InventoryIndoorTransfer->purchase_unit_price = $req->purchase_amount[$i];
                $InventoryIndoorTransfer->department_id = $department;
                $InventoryIndoorTransfer->user_id =  Auth::user()->emp_id;
                $InventoryIndoorTransfer->employee_id = $employee_id;
                $InventoryIndoorTransfer->exDate = $req->exDate[$i];
                $InventoryIndoorTransfer->Qty_type = $req->qtyType[$i];
                $InventoryIndoorTransfer->status = 1;

                try {
                    $InventoryIndoorTransfer->save();
                    $a = app('App\Http\Controllers\ActivityLogController')->index("Create Indoor Transfer");
                } catch (\Throwable $th) {
                    return back()->with('error', 'Something  wrong');
                }

                // store data for accounts
                $CashAccount = new account_main_account();
                $CashAccount->debit = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $CashAccount->dept_id = 6; //Inventory
                $CashAccount->date = Carbon::now()->format('Y-m-d');
                $CashAccount->account_type = 1000;
                $CashAccount->description = $department_name . "Transfer";
                $CashAccount->connected_id = $InventoryIndoorTransfer->id;
                $CashAccount->sub_category = $req->pro_id[$i]; //product_id
                $CashAccount->table_id = 1; //cash account
                $CashAccount->cash = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $CashAccount->save();
                
                $SalesAccount = new account_main_account();
                $SalesAccount->credit = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $SalesAccount->dept_id = 6; //Inventory
                $SalesAccount->date = Carbon::now()->format('Y-m-d');
                $SalesAccount->account_type = 4000;
                $SalesAccount->description  = $department_name . "Transfer";
                $SalesAccount->connected_id = $InventoryIndoorTransfer->id;
                $SalesAccount->table_id = 2; //sales account
                $SalesAccount->cash = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $SalesAccount->sub_category = $req->pro_id[$i]; //product_id
                $SalesAccount->cash = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $SalesAccount->purchase_amount = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $SalesAccount->save();


                $CashAccount = new account_main_account();
                $CashAccount->credit = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $CashAccount->dept_id = $acc_dept_id;
                $CashAccount->date = Carbon::now()->format('Y-m-d');
                $CashAccount->account_type = 1000;
                $CashAccount->description = "Inventory Transfer";
                $CashAccount->connected_id = $InventoryIndoorTransfer->id;
                $CashAccount->sub_category = $req->pro_id[$i]; //product_id
                $CashAccount->table_id = 1; //cash account
                $CashAccount->cash = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $CashAccount->save();

                $purchaseAccount = new account_main_account();
                $purchaseAccount->debit = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $purchaseAccount->dept_id = $acc_dept_id;
                $purchaseAccount->date = Carbon::now()->format('Y-m-d');
                $purchaseAccount->account_type = 1000;
                $purchaseAccount->description = "Inventory Transfer";
                $purchaseAccount->connected_id = $InventoryIndoorTransfer->id;
                $purchaseAccount->table_id = 5; //purchase account
                $purchaseAccount->sub_category = $req->pro_id[$i]; //product_id
                $purchaseAccount->cash = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $purchaseAccount->purchase_amount = $req->transfer_qty[$i] * $req->purchase_amount[$i];
                $purchaseAccount->save();

                if ($department == 1) {
                    $foodCity = new foodCity();
                    $foodCity->name = $req->product_name[$i];
                    $foodCity->product_code = $req->product_code[$i];
                    $foodCity->quantity = $req->transfer_qty[$i];
                    $foodCity->status = 0;
                    $foodCity->sub_category_id = $req->proSubCatId[$i];
                    $foodCity->purchase_order_id = $req->purchase_id[$i];
                    $foodCity->purchase_price = $req->purchase_amount[$i];
                    $foodCity->scale_id = $req->qtyType[$i];
                    $foodCity->expire_date = $req->exDate[$i];
                    $foodCity->user_id = Auth::user()->emp_id;
                    $foodCity->transection_id = $InventoryIndoorTransfer->id;
                    $foodCity->item_id  = $req->pro_id[$i];  //inventory product table id

                    try {
                        $foodCity->save();
                        $a = app('App\Http\Controllers\ActivityLogController')->index("Create Foodcity Products");
                    } catch (\Throwable $th) {
                        return back()->with('error', 'Something  wrong');
                    }
                } elseif ($department == 2) {
                    $InventoryHotelTransaction = new InventoryHotelTransaction();
                    $InventoryHotelTransaction->name = $req->product_name[$i];
                    $InventoryHotelTransaction->item_id = $req->pro_id[$i];
                    $InventoryHotelTransaction->quantity = $req->qty[$i];
                    $InventoryHotelTransaction->transfer_quantity = $req->transfer_qty[$i];
                    $InventoryHotelTransaction->status = 0;
                    $InventoryHotelTransaction->is_delete = 0;
                    $InventoryHotelTransaction->transaction_id = $InventoryIndoorTransfer->id;
                    $InventoryHotelTransaction->exp_date = $req->exDate[$i];
                    $InventoryHotelTransaction->purchase_id = $req->purchase_id[$i];
                    $InventoryHotelTransaction->unit_purchase_price = $req->purchase_amount[$i];

                    try {
                        $InventoryHotelTransaction->save();
                        $a = app('App\Http\Controllers\ActivityLogController')->index("Create hotel Transaction");
                    } catch (\Throwable $th) {
                        return back()->with('error', 'Something  wrong');
                    }
                }
            }
        }



        return redirect('IndoorTransferShow')->with('success', 'Successfully Recorded');
    }



    function IndoorTransferShow()
    {
        $transfers = InventoryIndoorTransfer::join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_transfer.product_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_transfer.department_id')
            ->leftJoin('employees', 'employees.id', '=', 'inventory_indoor_transfer.employee_id')
            ->select('inventory_indoor_transfer.*', 'employees.f_name', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_departments.dept_name', 'inventory_indoor_transfer.id as indoorTransId')
            ->orderBy('id', 'DESC')
            ->get();

        return view('IndoorTransfer.IndoorTransferShow', compact('transfers'));
    }



    function IndoorTransferDelete($id)
    {
        $delete = InventoryIndoorTransfer::find($id);
        $delete->delete();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Delete Indoor Transfer");
        } catch (\Throwable $th) {
        }

        if ($delete->department_id == 1) {
            $foodCity = DB::table('food_city_products')
                ->select('id')
                ->where('item_id', '=', $delete->product_id)
                ->where('purchase_order_id', '=', $delete->purchase_id)
                ->get();

            foreach ($foodCity as $food) {
                $foodDelete = foodCity::find($food->id);
                $foodDelete->delete();
                try {
                    $a = app('App\Http\Controllers\ActivityLogController')->index("Delete Indoor Foodcity Products");
                } catch (\Throwable $th) {
                }
            }
        }

        if ($delete->department_id == 2) {
            $InventoryHotelTransaction = InventoryHotelTransaction::where('inventory_hotel_transactions.transaction_id', $delete->id);
            $InventoryHotelTransaction->delete();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Delete Hotel Transaction");
            } catch (\Throwable $th) {
            }
        }

        return redirect('IndoorTransferShow')->with('success', 'Record Deleted');
    }


    function IndoorTransferEdit($id)
    {

        $Transfers = DB::table('inventory_indoor_transfer')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_transfer.product_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_transfer.department_id')
            ->select('inventory_indoor_transfer.*', 'inventory_products.product_name', 'inventory_departments.dept_name', 'inventory_indoor_transfer.id as indoorTransId')
            ->where('inventory_indoor_transfer.id', $id)
            ->get();
        // return $Transfers;
        return view('IndoorTransfer.IndoorTransferEdit', compact('Transfers'));
    }


    function IndoorTransferUpdate(Request $req)
    {

        // return $req;

        $InventoryIndoorTransfer = InventoryIndoorTransfer::find($req->id);

        if ($InventoryIndoorTransfer->status != $req->status) {
            $InventoryIndoorTransfer->approved_by = Auth::user()->emp_id;
            $InventoryIndoorTransfer->status = $req->status;
            $InventoryIndoorTransfer->save();


            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Indoor Transfer");
            } catch (\Throwable $th) {
            }
        }

        if ($InventoryIndoorTransfer->department_id == 2) {
            $InventoryHotelTransaction = InventoryHotelTransaction::where('transaction_id', $req->id)->first();
            $InventoryHotelTransaction->status = $req->status;
            $InventoryHotelTransaction->unit_sales_price = $req->sales_price;
            $InventoryHotelTransaction->save();
        }


        // Store account profit loss table code by sivakaran
        if ($req->status == 1) {
            $account_profit_loss = new account_profit_loss();
            $account_profit_loss->total_purchase_price = $InventoryIndoorTransfer->purchase_unit_price * $InventoryIndoorTransfer->transfer_quantity;
            $account_profit_loss->department_id = $InventoryIndoorTransfer->department_id;
            $account_profit_loss->type = "Inventory Transfer";
            $account_profit_loss->cash_out = $InventoryIndoorTransfer->purchase_unit_price * $InventoryIndoorTransfer->transfer_quantity;
            $account_profit_loss->connected_id = $InventoryIndoorTransfer->id;
            $account_profit_loss->is_delete = 0;
            $account_profit_loss->save();

            // this function use for calculation of inventory department
            $account_profit_loss = new account_profit_loss();
            $account_profit_loss->total_purchase_price = $InventoryIndoorTransfer->purchase_unit_price * $InventoryIndoorTransfer->transfer_quantity;
            $account_profit_loss->department_id = 4;
            $account_profit_loss->type = "Inventory Transfer";
            $account_profit_loss->cash_in = $InventoryIndoorTransfer->purchase_unit_price * $InventoryIndoorTransfer->transfer_quantity;
            $account_profit_loss->connected_id = $InventoryIndoorTransfer->id;
            $account_profit_loss->is_delete = 0;
            $account_profit_loss->save();
        }
        return redirect('IndoorTransferShow')->with('success', 'Successfully Updated');
    }



    // reports  start by kiruththigan
    public function indoorTransferReport()
    {
        $from = substr(Carbon::now()->subDays(30), 0, 10);
        $to = substr(Carbon::now(), 0, 10);
        $department_id = '';
        $status = "";
        $purchase_item = InventoryPurchaseItem::all();


        $departments = InventoryIndoorTransfer::join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_transfer.department_id')
            ->select('inventory_indoor_transfer.department_id', 'inventory_departments.dept_name')
            ->groupBy('inventory_indoor_transfer.department_id', 'inventory_departments.dept_name')
            ->get();

        $batches = DB::table('inventory_purchase_items')
            ->select(
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_purchase_items.pur_item_qty_type'
            )
            ->get();

        $indoor_transfer = InventoryPurchaseItem::all();
        $indoor_transfer = InventoryIndoorTransfer::leftJoin('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_transfer.product_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_transfer.department_id')
            ->join('inventory_purchase_items', 'inventory_purchase_items.id', '=', 'inventory_products.id')
            ->select(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_indoor_transfer.created_at',
                'inventory_departments.dept_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_products.id as product_id',
                'inventory_departments.id as dept_id',
                'inventory_indoor_transfer.status',
                DB::raw('SUM(inventory_indoor_transfer.transfer_quantity) as qty'),
            )
            ->groupBy('inventory_purchase_items.pur_item_qty', 'inventory_purchase_items.pur_item_qty_type', 'inventory_purchase_items.pur_item_amount', 'inventory_departments.dept_name', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_products.id', 'inventory_departments.id', 'inventory_indoor_transfer.status', 'inventory_indoor_transfer.created_at')
            ->where('inventory_indoor_transfer.created_at', '>=', $from)
            ->where('inventory_indoor_transfer.created_at', '<=', $to)
            ->get();

        return view('reports.IndoorTransferReport', ['departments' => $departments, 'department_id' => $department_id, 'from' => $from, 'to' => $to, 'indoor_transfer' => $indoor_transfer, 'status' => $status, 'batches' => $batches, 'purchase_item' => $purchase_item]);
    }

    public function indoorTransferReportView(Request $request)
    {
        $status = $request->status;
        $departments = InventoryIndoorTransfer::join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_transfer.department_id')
            ->select('inventory_indoor_transfer.department_id', 'inventory_departments.dept_name')
            ->groupBy('inventory_indoor_transfer.department_id', 'inventory_departments.dept_name')
            // ->where('status', '=', 1)
            ->get();

        $indoor_transfer = InventoryPurchaseItem::all();
        $indoor_transfer = InventoryIndoorTransfer::leftJoin('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_transfer.product_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_transfer.department_id')
            ->join('inventory_purchase_items', 'inventory_purchase_items.id', '=', 'inventory_products.id')
            ->select(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_indoor_transfer.created_at',
                'inventory_departments.dept_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_products.id as product_id',
                'inventory_departments.id as dept_id',
                'inventory_indoor_transfer.status',
                DB::raw('SUM(inventory_indoor_transfer.transfer_quantity) as qty')
            )
            ->groupBy('inventory_purchase_items.pur_item_qty', 'inventory_purchase_items.pur_item_qty_type', 'inventory_purchase_items.pur_item_amount', 'inventory_indoor_transfer.created_at', 'inventory_departments.dept_name', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_products.id', 'inventory_departments.id', 'inventory_indoor_transfer.status');
        // ->where('status','=',1);
        if ($status != "") {
            $indoor_transfer = $indoor_transfer->where('inventory_indoor_transfer.status', '=', $status);
        }
        if ($request->from) {
            $indoor_transfer = $indoor_transfer->where('inventory_indoor_transfer.created_at', '>=', $request->from);
        }

        if ($request->to) {
            $indoor_transfer = $indoor_transfer->where('inventory_indoor_transfer.created_at', '<=', $request->to);
        }

        if ($request->department) {
            $indoor_transfer = $indoor_transfer->where('inventory_indoor_transfer.department_id', '=', $request->department);
        }
        $indoor_transfer = $indoor_transfer->get();
        return view('reports.IndoorTransferReport', ['departments' => $departments, 'department_id' => $request->department, 'from' => $request->from, 'to' => $request->to, 'indoor_transfer' => $indoor_transfer, 'status' => $status]);
    }

    public function getDetailsForReport(Request $request)
    {
        // return $request;
        $indoor_transfer = InventoryPurchaseItem::all();
        $indoor_transfer = InventoryIndoorTransfer::leftJoin('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_transfer.product_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_transfer.department_id')
            ->join('inventory_purchase_items', 'inventory_purchase_items.id', '=', 'inventory_products.id')
            ->select(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_indoor_transfer.created_at',
                // DB::raw('DATE_FORMAT(inventory_indoor_transfer.created_at, "%Y-%m-%d") as created_at'),
                'inventory_departments.dept_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_products.id as product_id',
                'inventory_departments.id as dept_id',
                'inventory_indoor_transfer.purchase_id',
                'inventory_indoor_transfer.status',
                DB::raw('SUM(inventory_indoor_transfer.transfer_quantity) as qty')
            )
            ->groupBy('inventory_purchase_items.pur_item_qty', 'inventory_purchase_items.pur_item_qty_type', 'inventory_purchase_items.pur_item_amount', 'inventory_indoor_transfer.created_at', 'inventory_departments.dept_name', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_products.id', 'inventory_departments.id', 'inventory_indoor_transfer.purchase_id', 'inventory_indoor_transfer.status');
        // ->where('status','=',1)
        if ($request->status != "") {
            $indoor_transfer = $indoor_transfer->where('inventory_indoor_transfer.status', '=', $request->status);
        }
        if ($request->pro_id != "") {
            $indoor_transfer = $indoor_transfer->where('inventory_indoor_transfer.product_id', '=', $request->pro_id);
        }
        if ($request->dept_id != "") {
            $indoor_transfer = $indoor_transfer->where('inventory_indoor_transfer.department_id', '=', $request->dept_id);
        }
        if ($request->from != "") {
            $indoor_transfer = $indoor_transfer->where('inventory_indoor_transfer.created_at', '>=', $request->from);
        }
        if ($request->to != "") {
            $indoor_transfer = $indoor_transfer->where('inventory_indoor_transfer.created_at', '<=', $request->to);
        }

        $indoor_transfer = $indoor_transfer->get();
        return $indoor_transfer;
    }
}
