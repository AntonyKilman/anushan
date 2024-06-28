<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventorySeller;
use App\Models\InventoryPurchaseOrder;
use App\Models\InventoryProduct;
use App\Models\purchase_order_credit_payment;
use App\Models\InventoryPurchaseItem;
use App\Models\InventoryPermanentAssets;
use App\Models\InventoryIndoorTransfer;
use App\Models\InventoryAssetStatus;
use App\Models\foodCity;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Models\account_main_account;


class InventoryPurchaseOrderController extends Controller
{

    public function purchaseBoucherUpdateProcess(Request $request){
        // return $request;
        $purchase_order = InventoryProduct::find($request->id);
        $purchase_order->pur_ord_bill_no = $request->bill_no;
        $purchase_order->pur_ord_amount = $request->amount;
        $purchase_order->pur_ord_cash = $request->cash_amount;
        $purchase_order->pur_ord_cheque = $request->cheque_amount;
        $purchase_order->pur_ord_cheque_no = $request->cheque_no;
        $purchase_order->pur_ord_cheque_date = $request->cheque_date;
        $purchase_order->pur_ord_online_or_card = $request->online_amount;
        $purchase_order->pur_ord_reference_no = $request->reference_no;
        $purchase_order->pur_ord_credit = $request->credit_amount;
        $purchase_order->seller_id = $request->seller_id;
        $purchase_order->date = $request->date;
        $purchase_order->user_id = Auth::user()->emp_id;
        $purchase_order->approved_by = 0;

   
    }

    // purchase order add
    public function purchaseOrderAdd()
    {
        $sellers = InventorySeller::all();
        $products = InventoryProduct::all();
        return view('purchase_order.PurchaseAdd', ['sellers' => $sellers, 'products' => $products]);
    }


    function goodReceiveData($id)
    {
        $InventoryPurchaseOrders = InventoryPurchaseOrder::join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_purchase_orders.seller_id')
            ->select("inventory_purchase_orders.*", 'inventory_sellers.seller_name')
            ->where('inventory_purchase_orders.id', $id)
            ->first();
        return  $InventoryPurchaseOrders;
    }

    // new purchaseItem add
    function newPurchaseItemAdd()
    {
        $InventoryPurchasedItems =DB::table("inventory_purchase_items")
        ->groupBy('inventory_purchase_items.purchase_order_id')
        ->select('inventory_purchase_items.purchase_order_id')
        ->get();

        $notArray = [];

        foreach($InventoryPurchasedItems as $data){
            array_push($notArray,$data->purchase_order_id);
        }

        $InventoryPurchaseOrders = DB::table("inventory_purchase_orders")
        ->whereNotIn('inventory_purchase_orders.id',$notArray)
        ->select('inventory_purchase_orders.*')
        ->get();

        $products = InventoryProduct::all();
        $qty_types = DB::table('inventory_qty_types')->select('name')->get();

        return view('purchased_item.newPurchasedItem', compact('InventoryPurchaseOrders', 'products','qty_types'));
    }

    //new purchase boucher add process
    function purchaseBoucherAddProcess(Request $request)
    {

        $request->validate([
            'cheque_no' => 'nullable|unique:inventory_purchase_orders,pur_ord_cheque_no',
            'amount' => 'required',
            'date' => 'required',
            'img_1' => 'max:2048',
            'img_2' => 'max:2048',
            'img_3' => 'max:2048'
        ]);

        $purchase_order = new InventoryPurchaseOrder();
        $purchase_order->pur_ord_bill_no = $request->bill_no;
        $purchase_order->pur_ord_amount = $request->amount;
        $purchase_order->pur_ord_cash = $request->cash_amount;
        $purchase_order->pur_ord_cheque = $request->cheque_amount;
        $purchase_order->pur_ord_cheque_no = $request->cheque_no;
        $purchase_order->pur_ord_cheque_date = $request->cheque_date;
        $purchase_order->pur_ord_online_or_card = $request->online_amount;
        $purchase_order->pur_ord_reference_no = $request->reference_no;
        $purchase_order->pur_ord_credit = $request->credit_amount;
        $purchase_order->seller_id = $request->seller_id;
        $purchase_order->date = $request->date;

        if($request->credit_amount>0){
            $purchase_order->status = 0;
        }
        else{
            $purchase_order->status = 1;
        }

        $purchase_order->user_id = Auth::user()->emp_id;
        $purchase_order->approved_by = 0;

        if($request->credit_amount>0){
            $purchase_order->status = 0; //not paid full payment
        }
        else{
            $purchase_order->status = 1;    //paid full payment
        }
        $purchase_order->save();



        if($request->img_1){
            $image_name = $purchase_order->id . '-0.' . $request->img_1->extension();
            $request->img_1->move('bill', $image_name);
            $purchase_order->bill_img_1 = $image_name;
        }

        if($request->img_2){
            $image_name = $purchase_order->id . '-0.' . $request->img_2->extension();
            $request->img_2->move('bill', $image_name);
            $purchase_order->bill_img_2 = $image_name;
        }

        if($request->img_3){
            $image_name = $purchase_order->id . '-0.' . $request->img_3->extension();
            $request->img_3->move('bill', $image_name);
            $purchase_order->bill_img_3 = $image_name;
        }

        try {
            $purchase_order->save();
            $a = app('App\Http\Controllers\ActivityLogController')->index("Create Purchase Order");
            return redirect('/purchase-order-show-all')->with('success', 'Successfully Recorded');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something Wrong Try Again');
        }


    }

    // purchase order add process
    public function purchaseOrderAddProcess(Request $request)
    {
        // return $request;

        $request->validate([
            'product_id' => 'required',
            'qty' => 'required',
            'qty_type' => 'required',
            'price' => 'required'
        ]);

        $length = count($request->qty);
        for ($i = 0; $i < $length; $i++) {

            // **************** GR Item Create ********************
            $purchase_item = new InventoryPurchaseItem();
            $purchase_item->product_id = $request->product_id[$i];
            $purchase_item->pur_item_qty = $request->qty[$i];
            $purchase_item->pur_item_qty_type = $request->qty_type[$i];
            $purchase_item->pur_item_amount = $request->price[$i];
            $purchase_item->purchase_order_id = $request->purchase_order_id;
            $purchase_item->pur_item_expery_date = $request->expery_date[$i];
            $purchase_item->save();

            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Purchase Item");

            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'Something Wrong Try Again');
            }


            // Double entry for accounts
           $PurchaseAccount = new account_main_account();
           $PurchaseAccount->debit = $request->price[$i];
           $PurchaseAccount->dept_id = 6; //Inventory
           $PurchaseAccount->date = Carbon::now()->format('Y-m-d');
           $PurchaseAccount->account_type = 1000;
           $PurchaseAccount->description = "Inventory Purchase";
           $PurchaseAccount->connected_id =  $request->purchase_order_id;
           $PurchaseAccount->sub_category = $request->product_id[$i]; //product_id
           $PurchaseAccount->table_id = 5; //purchase account
           $PurchaseAccount->cash = $request->price[$i];
           $PurchaseAccount->purchase_amount = $request->price[$i];
           $PurchaseAccount->save();

           $CashAccount = new account_main_account();
           $CashAccount->credit = $request->price[$i];
           $CashAccount->dept_id = 6; //Inventory
           $CashAccount->date = Carbon::now()->format('Y-m-d');
           $CashAccount->account_type = 4000;
           $CashAccount->description  = "Inventory Purchase";
           $CashAccount->connected_id =  $request->purchase_order_id;
           $CashAccount->table_id = 1; //cash account
           $CashAccount->sub_category =  $request->product_id[$i]; //product_id
           $CashAccount->cash = $request->price[$i];
           $CashAccount->purchase_amount = $request->price[$i];
           $CashAccount->save();
           // **************** GR Item Create ********************

           // **************** SALE Product Transfer ********************
            $InventoryIndoorTransfer = new InventoryIndoorTransfer();
            $InventoryIndoorTransfer->purchase_id = $request->purchase_order_id;
            $InventoryIndoorTransfer->product_id = $request->product_id[$i];
            $InventoryIndoorTransfer->quantity = $request->qty[$i];
            $InventoryIndoorTransfer->transfer_quantity = $request->qty[$i];
            $InventoryIndoorTransfer->purchase_unit_price = $request->price[$i] / $request->qty[$i];
            $InventoryIndoorTransfer->department_id = 1;
            $InventoryIndoorTransfer->user_id =  Auth::user()->emp_id;
            $InventoryIndoorTransfer->employee_id = Auth::user()->emp_id;
            $InventoryIndoorTransfer->exDate = $request->expery_date[$i];
            $InventoryIndoorTransfer->Qty_type = $request->qty_type[$i];
            $InventoryIndoorTransfer->status = 1;
            try {
                $InventoryIndoorTransfer->save();
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Indoor Transfer");
            } catch (\Throwable $th) {
                return back()->with('error', 'Something  wrong');
            }

            $department_name = 'Sales';
            // store data for accounts
            $CashAccount = new account_main_account();
            $CashAccount->debit = $request->price[$i];
            $CashAccount->dept_id = 6; //Inventory
            $CashAccount->date = Carbon::now()->format('Y-m-d');
            $CashAccount->account_type = 1000;
            $CashAccount->description = $department_name . " Transfer";
            $CashAccount->connected_id = $InventoryIndoorTransfer->id;
            $CashAccount->sub_category = $request->product_id[$i]; //product_id
            $CashAccount->table_id = 1; //cash account
            $CashAccount->cash = $request->price[$i];
            $CashAccount->save();
            
            $SalesAccount = new account_main_account();
            $SalesAccount->credit = $request->price[$i];
            $SalesAccount->dept_id = 6; //Inventory
            $SalesAccount->date = Carbon::now()->format('Y-m-d');
            $SalesAccount->account_type = 4000;
            $SalesAccount->description  = $department_name . " Transfer";
            $SalesAccount->connected_id = $InventoryIndoorTransfer->id;
            $SalesAccount->table_id = 2; //sales account
            $SalesAccount->cash = $request->price[$i];
            $SalesAccount->sub_category = $request->product_id[$i]; //product_id
            $SalesAccount->cash = $request->price[$i];
            $SalesAccount->purchase_amount = $request->price[$i];
            $SalesAccount->save();


            $CashAccount = new account_main_account();
            $CashAccount->credit = $request->price[$i];
            $CashAccount->dept_id = 1;
            $CashAccount->date = Carbon::now()->format('Y-m-d');
            $CashAccount->account_type = 1000;
            $CashAccount->description = "Inventory Transfer";
            $CashAccount->connected_id = $InventoryIndoorTransfer->id;
            $CashAccount->sub_category = $request->product_id[$i]; //product_id
            $CashAccount->table_id = 1; //cash account
            $CashAccount->cash = $request->price[$i];
            $CashAccount->save();

            $purchaseAccount = new account_main_account();
            $purchaseAccount->debit = $request->price[$i];
            $purchaseAccount->dept_id = 1;
            $purchaseAccount->date = Carbon::now()->format('Y-m-d');
            $purchaseAccount->account_type = 1000;
            $purchaseAccount->description = "Inventory Transfer";
            $purchaseAccount->connected_id = $InventoryIndoorTransfer->id;
            $purchaseAccount->table_id = 5; //purchase account
            $purchaseAccount->sub_category = $request->product_id[$i]; //product_id
            $purchaseAccount->cash = $request->price[$i];
            $purchaseAccount->purchase_amount = $request->price[$i];
            $purchaseAccount->save();

            $product = DB::table('inventory_products')->where('id', $request->product_id[$i])->first();
            $product_name = $product->product_name;
            $product_code = $product->product_code;
            $product_sub_cat_id = $product->product_sub_cat_id;

            $foodCity = new foodCity();
            $foodCity->name = $product_name;
            $foodCity->product_code = $product_code;
            $foodCity->quantity = $request->qty[$i];
            $foodCity->status = 0;
            $foodCity->sub_category_id = $product_sub_cat_id;
            $foodCity->purchase_order_id = $request->purchase_order_id;
            $foodCity->purchase_price = $request->price[$i] / $request->qty[$i];;
            $foodCity->scale_id = $request->qty_type[$i];
            $foodCity->expire_date = $request->expery_date[$i];
            $foodCity->user_id = Auth::user()->emp_id;
            $foodCity->transection_id = $InventoryIndoorTransfer->id;
            $foodCity->item_id  = $request->product_id[$i];  //inventory product table id

            try {
                $foodCity->save();
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Foodcity Products");
            } catch (\Throwable $th) {
                return back()->with('error', 'Something  wrong');
            }
           // **************** SALE Product Transfer ********************

        }

        return redirect('/purchased-item-show-all')->with('success', 'Successfully Recorded');


    }

    // purchase order show all
    public function purchaseOrderShowAll()
    {
         $purchase_orders = DB::table('inventory_purchase_orders')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_purchase_orders.seller_id')
            ->select('inventory_purchase_orders.*', 'inventory_sellers.seller_name')
            ->orderBy('id', 'DESC')
            ->get();
        return view('purchase_order.PurchaseShowAll', ['purchase_orders' => $purchase_orders]);
    }

    // purchase order edit
    public function purchaseOrderEdit($id)
    {
        $sellers = InventorySeller::all();
        $products = InventoryProduct::all();
        $purchase_order = InventoryPurchaseOrder::find($id);
        $purchase_items = DB::table('inventory_purchase_items')
            ->where('purchase_order_id', '=', $id)
            ->get();
        $permanent_purchase_items = DB::table('inventory_permanent_assets')
            ->where('purchase_order_id', '=', $id)
            ->get();
        return view('purchase_order.PurchaseEdit', ['sellers' => $sellers, 'products' => $products, 'purchase_order' => $purchase_order, 'purchase_items' => $purchase_items, 'permanent_purchase_items' => $permanent_purchase_items]);
    }

    // purchase order update process
    public function purchaseOrderUpdateProcess(Request $request)
    {
        // return $request;
        $purchase_order = InventoryPurchaseOrder::find($request->id);
        $request->validate([
            'bill_no' => 'nullable|unique:inventory_purchase_orders,pur_ord_bill_no,' . $purchase_order->id,
            'cheque_no' => 'nullable|unique:inventory_purchase_orders,pur_ord_cheque_no,' . $purchase_order->id,
            'amount' => 'required',
            'seller_id' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
            'qty_type' => 'required',
            'price' => 'required',
            'img_1' => 'max:2048',
            'img_2' => 'max:2048',
            'img_3' => 'max:2048'
        ]);
        $purchase_order = InventoryPurchaseOrder::find($request->id);
        $purchase_order->pur_ord_bill_no = $request->bill_no;
        $purchase_order->pur_ord_amount = $request->amount;
        $purchase_order->pur_ord_cash = $request->cash_amount;
        $purchase_order->pur_ord_cheque = $request->cheque_amount;
        $purchase_order->pur_ord_cheque_no = $request->cheque_no;
        $purchase_order->pur_ord_cheque_date = $request->cheque_date;
        $purchase_order->pur_ord_online_or_card = $request->online_amount;
        $purchase_order->pur_ord_reference_no = $request->reference_no;
        $purchase_order->pur_ord_credit = $request->credit_amount;
        $purchase_order->seller_id = $request->seller_id;
        $purchase_order->date = $request->date;
        $purchase_order->approved_by = Auth::user()->emp_id;

        try {
            $image_name = $request->id . '-0.' . $request->img_1->extension();
            $request->img_1->move('bill', $image_name);
            $purchase_order->bill_img_1 = $image_name;
        } catch (\Throwable $th) {
        }

        try {
            $image_name = $request->id . '-1.' . $request->img_2->extension();
            $request->img_2->move('bill', $image_name);
            $purchase_order->bill_img_2 = $image_name;
        } catch (\Throwable $th) {
        }

        try {
            $image_name = $request->id . '-2.' . $request->img_3->extension();
            $request->img_3->move('bill', $image_name);
            $purchase_order->bill_img_3 = $image_name;
        } catch (\Throwable $th) {
        }

        try {
            $purchase_order->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Purchase Order");
            } catch (\Throwable $th) {
            }
            try {
                $old_item_length = count($request->item_id);
            } catch (\Throwable $th) {
                $old_item_length = 0;
            }

            $length = count($request->qty);
            for ($i = 0; $i < $length; $i++) {
                if ($i < $old_item_length) {
                    if ($request->type == 'true') {
                        $purchase_item = InventoryPurchaseItem::find($request->item_id[$i]);
                        $purchase_item->product_id = $request->product_id[$i];
                        $purchase_item->pur_item_qty = $request->qty[$i];
                        $purchase_item->pur_item_qty_type = $request->qty_type[$i];
                        $purchase_item->pur_item_amount = $request->price[$i];
                        $purchase_item->purchase_order_id = $purchase_order->id;
                        $purchase_item->pur_item_expery_date = $request->expery_date[$i];
                        $purchase_item->save();
                        try {
                            $a = app('App\Http\Controllers\ActivityLogController')->index("Update Purchase Item");
                        } catch (\Throwable $th) {
                        }
                    } else {
                        $permanent_purchase_item = InventoryPermanentAssets::find($request->item_id[$i]);
                        $permanent_purchase_item->product_id = $request->product_id[$i];
                        $permanent_purchase_item->pur_item_qty = $request->qty[$i];
                        $permanent_purchase_item->pur_item_qty_type = $request->qty_type[$i];
                        $permanent_purchase_item->pur_item_amount = $request->price[$i];
                        $permanent_purchase_item->purchase_order_id = $purchase_order->id;
                        $permanent_purchase_item->warranty = $request->warrenty[$i];
                        $permanent_purchase_item->serial_number = $request->serial_number[$i];
                        $permanent_purchase_item->description = $request->description[$i];
                        $permanent_purchase_item->save();
                        try {
                            $a = app('App\Http\Controllers\ActivityLogController')->index("Update Permanent Assets");
                        } catch (\Throwable $th) {
                        }
                    }
                } else {
                    if ($request->type == 'true') {
                        $purchase_item = new InventoryPurchaseItem();
                        $purchase_item->product_id = $request->product_id[$i];
                        $purchase_item->pur_item_qty = $request->qty[$i];
                        $purchase_item->pur_item_qty_type = $request->qty_type[$i];
                        $purchase_item->pur_item_amount = $request->price[$i];
                        $purchase_item->purchase_order_id = $purchase_order->id;
                        $purchase_item->pur_item_expery_date = $request->expery_date[$i];
                        $purchase_item->save();
                        try {
                            $a = app('App\Http\Controllers\ActivityLogController')->index("Create Purchase Item");
                        } catch (\Throwable $th) {
                        }
                    } else {
                        $permanent_purchase_item = new InventoryPermanentAssets();
                        $permanent_purchase_item->product_id = $request->product_id[$i];
                        $permanent_purchase_item->pur_item_qty = $request->qty[$i];
                        $permanent_purchase_item->pur_item_qty_type = $request->qty_type[$i];
                        $permanent_purchase_item->pur_item_amount = $request->price[$i];
                        $permanent_purchase_item->purchase_order_id = $purchase_order->id;
                        $permanent_purchase_item->warranty = $request->warrenty[$i];
                        $permanent_purchase_item->serial_number = $request->serial_number[$i];
                        $permanent_purchase_item->description = $request->description[$i];

                        try {
                            $permanent_purchase_item->save();

                            $asset_status = new InventoryAssetStatus();
                            $asset_status->purchased_product_id = $permanent_purchase_item->id;
                            $asset_status->status_id = 4;
                            $asset_status->save();

                            try {
                                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Permanent Assets");
                            } catch (\Throwable $th) {
                            }
                        } catch (\Throwable $th) {
                            return $th;
                        }
                    }
                }
            }
            return redirect('/purchase-order-show-all')->with('success', 'Successfully Updated');
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    // purchase order view
    public function purchaseOrderView($id,$product_id)
    {
        $sellers = InventorySeller::all();
        $products = InventoryProduct::all();

        $purchase_order = DB::table('inventory_purchase_orders')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_purchase_orders.seller_id')
            ->select('inventory_purchase_orders.*', 'inventory_sellers.seller_name')
            ->where('inventory_purchase_orders.id', '=', $id)
            ->first();

        $purchase_items = DB::table('inventory_indoor_transfer')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_transfer.product_id')
            ->join('employees', 'employees.id', '=', 'inventory_indoor_transfer.employee_id')
            ->join('inventory_departments', 'inventory_departments.id', '=', 'inventory_indoor_transfer.department_id')
            ->select('inventory_indoor_transfer.*', 'inventory_products.product_name' , 'employees.f_name' , 'inventory_departments.dept_name')
            ->where('inventory_indoor_transfer.purchase_id', '=', $id)
            ->where('inventory_products.product_code', '=', $product_id)
            ->get();

            // dd($id  );   

            // $purchase_items = DB::table('inventory_purchase_items')
            // ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            // ->select('inventory_purchase_items.*', 'inventory_products.product_name')
            // ->where('inventory_purchase_items.purchase_order_id', '=', $id)
            // ->get();

        $permanent_purchase_items = DB::table('inventory_permanent_assets')
            ->where('purchase_order_id', '=', $id)
            ->get();

        return view('purchase_order.PurchaseView', ['sellers' => $sellers, 'products' => $products, 'purchase_order' => $purchase_order, 'purchase_items' => $purchase_items, 'permanent_purchase_items' => $permanent_purchase_items, 'from' => '']);
    }

    // permanent assets
    public function permanentAssetsAdd()
    {
        $sellers = InventorySeller::all();
        $products = InventoryProduct::all();
        return view('purchase_order.PermanentAssetsAdd', ['sellers' => $sellers, 'products' => $products]);
    }

    public function permanentAssetsAddProcess(Request $request)
    {
        // return $request;
        $request->validate([
            'bill_no' => 'nullable|unique:inventory_purchase_orders,pur_ord_bill_no',
            'cheque_no' => 'nullable|unique:inventory_purchase_orders,pur_ord_cheque_no',
            'amount' => 'required',
            'seller_id' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
            'qty_type' => 'required',
            'price' => 'required',
            'img_1' => 'max:2048',
            'img_2' => 'max:2048',
            'img_3' => 'max:2048'
        ]);

        $purchase_order = new InventoryPurchaseOrder();
        $purchase_order->pur_ord_bill_no = $request->bill_no;
        $purchase_order->pur_ord_amount = $request->amount;
        $purchase_order->pur_ord_cash = $request->cash_amount;
        $purchase_order->pur_ord_cheque = $request->cheque_amount;
        $purchase_order->pur_ord_cheque_no = $request->cheque_no;
        $purchase_order->pur_ord_cheque_date = $request->cheque_date;
        $purchase_order->pur_ord_online_or_card = $request->online_amount;
        $purchase_order->pur_ord_reference_no = $request->reference_no;
        $purchase_order->pur_ord_credit = $request->credit_amount;
        $purchase_order->seller_id = $request->seller_id;
        $purchase_order->user_id = Auth::user()->emp_id;

        try {
            $purchase_order->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Purchase Order");
            } catch (\Throwable $th) {
            }
            $length = count($request->qty);
            for ($i = 0; $i < $length; $i++) {
                $purchase_item = new InventoryPermanentAssets();
                $purchase_item->product_id = $request->product_id[$i];
                $purchase_item->pur_item_qty = $request->qty[$i];
                $purchase_item->pur_item_qty_type = $request->qty_type[$i];
                $purchase_item->pur_item_amount = $request->price[$i];
                $purchase_item->purchase_order_id = $purchase_order->id;
                $purchase_item->warranty = $request->warrenty[$i];
                $purchase_item->serial_number = $request->serial_number[$i];
                $purchase_item->description = $request->description[$i];
                $purchase_item->save();

                $asset_status = new InventoryAssetStatus();
                $asset_status->purchased_product_id = $purchase_item->id;
                $asset_status->status_id = 4;
                $asset_status->save();

                try {
                    $a = app('App\Http\Controllers\ActivityLogController')->index("Create Permanent Assets");
                } catch (\Throwable $th) {
                }
            }

            $purchase_order_update = InventoryPurchaseOrder::find($purchase_order->id);
            try {
                $image_name = $purchase_order_update->id . '-0.' . $request->img_1->extension();
                $request->img_1->move('bill', $image_name);
                $purchase_order_update->bill_img_1 = $image_name;
            } catch (\Throwable $th) {
            }

            try {
                $image_name = $purchase_order_update->id . '-1.' . $request->img_2->extension();
                $request->img_2->move('bill', $image_name);
                $purchase_order_update->bill_img_2 = $image_name;
            } catch (\Throwable $th) {
            }

            try {
                $image_name = $purchase_order_update->id . '-2.' . $request->img_3->extension();
                $request->img_3->move('bill', $image_name);
                $purchase_order_update->bill_img_3 = $image_name;
            } catch (\Throwable $th) {
            }

            $purchase_order_update->save();

            return redirect('/permanent-assets-show-all')->with('success', 'Successfully Recorded');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something Wrong Try Again');
        }
    }


    // reports
    public function purchaseOrderReport(Request $request)
    {

        $to  = Carbon::now()->format('Y-m-d');
        $from  = Carbon::now()->subMonth(3)->format('Y-m-d');
        $supplier = "";

        if ($request->from) {
            $from  = $request->from;
        }

        if ($request->to) {
            $to  = $request->to;
        }

        if ($request->supplier) {
            $supplier  = $request->supplier;
        }

        $purchase_order = InventoryPurchaseOrder::join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_purchase_orders.seller_id')
            ->select('inventory_purchase_orders.*', 'inventory_sellers.seller_name');
            if($request->supplier) {
                $purchase_order = $purchase_order->where('inventory_purchase_orders.seller_id',$supplier);
            }
            $purchase_order = $purchase_order->where('inventory_purchase_orders.date', '>=', $from)
            ->where('inventory_purchase_orders.date', '<=', $to)
            ->orderBy('date','DESC')
            ->get();

            $total_amount = 0;
            $total_cash = 0;
            $total_credit = 0;
            foreach($purchase_order as $order){
                $total_amount+= $order->pur_ord_amount;
                $total_cash+= $order->pur_ord_cash;
                $total_credit+= $order->pur_ord_credit;
            }

            $inventory_sellers = DB::table('inventory_sellers')->get();
            return view('reports.newPurchaseOrder',compact('purchase_order','to','from','inventory_sellers','supplier','total_amount','total_cash','total_credit'));
    }

    public function purchaseOrderReportView(Request $request)
    {
        $sellers = InventorySeller::all();
        $purchase_order = InventoryPurchaseOrder::join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_purchase_orders.seller_id')
            ->select('inventory_purchase_orders.*', 'inventory_sellers.seller_name');
        if ($request->from) {
            $purchase_order = $purchase_order->where('inventory_purchase_orders.created_at', '>=', $request->from);
        }
        if ($request->to) {
            $purchase_order = $purchase_order->where('inventory_purchase_orders.created_at', '<=', $request->to);
        }
        if ($request->seller) {
            $purchase_order = $purchase_order->where('inventory_purchase_orders.seller_id', '=', $request->seller);
        }

        $purchase_order = $purchase_order->get();

        return view('reports.PurchaseOrderReport', ['filter_by' => $request->filter_by, 'seller_id' => $request->seller, 'from' => $request->from, 'to' => $request->to, 'purchase_order' => $purchase_order, 'sellers' => $sellers]);
    }

    public function purchaseOrderViewPost(Request $request)
    {
        $sellers = InventorySeller::all();
        $products = InventoryProduct::all();

        $purchase_order = DB::table('inventory_purchase_orders')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_purchase_orders.seller_id')
            ->select('inventory_purchase_orders.*', 'inventory_sellers.seller_name')
            ->where('inventory_purchase_orders.id', '=', $request->id)
            ->first();

        $purchase_items = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->select('inventory_purchase_items.*', 'inventory_products.product_name')
            ->where('inventory_purchase_items.purchase_order_id', '=', $request->id)
            ->get();

        $permanent_purchase_items = DB::table('inventory_permanent_assets')
            ->where('purchase_order_id', '=', $request->id)
            ->get();

        if ($request->from == 'report') {
            $from = $request->from;
        }
        return view('purchase_order.PurchaseView', ['sellers' => $sellers, 'products' => $products, 'purchase_order' => $purchase_order, 'purchase_items' => $purchase_items, 'permanent_purchase_items' => $permanent_purchase_items, 'from' => $from]);
    }

    public function creditPaymentShow(Request $request){

        $to  = Carbon::now()->format('Y-m-d');

        if ($request->to) {
            $to  = $request->to;
        }
        $from = $request->from;
        $seller = $request->supplier;


        $credits = InventoryPurchaseOrder::join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_purchase_orders.seller_id')
            ->select('inventory_purchase_orders.*', 'inventory_sellers.seller_name');
            if($seller) {
                $credits = $credits->where('inventory_purchase_orders.seller_id',$seller);
            }

            if($from) {
                $credits = $credits->where('inventory_purchase_orders.date','>=',$from);
            }

            $credits = $credits->where('inventory_purchase_orders.status',0)
            ->orderBy('inventory_purchase_orders.date','DESC')
            ->where('inventory_purchase_orders.date','<=',$to)
            ->get();

            $supplier = DB::table('inventory_sellers')->select('inventory_sellers.id','inventory_sellers.seller_name')->get();

            $total_credit = 0;
            foreach ($credits as $credit) {
                $total_credit+= $credit->pur_ord_credit;
                $interval = now()->diff(Carbon::parse($credit->date));
                $credit->days = $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";

                $purchase_order_id = $credit->id;
                $nowPayTotalAmount = DB::table('inventory_purchase_order_credit_payment')
                                    ->where('purchase_order_id', $purchase_order_id)
                                    ->value(DB::raw('IFNULL(SUM(cash_amount + cheque_amount + discount_amount), 0)'));
                
                $credit->nowPayTotalAmount = $nowPayTotalAmount;
                $credit->pendingCreditAmount = $credit->pur_ord_credit - $nowPayTotalAmount;
            }

        return view('purchase_order.creditPayment',compact('credits','total_credit','supplier','to','from','seller'));

    }

    public function creditPaymentStore(Request $request){

        $request->validate([
            // 'cheque_amount'=>'required|numeric|min:0',
            // 'cheque_no'=>'required|numeric',
            // 'cheque_date'=>'required|date',
            // 'cash_date'=>'required|date',
            'id'=>'required|numeric',
        ]);

        $data = new purchase_order_credit_payment();
        $data -> cheque_amount = isset($request->cheque_amount) ? $request->cheque_amount : 0;
        $data -> cash_amount = isset($request->cash_amount) ? $request->cash_amount : 0;
        $data -> discount_amount = isset($request->discount_amount) ? $request->discount_amount : 0;
        $data -> cheque_number = $request->cheque_no;
        $data -> cheque_date = $request -> cheque_date;
        $data -> payment_date = $request -> cash_date;
        $data -> purchase_order_id = $request -> id;
        $data -> save();

        $cheque_amount = isset($request->cheque_amount) ? $request->cheque_amount : 0;
        $cash_amount = isset($request->cash_amount) ? $request->cash_amount : 0;
        $discount_amount = isset($request->discount_amount) ? $request->discount_amount : 0;
        $pur_ord_credit = isset($request->pur_ord_credit) ? $request->pur_ord_credit : 0;

        if ($pur_ord_credit == ($cheque_amount + $cash_amount + $discount_amount)) {
            // update purchase order table
            $paid = InventoryPurchaseOrder::find($request -> id);
            $paid -> status = 1;
            $paid -> save();
        }

        return redirect('/credit-payment-show')->with('success', 'Successfully Updated');
    }

    public function creditPayments(Request $request){

        $to  = Carbon::now()->format('Y-m-d');
        $from = $request->from;
        $seller = $request->supplier;

        if ($request->to) {
            $to  = $request->to;
        }

        $supplier = DB::table('inventory_sellers')->select('inventory_sellers.id','inventory_sellers.seller_name')->get();

        $payments = purchase_order_credit_payment::join('inventory_purchase_orders','inventory_purchase_orders.id','=','inventory_purchase_order_credit_payment.purchase_order_id')
        ->join('inventory_sellers','inventory_sellers.id','=','inventory_purchase_orders.seller_id')
        ->select('inventory_purchase_order_credit_payment.*','inventory_sellers.seller_name','inventory_purchase_orders.pur_ord_bill_no')
        ->where('inventory_purchase_order_credit_payment.payment_date','<=',$to);
        if($from){
            $payments = $payments->where('inventory_purchase_order_credit_payment.payment_date','>=',$from);
        }

        if($seller){
            $payments = $payments->where('inventory_purchase_orders.seller_id',$seller);
        }
        $payments = $payments->orderBy('inventory_purchase_order_credit_payment.payment_date','DESC')
        ->get();

        $total_payment = 0;
        foreach ($payments as $payment) {
            $total_payment+= $payment->cheque_amount;
        }
        return view('purchase_order.creditView', compact('payments','from','to','total_payment','supplier','seller'));

    }


}
