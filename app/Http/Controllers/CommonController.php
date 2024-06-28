<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    
    // common search employee 
    public function commonSearchEmployee(Request $request)
    {
        $key_word = $request->get('query');  // key word

        $employees = DB::table('employees')
                        ->select('employees.id', 'employees.f_name', 'employees.emp_code', 'employees.department_id as department_id', 'departments.name as department_name', 'user_roles.id as role_id', 'user_roles.user_role as role_name')
                        ->leftJoin('departments as departments', 'departments.id', 'employees.department_id')
                        ->leftJoin('user_roles', 'user_roles.id', 'employees.role_id')
                        ->where('employees.status', 'Active')
                        ->whereNotIn('employees.id', [1,2,3])  // super admin , admin
                        ->where(function($q) use($key_word){
                            $q->where('employees.f_name', 'like', '%' . $key_word . '%')
                                ->orWhere('employees.emp_code', 'like', '%' . $key_word . '%')
                                ->orWhere('departments.name', 'like', '%' . $key_word . '%');
                        })
                        ->limit(30)
                        ->get();

        return $employees;

    }
}
