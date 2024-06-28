<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryPurchaseItem;
use App\Models\InventorySeller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class inventoryStockController extends Controller
{
    public function stockReport()
    {

        $from = substr(Carbon::now()->subDays(30), 0, 10);
        $to = substr(Carbon::now(), 0, 10);
        $filter_by = '';

        // calculate purchased quantity
        $purchasedItems = DB::table('inventory_purchase_items')
            ->join("inventory_products", 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
            ->groupBy(
                "inventory_purchase_items.product_id",
                "inventory_purchase_items.pur_item_qty_type",
                "inventory_products.product_name",
                "inventory_purchase_items.pur_item_amount",
                "inventory_purchase_items.pur_item_qty",

            )
            ->select(
                "inventory_products.product_name",
                "inventory_purchase_items.product_id",
                "inventory_purchase_items.pur_item_qty_type",
                "inventory_purchase_items.pur_item_amount",
                "inventory_purchase_items.pur_item_qty",
                DB::raw('SUM(inventory_purchase_items.pur_item_qty) as totalQty')
            )
            // ->where('inventory_purchase_items.created_at', '>=', $from)
            // ->where('inventory_purchase_items.created_at', '<=', $to)
            ->get();
            // return $purchasedItems;


        // calculate transfered quantity
        $indoorTransfers =  DB::table('inventory_indoor_transfer')
            ->where("inventory_indoor_transfer.status", 1)
            ->select(
                'inventory_indoor_transfer.product_id',
                'inventory_indoor_transfer.Qty_type',
                DB::raw('SUM(inventory_indoor_transfer.transfer_quantity) as totalIndoorTransfered')
            )
            ->groupBy('inventory_indoor_transfer.product_id', 'inventory_indoor_transfer.Qty_type')
            ->get();

        // calculate returned quantity
        $indoorReturns = DB::table('inventory_indoor_returns')
            ->where("inventory_indoor_returns.status", 1)
            ->groupBy('inventory_indoor_returns.product_id', 'inventory_indoor_returns.Qty_type')
            ->select(
                "inventory_indoor_returns.product_id",
                'inventory_indoor_returns.Qty_type',
                DB::raw('SUM(inventory_indoor_returns.return_qty) as totalIndoorReturns')
            )
            ->get();


        // calculate outdoor return quantity
        $outdoorReturns = DB::table('inventory_outdoor_returns')
            ->where("inventory_outdoor_returns.status", 1)
            ->groupBy('inventory_outdoor_returns.product_id', 'inventory_outdoor_returns.Qty_type')
            ->select(
                "inventory_outdoor_returns.product_id",
                'inventory_outdoor_returns.Qty_type',
                DB::raw('SUM(inventory_outdoor_returns.return_qty) as totalOutdoorReturned')
            )
            ->get();

        $equipmentTransfers = DB::table('inventory_equipment_transfer')
            ->where("inventory_equipment_transfer.status", 1)
            ->groupBy('inventory_equipment_transfer.product_id', 'inventory_equipment_transfer.Qty_type')
            ->select(
                'inventory_equipment_transfer.product_id',
                'inventory_equipment_transfer.Qty_type',
                DB::raw('SUM(inventory_equipment_transfer.quantity) as totalEquipmentQtys')
            )
            ->get();


        // calculate stock quantity
        foreach ($purchasedItems as $prchasedItem) {

            // subtraction the indoor transfer quantity
            foreach ($indoorTransfers as $indoorTransfer) {
                $a = true;

                if ($prchasedItem->product_id == $indoorTransfer->product_id && $prchasedItem->pur_item_qty_type == $indoorTransfer->Qty_type) {
                    $prchasedItem->totalQty = $purchasedItems->totalQty - $indoorTransfer->totalIndoorTransfered;
                    $a = false;
                } elseif ($a) {
                    $prchasedItem->totalQty = $prchasedItem->totalQty;
                }
            }


            // addition the indoor return quantity
            foreach ($indoorReturns as $indoorReturn) {
                $a = true;

                if ($prchasedItem->product_id == $indoorReturn->product_id && $prchasedItem->pur_item_qty_type == $indoorReturn->Qty_type) {
                    $prchasedItem->totalQty = $prchasedItem->totalQty + $indoorReturn->totalIndoorReturns;
                    $a = false;
                } elseif ($a) {
                    $prchasedItem->totalQty = $prchasedItem->totalQty;
                }
            }


            // subtraction the outdoorReturns  quantity
            foreach ($outdoorReturns as $outdoorReturn) {
                $a = true;

                if ($prchasedItem->product_id == $outdoorReturn->product_id && $prchasedItem->pur_item_qty_type == $outdoorReturn->Qty_type) {
                    $prchasedItem->totalQty = $prchasedItem->totalQty - $outdoorReturn->totalOutdoorReturned;
                    $a = false;
                } elseif ($a) {
                    $prchasedItem->totalQty = $prchasedItem->totalQty;
                }
            }


            // subtraction the Equipment transfer  quantity
            foreach ($equipmentTransfers as $equipmentTransfer) {
                $a = true;

                if ($prchasedItem->product_id == $equipmentTransfer->product_id && $prchasedItem->pur_item_qty_type == $equipmentTransfer->Qty_type) {
                    $prchasedItem->totalQty = $prchasedItem->totalQty - $equipmentTransfer->totalEquipmentQtys;
                    $a = false;
                } elseif ($a) {
                    $prchasedItem->totalQty = $prchasedItem->totalQty;
                }
            }

            // return $purchasedItems;
            return view('reports.stockReport', ['filter_by' => $filter_by, 'from' => $from, 'to' => $to, 'purchasedItems' => $purchasedItems, 'name' => '']);

            // return view("reports.stockReport", compact('purchasedItems','filter_by', 'from', 'to', ));
        }
    }


    

    public function purchaseStockReportPost(Request $request)
    {

        $startDate = Carbon::parse($request->from)->toDateTimeString();
        $endDate = Carbon::parse($request->to)->toDateTimeString();

        $purchasedItems = DB::table('inventory_purchase_items')
        ->join("inventory_products", 'inventory_products.id', '=', 'inventory_purchase_items.product_id')
        ->join('inventory_purchase_orders', 'inventory_purchase_orders.id', '=' ,'inventory_purchase_items.purchase_order_id')
        ->join('inventory_sellers', 'inventory_sellers.id', '=', 'inventory_purchase_orders.seller_id');



        if ($request->filter_by == 1) {
            $purchasedItems = $purchasedItems->select(
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_purchase_orders.id',
                'inventory_sellers.seller_name',
                'inventory_purchase_items.pur_item_qty_type',
                'inventory_purchase_items.pur_item_amount',

                DB::raw('SUM(inventory_purchase_items.pur_item_qty) as totalQty')
            )
                ->groupBy(
                    'inventory_products.product_name',
                    'inventory_products.product_code',
                    'inventory_purchase_orders.id',
                    'inventory_sellers.seller_name',
                    'inventory_purchase_items.pur_item_amount',
                    'inventory_purchase_items.pur_item_qty_type',
                );
        }

        if ($request->filter_by == null) {
            $purchasedItems = $purchasedItems->select(
                'inventory_products.product_name',
                'inventory_products.product_code',
                'inventory_purchase_orders.id',
                'inventory_brands.brand_name',
                DB::raw('SUM(inventory_purchase_items.pur_item_qty) as qty')
            )
                ->groupBy(
                    'inventory_products.product_name',
                    'inventory_products.product_code',
                    'inventory_purchase_orders.id',
                    'inventory_brands.brand_name'
                );
        }

        if ($request->from) {
            $purchasedItems = $purchasedItems->where('inventory_purchase_items.created_at', '>=', $request->from);
        }
        if ($request->to) {
            $purchasedItems = $purchasedItems->where('inventory_purchase_items.created_at', '<=', $request->to);
        }

        $purchasedItems = $purchasedItems->get();

        if ($request->filter_by == 1) {
            return view('reports.stockReport', ['filter_by' => $request->filter_by, 'from' => $request->from, 'to' => $request->to, 'purchasedItems' => $purchasedItems, 'name' => 'Supplier']);
        }
        if ($request->filter_by == null) {
            return view('reports.stockReport', ['filter_by' => $request->filter_by, 'from' => $request->from, 'to' => $request->to, 'purchasedItems' => $purchasedItems, 'name' => '']);
        }
    }

}
