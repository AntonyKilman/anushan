<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;


class DailyAdjustmentReportController extends Controller
{
    public function ShowDailyCashReport(Request $req)
    {
      $to = isset($req->to) ? $req->to : Carbon::now()->format('Y-m-d');
      $from = isset($req->from) ? $req->from : Carbon::now()->format('Y-m-d');


        $AdjustmentCash=DB::table('closed_balance')
        ->whereDate('closed_balance.created_at', '>=',  $from)
        ->whereDate('closed_balance.created_at', '<=', $to)
        ->value( DB::raw('SUM(closed_balance.adjustment_amount) as adjustment_cash'));

        $CloseCash=DB::table('closed_balance')
        ->whereDate('closed_balance.created_at', '>=',  $from)
        ->whereDate('closed_balance.created_at', '<=', $to)
        ->value( DB::raw('SUM(closed_balance.amount) as close_cash'));

        if (isset($req->dailyadjustmentamount)) {
          $Diffrence=($AdjustmentCash-$CloseCash);
  
          return $Diffrence;
        }

      
      $Diffrence=($AdjustmentCash-$CloseCash);

    return view('accountspages.DailyAdjustmentReport',compact('to','from','AdjustmentCash','CloseCash','Diffrence'));
    }

}
