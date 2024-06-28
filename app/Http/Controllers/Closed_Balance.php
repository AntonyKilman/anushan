<?php

namespace App\Http\Controllers;
use App\Models\ClosedBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Closed_Balance extends Controller
{
    public function ShowclosedBalance()
    {
        $AddcloseBlance=DB::table('closed_balance')
        ->select('closed_balance.*')
        ->get();
        return view('accountspages.closed_balanced',compact('AddcloseBlance'));
    }


    public function AddclosedBalance(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $request-> validate([
                'Date' => 'required|',
                'Amount' => 'required|',
                'Adjustment_Amount' => 'required|',
            ]);
    
            $closeBalance = new ClosedBalance();
            $closeBalance->date = $request->Date;
            $closeBalance->amount = $request->Amount;
            $closeBalance->adjustment_amount = $request->Adjustment_Amount;
            
            $closeBalance->save();
    
            return redirect()->route('Accounts.ShowclosedBalance')
                        ->with('success', 'Balance successfully Created!!');

        } else {

            $request-> validate([
                'Date' => 'required|',
                'Amount' => 'required|',
                'Adjustment_Amount' => 'required|',

            ]);
    
            $closeBalance = ClosedBalance::find($id);
            $closeBalance->date = $request->Date;
            $closeBalance->amount = $request->Amount;
            $closeBalance->adjustment_amount = $request->Adjustment_Amount;

            
            $closeBalance->save();
            return redirect()->route('Accounts.ShowclosedBalance')
            ->with('success', 'Balance successfully Updated!!');
        }
    }
}
