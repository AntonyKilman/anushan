<?php

namespace App\Http\Controllers;

use App\Models\OwnerModel;
use App\Models\owner_transactionModel;
use App\Models\OwnerTransactionpaymentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OwnerController extends Controller
{
    public function index()
    {
        $owner_list = DB::table('owners_list')
            ->select('owners_list.*')
            ->get();
        return view('owner.owner_list', compact('owner_list'));
    }

    public function StoreOwnerList(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $owner_list = new OwnerModel();
            $owner_list->owner_name = $request->name;
            $owner_list->phonenumber = $request->phonenumber;
            $owner_list->shop_name = $request->shop_name;

            $owner_list->save();

            return redirect()->route('owner.index')
                ->with('success', 'Owner List  successfully Created!!');
        } else {

            $owner_list = OwnerModel::find($id);
            $owner_list->owner_name = $request->name;
            $owner_list->phonenumber = $request->phonenumber;
            $owner_list->shop_name = $request->shop_name;

            $owner_list->save();
            return redirect()->route('owner.index')
                ->with('success', 'Owner List  successfully Updated!!');
        }
    }

    public function ownertransactionindex(Request $request)
    {
        if ($request->from) {
            $from = $request->from;
        } else {
            $from = date('Y-m-d');
        }

        $ownertransaction = DB::table('owners_transaction')
            ->select('owners_transaction.*','owners_list.owner_name')
            ->leftJoin('owners_list','owners_list.id','owners_transaction.owners_list_id')
            ->whereDate('owners_transaction.date', $from)
            ->get();

        $ownerlist = DB::table('owners_list')
            ->select('owners_list.id','owners_list.owner_name')
            ->get();

        return view('owner.owner_transaction', compact('ownertransaction','ownerlist','from'));
    }

    public function StoreOwnertransaction(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $owner_list = new owner_transactionModel();
            $owner_list->owners_list_id = $request->owners_list_id;
            $owner_list->date = $request->date;
            $owner_list->amount = $request->amount;
            $owner_list->option = $request->option;
            $owner_list->reason = $request->reason;
            $owner_list->save();

            return redirect()->route('owner.ownertransactionindex')
                ->with('success', 'Owner Transaction  successfully Created!!');
        } else {

            $owner_list = owner_transactionModel::find($id);
            $owner_list->owners_list_id = $request->owners_list_id;
            $owner_list->date = $request->date;
            $owner_list->amount = $request->amount;
            $owner_list->option = $request->option;
            $owner_list->reason = $request->reason;
            $owner_list->save();

            return redirect()->route('owner.ownertransactionindex')
                ->with('success', 'Owner Transaction  successfully Updated!!');
        }
    }

    public function ownertransactionpaymentindex()
    {
        $data = DB::table('owners_transaction')
                    ->select('owners_transaction.*', 'owners_list.owner_name', 'owners_list.phonenumber')
                    ->leftJoin('owners_list', 'owners_list.id', 'owners_transaction.owners_list_id')
                    ->where('payment_status', 'Un Finish')
                    ->orderByDesc('owners_transaction.id')
                    ->get();

        foreach ($data as $value) {
            $owners_transaction_id = $value->id;
            $credit_amount = $value->amount;

            $tillPayAmount = DB::table('owners_transaction_payment')
                            ->where('owners_transaction_id', $owners_transaction_id)
                            ->value(DB::raw('IFNULL(sum(amount),0)'));

            $value->tillPayAmount = $tillPayAmount;
            $value->tillPayableAmount = $credit_amount - $tillPayAmount;
        }


        return view('owner.owner_payment_list',compact('data'));
    }

    public function saveownertransactionpayment(Request $request)
    {
            
            $request-> validate([
                'owners_transaction_id' => 'required',
                'pay_amount' => 'required',
            ]);
    
            $payment = new OwnerTransactionpaymentModel();
            $payment->owners_transaction_id = $request->owners_transaction_id;
            $payment->amount = $request->pay_amount;
            $payment->date = $request->date;
            $payment->save();

            if ($request->pay_amount == $request->payable_amount) {
                $owner_list = owner_transactionModel::find($request->owners_transaction_id);
                $owner_list->payment_status = 'Finish';
                $owner_list->save();
            }
    
            return redirect()->route('owner.ownertransactionpaymentindex')
                        ->with('success', 'Paid successfully !!');

  }
}
