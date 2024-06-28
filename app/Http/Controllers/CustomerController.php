<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    
    public function index()
    {
        $credit_customers=DB::table('credit_customers')
            ->select('customers.*',)
            ->leftJoin('customers', 'customers.id', 'credit_customers.customer_id')
            ->get();

        foreach ($credit_customers as $value) {
            $customer_id = $value->id;

            $total_credit_amount = DB::table('foodcity_sales')
                ->where('customer_type', 'Credit Customer')
                ->where('credit_payment', '>', 0)
                ->where('foodcity_sales.customer_id', $customer_id)
                ->value(DB::raw('IFNULL(sum(credit_payment),0)'));

            $total_pay_credit_amount = DB::table('foodcity_credit_payments as fcp')
                ->leftJoin('foodcity_sales as fc', 'fc.id', 'fcp.sales_id')
                ->where('fcp.customer_id', $customer_id)
                ->where('fc.customer_type', 'Credit Customer')
                ->value(DB::raw('IFNULL(sum(fcp.total_pay),0)'));

            

            $till_total_payable_amount = $total_credit_amount - $total_pay_credit_amount;

            $value->total_credit_amount = $total_credit_amount;
            $value->total_pay_credit_amount = $total_pay_credit_amount;
            $value->till_total_payable_amount = $till_total_payable_amount;
        }

        return view('sales.customer',compact('credit_customers'));
    }

    public function customer_create(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $request-> validate([
                'name' => 'required|',
                'phonenumber' =>'required',
                'Customer_Code' =>'required',
            ]);

            $haveCustomer = DB::table('customers')
                ->where('phone_number', 'like', '%' . substr($request->phonenumber, -9))
                ->value('id');
    
            if ($haveCustomer) {
                $customer = Customer::find($haveCustomer);
                $customer->name = $request->name;
                $customer->address = $request->address;
                $customer->customer_code = $request->Customer_Code;
                $customer->save();
            } else {
                $request-> validate([
                    'Customer_Code' =>'required|unique:customers,customer_code',
                ]);
                $customer = new Customer();
                $customer->name = $request->name;
                $customer->phone_number = $request->phonenumber;
                $customer->address = $request->address;
                $customer->customer_code = $request->Customer_Code;
                $customer->save();
            }
            
            $havethisCustomerCreate = DB::table('credit_customers')->where('customer_id', $customer->id)->value('id');

            if (!$havethisCustomerCreate) {
                DB::table('credit_customers')->insert(['customer_id' => $customer->id]);
            }
    
            return redirect()->route('customer.index')
                        ->with('success', 'Customer successfully Created!!');

        } else {

            $request-> validate([
                'name' => 'required|',
                'phonenumber' =>'required|unique:customers,phone_number,'.$id,
                'Customer_Code' => 'required|unique:customers,customer_code,'.$id,

            ]);
    
            $customer = Customer::find($id);
            $customer->name = $request->name;
            $customer->phone_number = $request->phonenumber;
            $customer->address = $request->address;
            $customer->customer_code = $request->Customer_Code;
            $customer->save();

            return redirect()->route('customer.index')
            ->with('success', 'Customer successfully Updated!!');
        }
    }


    public function customerTaleReport($id, Request $request)
    {
        $customer_id = $id;
        $year = isset($request->year) ? $request->year : date('Y');

        $beforeYearTotalCreditPayment = DB::table('foodcity_credit_payments')
            ->whereYear('date', '<', $year)
            ->where('customer_id', $customer_id)
            ->sum('total_pay');

        $beforeYearTotalCreditBills = DB::table('foodcity_sales')
            ->where('customer_type', 'Credit Customer')
            ->whereYear('billing_date', '<', $year)
            ->where('customer_id', $customer_id)
            ->where('credit_payment', '>', 0)
            ->sum('credit_payment');

        $Tali = DB::table('foodcity_credit_payments')
            ->select('date', DB::raw('sum(total_pay) as totalPayedAmount'))
            ->leftJoin('foodcity_sales as fc', 'fc.id', 'foodcity_credit_payments.sales_id')
            ->whereYear('foodcity_credit_payments.date', $year)
            ->where('foodcity_credit_payments.customer_id', $customer_id)
            ->where('fc.customer_type', 'Credit Customer')
            ->groupby('foodcity_credit_payments.date')
            ->orderBy('foodcity_credit_payments.date', 'asc')
            ->get();

        $beforeYearBlance = (double) $beforeYearTotalCreditBills - (double) $beforeYearTotalCreditPayment;
        $blancePay = $beforeYearBlance;
        $startDate = date('Y-m-d', strtotime('-1 day', strtotime( $year . "-01-01")));
        if (count($Tali) > 0) {
            foreach ($Tali as $value) {
                $endDate = $value->date;
    
                $bills = DB::table('foodcity_sales')
                    ->select('billing_date', 'invoice_no', 'credit_payment')
                    ->where('customer_type', 'Credit Customer')
                    ->whereDate('billing_date', '>', $startDate)
                    ->whereDate('billing_date', '<=', $endDate)
                    ->where('customer_id', $customer_id)
                    ->where('credit_payment', '>', 0)
                    ->orderBy('billing_date', 'asc')
                    ->get();
    
                $totalCreditAmount = $bills->sum('credit_payment');
    
                $blancePay += (double) $totalCreditAmount - (double) $value->totalPayedAmount;
    
                $value->bills = $bills;
                $value->totalCreditAmount = $totalCreditAmount;
                $value->blancePay = $blancePay;
    
                $startDate = $endDate;
            }
        } else {
            $endDate = date('Y-m-d', strtotime('1 year', strtotime( $startDate)));

            $arry = [];
    
            $bills = DB::table('foodcity_sales')
                ->select('billing_date', 'invoice_no', 'credit_payment')
                ->where('customer_type', 'Credit Customer')
                ->whereDate('billing_date', '>', $startDate)
                ->whereDate('billing_date', '<=', $endDate)
                ->where('customer_id', $customer_id)
                ->where('credit_payment', '>', 0)
                ->orderBy('billing_date', 'asc')
                ->get();
    
                $totalCreditAmount = $bills->sum('credit_payment');
    
                $blancePay += (double) $totalCreditAmount;
    
                $arry['bills'] = $bills;
                $arry['totalCreditAmount'] = $totalCreditAmount;
                $arry['blancePay'] = $blancePay;

                $arry = json_decode(json_encode($arry));

                $Tali = $Tali->toArray();
                array_push($Tali, $arry);
        }


        $customerDatails = DB::table('customers')
                    ->select('customers.*',)
                    ->where('id', $customer_id)
                    ->first();

        return view('sales.credit_bills_payment_details', compact('Tali','beforeYearBlance', 'year', 'customerDatails','customer_id'));
    }
}


