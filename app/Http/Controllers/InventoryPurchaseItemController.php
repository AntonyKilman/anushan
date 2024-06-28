<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryPurchaseItem;
use App\Models\InventoryDepartment;
use App\Models\InventoryReturnReason;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryPurchaseItemController extends Controller
{
    public function purchasedItemShowAll()
    {
        $purchase_items = InventoryPurchaseItem::all();
        $purchase_items = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->select('inventory_purchase_items.*', 'inventory_products.product_name','inventory_products.product_code')
            ->orderBy('id','desc')
            ->get();
        return view('purchased_item.PurchasedItem',['purchase_items'=>$purchase_items]);

    }


          //Reports for purchase Item

          public function purchaseItemReport()
          {
            $from=substr(Carbon::now()->subDays(30),0,10);
            $to=substr(Carbon::now(),0,10);
            $filter_by='';
            $purchase_item=[];
            $purchase_item = InventoryPurchaseItem::join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->join('inventory_brands', 'inventory_brands.id', '=', 'inventory_products.brand_id')
            ->join('inventory_purchase_orders','inventory_purchase_orders.id','=','inventory_purchase_items.purchase_order_id')
            ->select('inventory_purchase_items.product_id',
            'inventory_purchase_items.pur_item_amount',
            'inventory_products.product_name',
            'inventory_products.product_code',
            'inventory_products.brand_id',
            'inventory_purchase_orders.id',
            'inventory_brands.brand_name',

            DB::raw('SUM(inventory_purchase_items.pur_item_qty) as qty'))
            ->groupBy('inventory_purchase_items.pur_item_amount','inventory_purchase_items.product_id','inventory_products.product_name','inventory_products.product_code','inventory_products.brand_id','inventory_purchase_orders.id','inventory_brands.brand_name')
            ->where('inventory_purchase_items.created_at','>=',$from)
            ->where('inventory_purchase_items.created_at','<=',$to)
            ->get();
            return view('reports.PurchaseItemReports',['filter_by'=>$filter_by,'from'=>$from,'to'=>$to,'purchase_item'=>$purchase_item,'name'=>'']);

          }

          public function purchaseItemReportPost(Request $request)
          {

              $startDate = Carbon::parse($request->from)->toDateTimeString();
              $endDate = Carbon::parse($request->to)->toDateTimeString();

            //   if ($request->filter_by==1) {
            //       $purchase_item = InventoryPurchaseItem::join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            //       ->join('inventory_brands', 'inventory_brands.id', '=', 'inventory_products.brand_id')
            //       ->select('inventory_products.product_name','inventory_products.product_code','inventory_brands.brand_name',
            //           DB::raw('SUM(inventory_purchase_items.pur_item_qty) as qty'))
            //       ->groupBy('inventory_products.product_name','inventory_products.product_code','inventory_brands.brand_name')
            //       ->whereBetween('inventory_purchase_items.created_at', [$request->from, $request->to])->get();
            //       // return $purchase_item;
            //       return view('reports.PurchaseItemReports',['filter_by'=>$request->filter_by,'from'=>$request->from,'to'=>$request->to,'purchase_item'=>$purchase_item,'name'=>'Brand']);

            //     }elseif($request->filter_by==2) {
            //     $purchase_item = InventoryPurchaseItem::join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            //     ->join('inventory_purchase_orders', 'inventory_purchase_orders.id', '=' ,'inventory_purchase_items.purchase_order_id')
            //     ->join('inventory_sellers','inventory_sellers.id', '=' ,'inventory_purchase_orders.seller_id')
            //     ->select('inventory_products.product_name','inventory_products.product_code','inventory_purchase_items.purchase_order_id','inventory_sellers.seller_name',
            //         DB::raw('SUM(inventory_purchase_items.pur_item_qty) as qty'))
            //     ->groupBy('inventory_products.product_name','inventory_products.product_code','inventory_purchase_items.purchase_order_id','inventory_sellers.seller_name')
            //     ->whereBetween('inventory_purchase_items.created_at', [$request->from, $request->to])->get();
            //     // return $purchase_item;
            //     return view('reports.PurchaseItemReports',['filter_by'=>$request->filter_by,'from'=>$request->from,'to'=>$request->to,'purchase_item'=>$purchase_item, 'name'=>'Seller']);
            //   }

              // null view

                $purchase_item = InventoryPurchaseItem::join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
                ->join('inventory_brands', 'inventory_brands.id', '=', 'inventory_products.brand_id')
                ->join('inventory_purchase_orders', 'inventory_purchase_orders.id', '=' ,'inventory_purchase_items.purchase_order_id')
                // ->join('inventory_purchase_orders','inventory_purchase_orders.id','=','inventory_purchase_items.purchase_order_id')
                ->join('inventory_sellers','inventory_sellers.id', '=' ,'inventory_purchase_orders.seller_id');





            if ($request->filter_by==1) {
                $purchase_item=$purchase_item->select( 'inventory_purchase_items.pur_item_amount','inventory_products.product_name','inventory_products.product_code','inventory_purchase_orders.id','inventory_brands.brand_name',
                DB::raw('SUM(inventory_purchase_items.pur_item_qty) as qty'))
                ->groupBy( 'inventory_purchase_items.pur_item_amount','inventory_products.product_name','inventory_products.product_code','inventory_purchase_orders.id','inventory_brands.brand_name');
            }

            if ($request->filter_by==2) {
                $purchase_item=$purchase_item->select( 'inventory_purchase_items.pur_item_amount','inventory_products.product_name','inventory_products.product_code','inventory_purchase_orders.id','inventory_sellers.seller_name',
                DB::raw('SUM(inventory_purchase_items.pur_item_qty) as qty'))
                ->groupBy( 'inventory_purchase_items.pur_item_amount','inventory_products.product_name','inventory_products.product_code','inventory_purchase_orders.id','inventory_sellers.seller_name');
            }

            if ($request->filter_by==null) {
                $purchase_item=$purchase_item->select( 'inventory_purchase_items.pur_item_amount','inventory_products.product_name','inventory_products.product_code','inventory_purchase_orders.id','inventory_brands.brand_name',
                DB::raw('SUM(inventory_purchase_items.pur_item_qty) as qty'))
                ->groupBy( 'inventory_purchase_items.pur_item_amount','inventory_products.product_name','inventory_products.product_code','inventory_purchase_orders.id','inventory_brands.brand_name');
            }

            if ($request->from) {
                $purchase_item=$purchase_item->where('inventory_purchase_items.created_at','>=',$request->from);
            }
            if ($request->to) {
                $purchase_item=$purchase_item->where('inventory_purchase_items.created_at','<=',$request->to);
            }

            $purchase_item=$purchase_item->get();
            if ($request->filter_by==1) {
                return view('reports.PurchaseItemReports',['filter_by'=>$request->filter_by,'from'=>$request->from,'to'=>$request->to,'purchase_item'=>$purchase_item,'name'=>'Brand']);
            }
            if ($request->filter_by==2) {
                return view('reports.PurchaseItemReports',['filter_by'=>$request->filter_by,'from'=>$request->from,'to'=>$request->to,'purchase_item'=>$purchase_item, 'name'=>'Seller']);
            }
            if ($request->filter_by==null) {
                return view('reports.PurchaseItemReports',['filter_by'=>$request->filter_by,'from'=>$request->from,'to'=>$request->to,'purchase_item'=>$purchase_item,'name'=>'']);
            }

          }

    public function purchaseItemByProidBrandid(Request $request)
    {
        //    return $request;
        $purchase_item = InventoryPurchaseItem::join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->join('inventory_purchase_orders', 'inventory_purchase_orders.id', '=', 'inventory_purchase_items.purchase_order_id')
            ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_purchase_orders.seller_id')
            ->join('inventory_brands', 'inventory_brands.id', '=', 'inventory_products.brand_id')
            ->select( 'inventory_purchase_items.pur_item_amount','inventory_products.product_name', 'inventory_products.product_code', 'inventory_purchase_items.purchase_order_id', 'inventory_sellers.seller_name', 'inventory_brands.id as brand_id', 'inventory_purchase_items.product_id', 'inventory_products.product_name', 'inventory_brands.brand_name', 'inventory_purchase_items.pur_item_qty')
            ->where('inventory_purchase_items.product_id', '=', $request->product_id)
            ->where('inventory_brands.id', '=', $request->brand_id)->get();
        return $purchase_item;
    }



    public function experyDateReport()
    {
        $from = '';
        $to = '';

        $purchase_items = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->select('inventory_purchase_items.*', 'inventory_products.product_name', 'inventory_products.product_code')
            ->where('inventory_purchase_items.pur_item_expery_date', '>=', date('Y-m-d', strtotime(now())))
            ->orderBy('inventory_purchase_items.pur_item_expery_date', 'ASC')
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
        }

        return view('reports.ExperyDateReport', ['purchase_items' => $purchase_items, 'from' => $from, 'to' => $to, 'filter_by' => '']);
    }


    public function experyDateReportPost(Request $request)
    {
        // return $request;
        $purchase_items = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->select('inventory_purchase_items.*', 'inventory_products.product_name', 'inventory_products.product_code')
            ->orderBy('inventory_purchase_items.pur_item_expery_date', 'ASC');

        if ($request->from) {
            $purchase_items = $purchase_items->where('inventory_purchase_items.pur_item_expery_date', '>=', $request->from);
        }

        if ($request->to) {
            $purchase_items = $purchase_items->where('inventory_purchase_items.pur_item_expery_date', '<=', $request->to);
        }

        if ($request->from == null && $request->to == null) {
            $purchase_items = $purchase_items->where('inventory_purchase_items.pur_item_expery_date', '>=', date('Y-m-d', strtotime(now())));
        }

        $purchase_items = $purchase_items->get();

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
        }

        // return $purchase_items;
        return view('reports.ExperyDateReport', ['purchase_items' => $purchase_items, 'from' => $request->from, 'to' => $request->to, 'filter_by' => $request->filter_by]);
    }

    public function experyDateItemShow()
    {
        $purchase_items = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->join('inventory_product_sub_categories','inventory_product_sub_categories.id','=','inventory_products.product_sub_cat_id')
            ->select('inventory_purchase_items.*', 'inventory_products.product_name', 'inventory_products.product_code','inventory_product_sub_categories.id as sub_cat_id')
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
            $purchase_item->pur_item_amount=$purchase_item->pur_item_amount/$purchase_item->pur_item_qty;
            $purchase_item->pur_item_qty = $purchase_item->pur_item_qty + $indoor_return_qty + $indoor_foodcity_return_qty - $outdoor_return_qty - $indoor_transfer_qty - $equipment_transfer_qty;
        }

        // return $purchase_item;
        $reasons = InventoryReturnReason::all();
        $departments = InventoryDepartment::all();
        return view('purchased_item.ExperyProductsShow',['purchase_items'=>$purchase_items,'departments'=>$departments,'reasons'=>$reasons]);
    }

    public function experyProductReturn($id)
    {
        $purchase_items = DB::table('inventory_purchase_items')
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->select('inventory_purchase_items.*', 'inventory_products.product_name')
            ->where('inventory_purchase_items.id','=',$id)
            ->get();

            return $purchase_items;
    }
}
