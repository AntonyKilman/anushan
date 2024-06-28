<?php

namespace App\Http\Controllers;

use App\Models\CreditModel;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    public function index()
    {
       $credit_payment = DB::table( 'foodcity_credit_payments' )
        ->select( 'foodcity_credit_payments.*','customers.name as customer_name','employees.f_name as bill_user',
        'foodcity_sales.invoice_no')
        ->leftJoin('foodcity_sales', 'foodcity_sales.id','=','foodcity_credit_payments.sales_id')
        ->leftJoin('customers', 'customers.id','=','foodcity_credit_payments.customer_id')
        ->leftJoin('employees', 'employees.id','=','foodcity_credit_payments.emp_id')
        ->orderByDesc('foodcity_credit_payments.id')
        ->get();
        
        return view('sales.credit_payment',compact('credit_payment'));
    }

    public function save_credit_payment_details(Request $request)
    {
            $EmployeeId = Auth::user()->emp_id;
                
            $request-> validate([
                'sales_id' => 'required',
                'customer_id' => 'required',
            ]);

            $cash = $request->cash ? $request->cash : 0;
            $card = $request->card ? $request->card : 0;
            $cheque = $request->cheque ? $request->cheque : 0;
            $discount_amount = $request->discount_amount ? $request->discount_amount : 0;

            $credit_payment = CreditModel::find($request->id);
            $credit_payment->sales_id = $request->sales_id;
            $credit_payment->customer_id = $request->customer_id;
            $credit_payment->amount = $request->amount;
            $credit_payment->cash = $request->cash ? $request->cash : 0;
            $credit_payment->card = $request->card ? $request->card : 0;
            $credit_payment->check_ = $request->cheque ? $request->cheque : 0;
            $credit_payment->discount_amount = $request->discount_amount ? $request->discount_amount : 0;
            $credit_payment->check_number = $request->cheque_number;
            $credit_payment->check_date = $request->cheque_date;
            $credit_payment->date = $request->date;
            $credit_payment->emp_id = $EmployeeId;
            $credit_payment->total_pay = $cash + $card + $cheque + $discount_amount;
            $credit_payment->save();

            if ($credit_payment->amount == ($credit_payment->cash + $credit_payment->card + $credit_payment->check_ + $credit_payment->discount_amount)) {
                $sales = Sales::find($credit_payment->sales_id);
                $sales->payment_status = 1;
                $sales->save();
            } else {
                $sales = Sales::find($credit_payment->sales_id);
                $sales->payment_status = 0;
                $sales->save();
            }
    
            return redirect()->route('credit_payment.index')
                        ->with('success', 'Details successfully Updated!!');
    }


    public function credit_list_view()
    {
        $data = DB::table('foodcity_sales as fcs')
                    ->select('fcs.*', 'c.name as customer_name', 'c.phone_number')
                    ->leftJoin('customers as c', 'c.id', 'fcs.customer_id')
                    ->where('fcs.payment_status', 0)  // not fenish
                    ->where('fcs.is_cancelled', 0)  // not cancell
                    ->where('credit_payment', '>', 0)
                    ->orderByDesc('fcs.id')
                    ->get();

        foreach ($data as $value) {
            $sales_id = $value->id;
            $credit_payment = $value->credit_payment;

            $tillPayAmount = DB::table('foodcity_credit_payments')
                            ->where('sales_id', $sales_id)
                            ->value(DB::raw('IFNULL(sum(total_pay),0)'));

            $value->tillPayAmount = $tillPayAmount;
            $value->tillPayableAmount = $credit_payment - $tillPayAmount;
        }


        return view('sales.credit_list',compact('data'));
    }

    public function savecreditdetails(Request $request)
    {

            $EmployeeId = Auth::user()->emp_id;
            
            $request-> validate([
                'sales_id' => 'required',
                'customer_id' => 'required',
            ]);

            $cash = $request->cash ? $request->cash : 0;
            $card = $request->card ? $request->card : 0;
            $cheque = $request->cheque ? $request->cheque : 0;
            $discount_amount = $request->discount_amount ? $request->discount_amount : 0;
    
            $credit_list = new CreditModel();
            $credit_list->sales_id = $request->sales_id;
            $credit_list->customer_id = $request->customer_id;
            $credit_list->amount = $request->amount;
            $credit_list->cash = $cash;
            $credit_list->card = $card;
            $credit_list->check_ = $cheque;
            $credit_list->discount_amount = $discount_amount;
            $credit_list->check_number = $request->cheque_number;
            $credit_list->check_date = $request->cheque_date;
            $credit_list->date = $request->date;
            $credit_list->emp_id = $EmployeeId;
            $credit_list->total_pay = $cash + $card + $cheque + $discount_amount;
            $credit_list->save();

            if ($credit_list->amount == ($credit_list->cash + $credit_list->card + $credit_list->check_ + $credit_list->discount_amount)) {
                $sales = Sales::find($credit_list->sales_id);
                $sales->payment_status = 1;
                $sales->save();
            }
    
            return redirect()->route('credit_list.credit_list_view')
                        ->with('success', 'Paid successfully !!');

  }
}
