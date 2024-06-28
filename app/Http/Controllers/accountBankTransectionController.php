<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accountBankTransection;
use App\Models\accountBank;
use App\Models\accountBankBalance;
use App\Models\accountChequePayment;
use Illuminate\Support\Facades\Auth;

class accountBankTransectionController extends Controller
{
    function bankTransectionShow()
    {
        $accountBanks = accountBank::where('status', '=', 'Active')->get();
        $accountBankTransections = accountBankTransection::join('account_banks', 'account_banks.id', '=', 'account_bank_transections.bank_id')
            ->select('account_bank_transections.*', 'account_banks.account_no')
            ->orderBy('date', 'asc')
            ->get();
        return view('accountspages.bankTransection', compact('accountBankTransections', 'accountBanks'));
    }

    function bankTransectionStore(Request $request)
    {

        // for add function-------------------------
        if (($request->id) == null) {

            $request->validate([
                'bank_id' => 'required',
                'amount' => 'required|numeric',
                'type' => 'required',
                'no' => 'required',
                'date' => 'required',

            ]);

            // store account bank transaction table------------------------------------------

            $accountBankTransection = new accountBankTransection();
            $accountBankTransection->bank_id  = $request->bank_id;
            $accountBankTransection->amount  = $request->amount;
            $accountBankTransection->type  = $request->type;
            $accountBankTransection->no  = $request->no;
            $accountBankTransection->date  = $request->date;
            $accountBankTransection->note  = $request->note;
            $accountBankTransection->cheque_no  = $request->cheque_no;
            $accountBankTransection->cheque_date  = $request->cheque_date;
            $accountBankTransection->save();

            // bank balance table-----------------------------------------------------------------

            if (($request->type) == 'Deposit' || ($request->type) == 'Online Deposit' ||  ($request->type) == 'Withdraw' || ($request->type) == 'Online Withdraw' || ($request->type) == 'Commission') {

                $accountBankBalance = new accountBankBalance();
                $accountBankBalance->bank_id  = $request->bank_id;
                $accountBankBalance->details  = $request->note;
                $accountBankBalance->date  = $request->date;
                $accountBankBalance->transection_id  = $accountBankTransection->id;


                if (($request->type) == 'Deposit') {
                    $accountBankBalance->credit  = $request->amount;
                    $accountBankBalance->debit  = 0.00;
                }

                if (($request->type) == 'Online Deposit') {
                    $accountBankBalance->credit  = $request->amount;
                    $accountBankBalance->debit  = 0.00;
                }

                if (($request->type) == 'Withdraw') {
                    $accountBankBalance->credit  = 0.00;
                    $accountBankBalance->debit  = $request->amount;
                }

                if (($request->type) == 'Online Withdraw') {
                    $accountBankBalance->credit  = 0.00;
                    $accountBankBalance->debit  = $request->amount;
                }

                if (($request->type) == 'Commission') {
                    $accountBankBalance->credit  = $request->amount;
                    $accountBankBalance->debit  = 0.00;
                }

                $accountBankBalance->save();
            }


            // store check payment table-----------------------------------------------------------


            if (($request->type) == 'Cheque Deposit' || ($request->type) == 'Cheque Withdraw') {

                if (($request->type) == 'Cheque Deposit') {

                    // store check payment table
                    $accountChequePayment = new accountChequePayment();
                    $accountChequePayment->bank_transaction_id = $accountBankTransection->id;
                    $accountChequePayment->credit = $request->amount;
                    $accountChequePayment->cheque_no = $request->cheque_no;
                    $accountChequePayment->cheque_date = $request->cheque_date;
                    $accountChequePayment->date = $request->date;
                    $accountChequePayment->updatedBy = Auth::user()->id;
                    $accountChequePayment->status = 0; //pending
                    $accountChequePayment->bank_id = $request->bank_id;
                    $accountChequePayment->note = $request->note;
                    $accountChequePayment->save();
                }

                if (($request->type) == 'Cheque Withdraw') {

                    // store check payment table
                    $accountChequePayment = new accountChequePayment();
                    $accountChequePayment->bank_transaction_id = $accountBankTransection->id;
                    $accountChequePayment->debit = $request->amount;
                    $accountChequePayment->cheque_no = $request->cheque_no;
                    $accountChequePayment->cheque_date = $request->cheque_date;
                    $accountChequePayment->date = $request->date;
                    $accountChequePayment->updatedBy = Auth::user()->id;
                    $accountChequePayment->status = 0; //pending
                    $accountChequePayment->bank_id = $request->bank_id;
                    $accountChequePayment->note = $request->note;
                    $accountChequePayment->save();
                }
            }

            $actvity = 'New Deparment Create - ' . $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect('bank-transection-show')->with('sucess', "Successfully Recorded");
        }

        // For update function-------------------------------------
        else {

            $request->validate([
                'bank_id' => 'required',
                'amount' => 'required|numeric',
                'type' => 'required',
                'no' => 'required',
                'date' => 'required',

            ]);

            $accountBankTransection =  accountBankTransection::find($request->id);
            $accountBankTransection->bank_id  = $request->bank_id;
            $accountBankTransection->amount  = $request->amount;
            $accountBankTransection->type  = $request->type;
            $accountBankTransection->no  = $request->no;
            $accountBankTransection->date  = $request->date;
            $accountBankTransection->note  = $request->note;
            $accountBankTransection->cheque_no  = $request->cheque_no;
            $accountBankTransection->cheque_date  = $request->cheque_date;
            $accountBankTransection->save();


            // for edit bank balance table-------------------------------------
            $accountBankBalance = accountBankBalance::where('bank_id', $request->bank_id)
                ->where('transection_id', $request->id)
                ->first();

            if ($accountBankBalance) {
                $accountBankBalance->delete();
            }

            $accountChequePayment = accountChequePayment::where('bank_transaction_id', $request->id)->first();
            if ($accountChequePayment) {
                $accountChequePayment->delete();
            }

            if (($request->type) == 'Deposit' || ($request->type) == 'Online Deposit' ||  ($request->type) == 'Withdraw' || ($request->type) == 'Online Withdraw' || ($request->type) == 'Commission') {

                $accountBankBalance = new accountBankBalance();
                $accountBankBalance->bank_id  = $request->bank_id;
                $accountBankBalance->details  = $request->note;
                $accountBankBalance->date  = $request->date;
                $accountBankBalance->transection_id  = $request->id;


                if (($request->type) == 'Deposit') {
                    $accountBankBalance->credit  = $request->amount;
                    $accountBankBalance->debit  = 0.00;
                }

                if (($request->type) == 'Online Deposit') {
                    $accountBankBalance->credit  = $request->amount;
                    $accountBankBalance->debit  = 0.00;
                }

                if (($request->type) == 'Withdraw') {
                    $accountBankBalance->credit  = 0.00;
                    $accountBankBalance->debit  = $request->amount;
                }

                if (($request->type) == 'Online Withdraw') {
                    $accountBankBalance->credit  = 0.00;
                    $accountBankBalance->debit  = $request->amount;
                }

                if (($request->type) == 'Commission') {
                    $accountBankBalance->credit  = $request->amount;
                    $accountBankBalance->debit  = 0.00;
                }

                $accountBankBalance->save();
            }

            if (($request->type) == 'Cheque Deposit' || ($request->type) == 'Cheque Withdraw') {

                if (($request->type) == 'Cheque Deposit') {

                    // store check payment table
                    $accountChequePayment = new accountChequePayment();
                    $accountChequePayment->bank_transaction_id = $request->id;
                    $accountChequePayment->credit = $request->amount;
                    $accountChequePayment->cheque_no = $request->cheque_no;
                    $accountChequePayment->cheque_date = $request->cheque_date;
                    $accountChequePayment->date = $request->date;
                    $accountChequePayment->updatedBy = Auth::user()->id;
                    $accountChequePayment->status = 0; //pending
                    $accountChequePayment->bank_id = $request->bank_id;
                    $accountChequePayment->note = $request->note;
                    $accountChequePayment->save();
                }

                if (($request->type) == 'Cheque Withdraw') {

                    // store check payment table
                    $accountChequePayment = new accountChequePayment();
                    $accountChequePayment->bank_transaction_id = $request->id;
                    $accountChequePayment->debit = $request->amount;
                    $accountChequePayment->cheque_no = $request->cheque_no;
                    $accountChequePayment->cheque_date = $request->cheque_date;
                    $accountChequePayment->date = $request->date;
                    $accountChequePayment->updatedBy = Auth::user()->id;
                    $accountChequePayment->status = 0; //pending
                    $accountChequePayment->bank_id = $request->bank_id;
                    $accountChequePayment->note = $request->note;
                    $accountChequePayment->save();
                }
            }

            $actvity = 'New Deparment Create - ' . $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect('bank-transection-show')->with('sucess', "Successfully Updated");
        }
    }
}
