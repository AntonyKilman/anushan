<?php

namespace App\Http\Controllers;

use App\Models\InventoryIndoorReturn;
use App\Models\InventoryProduct;
use App\Models\InventoryReturnReason;
use App\Models\InventoryDepartment;
use App\Models\InventoryPurchaseOrder;
use App\Models\FoodcityProductReturn;
use App\Models\InventorySeller;
use App\Models\account_profit_loss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\InventoryPurchaseItem;
use App\Models\account_main_account;
use Illuminate\Support\Facades\Auth;

class InventoryIndoorReturnController extends Controller
{
    public function viewPurchaseItem(Request $request)
    {
        $batches = DB::table('inventory_indoor_transfer')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_transfer.product_id')
            ->where('product_id', '=', $request->id)
            ->where('department_id', '=', $request->department_id)
            ->where('status', '=', 1)
            ->groupBy('inventory_indoor_transfer.purchase_id', 'inventory_indoor_transfer.department_id', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_indoor_transfer.product_id', 'inventory_indoor_transfer.purchase_unit_price', 'inventory_indoor_transfer.Qty_type',)
            ->select(
                'inventory_indoor_transfer.purchase_id',
                'inventory_indoor_transfer.product_id',
                'inventory_indoor_transfer.purchase_unit_price',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_indoor_transfer.department_id',
                'inventory_indoor_transfer.Qty_type',
                DB::raw("SUM(inventory_indoor_transfer.transfer_quantity) as qty")
            )
            ->get();
        // return $batches;

        foreach ($batches as $batch) {
            $indoor_return = DB::table('inventory_indoor_returns')
                ->select('return_qty', 'id')
                ->where('product_id', '=', $batch->product_id)
                ->where('purchase_order_id', '=', $batch->purchase_id)
                ->where('department_id', '=', $batch->department_id)
                ->where('status', '=', 1)
                ->get();

            $indoor_return_qty = 0;
            foreach ($indoor_return as $value) {
                $indoor_return_qty += $value->return_qty;
            }
            $batch->qty = $batch->qty - $indoor_return_qty;
        }

        return response()->json([
            'batches' => $batches
        ]);
    }

    public function indoorReturnAdd()
    {
        $products =  DB::table("inventory_purchase_items")
            ->join("inventory_products", "inventory_products.id", "=", "inventory_purchase_items.product_id")
            ->groupBy("inventory_purchase_items.product_id", "inventory_products.product_name")
            ->select("inventory_purchase_items.product_id", "inventory_products.product_name")
            ->get();
        $reasons = InventoryReturnReason::all();
        $departments = InventoryDepartment::all();
        $all_product = InventoryProduct::all();
        return view('indoor_return.IndoorReturnAdd', ['products' => $products, 'reasons' => $reasons, 'departments' => $departments, 'all_product' => $all_product]);
    }

    public function indoorReturnAddProcess(Request $request)
    {
        // return $request;
        $request->validate([
            'produt_id' => 'required',
            'department_id' => 'required',
        ]);


        $depts = explode(",", $request->department_id);
        $department = $depts[0];
        $department_name = $depts[1];
        $acc_dept_id = $depts[2];


        $check = 0;

        try {
            $length = count($request->produt_id);
            $depts = explode(",", $request->department_id);
            // return $depts;
            $department = $depts[0];
            $department_name = $depts[1];
            $acc_dept_id = $depts[2];
            // return "jhjh";

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Please select atleast one raw');
        }


        for ($i = 0; $i < $length; $i++) {
            $a = $request->pur_ord_id[$i] . '-' . $request->produt_id[$i];

            if ($request->$a) {
                $seller = InventoryPurchaseOrder::where('id', '=', $request->pur_ord_id[$i])->select('seller_id')->first();

                $outdoor_return = new InventoryIndoorReturn();
                $outdoor_return->department_id = $department;
                $outdoor_return->purchase_order_id = $request->pur_ord_id[$i];
                $outdoor_return->product_id = $request->produt_id[$i];
                $outdoor_return->seller_id = $seller->seller_id;
                $outdoor_return->return_reason_id = $request->reason_id[$i];
                $outdoor_return->qty = $request->qty[$i];
                $outdoor_return->purchase_unit_price = $request->purchase_unit_price[$i];
                $outdoor_return->return_qty = $request->return_qty[$i];
                $outdoor_return->Qty_type = $request->Qty_type[$i];
                $outdoor_return->user_id = Auth::user()->emp_id;
                $outdoor_return->status = 1;
                // $outdoor_return->approved_by=66;
                try {
                    $outdoor_return->save();
                    try {
                        $a = app('App\Http\Controllers\ActivityLogController')->index("Create Indoor Return");
                    } catch (\Throwable $th) {
                    }
                    $check = 1 + $i;
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', 'Something Wrong Please Try Again');
                }

                // store data for accounts
                $CashAccount = new account_main_account();
                $CashAccount->credit = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $CashAccount->dept_id = 6; //Inventory
                $CashAccount->date = Carbon::now()->format('Y-m-d');
                $CashAccount->account_type = 1000;
                $CashAccount->description = $department_name . "Return";
                $CashAccount->connected_id = $outdoor_return->id;
                $CashAccount->sub_category = $request->produt_id[$i]; //product_id
                $CashAccount->table_id = 1; //cash account
                $CashAccount->cash = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $CashAccount->save();

                $Sales_returnAccount = new account_main_account();
                $Sales_returnAccount->debit = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $Sales_returnAccount->dept_id = 6; //Inventory
                $Sales_returnAccount->date = Carbon::now()->format('Y-m-d');
                $Sales_returnAccount->account_type = 1000;
                $Sales_returnAccount->description = $department_name . "Return";
                $Sales_returnAccount->connected_id = $outdoor_return->id;
                $Sales_returnAccount->sub_category = $request->produt_id[$i]; //product_id
                $Sales_returnAccount->table_id = 3; //sales return account
                $Sales_returnAccount->cash = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $Sales_returnAccount->purchase_amount = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $Sales_returnAccount->save();

                $CashAccount = new account_main_account();
                $CashAccount->debit = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $CashAccount->dept_id = $acc_dept_id; //selected department
                $CashAccount->date = Carbon::now()->format('Y-m-d');
                $CashAccount->account_type = 1000;
                $CashAccount->description = "Inventory Return";
                $CashAccount->connected_id = $outdoor_return->id;
                $CashAccount->sub_category = $request->produt_id[$i]; //product_id
                $CashAccount->table_id = 1; //cash account
                $CashAccount->cash = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $CashAccount->save();

                $purchaseReturnAccount = new account_main_account();
                $purchaseReturnAccount->credit = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $purchaseReturnAccount->dept_id = $acc_dept_id; //selected department
                $purchaseReturnAccount->date = Carbon::now()->format('Y-m-d');
                $purchaseReturnAccount->account_type = 1000;
                $purchaseReturnAccount->description = "Inventory Return";
                $purchaseReturnAccount->connected_id = $outdoor_return->id;
                $purchaseReturnAccount->sub_category = $request->produt_id[$i]; //product_id
                $purchaseReturnAccount->table_id = 6; //purchase Return account
                $purchaseReturnAccount->cash = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $purchaseReturnAccount->purchase_amount = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $purchaseReturnAccount->save();
            }
        }

           // store data for accounts double entry
        //    $OutdoorReturn = new account_main_account();
        //    // $SalesAccount -> debit = $total_transfer_amount;
        //       $OutdoorReturn -> dept_id = 6; //Inventory
        //       $OutdoorReturn -> date = Carbon::now()->format('Y-m-d');
        //       $OutdoorReturn -> account_type = 4000;
        //       $OutdoorReturn -> description  = $department_name."Transfer";
        //       $OutdoorReturn -> connected_id = $outdoor_return->id;
        //       $OutdoorReturn -> table_id = 2; //return account
        //    // $OutdoorReturn -> cash = $total_transfer_amount;
        //       $OutdoorReturn -> save();


        //    $CashAccount = new account_main_account();
        // // $CashAccount -> credit = $total_transfer_amount;
        //    $CashAccount -> dept_id = 6; //Inventory
        //    $CashAccount -> date = Carbon::now()->format('Y-m-d');
        //    $CashAccount -> account_type = 1000;
        //    $CashAccount -> description = $department_name."Transfer";
        //    $CashAccount -> connected_id = $outdoor_return->id;
        //    $CashAccount -> table_id = 1; //cash account
        //    $CashAccount -> save();

        if ($check > 0) {
            return redirect('/indoor-return-show-all')->with('success', 'Successfully Recorded');
        } else {
            return redirect()->back()->with('error', 'Please select atleast one raw');
        }
    }

    public function indoorReturnShowAll()
    {
        $indoor_returns = InventoryIndoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_returns.product_id')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_indoor_returns.seller_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_returns.department_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'inventory_indoor_returns.return_reason_id')
            ->orderBy('id', 'desc')
            ->select('inventory_indoor_returns.*', 'inventory_departments.dept_name', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_sellers.seller_name', 'inventory_return_reasons.reason_name')
            ->get();

        $foodcity_returns = FoodcityProductReturn::join('inventory_products', 'inventory_products.id', '=', 'foodcity_product_returns.product_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'foodcity_product_returns.return_reason_id')
            ->select('foodcity_product_returns.*', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_return_reasons.reason_name')
            ->where('foodcity_product_returns.status', '=', 0)
            ->get();


        return view('indoor_return.IndoorReturnShowAll', ['indoor_returns' => $indoor_returns, 'foodcity_returns' => $foodcity_returns]);
    }

    public function indoorReturnEdit($id)
    {
        $indoor_return = InventoryIndoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_returns.product_id')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_indoor_returns.seller_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'inventory_indoor_returns.return_reason_id')
            ->select('inventory_indoor_returns.*', 'inventory_products.product_name', 'inventory_sellers.seller_name', 'inventory_return_reasons.reason_name')
            ->where('inventory_indoor_returns.id', '=', $id)->first();
        // return $indoor_return;
        return view('indoor_return.IndoorReturnEdit', ['indoor_return' => $indoor_return]);
    }

    public function indoorReturnUpdateProcess(Request $request)
    {


        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $indoor_return = InventoryIndoorReturn::find($request->id);



        if ($indoor_return->status != $request->status) {
            $indoor_return->status = $request->status;
            $indoor_return->approved_by = Auth::user()->emp_id;
            $indoor_return->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Indoor Return");
            } catch (\Throwable $th) {
            }
        }


        // Store account profit loss table code by sivakaran
        if ($request->status == 1) {
            $account_profit_loss = new account_profit_loss();
            $account_profit_loss->total_purchase_price = $request->purchase_unit_price *  $request->return_qty;
            $account_profit_loss->department_id = $indoor_return->department_id;
            $account_profit_loss->type = "Inventory Indoor Return";
            $account_profit_loss->cash_out =  $request->purchase_unit_price *  $request->return_qty;
            $account_profit_loss->connected_id = $indoor_return->id;
            $account_profit_loss->is_delete = 0;
            $account_profit_loss->save();
        }

        return redirect('/indoor-return-show-all')->with('success', 'successfully updated');
    }


    public function indoorFoodcityReturnEdit($id)
    {
        $foodcity_returns = FoodcityProductReturn::join('inventory_products', 'inventory_products.id', '=', 'foodcity_product_returns.item_id')
            // ->join('inventory_sellers','inventory_sellers.id','=','foodcity_product_returns.seller_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'foodcity_product_returns.return_reason_id')
            ->select('foodcity_product_returns.*', 'inventory_products.product_name', 'inventory_return_reasons.reason_name')
            ->where('foodcity_product_returns.status', '=', 0)
            ->where('foodcity_product_returns.id', '=', $id)
            ->first();
        return view('indoor_return.IndoorFoodcityReturnEdit', ['foodcity_returns' => $foodcity_returns]);
    }

    public function indoorFoodcityReturnUpdateProcess(Request $request)
    {
        // return $request;
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $indoor_return = FoodcityProductReturn::find($request->id);
        if ($indoor_return->status != $request->status) {
            $indoor_return->status = $request->status;
            $indoor_return->approved_by = Auth::user()->emp_id;
            $indoor_return->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Indoor Foodcity Return");
            } catch (\Throwable $th) {
            }
        }
        return redirect('/indoor-return-show-all')->with('success', 'successfully updated');
    }

    public function indoorReturnDelete($id)
    {
        $indoor_return = InventoryIndoorReturn::find($id);
        $indoor_return->delete();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Delete Indoor Return");
        } catch (\Throwable $th) {
        }
        return redirect('/indoor-return-show-all')->with('success', 'successfully deleted');
    }

    public function indoorFoodcityReturnDelete($id)
    {
        $indoor_foodcity_return = FoodcityProductReturn::find($id);
        $indoor_foodcity_return->delete();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Delete Indoor Foodcity Return");
        } catch (\Throwable $th) {
        }
        return redirect('/indoor-return-show-all')->with('success', 'successfully deleted');
    }

    // reports

    public function indoorReturnReport()
    {
        $from = substr(Carbon::now()->subDays(30), 0, 10);
        $to = substr(Carbon::now(), 0, 10);
        $filter_by = '';
        $department_id = '';
        $seller_id = '';
        $product_id = '';
        $status = "";
        $indoor_return = [];

        $departments = InventoryIndoorReturn::join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_returns.department_id')
            ->select('inventory_indoor_returns.department_id', 'inventory_departments.dept_name')
            ->groupBy('inventory_indoor_returns.department_id', 'inventory_departments.dept_name')
            ->get();

        $sellers = InventoryIndoorReturn::join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_indoor_returns.seller_id')
            ->select('inventory_indoor_returns.seller_id', 'inventory_sellers.seller_name')
            ->groupBy('inventory_indoor_returns.seller_id', 'inventory_sellers.seller_name')
            ->get();

        $products = InventoryIndoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_returns.product_id')
            ->select('inventory_indoor_returns.product_id', 'inventory_products.product_name')
            ->groupBy('inventory_indoor_returns.product_id', 'inventory_products.product_name')
            ->get();

        //...........................

        $indoor_return = InventoryPurchaseItem::all();
        $indoor_return = InventoryIndoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_returns.product_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_returns.department_id')
            ->join('inventory_purchase_items', 'inventory_purchase_items.id', '=', 'inventory_products.id')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_indoor_returns.seller_id')
            ->select(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_indoor_returns.created_at',
                'inventory_indoor_returns.status',
                'inventory_departments.dept_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_indoor_returns.product_id',
                'inventory_indoor_returns.department_id',
                'inventory_indoor_returns.purchase_unit_price',
                DB::raw('SUM(inventory_indoor_returns.return_qty) as qty'),
                'inventory_indoor_returns.Qty_type'
            )
            ->groupBy('inventory_purchase_items.pur_item_qty_type', 'inventory_purchase_items.pur_item_amount', 'inventory_purchase_items.pur_item_qty', 'inventory_indoor_returns.created_at', 'inventory_indoor_returns.status', 'inventory_departments.dept_name', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_indoor_returns.product_id', 'inventory_indoor_returns.department_id', 'inventory_indoor_returns.purchase_unit_price', 'inventory_indoor_returns.Qty_type')
            ->where('inventory_indoor_returns.created_at', '>=', $from)
            ->where('inventory_indoor_returns.created_at', '<=', $to)
            ->get();


        return view('reports.IndoorReturnReport', ['products' => $products, 'product_id' => $product_id, 'sellers' => $sellers, 'seller_id' => $seller_id, 'department_id' => $department_id, 'filter_by' => $filter_by, 'from' => $from, 'to' => $to, 'indoor_return' => $indoor_return, 'departments' => $departments, 'status' => $status]);
    }

    public function indoorReturnReportView(Request $request)
    {
        $status = $request->status;
        $departments = InventoryIndoorReturn::join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_returns.department_id')
            ->select('inventory_indoor_returns.department_id', 'inventory_departments.dept_name')
            ->groupBy('inventory_indoor_returns.department_id', 'inventory_departments.dept_name')
            // ->where('status', '=', 1)
            ->get();

        $sellers = InventoryIndoorReturn::join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_indoor_returns.seller_id')
            ->select('inventory_indoor_returns.seller_id', 'inventory_sellers.seller_name')
            ->groupBy('inventory_indoor_returns.seller_id', 'inventory_sellers.seller_name')
            // ->where('status', '=', 1)
            ->get();

        $products = InventoryIndoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_returns.product_id')
            ->select('inventory_indoor_returns.product_id', 'inventory_products.product_name')
            ->groupBy('inventory_indoor_returns.product_id', 'inventory_products.product_name')
            // ->where('status', '=', 1)
            ->get();

        $indoor_return = InventoryPurchaseItem::all();
        $indoor_return = InventoryIndoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_returns.product_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_returns.department_id')
            ->join('inventory_purchase_items', 'inventory_purchase_items.id', '=', 'inventory_products.id')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_indoor_returns.seller_id')
            ->select(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_indoor_returns.created_at',
                'inventory_departments.dept_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_indoor_returns.product_id',
                'inventory_indoor_returns.department_id',
                'inventory_indoor_returns.status',
                'inventory_indoor_returns.purchase_unit_price',
                DB::raw('SUM(inventory_indoor_returns.return_qty) as qty'),
                'inventory_indoor_returns.Qty_type'
            )
            ->groupBy(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_indoor_returns.created_at',
                'inventory_purchase_items.pur_item_qty',
                'inventory_indoor_returns.status',
                'inventory_departments.dept_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_indoor_returns.product_id',
                'inventory_indoor_returns.department_id',
                'inventory_indoor_returns.purchase_unit_price',
                'inventory_indoor_returns.Qty_type'
            );
        // ->where('status', '=', 1);

        if ($request->from) {
            $indoor_return = $indoor_return->where('inventory_indoor_returns.created_at', '>=', $request->from);
        }

        if ($request->to) {
            $indoor_return = $indoor_return->where('inventory_indoor_returns.created_at', '<=', $request->to);
        }

        if ($status != "") {
            $indoor_return = $indoor_return->where('status', $status);
        }

        if ($request->filter_by) {
            if ($request->department) {
                $indoor_return = $indoor_return->where('inventory_indoor_returns.department_id', '=', $request->department);
            }
            if ($request->seller) {
                $indoor_return = $indoor_return->where('inventory_indoor_returns.seller_id', '=', $request->seller);
            }
            if ($request->product) {
                $indoor_return = $indoor_return->where('inventory_indoor_returns.product_id', '=', $request->product);
            }
        }
        $indoor_return = $indoor_return->get();
        // return $indoor_return;
        return view('reports.IndoorReturnReport', ['products' => $products, 'product_id' => $request->product, 'sellers' => $sellers, 'seller_id' => $request->seller, 'department_id' => $request->department, 'filter_by' => $request->filter_by, 'from' => $request->from, 'to' => $request->to, 'indoor_return' => $indoor_return, 'departments' => $departments, 'status' => $status]);
        // return view('reports.IndoorReturnReport', ['filter_by' => $request->filter_by,'department_id'=>$request->department, 'from' => $request->from, 'to' => $request->to, 'indoor_return' => $indoor_return, 'departments' => $departments]);
    }

    public function getDetailsIndoorReturnReport(Request $request)
    {
        $status = $request->status;

        $indoor_return = InventoryPurchaseItem::all();
        $indoor_return = InventoryIndoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_returns.product_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_returns.department_id')
            ->join('inventory_purchase_items', 'inventory_purchase_items.id', '=', 'inventory_products.id')
            ->select(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_indoor_returns.created_at',
                'inventory_departments.dept_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_products.id as product_id',
                'inventory_departments.id as dept_id',
                'inventory_indoor_returns.purchase_order_id',
                'inventory_indoor_returns.purchase_unit_price',
                'inventory_indoor_returns.created_at',
                // DB::raw('DATE_FORMAT(inventory_indoor_returns.created_at, "%Y-%m-%d") as created_at'),
                'inventory_indoor_returns.status',
                DB::raw('SUM(inventory_indoor_returns.return_qty) as qty'),
                'inventory_indoor_returns.Qty_type'

            )
            ->groupBy(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_indoor_returns.created_at',
                'inventory_departments.dept_name',
                'inventory_indoor_returns.status',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_products.id',
                'inventory_departments.id',
                'inventory_indoor_returns.purchase_order_id',
                'inventory_indoor_returns.purchase_unit_price',
                'inventory_indoor_returns.Qty_type'
            )
            // ->where('status', '=', 1)
            ->where('inventory_indoor_returns.product_id', '=', $request->pro_id);
        if ($status != "") {
            $indoor_return = $indoor_return->where('status', $status);
        }
        if ($request->dept_id != "") {
            $indoor_return = $indoor_return->where('inventory_indoor_returns.department_id', '=', $request->dept_id);
        }
        if ($request->from != "") {
            $indoor_return = $indoor_return->where('inventory_indoor_returns.created_at', '>=', $request->from);
        }
        if ($request->to != "") {
            $indoor_return = $indoor_return->where('inventory_indoor_returns.created_at', '<=', $request->to);
        }
        $indoor_return = $indoor_return->get();
        return $indoor_return;
    }
}
