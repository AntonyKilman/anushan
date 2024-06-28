<?php

namespace App\Http\Controllers;

use App\Models\InventoryOutdoorReturn;
use App\Models\InventoryReturnReason;
use App\Models\InventoryProduct;
use App\Models\InventoryPurchaseItem;
use App\Models\InventoryPurchaseOrder;
use App\Models\account_profit_loss;
use App\Models\account_main_account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class InventoryOutdoorReturnController extends Controller
{
    public function view()
    {
        $batches = DB::table('inventory_indoor_transfer')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_transfer.product_id')
            ->where('product_id', '=', 1)
            ->where('department_id', '=', 1)
            ->groupBy('inventory_indoor_transfer.purchase_id', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_indoor_transfer.product_id')
            ->select(
                'inventory_indoor_transfer.purchase_id',
                'inventory_indoor_transfer.product_id',
                'inventory_products.product_name',
                'inventory_products.product_code',
                DB::raw("SUM(inventory_indoor_transfer.transfer_quantity) as qty")
            )
            ->get();
        return $batches;
    }

    public function outdoorReturnAdd()
    {
        $products = DB::table("inventory_purchase_items")
            ->join("inventory_products", "inventory_products.id", "=", "inventory_purchase_items.product_id")
            ->groupBy("inventory_purchase_items.product_id", "inventory_products.product_name")
            ->select("inventory_purchase_items.product_id", "inventory_products.product_name")
            ->get();
        $reasons = InventoryReturnReason::all();
        $all_product = InventoryProduct::all();
        return view('outdoor_return.OutdoorReturnAdd', ['products' => $products, 'reasons' => $reasons, 'all_product' => $all_product]);
    }

    // public function viewPurchaseItem(Request $request)
    // {
    //     $batches=DB::table('inventory_purchase_items')
    //     ->join('inventory_products','inventory_products.id','=','inventory_purchase_items.product_id')
    //     ->where('product_id','=',$request->id)
    //     ->groupBy('inventory_purchase_items.purchase_order_id','inventory_products.product_name','inventory_products.product_code','inventory_purchase_items.product_id')
    //     ->select('inventory_purchase_items.purchase_order_id','inventory_purchase_items.product_id',
    //     'inventory_products.product_name','inventory_products.product_code',
    //      DB::raw("SUM(inventory_purchase_items.pur_item_qty) as qty")
    //     )
    //     ->get();

    //     return response()->json([
    //         'batches' => $batches
    //     ]);
    // }

    public function viewPurchaseItem(Request $request)
    {
        $batches = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->where('product_id', '=', $request->id)
            ->groupBy('inventory_purchase_items.purchase_order_id', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_purchase_items.product_id', 'inventory_purchase_items.pur_item_amount', 'inventory_purchase_items.pur_item_qty_type',)
            ->select(
                'inventory_purchase_items.purchase_order_id',
                'inventory_purchase_items.product_id',
                'inventory_purchase_items.pur_item_amount',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_purchase_items.pur_item_qty_type',
                DB::raw("SUM(inventory_purchase_items.pur_item_qty) as qty")
            )
            ->get();
        // return response()->json([
        //     'batches' => $batches
        // ]);

        foreach ($batches as $batch) {

            $outdoor_return = DB::table('inventory_outdoor_returns')
                ->select('return_qty', 'id')
                ->where('product_id', '=', $batch->product_id)
                ->where('purchase_order_id', '=', $batch->purchase_order_id)
                ->where('status', '=', 1)
                ->get();
            $outdoor_return_qty = 0;
            foreach ($outdoor_return as $value) {
                $outdoor_return_qty += $value->return_qty;
            }

            $indoor_return = DB::table('inventory_indoor_returns')
                ->select('return_qty', 'id')
                ->where('product_id', '=', $batch->product_id)
                ->where('purchase_order_id', '=', $batch->purchase_order_id)
                ->where('status', '=', 1)
                ->get();
            $indoor_return_qty = 0;
            foreach ($indoor_return as $value) {
                $indoor_return_qty += $value->return_qty;
            }

            $indoor_transfer = DB::table('inventory_indoor_transfer')
                ->select('transfer_quantity', 'id')
                ->where('product_id', '=', $batch->product_id)
                ->where('purchase_id', '=', $batch->purchase_order_id)
                ->where('status', "=", 1)
                ->get();
            $indoor_transfer_qty = 0;
            foreach ($indoor_transfer as $value) {
                $indoor_transfer_qty += $value->transfer_quantity;
            }

            $indoor_foodcity_return = DB::table('foodcity_product_returns')
                ->select('return_qty', 'id')
                ->where('item_id', '=', $batch->product_id)
                ->where('purchase_order_id', '=', $batch->purchase_order_id)
                ->where('status', '=', 1)
                ->get();
            $indoor_foodcity_return_qty = 0;
            foreach ($indoor_foodcity_return as $value) {
                $indoor_foodcity_return_qty += $value->return_qty;
            }

            // $equipment_transfer = DB::table('inventory_equipment_transfer')
            //     ->select('quantity', 'id')
            //     ->where('product_id', '=', $batch->product_id)
            //     ->where('purchase_id', '=', $batch->purchase_order_id)
            //     ->where('status', "=", 1)
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
            $batch->qty = $batch->qty + $indoor_return_qty + $indoor_foodcity_return_qty - $outdoor_return_qty - $indoor_transfer_qty - $stock_adjustment_qty;
            // $batch->qty = $batch->qty + $indoor_return_qty + $indoor_foodcity_return_qty - $outdoor_return_qty - $indoor_transfer_qty - $equipment_transfer_qty;
        }

        return response()->json([
            'batches' => $batches
        ]);
    }

    public function outdoorReturnAddProcess(Request $request)
    {

        // return $request;
        // return $request->reason_id;
        $request->validate([
            'produt_id' => 'required',
        ]);
        $check = 0;
        try {
            $length = count($request->produt_id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Please select atleast one raw');
        }

        for ($i = 0; $i < $length; $i++) {

            $a = $request->pur_ord_id[$i] . '-' . $request->produt_id[$i];
            if ($request->$a) {
                $seller = InventoryPurchaseOrder::where('id', '=', $request->pur_ord_id[$i])->select('seller_id')->first();

                $outdoor_return = new InventoryOutdoorReturn();
                $outdoor_return->purchase_order_id = $request->pur_ord_id[$i];
                $outdoor_return->product_id = $request->produt_id[$i];
                $outdoor_return->seller_id = $seller->seller_id;
                $outdoor_return->return_reason_id = $request->reason_id[$i];
                $outdoor_return->qty = $request->qty[$i];
                $outdoor_return->return_qty = $request->return_qty[$i];
                $outdoor_return->Qty_type = $request->pur_item_qty_type[$i];
                $outdoor_return->purchase_unit_price = $request->purchase_unit_price[$i];
                $outdoor_return->user_id = Auth::user()->emp_id;
                $outdoor_return->status = 1;

                try {
                    $outdoor_return->save();
                    try {
                        $a = app('App\Http\Controllers\ActivityLogController')->index("Create Outdoor Return");
                    } catch (\Throwable $th) {
                    }
                    $check = 1 + $i;
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', 'something wrong');
                }

                //Double entry for accounts
                $CashAccount = new account_main_account();
                $CashAccount->debit = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $CashAccount->dept_id = 6; //Inventory
                $CashAccount->date = Carbon::now()->format('Y-m-d');
                $CashAccount->account_type = 1000;
                $CashAccount->description = "Outdoor Return";
                $CashAccount->connected_id = $outdoor_return->id;
                $CashAccount->sub_category = $request->produt_id[$i]; //product_id
                $CashAccount->table_id = 1; //cash account
                $CashAccount->cash = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $CashAccount->save();

                $PurchaseReturnAccount = new account_main_account();
                $PurchaseReturnAccount->credit = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $PurchaseReturnAccount->dept_id = 6; //Inventory
                $PurchaseReturnAccount->date = Carbon::now()->format('Y-m-d');
                $PurchaseReturnAccount->account_type = 1000;
                $PurchaseReturnAccount->description = "Outdoor Return";
                $PurchaseReturnAccount->connected_id = $outdoor_return->id;
                $PurchaseReturnAccount->sub_category = $request->produt_id[$i]; //product_id
                $PurchaseReturnAccount->table_id = 6; //purchase return account
                $PurchaseReturnAccount->cash = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $PurchaseReturnAccount->purchase_amount = $request->purchase_unit_price[$i] * $request->return_qty[$i];
                $PurchaseReturnAccount->save();


            }
        }
        if ($check > 0) {
            return redirect('/outdoor-return-show-all')->with('success', 'successfully recorded');
        } else {
            return redirect()->back()->with('error', 'Please select atleast one raw');
        }
    }

    public function outdoorReturnShowAll()
    {
        $outdoor_returns = InventoryOutdoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_outdoor_returns.product_id')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_outdoor_returns.seller_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'inventory_outdoor_returns.return_reason_id')
            ->select('inventory_outdoor_returns.*', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_sellers.seller_name', 'inventory_return_reasons.reason_name')
            ->get();
        //  return    $outdoor_returns;
        return view('outdoor_return.OutdoorReturnShowAll', ['outdoor_returns' => $outdoor_returns]);
    }

    public function outdoorReturnEdit($id)
    {
        $outdoor_return = InventoryOutdoorReturn::join('inventory_products', 'inventory_products.id', '=', 'inventory_outdoor_returns.product_id')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_outdoor_returns.seller_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'inventory_outdoor_returns.return_reason_id')
            ->select('inventory_outdoor_returns.*', 'inventory_products.product_name', 'inventory_sellers.seller_name', 'inventory_return_reasons.reason_name')
            ->where('inventory_outdoor_returns.id', '=', $id)->first();
        // return $outdoor_return;
        return view('outdoor_return.OutdoorReturnEdit', ['outdoor_return' => $outdoor_return]);
    }

    public function outdoorReturnUpdateProcess(Request $request)
    {
        // return $request;
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $outdoor_return = InventoryOutdoorReturn::find($request->id);

        if ($outdoor_return->status != $request->status) {
            $outdoor_return->status = $request->status;
            $outdoor_return->approved_by = Auth::user()->emp_id;
            $outdoor_return->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Outdoor Return");
            } catch (\Throwable $th) {
            }
        }

        // Store account profit loss table code by sivakaran
        if ($request->status == 1) {
            $account_profit_loss = new account_profit_loss();
            $account_profit_loss->total_purchase_price = $request->purchase_unit_price * $request->return_qty;
            $account_profit_loss->department_id = 4;
            $account_profit_loss->type = "Inventory Outdoor Return";
            $account_profit_loss->cash_in = $request->purchase_unit_price * $request->return_qty;
            $account_profit_loss->connected_id = $outdoor_return->id;
            $account_profit_loss->is_delete = 0;
            $account_profit_loss->save();
        }

        return redirect('/outdoor-return-show-all')->with('success', 'successfully updated');
    }

    public function outdoorReturnDelete($id)
    {
        $outdoor_return = InventoryOutdoorReturn::find($id);
        $outdoor_return->delete();
        try {
            $a = app('App\Http\Controllers\ActivityLogController')->index("Delete Outdoor Return");
        } catch (\Throwable $th) {
        }
        return redirect('/outdoor-return-show-all')->with('success', 'successfully deleted');
    }


    public function outDoorReturnReport()
    {
        $from = substr(Carbon::now()->subDays(30), 0, 10);
        $to = substr(Carbon::now(), 0, 10);
        $filter_by = '';
        $seller_id = '';
        $status = "";
        $return_item = [];

        $return_item = InventoryPurchaseItem::all();
        $return_item = InventoryOutdoorReturn::join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_outdoor_returns.seller_id')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_outdoor_returns.product_id')
            ->join('inventory_purchase_orders', 'inventory_purchase_orders.id', '=', 'inventory_outdoor_returns.purchase_order_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'inventory_outdoor_returns.return_reason_id')
            ->join('inventory_purchase_items', 'inventory_purchase_items.id', '=', 'inventory_products.id')

            ->select(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_outdoor_returns.created_at',
                'inventory_sellers.seller_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_purchase_orders.id',
                'inventory_return_reasons.reason_name',
                'inventory_outdoor_returns.status',
                DB::raw('SUM(inventory_outdoor_returns.return_qty)as qty')
            )
            ->groupBy(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_outdoor_returns.created_at',
                'inventory_sellers.seller_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_purchase_orders.id',
                'inventory_return_reasons.reason_name',
                'inventory_outdoor_returns.status'
            )

            ->whereBetween('inventory_outdoor_returns.created_at', [$from, $to])->get();


        $sellers = InventoryOutdoorReturn::join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_outdoor_returns.seller_id')
            ->select('inventory_outdoor_returns.seller_id', 'inventory_sellers.seller_name')
            ->groupBy('inventory_outdoor_returns.seller_id', 'inventory_sellers.seller_name')
            ->get();
        return view('reports.OutDoorReturnReport', ['sellers' => $sellers, 'seller_id' => $seller_id, 'filter_by' => $filter_by, 'from' => $from, 'to' => $to, 'return_item' => $return_item, 'name' => 'Brand', 'status' => $status]);
    }

    public function outDoorReturnReportPost(Request $request)
    {
        // return $request;
        // $startDate = Carbon::pars e($request->from)->toDateTimeString();
        // $endDate = Carbon::parse($request->to)->toDateTimeString();
        $status = $request->status;

        $sellers = InventoryOutdoorReturn::join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_outdoor_returns.seller_id')
            ->select('inventory_outdoor_returns.seller_id', 'inventory_sellers.seller_name')
            ->groupBy('inventory_outdoor_returns.seller_id', 'inventory_sellers.seller_name')
            ->get();

        $return_item = InventoryPurchaseItem::all();
        $return_item = InventoryOutdoorReturn::join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_outdoor_returns.seller_id')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_outdoor_returns.product_id')
            ->join('inventory_purchase_orders', 'inventory_purchase_orders.id', '=', 'inventory_outdoor_returns.purchase_order_id')
            ->join('inventory_return_reasons', 'inventory_return_reasons.id', '=', 'inventory_outdoor_returns.return_reason_id')
            ->join('inventory_purchase_items', 'inventory_purchase_items.id', '=', 'inventory_products.id')
            ->select(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_outdoor_returns.created_at',

                'inventory_sellers.seller_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_purchase_orders.id',
                'inventory_return_reasons.reason_name',
                'inventory_outdoor_returns.status',
                DB::raw('SUM(inventory_outdoor_returns.return_qty)as qty')
            )
            ->groupBy(
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',
                'inventory_purchase_items.pur_item_qty',
                'inventory_outdoor_returns.created_at',
                'inventory_sellers.seller_name',
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_purchase_orders.id',
                'inventory_return_reasons.reason_name',
                'inventory_outdoor_returns.status'
            );
        // ->where('status','=',1);
        if ($status != "") {
            $return_item = $return_item->where('inventory_outdoor_returns.status', '=', $status);
        }
        if ($request->from) {
            $return_item = $return_item->where('inventory_outdoor_returns.created_at', '>=', $request->from);
        }
        if ($request->to) {
            $return_item = $return_item->where('inventory_outdoor_returns.created_at', '<=', $request->to);
        }
        if ($request->seller) {
            $return_item = $return_item->where('inventory_outdoor_returns.seller_id', '=', $request->seller);
        }
        $return_item = $return_item->get();

        // ->whereBetween('inventory_outdoor_returns.created_at', [$startDate, $endDate])->get();
        // return $return_item;
        return view('reports.OutDoorReturnReport', ['sellers' => $sellers, 'seller_id' => $request->seller, 'filter_by' => $request->filter_by, 'from' => $request->from, 'to' => $request->to, 'return_item' => $return_item, 'name' => 'Brand', 'status' => $status]);
    }
}
