<?php

namespace App\Http\Controllers;

use App\Models\InventoryReturnReason;
use Illuminate\Http\Request;

class InventoryReturnReasonController extends Controller
{
    public function reasonShowAll()
    {
        $reasons=InventoryReturnReason::all();
        return view('return_reason.ReturnReasonShowAll',['reasons'=>$reasons]);
    }

    public function reasonAddProcess(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:inventory_return_reasons,reason_name',
        ]);


        $reason=new InventoryReturnReason();
        $reason->reason_name=$request->name;
        $reason->reason_des=$request->description;

        try {
            $reason->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Create Return Reason");
            } catch (\Throwable $th) {
            }
            return redirect('/reason-show-all')->with('success','successfull recorded');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','something wrong try again');
        }
    }    

    public function reasonUpdateProcess(Request $request)
    {
        $reason=InventoryReturnReason::find($request->id);

        $request->validate([
            'name' => 'required|unique:inventory_return_reasons,reason_name,'.$request->id,
        ]);

        $reason->reason_name=$request->name;
        $reason->reason_des=$request->description;

        try {
            $reason->save();
            try {
                $a = app('App\Http\Controllers\ActivityLogController')->index("Update Return Reason");
            } catch (\Throwable $th) {
            }
            return redirect('/reason-show-all')->with('success','successfull recorded');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','something wrong try again');
        }
    }    
}
