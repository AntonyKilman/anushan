<?php

namespace App\Http\Controllers;

use App\Models\CommisionCustomerModel;
use App\Models\AddCommisionDetailsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommisionCustomerController extends Controller
{

    public function index()
    {

        $commisioncustomer = DB::table('commision_customer')
            ->select('commision_customer.*')
            ->get();
        return view('sales.commision_customer', compact('commisioncustomer'));
    }


    public function commision_customer_create(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $request->validate([
                'name' => 'required|',
                'phonenumber' => 'required|unique:commision_customer,phone_number',
            ]);

            $commisioncustomer = new CommisionCustomerModel();
            $commisioncustomer->name = $request->name;
            $commisioncustomer->phone_number = $request->phonenumber;

            $commisioncustomer->save();

            return redirect()->route('commisioncustomer.index')
                ->with('success', 'Customer successfully Created!!');
        } else {

            $request->validate([
                'name' => 'required|',
                'phonenumber' => 'required|unique:commision_customer,phone_number,' . $id,
            ]);

            $commisioncustomer = CommisionCustomerModel::find($id);
            $commisioncustomer->name = $request->name;
            $commisioncustomer->phone_number = $request->phonenumber;

            $commisioncustomer->save();
            return redirect()->route('commisioncustomer.index')
                ->with('success', 'Customer successfully Updated!!');
        }
    }

    public function addCommision()
    {
        $addcommisiondetails = DB::table('add_commision')
            ->select('add_commision.*', 'foodcity_sales.invoice_no', 'commision_customer.name')
            ->leftJoin('foodcity_sales', 'foodcity_sales.id', '=', 'add_commision.sales_id')
            ->leftJoin('commision_customer', 'commision_customer.id', '=', 'add_commision.commision_cust_id')
            ->orderByDesc('add_commision.id')
            ->get();

        return view('sales.add_commission', compact('addcommisiondetails'));
    }

    public function add_commision_details(Request $request)
    {

        $id = $request->id; // id

        if ($id == 0) {

            $request->validate([
                'sales_id' => 'required',
                'commision_cust_id' => 'required',
            ]);

            $addcommisiondetails = new AddCommisionDetailsModel();
            $addcommisiondetails->commision_cust_id = $request->commision_cust_id;
            $addcommisiondetails->sales_id = $request->sales_id;
            $addcommisiondetails->date = $request->date;
            $addcommisiondetails->commision_amount = $request->camount;
            $addcommisiondetails->total_amount = $request->tamount;

            $addcommisiondetails->save();

            return redirect()->route('addcommision.addCommision')
                ->with('success', 'Details successfully Created!!');
        } else {

            $request->validate([
                'sales_id' => 'required',
                'commision_cust_id' => 'required',
            ]);

            $addcommisiondetails = AddCommisionDetailsModel::find($id);
            $addcommisiondetails->commision_cust_id = $request->commision_cust_id;
            $addcommisiondetails->sales_id = $request->sales_id;
            $addcommisiondetails->date = $request->date;
            $addcommisiondetails->commision_amount = $request->camount;
            $addcommisiondetails->total_amount = $request->tamount;

            $addcommisiondetails->save();
            return redirect()->route('addcommision.addCommision')
                ->with('success', 'Details successfully Updated!!');
        }
    }

    // search sales
    public function searchSales(Request $request)
    {
        $key = $request->q;

        $commisionfoodcitydetails = DB::table('foodcity_sales')
            ->select('id', 'invoice_no', 'billing_date', 'amount')
            ->where('invoice_no', 'like', '%' . $key . '%')
            ->orWhere('billing_date', 'like', '%' . $key . '%')
            ->orderByDesc('id')
            ->limit(30)
            ->get();

        return $commisionfoodcitydetails;
    }

    public function searchCommisionCustomers(Request $request)
    {
        $key = $request->c;

        $commisioncustomerdetails = DB::table('commision_customer')
            ->select('id', 'name', 'phone_number')
            ->where('name', 'like', '%' . $key . '%')
            ->orWhere('phone_number', 'like', '%' . $key . '%')
            ->limit(30)
            ->get();

        return $commisioncustomerdetails;
    }
}
