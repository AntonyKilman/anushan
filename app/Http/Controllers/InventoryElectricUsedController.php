<?php

namespace App\Http\Controllers;

use App\Models\InventoryElectricUsed;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryElectricUsedController extends Controller
{
    public function electricUseAdd()
    {
        $products=DB::table('inventory_indoor_transfer')
        ->leftJoin('inventory_products','inventory_products.id','=','inventory_indoor_transfer.product_id')
        ->where('department_id','=',17)
        ->select('inventory_indoor_transfer.product_id as id','inventory_products.product_name as name')
        ->groupBy('inventory_indoor_transfer.product_id','inventory_products.product_name')
        ->get();

        // return $products;
        return view('electric_mini_store_used.electric_used_add',compact('products'));
    }

    public function electricUseAddProcess(Request $request)
    {
        // return $request;
        $check=0;
        try {
            $length = count($request->count);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Please select atleast one raw');
        }

        for ($i = 0; $i < $length; $i++) {
            $a = $request->count[$i];
            if ($request->$a) {
                $used = new InventoryElectricUsed();
                $used->product_id = $request->pro_id[$i];
                $used->purchase_id = $request->purchase_id[$i];
                $used->used_qty = $request->use_qty[$i];
                $used->qty_type = $request->qty_type[$i];
                $used->user_id = Auth::user()->emp_id;
                $used->reason = $request->reason[$i];
                $used->date=$request->date;
                $used->save();
                $check++;
            }
        }
        if ($check > 0) {
            return redirect('/electric-use-showall')->with('success', 'Successfully Recorded');
        } else {
            return redirect()->back()->with('error', 'Please select atleast one raw');
        }
    }

    public function electricUseShowall(Request $request)
    {
        $from=$request->from;
        $to=$request->to;
        $electric_uses=InventoryElectricUsed::
        join('inventory_products', 'inventory_products.id', '=', 'inventory_electric_useds.product_id')
        ->select('inventory_electric_useds.*','inventory_products.product_name','inventory_products.product_code');
        if ($from) {
            $electric_uses=$electric_uses->where('inventory_electric_useds.date','>=',$from);
        }
        if ($to) {
            $electric_uses=$electric_uses->where('inventory_electric_useds.date','<=',$to);
        }
        $electric_uses=$electric_uses->get();
        return view('electric_mini_store_used.electric_used_showall',compact('electric_uses','from','to'));
    }

    public function electricUseEdit(Request $request)
    {
        $date=$request->date;
        $filter_date=InventoryElectricUsed::
        join('inventory_products', 'inventory_products.id', '=', 'inventory_electric_useds.product_id')
        ->select('inventory_electric_useds.*','inventory_products.product_name','inventory_products.product_code')
        ->where('date','=',$date)->get();

        return view('electric_mini_store_used.electric_used_edit',compact('filter_date','date'));
        // return ;
    }

    public function electricUseUpdateProcess(Request $request)
    {
        try {
            $length=count($request->id);
        } catch (\Throwable $th) {
            $length=0;
        }

        for ($i=0; $i < $length; $i++) { 
            $row=InventoryElectricUsed::find($request->id[$i]);
            $row->return_qty=$request->return_qty[$i];
            $row->save();
        }
        return redirect('/electric-use-showall')->with('success', 'Successfully Updated');
    }

    public function electricUseAddGetdata($id)
    {
        $batches = DB::table('inventory_indoor_transfer')
        ->join('inventory_products', 'inventory_products.id', '=', 'inventory_indoor_transfer.product_id')
        ->where('inventory_indoor_transfer.product_id', $id)
        ->where('inventory_indoor_transfer.department_id', 17)
        ->groupBy('inventory_indoor_transfer.purchase_id', 'inventory_products.product_name', 'inventory_products.product_code', 'inventory_indoor_transfer.product_id', 'inventory_indoor_transfer.exDate', 'inventory_indoor_transfer.transfer_quantity')
        ->select(
            'inventory_indoor_transfer.purchase_id',
            'inventory_indoor_transfer.product_id',
            'inventory_indoor_transfer.exDate',
            // 'inventory_indoor_transfer.pur_item_amount',
            'inventory_products.product_name',
            'inventory_products.product_code',
            // 'inventory_indoor_transfer.pur_item_qty_type',
            DB::raw("SUM(inventory_indoor_transfer.transfer_quantity) as qty")
        )
        ->get();

        foreach ($batches as $value) {
            $e_use=InventoryElectricUsed::where('purchase_id','=',$value->purchase_id)->where('product_id','=',$value->product_id)->get();
            $e_use_qty=0;
            foreach ($e_use as $e_use_) {
                $value->qty=$value->qty-$e_use_->used_qty+$e_use_->return_qty;
            }
        }
        return $batches;
    }
}
