<?php

namespace App\Http\Controllers;

use App\Models\account_OtherExpensesType;
use App\Models\AccountOtherExpenswCategory;
use Illuminate\Http\Request;

class AccountOtherExpensesTypeController extends Controller
{

    public function index()
    {
        $otherExpenseTypes = account_OtherExpensesType::join('account_other_expense_categories','account_other_expense_categories.id','account__other_expenses_types.oth_exp_cat_id')
        ->select('account__other_expenses_types.*','account_other_expense_categories.category_name')
        ->get();
        $categories = AccountOtherExpenswCategory::all();
        return view('accountspages.oth_exp_type',compact('otherExpenseTypes','categories'));
    }

    public function store(Request $request)
    {
        if ($request->id==null) {
            $request->validate([
                'name' => 'required|unique:account__other_expenses_types,name',
            ]);
            $otherExpenseType = new account_OtherExpensesType();
            $otherExpenseType -> name = $request -> name;
            $otherExpenseType -> oth_exp_cat_id = $request -> oth_exp_sub_category;
            $otherExpenseType -> description = $request -> description;
            $otherExpenseType ->save();
            $actvity = 'Add New Other Expense Type , Other Expense Type :- '. $otherExpenseType->id;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
            return redirect('/other-expenses-type')->with('success','Successfully Recorded');
        } else {
            $otherExpenseType=account_OtherExpensesType::find($request->id);
            $otherExpenseType -> name = $request -> name;
            $otherExpenseType -> oth_exp_cat_id = $request -> oth_exp_sub_category;
            $otherExpenseType -> description = $request -> description;
            $otherExpenseType ->save();
            $actvity = 'Update otherExpenseType  , otherExpenseType :- '. $otherExpenseType->id;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
            return redirect('/other-expenses-type')->with('success','Successfully Updated');
        }
    }





}
