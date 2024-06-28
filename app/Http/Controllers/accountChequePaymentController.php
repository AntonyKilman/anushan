<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accountBankBalance;
use App\Models\accountChequePayment;

class accountChequePaymentController extends Controller
{


   function chequePaymentShow(Request $request){

      $bank_id = $request ->bank_id;

      $accountChequePayments_acc_no = accountChequePayment::whereNotIn('account_cheque_payments.status',[0])
                              ->select('account_cheque_payments.*','account_banks.account_no')
                              ->leftJoin('account_banks','account_banks.id','account_cheque_payments.bank_id')
                              ->groupBy('account_cheque_payments.bank_id')
                              ->get();

      $accountChequePayments = accountChequePayment::whereNotIn('account_cheque_payments.status',[0])
                              ->select('account_cheque_payments.*','account_banks.account_no')
                              ->leftJoin('account_banks','account_banks.id','account_cheque_payments.bank_id');
                              if ($bank_id) {
      $accountChequePayments = $accountChequePayments->where('account_cheque_payments.bank_id',$bank_id);
                              }
      $accountChequePayments = $accountChequePayments
                              ->orderBy('cheque_date','asc')
                              ->get();

      return view('accountspages.chequePayments',compact('accountChequePayments','accountChequePayments_acc_no','bank_id')); 
   }

   function chequePaymentPending(){
      $accountChequePayments = accountChequePayment::where('account_cheque_payments.status',0)
      ->join('account_banks','account_banks.id','=','account_cheque_payments.bank_id')
      ->select('account_cheque_payments.*','account_banks.account_no')
      ->orderBy('cheque_date','asc')
      ->get();
      return view('accountspages.chequePaymentPending',compact('accountChequePayments')); 
   }

   function chequePaymentPendingupdate(Request $request){
  
      $accountChequePayments =  accountChequePayment::find($request->id);
      $accountChequePayments -> status = $request -> editType;
      $accountChequePayments -> save();

      

      if(($request -> editType)=="1"){
         $accountBankBalance =  new accountBankBalance();
         $accountBankBalance -> bank_id  = $accountChequePayments->bank_id;
         $accountBankBalance -> cheque_payment_id = $accountChequePayments ->id;
         $accountBankBalance -> details  = $request -> note;
         $accountBankBalance -> date  = $request -> cheque_date;
         $accountBankBalance -> details  = $request -> description;

         if(($request->credit)>1){
            $accountBankBalance -> credit  = $request -> credit;
            $accountBankBalance -> debit  = 0.00;
         }

         else{
            $accountBankBalance -> credit  = 0.00;
            $accountBankBalance -> debit  = $request -> debit;

         }
         $accountBankBalance -> save();

      }
      return redirect("cheque-payment-pending");

   }

   function chequePaymentUpdate($id){
     
      $accountChequePayments =  accountChequePayment::find($id);
      $accountChequePayments -> status = 0;
      $accountChequePayments -> save();

      $accountBankBalance = accountBankBalance::where('cheque_payment_id',$id)->first();
      if($accountBankBalance){
         $accountBankBalance->delete();
      }
      
      return redirect("cheque-payment-show");
   }

}
