<?php

namespace App\Http\Controllers;
use App\Models\accountBank;
use App\Models\accountBankBalance;
use App\Models\AccountsServiceExpense;
use App\Models\accountChequePayment;
use App\Models\accountServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;




use Illuminate\Http\Request;

class AccountsServiceExpenseController extends Controller
{
    public function serviceExpenseShow()
    {
        $service_providers = accountServiceProvider::all();
        $accountBanks = accountBank::all();
        $service_expenses = AccountsServiceExpense::join('account_service_providers','account_service_providers.id','=','accounts_service_expenses.service_provider_id')
        ->join('account_banks','account_banks.id','=','accounts_service_expenses.bank_id')
        ->select('accounts_service_expenses.*','account_service_providers.name','account_banks.account_no')
        ->get();
        $service_expense_1=AccountsServiceExpense::join('account_service_providers','account_service_providers.id','=','accounts_service_expenses.service_provider_id')
        ->select('accounts_service_expenses.*','account_service_providers.name')
        ->get();
        // $service_expenses = AccountsServiceExpense::all();
        // return $service_expenses;
        return view('accountspages.ServiceExpense',compact('service_expense_1','service_providers','service_expenses','accountBanks'));
    }

    public function serviceExpenseStore(Request $request)
    {

        // return $request;
        $request-> validate([

            'service_provider_id'=> 'required',
            'date'=> 'required',
            'amount'=> 'required',
            // 'ser_exp_cash'=>'required'
        ]);

        $service_expenses = new AccountsServiceExpense();
        $service_expenses->service_provider_id  = $request->service_provider_id ;
        $service_expenses->date = $request->date;
        $service_expenses->amount = $request->amount;
        $service_expenses->ser_exp_cash = $request->ser_exp_cash;
        $service_expenses->ser_exp_cheque = $request->ser_exp_cheque;
        $service_expenses->ser_exp_cheque_no = $request->ser_exp_cheque_no;
        $service_expenses->ser_exp_cheque_date = $request->ser_exp_cheque_date;
        $service_expenses->bank_id = $request->bank_id;
        $service_expenses->ser_exp_online = $request->ser_exp_online;
        $service_expenses->ser_exp_reference_no = $request->ser_exp_reference_no;

        if ($request->image!=null) {

            $request-> validate([
                'image' => 'mimes:jpeg,bmp,png' ,
            ]);

            $image = $request->file('image');
            $image_name =  time() . '.' . $image->extension();
            $image->move("ServiceExpenses", $image_name);
            $service_expenses->image = $image_name;


            // $image_name="123123".'.'.$request->image->extension();
            // $request->image->move(public_path('images'),$image_name);
        }

        // $image_name="123123".'.'.$request->image->extension();
        // $request->image->move(public_path('images'),$image_name);
        // $service_expenses->image = $image_name;
        // $service_expenses->image = $request->image;
        $service_expenses->status = "0";
        $service_expenses->description = $request->description;
        $service_expenses->save();

        if(!($request->ser_exp_cheque)==null){
            $accountChequePayment = new accountChequePayment();
            $accountChequePayment -> service_expense_id = $service_expenses->id;
            $accountChequePayment -> debit = $request->ser_exp_cheque;
            $accountChequePayment -> cheque_no = $request->ser_exp_cheque_no;
            $accountChequePayment -> cheque_date = $request->ser_exp_cheque_date;
            $accountChequePayment -> date = $request->date;
            $accountChequePayment -> updatedBy = Auth::user()->id;
            $accountChequePayment -> status = 0; //pending 
            $accountChequePayment -> bank_id = $request -> bank_id;
            $accountChequePayment -> note = $request -> note;
            $accountChequePayment -> bank_id = $request -> bank_id;
            $accountChequePayment -> note = $request -> description;
            $accountChequePayment ->save(); 

        }

        if(!($request->ser_exp_online)==null){
            $accountBankBalance = new accountBankBalance();
            $accountBankBalance -> bank_id  = $request -> bank_id;
            $accountBankBalance -> credit  = 0.00;
            $accountBankBalance -> debit  = $request->ser_exp_online;
            $accountBankBalance -> service_expense_id = $service_expenses ->id;
            $accountBankBalance -> details  = $request -> description;
            $accountBankBalance -> date  = $request -> date;
            $accountBankBalance -> save();
            
        }
               

        $actvity = 'New Deparment Create - '. $request->name;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

        return redirect('service-expenses-view')->with('success','Record Successfully');

    }

    public function serviceExpenseUpdate(Request $request)
    {
        // return $request;
        //  $request-> validate([

        //     'service_provider_id'=> 'required',
        //     'date'=> 'required',
        //     'amount'=> 'required',
        //     'ser_exp_cash'=>'required'
        // ]);

        $service_expenses =  AccountsServiceExpense::find($request->id);
        
        $service_expenses->service_provider_id   = $request->service_provider_id ;
        $service_expenses->date = $request->date;
        $service_expenses->amount = $request->amount;
        $service_expenses->ser_exp_cash = $request->ser_exp_cash;
        $service_expenses->ser_exp_cheque = $request->ser_exp_cheque;
        $service_expenses->ser_exp_cheque_no = $request->ser_exp_cheque_no;
        $service_expenses->ser_exp_cheque_date = $request->ser_exp_cheque_date;
        $service_expenses->bank_id = $request->bank_id;
        $service_expenses->ser_exp_online = $request->ser_exp_online;
        $service_expenses->ser_exp_reference_no = $request->ser_exp_reference_no;
        
        if ($request->image!=null) {
            $request-> validate([
                'image' => 'mimes:jpeg,bmp,png' ,
            ]);

            $image = $request->file('image');
            $image_name =  time() . '.' . $image->extension();
            $image->move("ServiceExpenses", $image_name);
            $service_expenses->image = $image_name;

           
        }
        
        $service_expenses->status = $request->status;
        $service_expenses->description = $request->description;
        $service_expenses->save();

        $accountChequePayment = accountChequePayment::where('service_expense_id',$request->id)->first();
        if($accountChequePayment){
            $accountChequePayment->delete();
        }

        $accountBankBalance = accountBankBalance::where('service_expense_id',$request->id)->first();
        if($accountBankBalance){
            $accountBankBalance->delete();
        }

        if(!($request->ser_exp_cheque)==null){
            $accountChequePayment = new accountChequePayment();
            $accountChequePayment -> service_expense_id = $request->id;
            $accountChequePayment -> debit = $request->ser_exp_cheque;
            $accountChequePayment -> cheque_no = $request->ser_exp_cheque_no;
            $accountChequePayment -> cheque_date = $request->ser_exp_cheque_date;
            $accountChequePayment -> date = $request->date;
            $accountChequePayment -> updatedBy = Auth::user()->id;
            $accountChequePayment -> status = 0; //pending 
            $accountChequePayment -> bank_id = $request -> bank_id;
            $accountChequePayment -> note = $request -> note;
            $accountChequePayment -> bank_id = $request -> bank_id;
            $accountChequePayment -> note = $request -> description;
            $accountChequePayment ->save(); 

        }

        if(!($request->ser_exp_online)==null){
            $accountBankBalance = new accountBankBalance();
            $accountBankBalance -> bank_id  = $request -> bank_id;
            $accountBankBalance -> credit  = 0.00;
            $accountBankBalance -> debit  = $request->ser_exp_online;
            $accountBankBalance -> service_expense_id = $request ->id;
            $accountBankBalance -> details  = $request -> description;
            $accountBankBalance -> date  = $request -> date;
            $accountBankBalance -> save();
            
        }


        $actvity = 'New Deparment Create - '. $request->name;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

        return redirect('service-expenses-view')->with('success','Update Successfully');
    }

    
    public function serviceExpenseViewByid($id)
    {
        $service_expenses =DB::table('accounts_service_expenses')

        ->join('account_service_providers','account_service_providers.id','=','accounts_service_expenses.service_provider_id')
        ->join('account_banks','account_banks.id','=','accounts_service_expenses.bank_id')
        ->select('accounts_service_expenses.*','account_service_providers.name','account_banks.account_no')
        ->where('accounts_service_expenses.id','=',$id)
        ->first();
    
        return view('accountspages.ServiceExpenseView',['service_expenses'=>$service_expenses]);
    
    }

}
