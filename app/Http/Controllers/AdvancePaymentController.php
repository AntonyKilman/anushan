<?php

namespace App\Http\Controllers;

use App\Models\AdvancePayment;
use App\Models\account_profit_loss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdvancePaymentController extends Controller
{

    // show
    public function index(Request $request)
    {
        if($request->get('month')){
            $month = date('Y-m', strtotime($request->get('month')));
        }else{
            $month = date("Y-m");
        }

        $salaryDateStart = '01';

        // salary month start date
        $advance_payment_month_start_date = date("Y-m-" . $salaryDateStart, strtotime($month));
        // salary month end date
        $advance_payment_month_end_date = date("Y-m-d", strtotime('+1 month -1 day', strtotime($advance_payment_month_start_date)));

        $advance_payment = DB::table('hr_advance_payments')
                        ->select('hr_advance_payments.*', 'employees.f_name as emp_name')
                        ->leftJoin('employees', 'employees.id', 'hr_advance_payments.emp_id')
                        ->whereDate('hr_advance_payments.month', '>=', $advance_payment_month_start_date)
                        ->whereDate('hr_advance_payments.month', '<=', $advance_payment_month_end_date)
                        ->orderByDesc('hr_advance_payments.id')
                        ->get();

        return view('hr.advancePayment', compact('advance_payment', 'month'));

    }

    // create & update
    public function store(Request $request)
    {

        $rules = array(
            'emp_id' => 'required',
        );

        $customMessages = [
            'emp_id.required' => 'Select One Employee',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {  // check back end validator

            return redirect()->back()->withErrors($validator)->withInput($request->all());

        } else {

            $id = $request->id;  // id

            if ($id == 0) {  // create

                $advance_payment = new AdvancePayment();
                $advance_payment->emp_id = $request->emp_id;
                $advance_payment->month = $request->month;
                $advance_payment->amount = $request->amount;
                $advance_payment->save();

                // store account profit loss table code by sivakaran
                $account_profit_loss = new account_profit_loss();
                $account_profit_loss->customer_id = $request->emp_id;
                $account_profit_loss->date = $request->month;
                $account_profit_loss->cash_out = $request->amount;
                $account_profit_loss->type = "HR_ADVANCE_SALARY";
                $account_profit_loss->department_id = 5;
                $account_profit_loss->is_delete = 0;
                $account_profit_loss->connected_id = $advance_payment->id;
                $account_profit_loss->save();


                $actvity = 'New Advance Payment Create  Emp_id - '. $request->emp_id;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

                return redirect()->route('hr.advancePayment.index')
                        ->with('success', 'Advance Payment successfully Created!!');

            } else {  // update

                $advance_payment = AdvancePayment::find($id);
                $advance_payment->emp_id = $request->emp_id;
                $advance_payment->month = $request->month;
                $advance_payment->amount = $request->amount;
                $advance_payment->save();

                // update account profit loss table code by sivakaran
                $data = account_profit_loss::where('account_profit_loss.type','HR_ADVANCE_SALARY')
                ->where('account_profit_loss.connected_id',$id)
                ->first();

                $account_profit_loss =account_profit_loss::find($data->id);
                $account_profit_loss->customer_id = $request->emp_id;
                $account_profit_loss->date = $request->month;
                $account_profit_loss->cash_out = $request->amount;
                $account_profit_loss->save();


                $actvity = 'Advance Payment Update  Emp_id - '. $request->emp_id;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

                return redirect()->route('hr.advancePayment.index')
                        ->with('success', 'Advance Payment successfully Updated!!');
            }

        }
    }


}
