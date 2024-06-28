<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\InventoryIndoorTransfer;
use App\Models\InventoryIndoorReturn;
use App\Models\FoodcityProductReturn;
use App\Models\InventoryDepartment;
use App\Models\foodCity;
use App\Models\InventoryPurchaseOrder;
use App\Models\InventoryEquipment;



use App\Models\InventoryOutdoorReturn;
use LengthException;
use Illuminate\Support\Facades\DB;

class inventoryDashController extends Controller
{
    function getDashDatas()
    {
        $experydate_count = 0;

        $transfers = InventoryIndoorTransfer::join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_transfer.product_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_transfer.department_id')
            ->select('inventory_indoor_transfer.*', 'inventory_products.product_name', 'inventory_departments.dept_name', 'inventory_indoor_transfer.id as indoorTransId')
            ->where('status', "0")
            ->get()
            ->count();

        $indoor_return_dash = InventoryIndoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_returns.product_id')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_indoor_returns.seller_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_returns.department_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'inventory_indoor_returns.return_reason_id')
            ->select('inventory_indoor_returns.*', 'inventory_departments.dept_name', 'inventory_products.product_name', 'inventory_sellers.seller_name', 'inventory_return_reasons.reason_name')
            ->where('inventory_indoor_returns.status', '=', 0)
            ->get()
            ->count();

        $foodcity_returns = FoodcityProductReturn::join('inventory_products', 'inventory_products.id', '=', 'foodcity_product_returns.item_id')
            // ->join('inventory_sellers','inventory_sellers.id','=','foodcity_product_returns.seller_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'foodcity_product_returns.return_reason_id')
            ->select('foodcity_product_returns.*', 'inventory_products.product_name', 'inventory_return_reasons.reason_name')
            ->where('foodcity_product_returns.status', '=', 0)
            ->get()
            ->count();

        $indoorRetrun = $indoor_return_dash + $foodcity_returns;

        $outdoor_returns = InventoryOutdoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_outdoor_returns.product_id')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_outdoor_returns.seller_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'inventory_outdoor_returns.return_reason_id')
            ->select('inventory_outdoor_returns.*', 'inventory_products.product_name', 'inventory_sellers.seller_name', 'inventory_return_reasons.reason_name')
            ->where('inventory_outdoor_returns.status', '=', 0)
            ->get()
            ->count();


        $equipments_count = InventoryEquipment::join('inventory_products', 'inventory_products.id', '=', 'inventory_equipment_transfer.product_id')
            ->join('departments', 'departments.id', '=', 'inventory_equipment_transfer.department_id')
            ->join('employees', 'employees.id', '=', 'inventory_equipment_transfer.employee_id')
            ->select('inventory_equipment_transfer.*', 'inventory_products.product_name', 'inventory_equipment_transfer.id as equTransId', 'departments.name as mainDeptName', 'employees.emp_code')
            ->where('inventory_equipment_transfer.status', 1)
            ->get()
            ->count();

        //expery date by kiruththigan 
        $purchase_items = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->select('inventory_purchase_items.*', 'inventory_products.product_name', 'inventory_products.product_code')
            ->where('inventory_purchase_items.pur_item_expery_date', '<=', date('Y-m-d', strtotime(now()->adddays(10))))
            ->where('inventory_purchase_items.pur_item_expery_date', '>=', date('Y-m-d', strtotime(now())))
            ->orderBy('inventory_purchase_items.pur_item_expery_date', 'DESC')
            ->get();


        foreach ($purchase_items as $purchase_item) {

            $outdoor_return = DB::table('inventory_outdoor_returns')
                ->select('return_qty', 'id')
                ->where('product_id', '=', $purchase_item->product_id)
                ->where('purchase_order_id', '=', $purchase_item->purchase_order_id)
                ->where('status', '=', 1)
                ->get();
            $outdoor_return_qty = 0;
            foreach ($outdoor_return as $value) {
                $outdoor_return_qty += $value->return_qty;
            }

            $indoor_return = DB::table('inventory_indoor_returns')
                ->select('return_qty', 'id')
                ->where('product_id', '=', $purchase_item->product_id)
                ->where('purchase_order_id', '=', $purchase_item->purchase_order_id)
                ->where('status', '=', 1)
                ->get();
            $indoor_return_qty = 0;
            foreach ($indoor_return as $value) {
                $indoor_return_qty += $value->return_qty;
            }

            $indoor_transfer = DB::table('inventory_indoor_transfer')
                ->select('transfer_quantity', 'id')
                ->where('product_id', '=', $purchase_item->product_id)
                ->where('purchase_id', '=', $purchase_item->purchase_order_id)
                ->where('status', "=", 1)
                ->get();
            $indoor_transfer_qty = 0;
            foreach ($indoor_transfer as $value) {
                $indoor_transfer_qty += $value->transfer_quantity;
            }

            $indoor_foodcity_return = DB::table('foodcity_product_returns')
                ->select('return_qty', 'id')
                ->where('item_id', '=', $purchase_item->product_id)
                ->where('purchase_order_id', '=', $purchase_item->purchase_order_id)
                ->where('status', '=', 1)
                ->get();
            $indoor_foodcity_return_qty = 0;
            foreach ($indoor_foodcity_return as $value) {
                $indoor_foodcity_return_qty += $value->return_qty;
            }

            $equipment_transfer = DB::table('inventory_equipment_transfer')
                ->select('quantity', 'id')
                ->where('product_id', '=', $purchase_item->product_id)
                ->where('purchase_id', '=', $purchase_item->purchase_order_id)
                ->where('status', "=", 1)
                ->get();
            $equipment_transfer_qty = 0;

            foreach ($equipment_transfer as $value) {
                $equipment_transfer_qty += $value->quantity;
            }

            $purchase_item->pur_item_qty = $purchase_item->pur_item_qty + $indoor_return_qty + $indoor_foodcity_return_qty - $outdoor_return_qty - $indoor_transfer_qty - $equipment_transfer_qty;
            if ($purchase_item->pur_item_qty > 0) {
                $experydate_count++;
            }
        }
        //end expery date

        $departments = InventoryDepartment::all();

        // start qty alert count
        $danger = 0;
        $min = 0;
        $max = 0;
        
        for ($i = 1; $i < 4; $i++) {
            $batches = DB::table('inventory_purchase_items')
                ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
                ->groupBy('inventory_purchase_items.pur_item_qty_type', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_purchase_items.product_id')
                ->select(
                    'inventory_purchase_items.product_id',
                    'inventory_purchase_items.pur_item_qty_type as qty_type',
                    'inventory_products.product_name',
                    'inventory_products.product_code',
                    DB::raw("SUM(inventory_purchase_items.pur_item_qty) as qty")
                )
                ->orderBy('inventory_purchase_items.product_id', 'ASC')
                ->get();

            foreach ($batches as $batch) {
                $alert_qty = DB::table('inventory_products')->select('danger_qty', 'min_qty', 'max_qty')->where('id', '=', $batch->product_id)->first();

                $outdoor_return = DB::table('inventory_outdoor_returns')
                    ->select('return_qty', 'id')
                    ->where('product_id', '=', $batch->product_id)
                    // ->where('purchase_order_id','=',$batch->purchase_order_id)
                    ->where('status', '=', 1)
                    ->get();
                $outdoor_return_qty = 0;
                foreach ($outdoor_return as $value) {
                    $outdoor_return_qty += $value->return_qty;
                }

                $indoor_return = DB::table('inventory_indoor_returns')
                    ->select('return_qty', 'id')
                    ->where('product_id', '=', $batch->product_id)
                    // ->where('purchase_order_id','=',$batch->purchase_order_id)
                    ->where('status', '=', 1)
                    ->get();
                $indoor_return_qty = 0;
                foreach ($indoor_return as $value) {
                    $indoor_return_qty += $value->return_qty;
                }

                $indoor_transfer = DB::table('inventory_indoor_transfer')
                    ->select('transfer_quantity', 'id')
                    ->where('product_id', '=', $batch->product_id)
                    // ->where('purchase_id','=',$batch->purchase_order_id)
                    ->where('status', "=", 1)
                    ->get();
                $indoor_transfer_qty = 0;
                foreach ($indoor_transfer as $value) {
                    $indoor_transfer_qty += $value->transfer_quantity;
                }

                $indoor_foodcity_return = DB::table('foodcity_product_returns')
                    ->select('return_qty', 'id')
                    ->where('item_id', '=', $batch->product_id)
                    // ->where('purchase_order_id','=',$batch->purchase_order_id)
                    ->where('status', '=', 1)
                    ->get();
                $indoor_foodcity_return_qty = 0;
                foreach ($indoor_foodcity_return as $value) {
                    $indoor_foodcity_return_qty += $value->return_qty;
                }

                $equipment_transfer = DB::table('inventory_equipment_transfer')
                    ->select('quantity', 'id')
                    ->where('product_id', '=', $batch->product_id)
                    // ->where('purchase_id', '=', $batch->purchase_order_id)
                    ->where('status', "=", 1)
                    ->get();
                $equipment_transfer_qty = 0;

                foreach ($equipment_transfer as $value) {
                    $equipment_transfer_qty += $value->quantity;
                }

                $batch->qty = $batch->qty + $indoor_return_qty + $indoor_foodcity_return_qty - $outdoor_return_qty - $indoor_transfer_qty - $equipment_transfer_qty;

                if ($i == 1) {
                    if ($alert_qty->danger_qty != null) {
                        if ($alert_qty->danger_qty >= $batch->qty) {
                            $danger++;
                        }
                    }
                }
                if ($i == 2) {
                    if ($alert_qty->min_qty != null) {
                        if ($alert_qty->danger_qty < $batch->qty && $alert_qty->min_qty >= $batch->qty) {
                            $min++;
                        }
                    }
                }
                if ($i == 3) {
                    if ($alert_qty->max_qty != null) {
                        if ($alert_qty->max_qty < $batch->qty) {
                            $max++;
                        }
                    }
                }
            }
        }
        // end qty alert count

        return view('dashboard.main-dash', compact('transfers', 'outdoor_returns', 'experydate_count', 'indoorRetrun', 'equipments_count', 'danger', 'min', 'max'));
    }


    function dashIndoorTransfer(Request $req)
    {

        $department = $req->dept_id;

        $InventoryIndoorTransfer = new InventoryIndoorTransfer();
        $InventoryIndoorTransfer->purchase_id = $req->purchase_id;
        $InventoryIndoorTransfer->product_id = $req->productId;
        $InventoryIndoorTransfer->quantity = $req->qty;
        $InventoryIndoorTransfer->transfer_quantity = $req->trans_qty;
        $InventoryIndoorTransfer->department_id = $department;
        $InventoryIndoorTransfer->user_id = 1;
        $InventoryIndoorTransfer->exDate = $req->exDate;
        $InventoryIndoorTransfer->status = 0;
        $InventoryIndoorTransfer->save();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Create Indoortransfer");
        } catch (\Throwable $th) {
        }

        if ($department == 1) {
            $foodCity = new foodCity();
            $foodCity->name = $req->product_name;
            $foodCity->product_code = $req->ProCode;
            $foodCity->quantity = $req->trans_qty;
            $foodCity->status = 0;
            $foodCity->sub_category_id = $req->subCatId;
            $foodCity->purchase_order_id = $req->purchase_id;
            $foodCity->purchase_price = $req->amount;
            $foodCity->scale_id = $req->qty_type;
            $foodCity->expire_date = $req->exDate;
            $foodCity->user_id = 1;
            $foodCity->transection_id = $InventoryIndoorTransfer->id;
            $foodCity->item_id  = $req->productId;  //inventory product table id
            $foodCity->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Foodcity Products");
            } catch (\Throwable $th) {
            }
        }


        return redirect('expery-products-show')->with('success', 'Successfully Recorded');
    }


    function dashoutdoorReturn(Request $request)
    {

        $seller = InventoryPurchaseOrder::where('id', '=', $request->purchase_id)->select('seller_id')->first();
        $outdoor_return = new InventoryOutdoorReturn();
        $outdoor_return->purchase_order_id = $request->purchase_id;
        $outdoor_return->product_id = $request->productId;
        $outdoor_return->seller_id = $seller->seller_id;
        $outdoor_return->return_reason_id = $request->reason_id;
        $outdoor_return->qty = $request->qty;
        $outdoor_return->return_qty = $request->trans_qty;
        $outdoor_return->user_id = 55;
        $outdoor_return->status = 0;
        $outdoor_return->save();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Create Outdoor Return");
        } catch (\Throwable $th) {
        }
        return redirect('expery-products-show')->with('success', 'Successfully Recorded');
    }
}
