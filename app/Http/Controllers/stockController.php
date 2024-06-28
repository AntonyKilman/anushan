<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\account_profit_loss;

class stockController extends Controller
{
    function stock(){
        $datas = account_profit_loss::where('account_profit_loss.is_delete', 0)
        ->WhereIn('account_profit_loss.type',["Stock damage","Stock drawing"])
        ->where('account_profit_loss.department_id', 1)
        ->where('account_profit_loss.is_delete', 0)
        ->get();
        // return $datas;
        return view('sales.stock',compact('datas'));
    }

    function stockStore(Request $request){
        // return $request;

        if($request->id){
            $account_profit_loss = account_profit_loss::find($request->id);
            $account_profit_loss -> total_purchase_price =  $request-> amount;
            $account_profit_loss -> type =  $request-> action;
            $account_profit_loss -> save();
            return redirect('/stock')->with("sucess","Successfully Updated");

        }
        else{


        $account_profit_loss = new account_profit_loss();
        $account_profit_loss -> customer_id = 0;
        $account_profit_loss -> total_purchase_price =  $request-> amount;
        $account_profit_loss -> total_selling_price = 0.00;
        $account_profit_loss -> total_discount_in = 0.00;
        $account_profit_loss -> total_discount_out =  0.00;
        $account_profit_loss -> total_sales_price = 0.00;
        $account_profit_loss -> cheque_in = 0.00;
        $account_profit_loss -> cheque_out = 0.00;
        $account_profit_loss -> cheque_no = 0;
        $account_profit_loss -> cheque_date = "0";
        $account_profit_loss -> credit = 0.00;
        $account_profit_loss -> department_id = 1;
        $account_profit_loss -> type =  $request-> action;
        $account_profit_loss -> cash_in = 0.00;
        $account_profit_loss -> cash_out = 0.00;
        $account_profit_loss -> card_in =0.00;
        $account_profit_loss -> card_out = 0.00;
        $account_profit_loss -> connected_id =0;
        $account_profit_loss -> indoor_return = 0.00;
        $account_profit_loss -> outdoor_return =0.00;
        $account_profit_loss -> is_delete = 0;
        $account_profit_loss -> save();
        return redirect('/stock')->with("sucess","Successfully Recorded");

        }
    }
}
