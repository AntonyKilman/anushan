<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accountBankIntrest;
use App\Models\accountBank;
use App\Models\accountBankBalance;

class accountBankIntrestController extends Controller
{
    
    function bankIntrestShow(){
        $accountBanks = accountBank::where('status','=','Active')->get();
        $accountBankIntrests = accountBankIntrest::join('account_banks','account_banks.id','=','account_bank_intrests.bank_id')
        ->select('account_bank_intrests.*','account_banks.account_no')
        ->orderBy('date', 'asc')
        ->get();
        return view('accountspages.bankIntrest',compact('accountBankIntrests','accountBanks'));

    }

    function bankIntrestStore(Request $request){

        if(($request->id)==null){

            $request->validate([
                'bank_id'=>'required',
                'intrest_amount'=>'required|numeric',
                'date'=>'required',
            ]);
    
            $accountBankIntrest = new accountBankIntrest();
            $accountBankIntrest -> bank_id  = $request -> bank_id;
            $accountBankIntrest -> intrest_amount  = $request -> intrest_amount;
            $accountBankIntrest -> date  = $request -> date;
            $accountBankIntrest -> note  = $request -> note;
            $accountBankIntrest -> save();
    
            $accountBankBalance = new accountBankBalance();
            $accountBankBalance -> bank_id  = $request -> bank_id;
            $accountBankBalance -> credit  = $request -> intrest_amount;
            $accountBankBalance -> debit  = 0.00;
            $accountBankBalance -> intrest_id = $accountBankIntrest ->id;
            $accountBankBalance -> details  = $request -> note;
            $accountBankBalance -> date  = $request -> date;
            $accountBankBalance -> save();
       
            $actvity = 'New Deparment Create - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
        return redirect('bank-intrest-show')->with('sucess',"Successfully Recorded");

        }

        else{
           
            $request->validate([
                'bank_id'=>'required',
                'intrest_amount'=>'required|numeric',
                'date'=>'required',
            ]);
    
            $accountBankIntrest =  accountBankIntrest::find($request ->id);
            $accountBankIntrest -> bank_id  = $request -> bank_id;
            $accountBankIntrest -> intrest_amount  = $request -> intrest_amount;
            $accountBankIntrest -> date  = $request -> date;
            $accountBankIntrest -> note  = $request -> note;
            $accountBankIntrest -> save();
    
        
            $accountBankBalance =  accountBankBalance::where('bank_id',$request -> bank_id)
            ->where('intrest_id',$request ->id)
            ->first();
          
            $accountBankBalance -> bank_id  = $request -> bank_id;
            $accountBankBalance -> credit  = $request -> intrest_amount;
            $accountBankBalance -> debit  = 0.00;
            $accountBankBalance -> intrest_id = $request ->id;
            $accountBankBalance -> details  = $request -> note;
            $accountBankBalance -> date  = $request -> date;
            $accountBankBalance -> save();
    
            $actvity = 'New Deparment Create - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
            return redirect('bank-intrest-show')->with('sucess',"Successfully Updated");

        }
        
    }
}
