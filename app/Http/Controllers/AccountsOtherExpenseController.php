<?php
namespace App\Http\Controllers;

use App\Models\accountBank;
use App\Models\AccountsOtherExpense;
use App\Models\employee;
use App\Models\accountBankBalance;
use App\Models\account_department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\accountChequePayment;
use App\Models\account_OtherExpensesType;
use Illuminate\Support\Facades\Auth;
use App\Models\account_main_account;
use App\Models\AccountOtherExpenswCategory;
use App\Models\Accounts_Sub_Categeory;
use App\Models\AddCategeory;
use Carbon\Carbon;


class AccountsOtherExpenseController extends Controller
{
    public function otherExpenseShow()
    {
        $oth_exp_types = Accounts_Sub_Categeory::all();
        $employees = employee::all();
        $accountBanks = accountBank::all();
        $departments = account_department::all();
        $categories = AddCategeory::all();
        $other_expenses_1 = AccountsOtherExpense::
            leftJoin('inventory_departments', 'inventory_departments.id', '=', 'accounts_other_expenses.oth_dep_id')
            ->leftJoin('add_categeory','add_categeory.id','accounts_other_expenses.categeory_id')
            ->leftJoin('add_sub_categeory', 'add_sub_categeory.id', '=', 'accounts_other_expenses.sub_categeory_id')
            ->select('accounts_other_expenses.*',  'inventory_departments.dept_name', 'add_sub_categeory.name as sub_name','add_categeory.name as cat_name')
            ->orderByDesc('accounts_other_expenses.id')
            ->get();
        return view('accountspages.OtherExpense', compact('other_expenses_1', 'employees', 'departments', 'accountBanks', 'oth_exp_types','categories'));
    }

    public function otherExpenseStore(Request $request)
    {
        // return $request;
        $id = $request->id; // id

        if ($id == 0) {
        $request->validate([
            'Categeory_id' => 'required',
            'Sub_Categeory_id' => 'required',
            'date' => 'required',
            'amount' => 'required',
        ]);

                $other_expenses = new AccountsOtherExpense();
                $other_expenses->sub_categeory_id = $request->Sub_Categeory_id;
                $other_expenses->categeory_id = $request->Categeory_id;
                $other_expenses->date = $request->date;
                $other_expenses->oth_exp_amount = $request->amount;
                $other_expenses->oth_exp_cash = $request->cash;
                $other_expenses->oth_exp_credit = $request->credit;
                $other_expenses->oth_exp_cheque = $request->cheque;
                $other_expenses->oth_exp_cheque_no = $request->cheque_no;
                $other_expenses->oth_exp_cheque_date = $request->cheque_date;
                $other_expenses->bank_id = $request->bank_id;
                $other_expenses->oth_exp_online = $request->oth_exp_online;
                $other_expenses->oth_exp_reference_no = $request->reference_no;
                $other_expenses->oth_exp_reason = $request->reason;
                $other_expenses->save();

        return redirect()->route('otherExpense.otherExpenseShow')
                        ->with('success', 'Successfully Created!!');
        
         } else {

                $other_expenses = AccountsOtherExpense::find($id);
                $other_expenses->sub_categeory_id = $request->Sub_Categeory_id;
                $other_expenses->categeory_id = $request->Categeory_id;
                $other_expenses->date = $request->date;
                $other_expenses->oth_exp_amount = $request->amount;
                $other_expenses->oth_exp_cash = $request->cash;
                $other_expenses->oth_exp_credit = $request->credit;
                $other_expenses->oth_exp_cheque = $request->cheque;
                $other_expenses->oth_exp_cheque_no = $request->cheque_no;
                $other_expenses->oth_exp_cheque_date = $request->cheque_date;
                $other_expenses->bank_id = $request->bank_id;
                $other_expenses->oth_exp_online = $request->oth_exp_online;
                $other_expenses->oth_exp_reference_no = $request->reference_no;
                $other_expenses->oth_exp_reason = $request->reason;
                $other_expenses->save();
                
                return redirect()->route('otherExpense.otherExpenseShow')
                        ->with('success', 'Successfully Updated!!');
    }

    }

    public function otherExpenseUpdate(Request $request)
    {

        $request->validate([
            'oth_exp_type_id' => 'required',
            'oth_dep_id' => 'required',
            'employee_id' => 'required',
            'date' => 'oth_dep_id',
            'date' => 'required',
            'oth_exp_amount' => 'required|numeric',
        ]);

        $other_expenses = AccountsOtherExpense::find($request->id);
        $other_expenses->oth_exp_type_id = $request->oth_exp_type_id;
        $other_expenses->employee_id = $request->employee_id;
        $other_expenses->oth_dep_id = $request->oth_dep_id;
        $other_expenses->date = $request->date;
        $other_expenses->oth_exp_amount = $request->oth_exp_amount;
        $other_expenses->oth_exp_cash = $request->oth_exp_cash;
        $other_expenses->oth_exp_credit = $request->oth_exp_credit;
        $other_expenses->oth_exp_cheque = $request->oth_exp_cheque;
        $other_expenses->oth_exp_cheque_no = $request->oth_exp_cheque_no;
        $other_expenses->oth_exp_cheque_date = $request->oth_exp_cheque_date;
        $other_expenses->bank_id = $request->bank_id;
        $other_expenses->oth_exp_online = $request->oth_exp_online;
        $other_expenses->oth_exp_reference_no = $request->oth_exp_reference_no;
        $other_expenses->oth_exp_reason = $request->oth_exp_reason;
        $other_expenses->oth_exp_description = $request->oth_exp_description;
        $other_expenses->oth_exp_reason = $request->oth_exp_reason;
        $other_expenses->save();

        $accountChequePayment = accountChequePayment::where('other_expense_id', $request->id)->first();
        if ($accountChequePayment) {
            $accountChequePayment->delete();
        }

        $accountBankBalance = accountBankBalance::where("other_expense_id", $request->id)->first();
        if ($accountBankBalance) {
            $accountBankBalance->delete();
        }

        if (!($request->oth_exp_cheque == null)) {
            $accountChequePayment = new accountChequePayment();
            $accountChequePayment->other_expense_id = $request->id;
            $accountChequePayment->credit = 0.00;
            $accountChequePayment->debit = $request->oth_exp_cheque;
            $accountChequePayment->cheque_no = $request->oth_exp_cheque_no;
            $accountChequePayment->cheque_date = $request->oth_exp_cheque_date;
            $accountChequePayment->date = $request->date;
            $accountChequePayment->updatedBy = Auth::user()->id;
            $accountChequePayment->status = 0; //pending
            $accountChequePayment->bank_id = $request->bank_id;
            $accountChequePayment->note = $request->oth_exp_description;
            $accountChequePayment->save();
        }

        if (!($request->oth_exp_online == null)) {
            $accountBankBalance = new accountBankBalance();
            $accountBankBalance->bank_id  = $request->bank_id;
            $accountBankBalance->credit  = 0.00;
            $accountBankBalance->debit  = $request->oth_exp_online;
            $accountBankBalance->other_expense_id = $request->id;
            $accountBankBalance->details  = $request->note;
            $accountBankBalance->date  = $request->date;
            $accountBankBalance->save();
        }

        $actvity = 'New Deparment Create - ' . $request->name;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
        return redirect('other-expenses-view')->with('success', 'Update Successfully');
    }

    public function otherExpenseViewByid($id)
    {

        $check = DB::table('accounts_other_expenses')
            ->where('accounts_other_expenses.id', '=', $id)
            ->get()
            ->first();


        if ($check->bank_id) {

            $other_expenses = DB::table('accounts_other_expenses')
                ->join('employees', 'employees.id', '=', 'accounts_other_expenses.employee_id')
                ->join('account__other_expenses_types', 'account__other_expenses_types.id', '=', 'accounts_other_expenses.oth_exp_type_id')
                ->join('account_department', 'account_department.id', '=', 'accounts_other_expenses.oth_dep_id')
                ->join('account_banks', 'account_banks.id', '=', 'accounts_other_expenses.bank_id')
                ->select('accounts_other_expenses.*', 'employees.f_name', 'account_banks.account_no', 'account_department.acc_dept_name', 'account__other_expenses_types.name')
                ->where('accounts_other_expenses.id', '=', $id)
                ->first();
        } else {


            $other_expenses = DB::table('accounts_other_expenses')
                ->join('employees', 'employees.id', '=', 'accounts_other_expenses.employee_id')
                ->join('account_department', 'account_department.id', '=', 'accounts_other_expenses.oth_dep_id')
                ->join('account__other_expenses_types', 'account__other_expenses_types.id', '=', 'accounts_other_expenses.oth_exp_type_id')
                ->select('accounts_other_expenses.*', 'employees.f_name', 'account_department.acc_dept_name', 'account__other_expenses_types.name')
                ->where('accounts_other_expenses.id', '=', $id)
                ->first();
            $other_expenses->account_no = "";
        }
        return view('accountspages.OtherExpenseView', ['other_expenses' => $other_expenses]);
    }

    public function otherExpenseCategory()
    {
        $datas = AccountOtherExpenswCategory::all();
        return view('accountspages.oth_exp_cat', compact('datas'));
    }

    public function otherExpenseCategoryStore(Request $request)
    {

        if ($request->id) {

            $request->validate([
                'name' => 'required|unique:account_other_expense_categories,category_name,' . $request->id,
            ]);

            $obj = AccountOtherExpenswCategory::find($request->id);
            $obj->category_name = $request->name;
            $obj->description = $request->description;
            $obj->save();
            return redirect('/other-expenses-category')->with('success', 'Update Successfully');
        } else {

            $request->validate([
                'name' => 'required|unique:account_other_expense_categories,category_name'
            ]);

            $obj = new AccountOtherExpenswCategory();
            $obj->category_name = $request->name;
            $obj->description = $request->description;
            $obj->save();
            return redirect('/other-expenses-category')->with('success', 'Created Successfully');
        }
    }
}
