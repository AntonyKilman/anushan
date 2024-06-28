<?php

namespace App\Http\Controllers;

use App\Models\AccountsOtherIncomeCategeoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountsOtherIncomesCategeoryController extends Controller
{
    public function otherIncomeCategeoryShow()
    {
        $categeory = DB::table('accounts_other_incomes_categeory')
            ->select('accounts_other_incomes_categeory.*')
            ->get();
        return view('accountspages.categeory', compact('categeory'));
    }

    public function otherIncomecategeoryStore(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $categeory = new AccountsOtherIncomeCategeoryModel();
            $categeory->categeory_name = $request->categeory_name;

            $categeory->save();

            return redirect()->route('otherincome.otherIncomeCategeoryShow')
                ->with('success', ' successfully Created!!');
        } else {

            $categeory = AccountsOtherIncomeCategeoryModel::find($id);
            $categeory->categeory_name = $request->categeory_name;

            $categeory->save();
            return redirect()->route('otherincome.otherIncomeCategeoryShow')
                ->with('success', ' successfully Updated!!');
        }
    }
}
