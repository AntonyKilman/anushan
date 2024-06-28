<?php

namespace App\Http\Controllers;

use App\Models\AccountsOtherIncomes;
use App\Models\employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AccountsOtherIncomesController extends Controller
{
    public function otherIncomeStore(Request $request)
    {

        $request-> validate([


            'date'=> 'required',
            'amount'=>'required|numeric',

        ]);

        $other_incomes= new AccountsOtherIncomes();
        $other_incomes->date=$request->date;
        $other_incomes->amount=$request->amount;
        $other_incomes->categeory_id=$request->Categeory_id;
        $other_incomes->description=$request->description;
        $other_incomes->save();  

        return redirect('other-income-view')->with('success','Record Successfully');

    
    }

    public function otherIncomeShow()
    {
        
        $other_incomes = AccountsOtherIncomes::select('accounts_other_incomes.*','accounts_other_incomes_categeory.categeory_name')
        ->leftJoin('accounts_other_incomes_categeory','accounts_other_incomes_categeory.id','accounts_other_incomes.categeory_id')
        ->get();

        $categeory = DB::table('accounts_other_incomes_categeory')
                    ->select('accounts_other_incomes_categeory.*')
                    ->get();


        return view('accountspages.OtherIncome',compact('other_incomes','categeory'));

    }

    public function otherIncomeEdit($id)
    {
        $other_incomes= AccountsOtherIncomes::find($id);
        return view('accountspages.OtherIncome',compact('other_incomes'));


        
    }

    public function otherIncomeUpdate(Request $request)
    {
        // return $request; 

        $request-> validate([

            
            'date'=> 'required',
            'amount'=>'required|numeric',

        ]);
        $other_incomes= AccountsOtherIncomes::find($request->id);
        $other_incomes->date=$request->date;
        $other_incomes->amount=$request->amount;
        $other_incomes->categeory_id=$request->Categeory_id;
        $other_incomes->description=$request->description;
        $other_incomes->save();

        return redirect('other-income-view')->with('success','Update Successfully');
    }

   
}
