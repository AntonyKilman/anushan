<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accountBank;
use App\Models\accountBankBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument;

class accountBankController extends Controller
{
    function bankShow(){
        $banks = accountBank::all();
        return view('accountspages.bank',compact('banks'));
    }

    function bankAdd(Request $request){
        // return $request;
        if(($request->id)==null){

            $request->validate([
                'name'=>'required',
                'branch'=>'required',
                'account_no'=>'required|unique:account_banks',
                'account_type'=>'required',
                'contact_no'=>'required|numeric',
                'date'=>'required',
                'starting_balance'=>'required|numeric',
            ]);

            $bank = new accountBank();
            $bank->	name = $request-> name;
            $bank->	branch = $request-> branch;
            $bank->	account_no = $request-> account_no;
            $bank->	account_type = $request-> account_type;
            $bank->	contact_no = $request-> contact_no;
            $bank->	date = $request-> date;
            $bank->	starting_balance = $request-> starting_balance;
            $bank->	status = "Active";
            $bank->save();
    
            
            $accountBankBalance = new accountBankBalance();
            $accountBankBalance -> bank_id  = $bank->id;
            $accountBankBalance -> credit  = $request-> starting_balance;
            $accountBankBalance -> debit  = 0.00;
            $accountBankBalance -> details  = "New Account";
            $accountBankBalance -> date = $request-> date;
            $accountBankBalance -> save();
    
    
            $actvity = 'New Deparment Create - '. $request->name;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
            return redirect('bank-show')->with('sucess',"Successfully Recorded");

        }

        else{

            $request->validate([
                'name'=>'required',
                'branch'=>'required',
                'account_no'=>'required|unique:account_banks,account_no,'.$request->id,
                'account_type'=>'required',
                'contact_no'=>'required|numeric',
                'starting_balance'=>'required|numeric',
            ]);
           
            $bank = accountBank::find($request->id);
            $bank->	name = $request-> name;
            $bank->	branch = $request-> branch;
            $bank->	account_no = $request-> account_no;
            $bank->	account_type = $request-> account_type;
            $bank->	contact_no = $request-> contact_no;
            $bank->	date = $request-> date;
            $bank->	starting_balance = $request-> starting_balance;
            $bank->	status = $request-> status;
            $bank->save();
            
            if(($request-> status)=="Active"){
                $accountBankBalance =  accountBankBalance::where('bank_id',$request->id)->first();
                $accountBankBalance -> bank_id  = $request->id;
                $accountBankBalance -> credit  = $request-> starting_balance;
                $accountBankBalance -> debit  = 0.00;
                $accountBankBalance -> details  = "New Account";
                $accountBankBalance -> date = $request-> date;
                $accountBankBalance -> save();

            }
            
    
    
            $actvity = 'New Deparment Create - '. $request->name;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
            return redirect('bank-show')->with('sucess',"Successfully Updated");

        }
    
    }



    // bank balance tables

    function bankBalanceShow(Request $request){

        $bankId = $request->bank_id;
        $accountBanks = accountBank::all();

        $accountBankBalances = accountBankBalance::join('account_banks','account_banks.id','=','account_bank_balances.bank_id')
        ->select('account_bank_balances.*','account_banks.account_no');

        if($request->bank_id){
            $accountBankBalances=$accountBankBalances->where('bank_id',$bankId);
        }
        
        $accountBankBalances=$accountBankBalances->orderBy('date', 'asc')
        ->get();

        foreach($accountBankBalances as $item){

           $accountBalances = accountBankBalance::join('account_banks','account_banks.id','=','account_bank_balances.bank_id')
           ->where('bank_id',$item->bank_id)
           ->where('account_bank_balances.date','<=',$item->date)
           ->select('account_bank_balances.*','account_banks.account_no',
           DB::raw('sum(account_bank_balances.credit) as totalCredit'),
           DB::raw('sum(account_bank_balances.debit) as totalDebit'),
           )
           ->get();

           foreach($accountBalances as $value){
                $item->balance=$value->totalCredit- $value->totalDebit;
           }
        
        }
        
         return view('accountspages.bankBalance',compact('accountBankBalances','accountBanks','bankId'));
      
    }

}
