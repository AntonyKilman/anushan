<?php

namespace App\Http\Controllers;

use App\Models\Advance_Payment_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Advance_Payment_Controller extends Controller
{

    public function index(Request $request)
    {
        if($request->get('month')){
            $month = date('Y-m', strtotime($request->get('month')));
        }else{
            $month = date("Y-m");
        }

        $advancepayment = DB::table('advance_payments')
            ->select('advance_payments.*', 'customers.name', 'customers.phone_number')
            ->leftJoin('customers','customers.id', '=', 'advance_payments.coustomer_id')
            ->where(DB::raw('DATE_FORMAT(advance_payments.date, "%Y-%m")'), $month)
            ->orderByDesc('advance_payments.id')
            ->get();

        return view('sales.advance_payment', compact('advancepayment', 'month'));
    }


    public function addindex()
    {

    $addcustomername = DB::table('customers')
        ->select('customers.*')
        ->get();

    return view('sales.add_advance_payment', compact('addcustomername'));
    }

    public function add_advance_payment_create(Request $request)
    {

        $request->validate([
            'customer_id' => 'required|',
            'Amount' => 'required|',
            'Date' => 'required|',
            'product_id' => 'required|',
            'count' => 'required|',
        ]);

        $arry = [];
        foreach ($request->product_id as $key => $value) {
            $arry[$value] = $request->count[$key];
        }

        $advance_payment = new Advance_Payment_Model();
        $advance_payment->coustomer_id = $request->customer_id;
        $advance_payment->amount = $request->Amount;
        $advance_payment->date = $request->Date;
        $advance_payment->product_id = json_encode($arry);
        $advance_payment->save();

        return redirect()->route('advance_payment.index')
            ->with('success', 'Advance Payment successfully Created!!');
        
    }

    // view
    public function viewOrderProduct($id)
    {
        $details = DB::table('advance_payments')
                ->select('advance_payments.*', 'customers.name', 'customers.phone_number')
                ->leftJoin('customers','customers.id', '=', 'advance_payments.coustomer_id')
                ->where('advance_payments.id', $id)
                ->first();

        $orderProduct = json_decode($details->product_id);

        $arry = [];
        foreach ($orderProduct as $key => $value) {
            $productId = $key; 
            $count = $value; 

            $product = DB::table('inventory_products')
                            ->select('product_name', 'product_code')
                            ->where('id', $productId)
                            ->first();

            $body = [
                'product_name' => $product->product_name,
                'product_code' => $product->product_code,
                'count' => $count,
            ];

            array_push($arry, $body);
        }

        $details->array = $arry;

        return $details;
    }

    public function delete($id)
    {
        $advance_payment = Advance_Payment_Model::find($id);
        $advance_payment->delete();

        return $advance_payment;
    }

}
