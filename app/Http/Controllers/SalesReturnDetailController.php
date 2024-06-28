<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\FoodcitySalesReturnDetails;

use Carbon\Carbon;

class SalesReturnDetailController extends Controller
{
    ///for view sales return details
    public function showFoodcitySalesReturnDetails(Request $request)
    {
        if ($request->from) {
            $from = $request->from;
        } else {
            $from = date('Y-m-d');
        }

        $salesReturns = DB::table('foodcity_sales_return_details')
            ->select('foodcity_sales.invoice_no', 'foodcity_sales_return_details.*')
            ->join('foodcity_sales', 'foodcity_sales_return_details.sales_id', '=', 'foodcity_sales.id')
            ->whereDate('foodcity_sales_return_details.return_date', $from)
            ->latest()
            ->get();

        return view('sales.sales-return-details', compact('salesReturns', 'from'));
    }

    public function updatePaymentStatus(Request $request)
    {
        $salesReturn = FoodcitySalesReturnDetails::find($request->id);
        $salesReturn->cash = $request->input('payment_status');
        $salesReturn->save();
        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }
}