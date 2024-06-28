<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\account_department;

class account_department_controller extends Controller
{

    function account_dept_show()
    {
        $account_departments = account_department::all();
        return view('accountspages.acc_dept', compact('account_departments'));
    }

    function account_dept_store(Request $request)
    {

        if (!($request->id)) {

            $request->validate([
                'name' => "required |unique:account_department,acc_dept_name",
            ]);

            $account_department = new account_department();
            $account_department->acc_dept_name = $request->name;
            $account_department->acc_dept_des = $request->description;
            $account_department->save();
            return redirect('/account_dept_show')->with('sucess', "Successfully Recorded");
        } else {

            $request->validate([
                'name' => "required |unique:account_department,acc_dept_name," . $request->id
            ]);

            $account_department = account_department::find($request->id);
            $account_department->acc_dept_name = $request->name;
            $account_department->acc_dept_des = $request->description;
            $account_department->save();
            return redirect('/account_dept_show')->with('sucess', "Successfully Updated");
        }
    }

    public function departmentSearch(Request $request)
    {
        $departments = account_department::query()
            ->where('inventory_departments.dept_name', 'LIKE', "%" . $request->search . "%")
            ->limit(5)
            ->get();
        return $departments;
    }
}
