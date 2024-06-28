<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accountServiceCharge;
use App\Models\accountyServiceType;
use Illuminate\Support\Facades\Auth;
use App\Models\accountServiceProvider;
use App\Models\account_department;
use App\Models\account_main_account;
use Carbon\Carbon;
use Image;
use App\Models\account_dept_service_charge;



class accountServiceChargeController extends Controller
{

    function serviceChargeShow()
    {

        $accountServiceCharges = accountServiceCharge::join('account_service_providers', 'account_service_providers.id', '=', 'account_service_charges.service_provider_id')
            ->join('account_service_type', 'account_service_type.id', '=', 'account_service_providers.service_type_id')
            ->select('account_service_charges.*', 'account_service_providers.name', 'account_service_type.name as serviceTypeName')
            ->get();


        return view('accountspages.ServiceCharge.ServiceChargeShow', compact('accountServiceCharges'));
    }

    function serviceChargeAdd()
    {
        $accountServiceProviders = accountServiceProvider::all();
        $accountyServiceTypes = accountyServiceType::all();
        $account_departments = account_department::all();
        // $accountServiceCharge = accountServiceCharge::

        return view('accountspages.ServiceCharge.ServiceChargeStore', compact('accountyServiceTypes', 'accountServiceProviders', 'account_departments'));
    }

    function serviceChargeStore(Request $Request)
    {
        // return $Request;

        $Request->validate([
            'service_type_id' => 'required',
            'amount' => 'required|numeric',
            'payment_date' => 'required',
            'service_provider_id' => 'required',
            'month' => 'required',
        ]);

        $check = accountServiceCharge::where('service_type_id', $Request->service_type_id)
            ->where('month', $Request->month)
            ->where('service_provider_id', $Request->service_provider_id)
            ->get();

        if (count($check) > 0) {
            return back()->withInput()->with('error', 'Month already selected');;
        }


        if (($Request->id) == null) {
            $accountServiceCharge = new accountServiceCharge();
            $accountServiceCharge->service_type_id = $Request->service_type_id;
            $accountServiceCharge->user_id = Auth::user()->id;
            $accountServiceCharge->amount = $Request->amount;
            $accountServiceCharge->invoice_no = $Request->invoice_no;
            $accountServiceCharge->date = $Request->payment_date;
            $accountServiceCharge->service_provider_id  = $Request->service_provider_id;
            $accountServiceCharge->month = $Request->month;
            $accountServiceCharge->note = $Request->note;

            if ($Request->hasFile('image')) {

                $Request->validate([
                    'image' => 'mimes:jpeg,bmp,png',

                ]);

                $image = $Request->file('image');
                $image_name =  time() . '.' . $image->extension();
                $image->move("accountServiceCharge", $image_name);
                $accountServiceCharge->image = $image_name;
            }

            $accountServiceCharge->save();


            // store service chargre department wise
            $count = count($Request->departments);
            for ($i = 0; $i < $count; $i++) {

                if ($Request->dept_charge[$i] > 0) {

                    $account_dept_service_charge = new account_dept_service_charge();
                    $account_dept_service_charge->service_charge_id = $accountServiceCharge->id;
                    $account_dept_service_charge->dept_id = $Request->departments_id[$i];
                    $account_dept_service_charge->service_type_id = $Request->service_type_id;
                    $account_dept_service_charge->charge = $Request->dept_charge[$i];
                    $account_dept_service_charge->month = $Request->month . "-28";
                    $account_dept_service_charge->save();

                    // store data for accounts
                    $ExpenseAccount = new account_main_account();
                    $ExpenseAccount->debit = $Request->dept_charge[$i];
                    $ExpenseAccount->dept_id = $Request->acc_dept_id[$i];
                    $ExpenseAccount->date = $Request->month . "-28";
                    $ExpenseAccount->account_type = 5000;
                    $ExpenseAccount->description = "Service Charges";
                    $ExpenseAccount->connected_id = $account_dept_service_charge->id;
                    $ExpenseAccount->sub_category =$Request->service_type_id; //service charge
                    $ExpenseAccount->table_id = 7; //Expenses account
                    $ExpenseAccount->save();

                    $AccuredAccount = new account_main_account();
                    $AccuredAccount->credit = $Request->dept_charge[$i];
                    $AccuredAccount->dept_id = $Request->acc_dept_id[$i];
                    $AccuredAccount->date = $Request->month . "-28";
                    $AccuredAccount->account_type = 4000;
                    $AccuredAccount->description  = "Service Charges";
                    $AccuredAccount->connected_id = $account_dept_service_charge->id;
                    $AccuredAccount->table_id = 8; //Accured account
                    $AccuredAccount->sub_category = $Request->service_type_id; //service charge
                    $AccuredAccount->save();
                }
            }


            $actvity = 'New Deparment Create - ' . $Request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect('service-charge-show')->with('sucess', 'Record Successfully');
        } else {

            $accountServiceCharge = accountServiceCharge::find($Request->id);
            $accountServiceCharge->service_type_id = $Request->service_type_id;
            $accountServiceCharge->user_id = Auth::user()->id;
            $accountServiceCharge->amount = $Request->amount;
            $accountServiceCharge->invoice_no = $Request->invoice_no;
            $accountServiceCharge->date = $Request->payment_date;
            $accountServiceCharge->service_provider_id  = $Request->service_provider_id;
            $accountServiceCharge->month = $Request->month;

            if ($Request->hasFile('image')) {

                $Request->validate([
                    'image' => 'mimes:jpeg,bmp,png',
                ]);

                $image = $Request->file('image');
                $image_name =  time() . '.' . $image->extension();
                $image->move("accountServiceCharge", $image_name);
                $accountServiceCharge->image = $image_name;
            }


            $accountServiceCharge->note = $Request->note;
            $accountServiceCharge->save();

            // update service chargre department wise
            $count = count($Request->departments);
            for ($i = 0; $i < $count; $i++) {

                $account_dept_service_charge = account_dept_service_charge::find($Request->account_dept_service_charge_id[$i]);
                $account_dept_service_charge->service_charge_id = $Request->id;
                $account_dept_service_charge->dept_id = $Request->departments_id[$i];
                $account_dept_service_charge->service_type_id = $Request->service_type_id;
                $account_dept_service_charge->charge = $Request->dept_charge[$i];
                $account_dept_service_charge->month = $Request->month . "-01";
                $account_dept_service_charge->save();
            }

            $actvity = 'New Deparment Create - ' . $Request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect('service-charge-show')->with('sucess', 'Updated Successfully');
        }
    }

    function serviceChargeEdit($id)
    {
        $accountServiceProviders = accountServiceProvider::all();
        $accountyServiceTypes = accountyServiceType::all();
        $editDatas =  accountServiceCharge::find($id);

        $account_departments = account_dept_service_charge::where("service_charge_id", $id)
            ->join('inventory_departments', 'inventory_departments.id', '=', 'account_dept_service_charge.dept_id')
            ->select('account_dept_service_charge.*', 'inventory_departments.dept_name')
            ->get();
        // return $account_departments;

        return  view('accountspages.ServiceCharge.ServiceChargeEdit', compact('editDatas', 'accountServiceProviders', 'accountyServiceTypes', 'account_departments'));
    }

    function serviceChargeView($id)
    {
        $accountServiceProviders = accountServiceProvider::all();
        $accountyServiceTypes = accountyServiceType::all();
        $editDatas =  accountServiceCharge::find($id);


        $account_departments = account_dept_service_charge::where("service_charge_id", $id)
            ->join('inventory_departments', 'inventory_departments.id', '=', 'account_dept_service_charge.dept_id')
            ->select('account_dept_service_charge.*', 'inventory_departments.dept_name')
            ->get();
        return  view('accountspages.ServiceCharge.ServiceChargeView', compact('editDatas', 'accountServiceProviders', 'accountyServiceTypes', 'account_departments'));
    }
}
