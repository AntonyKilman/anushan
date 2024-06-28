<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\accountyServiceType;
use App\Models\accountServiceProvider;
use App\Models\account_department;
use App\Models\accountServiceCharge;
use App\Models\account_dept_service_charge;
use App\Models\account_dept_service_expense;
use App\Models\account_service_expense_new;
use App\Models\account_main_account;
use Carbon\Carbon;

class newServiceExpenseController extends Controller
{

    function newServiceExpense()
    {
        $accountyServiceTypes = accountyServiceType::all();
        $accountServiceProvider = accountServiceProvider::all();
        $account_departments = account_department::all();
        $account_service_expense_new = account_service_expense_new::join('account_service_type', 'account_service_type.id', '=', 'account_service_expense_new.service_type_id')
            ->join('account_service_providers', 'account_service_providers.id', '=', 'account_service_expense_new.service_provider_id')
            ->select('account_service_expense_new.*', 'account_service_type.name', 'account_service_providers.name as provider')
            ->get();
        return view('accountspages.newServiceExpense', compact('accountyServiceTypes', 'accountServiceProvider', 'account_departments', 'account_service_expense_new'));
    }

    function getServiceExpenseAmount(Request $request)
    {
        $datas = accountServiceCharge::where('service_type_id', $request->serviceType)
            ->where('service_provider_id', $request->serviceProvider)
            ->where('month', $request->month)
            ->get()
            ->first();
        // return  $datas;

        if ($datas) {

            $account_dept_service_charge = account_dept_service_charge::where('service_charge_id', $datas->id)
                ->join('inventory_departments', 'inventory_departments.id', '=', 'account_dept_service_charge.dept_id')
                ->select('account_dept_service_charge.*', 'inventory_departments.dept_name', 'inventory_departments.acc_dept_id')
                ->get();

            $pays = account_service_expense_new::where('service_type_id', $request->serviceType)
                ->where('service_provider_id', $request->serviceProvider)
                ->where('month', $request->month)
                ->get();

            if (count($pays) > 0) {

                $myArr = [];
                foreach ($pays as $pay) {
                    array_push($myArr, $pay->id);
                }

                $dept_pays = account_dept_service_expense::whereIn('account_dept_service_expense.account_service_expense_new_id', $myArr)
                    ->groupBy('account_dept_service_expense.departments_id')
                    ->select(
                        'account_dept_service_expense.*',
                        DB::raw('SUM(account_dept_service_expense.dept_pay) as TotalDeptPay')
                    )
                    ->get();


                foreach ($account_dept_service_charge as $dept_charge) {

                    foreach ($dept_pays as $dept_pay) {

                        if ($dept_pay->departments_id == $dept_charge->dept_id) {
                            $dept_charge->TotalDeptPay = $dept_pay->TotalDeptPay;
                        }
                    }
                }

                return $account_dept_service_charge;
            } else {

                foreach ($account_dept_service_charge as $dept_charge) {
                    $dept_charge->TotalDeptPay = 0.00;
                }
                return $account_dept_service_charge;
            }
        } else {
            return response()->json(['message' => 'No Record']);
        }
    }


    function deptServiceExpenseAmount(Request $request)
    {

        $account_service_expense_new = new account_service_expense_new();
        $account_service_expense_new->service_type_id = $request->service_type_id;
        $account_service_expense_new->service_provider_id = $request->service_provider_id;
        $account_service_expense_new->month = $request->month;
        $account_service_expense_new->total_charge = $request->total_charge;
        $account_service_expense_new->cash = $request->cash;
        $account_service_expense_new->save();

        $count = count($request->departments_id);

        for ($i = 0; $i < $count; $i++) {
            if ($request->dept_pay[$i] > 0) {

                $data = new  account_dept_service_expense();
                $data->account_service_expense_new_id = $account_service_expense_new->id;
                $data->departments_id = $request->departments_id[$i];
                $data->dept_charge = $request->dept_charge[$i];
                $data->dept_pay = $request->dept_pay[$i];
                $data->save();

                $cashAccount = new account_main_account();
                $cashAccount->credit = $request->dept_pay[$i];
                $cashAccount->dept_id = $request->acc_dept_id[$i];
                $cashAccount->date = Carbon::now()->format('Y-m-d');
                $cashAccount->account_type = 4000;
                $cashAccount->description  = "Service Payment";
                $cashAccount->connected_id = $data->id;
                $cashAccount->table_id = 1; //cash account
                $cashAccount->category = 2; //this is service expense 1 is other expense
                $cashAccount->sub_category = $request->service_type_id; //service type
                $cashAccount->save();

                $accuredAccount = new account_main_account();
                $accuredAccount->debit = $request->dept_pay[$i];
                $accuredAccount->dept_id = $request->acc_dept_id[$i];
                $accuredAccount->date = Carbon::now()->format('Y-m-d');
                $accuredAccount->account_type = 4000;
                $accuredAccount->description  = "Service Payment";
                $accuredAccount->connected_id = $data->id;
                $accuredAccount->table_id = 8; //accured account
                $accuredAccount->category = 2; //this is service expense 1 is other expense
                $accuredAccount->sub_category = $request->service_type_id; //service type
                $accuredAccount->save();
            }
        }
        return redirect('/new-service-expense')->with('sucess', "Successfully Recorded");
    }
}
