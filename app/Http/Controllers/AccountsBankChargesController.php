<?php

namespace App\Http\Controllers;

use App\Models\AccountsBankCharges;
use App\Models\accountBankChargesType;
use App\Models\accountBank;
use App\Models\accountBankBalance;
use Illuminate\Http\Request;

class AccountsBankChargesController extends Controller
{
     
    public function bankChargesStore(Request $request)
    {
        // return $request;

        $request-> validate([

            'bank_id'=> 'required',
            'bank_charges_type_id'=> 'required',
            'date'=> 'required',
            'charges_amount'=>'required|numeric'
        ]);

        $bank_charges= new AccountsBankCharges();
        $bank_charges->bank_id=$request->bank_id;
        $bank_charges->bank_charges_type_id=$request->bank_charges_type_id;
        $bank_charges->date=$request->date;
        $bank_charges->amount=$request->charges_amount;
        $bank_charges->note=$request->note;
        $bank_charges->save();  
        
        $accountBankBalance = new accountBankBalance();
        $accountBankBalance -> bank_id  = $request -> bank_id;
        $accountBankBalance -> credit  = 0.00;
        $accountBankBalance -> debit  = $request -> charges_amount;
        $accountBankBalance -> charges_id =$bank_charges->id;
        $accountBankBalance -> date  = $request -> date;
        $accountBankBalance -> details  = $request -> note;
        $accountBankBalance -> save();

        
        $actvity = 'New Deparment Create - '. $request->name;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

        return redirect('bank-charges-view')->with('success','Record Successfully');

    
    }

    public function bankChargesShow()
    {
        $banks = accountBank::all();
        $bank_charges_types=accountBankChargesType::all();
        $bank_charges = AccountsBankCharges::join('account_banks','account_banks.id','=','accounts_bank_charges.bank_id')
        ->join('account_bank_charges_types','account_bank_charges_types.id','=','accounts_bank_charges.bank_charges_type_id')
        ->select('accounts_bank_charges.*','account_banks.name as bank_name','account_bank_charges_types.name')
        ->get();

        // return $bank_charges;

        return view('accountspages.BankCharges',compact('banks','bank_charges_types','bank_charges'));

    }

    public function bankChargesUpdate(Request $request)
    {
        $request-> validate([

            'bank_id'=> 'required',
            'bank_charges_type_id'=> 'required',
            'date'=> 'required',
            'charges_amount'=>'required|numeric'
        ]);
        
        $bank_charges= AccountsBankCharges::find($request->id);
        $bank_charges->bank_id=$request->bank_id;
        $bank_charges->bank_charges_type_id=$request->bank_charges_type_id;
        $bank_charges->date=$request->date;
        $bank_charges->amount=$request->charges_amount;
        $bank_charges->note=$request->note;
        $bank_charges->save();  

        $accountBankBalance = accountBankBalance::where('bank_id',$request->bank_id)
        ->where('charges_id',$request->id)
        ->first();
        // return  $accountBankBalance;
        $accountBankBalance -> bank_id  = $request -> bank_id;
        $accountBankBalance -> credit  = 0.00;
        $accountBankBalance -> debit  = $request -> charges_amount;
        $accountBankBalance -> charges_id =$request->id;
        $accountBankBalance -> date  = $request -> date;
        $accountBankBalance -> details  = $request -> note;
        $accountBankBalance -> save();

        $actvity = 'New Deparment Create - '. $request->name;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
        return redirect('bank-charges-view')->with('success','Update Successfully');
        
        
    }

}
