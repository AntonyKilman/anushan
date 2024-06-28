<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accountBankChargesType;




class accountBankChargesTypeController extends Controller
{
    function bankChargesTypesShow(){
        $bankChargesTypes = accountBankChargesType::all();
        return view('accountspages.bankChargesTypes',compact('bankChargesTypes'));
    }

    function bankChargesTypesStore(Request $request){
        // return $request;
       

        if(($request->id)==null){

            $request->validate([
                'name'=>'required|unique:account_bank_charges_types',
            ]);

            $accountBankChargesType = new accountBankChargesType();
            $accountBankChargesType ->name = $request -> name;
            $accountBankChargesType -> description = $request -> description;
            $accountBankChargesType -> save();

            $actvity = 'New Deparment Create - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect('bank-chargestype-show')->with('sucess','Successfully Recordered');
        }
        
        else{

            $request->validate([
                'name'=>'required|unique:account_bank_charges_types,name,'.$request->id,
            ]);
 
            $accountBankChargesType = accountBankChargesType::find($request->id);
            $accountBankChargesType ->name = $request -> name;
            $accountBankChargesType -> description = $request -> description;
            $accountBankChargesType -> save();

            $actvity = 'New Deparment Create - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect('bank-chargestype-show')->with('sucess','Successfully Updated');

        }
        
    }
}
