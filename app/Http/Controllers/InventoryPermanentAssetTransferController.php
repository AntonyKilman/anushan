<?php

namespace App\Http\Controllers;

use App\Models\InventoryPermanentAssetTransfer;
use Illuminate\Http\Request;
use App\Models\InventoryProduct;
use App\Models\InventoryDepartment;
use App\Models\department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\account_main_account;


class InventoryPermanentAssetTransferController extends Controller
{

    public function permanentTransferShow()
    {

        $permanentAssets = InventoryPermanentAssetTransfer::join('inventory_departments', 'inventory_departments.id', '=', 'inventory_permanent_asset_transfers.department_id')
            ->leftJoin('inventory_products', 'inventory_products.id', 'inventory_permanent_asset_transfers.permanent_assets_id')
            ->leftJoin('inventory_permanent_assets', 'inventory_permanent_assets.id', 'inventory_products.id')

            ->select(
                'inventory_permanent_asset_transfers.*',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_departments.dept_name as department_name',
                // 'inventory_permanent_assets.serial_number as trasfer_serial_number'
            )
            ->get();
        // return $permanentAssets;

        return view('PermanentAssetTransfer.PermanentAssetTransfer', compact('permanentAssets'));
    }


    public function PermanentAssetsAdd()
    {

        $PermanentAssets =  DB::table("inventory_permanent_assets")
            ->join("inventory_products", "inventory_products.id", "=", "inventory_permanent_assets.product_id")
            ->groupBy("inventory_permanent_assets.product_id", "inventory_products.product_name")
            ->select("inventory_permanent_assets.product_id", "inventory_products.product_name")
            ->get();
        $departments = InventoryDepartment::all();
        return view('PermanentAssetTransfer.PermanentAssetTransferAdd', compact('PermanentAssets', 'departments'));
    }


    public function PermanentAssetsAll($id)
    {
        $PermanentAssets = DB::table('inventory_permanent_assets')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_permanent_assets.product_id')
            ->where('inventory_permanent_assets.product_id', $id)
            ->select(
                'inventory_products.product_name',
                'inventory_permanent_assets.*'
            )
            ->get();
        // return $PermanentAssets;

        foreach ($PermanentAssets as $item) {

            $transfered = DB::table('inventory_permanent_asset_transfers')
                ->groupBy('inventory_permanent_asset_transfers.purchase_id', 'inventory_permanent_asset_transfers.permanent_assets_id')
                ->where('inventory_permanent_asset_transfers.purchase_id', $item->purchase_order_id)
                ->where('inventory_permanent_asset_transfers.permanent_assets_id', $item->product_id)
                ->select(DB::raw('SUM(inventory_permanent_asset_transfers.quantity)as total_qty'))
                ->first();


            if ($transfered) {
                $item->stock = $item->pur_item_qty - $transfered->total_qty;
            } else {
                $item->stock = $item->pur_item_qty;
            }
        }



        return $PermanentAssets;
    }

    // function PermanentAssetsTransferAddNew(Request $request)
    // {
    //     // return $request;

    //     $length = count($request->purchase_id);
    //     $department = $request->department_id;

    //     for ($i = 0; $i < $length; $i++) {


    //         $InventoryPermanentTransferNew = new InventoryPermanentAssetTransfer();
    //         $InventoryPermanentTransferNew->purchase_id  =  $request->purchase_id[$i];
    //         $InventoryPermanentTransferNew->permanent_assets_id  = $request->pro_id[$i];
    //         $InventoryPermanentTransferNew->quantity  = $request->transfer_qty[$i];
    //         $InventoryPermanentTransferNew->date  = $request->date[$i];
    //         $InventoryPermanentTransferNew->userEnter  =  Auth::user()->emp_id;
    //         $InventoryPermanentTransferNew->department_id  = $department;
    //         $InventoryPermanentTransferNew->save();
    //     }

    //     return redirect('/permanent-asset-transfer')->with('success', 'Record successfully');
    // }




    public function PermanentAssetsTransferAdd(Request $request)
    {
        $length = count($request->purchase_id);
        $depts = explode(",", $request->department_id);
        $department = $depts[0];
        $acc_dept = $depts[1];


        for ($i = 0; $i < $length; $i++) {
            $InventoryPermanentTransfer = new InventoryPermanentAssetTransfer();
            $InventoryPermanentTransfer->purchase_id  = $request->purchase_id[$i];
            $InventoryPermanentTransfer->permanent_assets_id  = $request->pro_id[$i];
            $InventoryPermanentTransfer->quantity  = $request->transfer_qty[$i];
            // $InventoryPermanentTransfer->date  = $request->date[$i];
            $InventoryPermanentTransfer->depreciation_persentage  = $request->depreciation_persentage[$i];
            $InventoryPermanentTransfer->userEnter  =  Auth::user()->emp_id;
            $InventoryPermanentTransfer->unit_price  = $request->pur_item_amount[$i] / $request->transfer_qty[$i];
            $InventoryPermanentTransfer->department_id  = $department;
            $InventoryPermanentTransfer->date  = $request->date;
            $InventoryPermanentTransfer->save();

            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create PermanentTransfer");
            } catch (\Throwable $th) {
            }


            // store data for accounts
            $CashAccount = new account_main_account();
            $CashAccount->debit =  $request->pur_item_amount[$i] * $request->transfer_qty[$i];
            $CashAccount->dept_id = 6; //Inventory
            $CashAccount->date = Carbon::now()->format('Y-m-d');
            $CashAccount->account_type = 1000;
            $CashAccount->description = "Permanaent Asset Transfer";
            $CashAccount->connected_id = $InventoryPermanentTransfer->id;
            $CashAccount->sub_category = $request->pro_id[$i]; //product_id
            $CashAccount->table_id = 1; //cash account
            $CashAccount->cash =  $request->pur_item_amount[$i] * $request->transfer_qty[$i];
            $CashAccount->save();

            $SalesAccount = new account_main_account();
            $SalesAccount->credit = $request->pur_item_amount[$i] * $request->transfer_qty[$i];
            $SalesAccount->dept_id = 6; //Inventory
            $SalesAccount->date = Carbon::now()->format('Y-m-d');
            $SalesAccount->account_type = 4000;
            $SalesAccount->description  =  "Permanaent Asset Transfer";
            $SalesAccount->connected_id = $InventoryPermanentTransfer->id;
            $SalesAccount->table_id = 2; //sales account
            $SalesAccount->cash = $request->pur_item_amount[$i] * $request->transfer_qty[$i];
            $SalesAccount->sub_category = $request->pro_id[$i]; //product_id
            $SalesAccount->purchase_amount =  $request->pur_item_amount[$i] * $request->transfer_qty[$i];
            $SalesAccount->save();



            $CashAccount = new account_main_account();
            $CashAccount->credit =  $request->pur_item_amount[$i] * $request->transfer_qty[$i];
            $CashAccount->dept_id = $acc_dept;
            $CashAccount->date = Carbon::now()->format('Y-m-d');
            $CashAccount->account_type = 1000;
            $CashAccount->description = "Inventory Permanent Asset Transfer";
            $CashAccount->connected_id = $InventoryPermanentTransfer->id;
            $CashAccount->sub_category = $request->pro_id[$i]; //product_id
            $CashAccount->table_id = 1; //cash account
            $CashAccount->cash = $request->pur_item_amount[$i] * $request->transfer_qty[$i];
            $CashAccount->save();

            $purchaseAccount = new account_main_account();
            $purchaseAccount->debit = $request->pur_item_amount[$i] * $request->transfer_qty[$i];
            $purchaseAccount->dept_id =$acc_dept;
            $purchaseAccount->date = Carbon::now()->format('Y-m-d');
            $purchaseAccount->account_type = 1000;
            $purchaseAccount->description = "Inventory Permanent Asset Transfer";
            $purchaseAccount->connected_id = $InventoryPermanentTransfer->id;
            $purchaseAccount->table_id = 9; //current assets account
            $purchaseAccount->sub_category = $request->pro_id[$i]; //product_id
            $purchaseAccount->cash = $request->pur_item_amount[$i] * $request->transfer_qty[$i];
            $purchaseAccount->purchase_amount = $request->pur_item_amount[$i] * $request->transfer_qty[$i];
            $purchaseAccount->save();
        }

        return redirect('/permanent-asset-transfer')->with('success', 'Record Successfully');
    }


    public function permanentTransferReport()
    {
        $from = substr(Carbon::now()->subDays(30), 0, 10);
        $to = substr(Carbon::now(), 0, 10);
        $department_id = '';
        // $permanent_item = InventoryPermanentAssetTransfer::all();

        $departments = InventoryDepartment::all();

        $permanent_trasfer = InventoryPermanentAssetTransfer::join('inventory_departments', 'inventory_departments.id', '=', 'inventory_permanent_asset_transfers.department_id')
            ->leftJoin('inventory_products', 'inventory_products.id', 'inventory_permanent_asset_transfers.permanent_assets_id')
            ->leftJoin('inventory_permanent_assets', 'inventory_permanent_assets.id', 'inventory_products.id')

            ->select(
                'inventory_permanent_asset_transfers.*',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_departments.dept_name as department_name',
                'inventory_permanent_assets.serial_number'
            )

            ->groupBy(
                'inventory_permanent_asset_transfers.id',
                'inventory_permanent_asset_transfers.purchase_id',
                'inventory_permanent_asset_transfers.department_id',
                'inventory_permanent_asset_transfers.permanent_assets_id',
                'inventory_permanent_asset_transfers.quantity',
                'inventory_permanent_asset_transfers.userEnter',
                'inventory_permanent_asset_transfers.created_at',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_departments.dept_name',
                'inventory_products.id',
                'inventory_departments.id',
            )
            ->whereDate('inventory_permanent_asset_transfers.created_at', '>=', $from)
            ->whereDate('inventory_permanent_asset_transfers.created_at', '<=', $to)
            ->get();

        // return   $permanent_trasfer;

        return view('reports.PermanentAssetTransferReport', compact('from', 'to', 'permanent_trasfer', 'departments'));
    }



    public function permanentTransferReportView(Request $request)
    {
        $departments = InventoryDepartment::all();
        $permanent_trasfer = InventoryPermanentAssetTransfer::all();
        $permanent_trasfer = InventoryPermanentAssetTransfer::leftJoin('inventory_products', 'inventory_products.id', '=', 'inventory_permanent_asset_transfers.permanent_assets_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_permanent_asset_transfers.department_id')
            ->leftJoin('inventory_permanent_assets', 'inventory_permanent_assets.id', 'inventory_products.id')
            ->select(
                'inventory_permanent_asset_transfers.*',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_departments.dept_name as department_name',
                'inventory_permanent_assets.serial_number',
                DB::raw('SUM(inventory_permanent_asset_transfers.quantity) as qty')

            )

            ->groupBy(
                'inventory_permanent_asset_transfers.id',
                'inventory_permanent_asset_transfers.purchase_id',
                'inventory_permanent_asset_transfers.department_id',
                'inventory_permanent_asset_transfers.permanent_assets_id',
                'inventory_permanent_asset_transfers.quantity',
                'inventory_permanent_asset_transfers.userEnter',
                'inventory_permanent_asset_transfers.created_at',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_departments.dept_name',
                'inventory_products.id',
                'inventory_departments.id',
            );

        if ($request->from) {
            $permanent_trasfer = $permanent_trasfer->whereDate('inventory_permanent_asset_transfers.created_at', '>=', $request->from);
        }

        if ($request->to) {
            $permanent_trasfer = $permanent_trasfer->whereDate('inventory_permanent_asset_transfers.created_at', '<=', $request->to);
        }

        if ($request->department) {
            $permanent_trasfer = $permanent_trasfer->where('inventory_permanent_asset_transfers.department_id', '=', $request->department);
        }
        $permanent_trasfer = $permanent_trasfer->get();



        return view('reports.PermanentAssetTransferReport', ['departments' => $departments, 'department_id' => $request->department, 'from' => $request->from, 'to' => $request->to, 'permanent_trasfer' => $permanent_trasfer,]);
    }
}
