<?php

namespace App\Http\Controllers;

use App\Models\AddCategeory;
use App\Models\Accounts_Sub_Categeory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountsDeatailReportController extends Controller
{

    function DetailsReport(Request $request)
    {

        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
        } else {
            $from = now()->format('Y-m-d');
            $to = now()->format('Y-m-d');
        }

        $categeoryId = $request->categeoryId;
        $subcategeoryId = $request->subcategeoryId;


        $accountsDetailsReport = DB::table('accounts_details')
            ->select('accounts_details.*', 'add_categeory.name as categeory_name', 'add_sub_categeory.name as subcategeory_name')
            ->leftJoin('add_categeory', 'add_categeory.id', '=', 'accounts_details.Categeory_id')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', '=', 'accounts_details.sub_categeory_id')
            ->whereBetween(DB::raw('DATE_FORMAT(accounts_details.created_at,"%Y-%m-%d")'), [$from, $to]);
        if ($categeoryId) {
            $accountsDetailsReport = $accountsDetailsReport->where('accounts_details.Categeory_id', $categeoryId);
        }
        if ($subcategeoryId) {
            $accountsDetailsReport = $accountsDetailsReport->where('accounts_details.sub_categeory_id', $subcategeoryId);
        }
        $accountsDetailsReport = $accountsDetailsReport->get();

        $categeory = DB::table('add_categeory')
            ->select('add_categeory.*')
            ->get();

        $subcategeory = DB::table('add_sub_categeory')
            ->select('add_sub_categeory.*')
            ->get();

        return view('accountspages.accounts_details_report', compact('accountsDetailsReport', 'from', 'to', 'categeory', 'categeoryId', 'subcategeory', 'subcategeoryId'));
    }

    public function profitlossReport(Request $request)
    {

        $to = isset($request->to) ? $request->to : Carbon::now()->format('Y-m-d');
        $from = isset($request->from) ? $request->from : Carbon::now()->format('Y-m-d');

        $sales = DB::table('accounts_details')
            // ->where('date', '>=', $from)
            // ->where('date', '<=', $to)
            ->whereDate('accounts_details.date', '>=', $from)
            ->whereDate('accounts_details.date', '<=', $to)
            ->get();

        $salesamount = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 10)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $opening_stockamount = 0.00;

        $purchaseamount = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 11)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $closing_stockamount = 0.00;

        $other_income_amount = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 12)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $administrative_expenses = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 13)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $selling_distribution_expenses = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 14)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $financial_expenses = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 15)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        return view('accountspages.acc_profit_loss_report', compact(
            'salesamount',
            'to',
            'from',
            'purchaseamount',
            'opening_stockamount',
            'closing_stockamount',
            'other_income_amount',
            'administrative_expenses',
            'selling_distribution_expenses',
            'financial_expenses'
        ));
    }

    public function balancesheetReport()
    {
        $to = Carbon::now()->format('Y-m-d');
        $from = Carbon::now()->subMonths(12)->format('Y-m-d');

        $property_plants_equipment = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 1)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $fixdeposits = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 2)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $final_stock = 0.00;

        $debtors = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 3)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $cash_at_bank = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 4)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $cash_in_hand = 0.00;

        $mugunthan = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 5)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $bank_loan = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 6)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $leasing = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 7)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $creditors = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 8)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        $accrued_expenses = DB::table('accounts_details')
            ->where('accounts_details.Categeory_id', 9)
            ->value(DB::raw('SUM(accounts_details.amount)'));

        return view('accountspages.Balance_sheet', compact(
            'to',
            'from',
            'property_plants_equipment',
            'final_stock',
            'debtors',
            'fixdeposits',
            'cash_at_bank',
            'cash_in_hand',
            'mugunthan',
            'bank_loan',
            'leasing',
            'creditors',
            'accrued_expenses'
        ));
    }
}
