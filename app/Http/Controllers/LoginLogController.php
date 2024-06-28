<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginLogController extends Controller
{
    
    // show
    // public function index()
    // {
    //     $login_log = DB::table('login_logs')
    //                 ->select('login_logs.*', 'employees.f_name as emp_name')
    //                 ->leftJoin('employees', 'employees.id', 'login_logs.emp_id')
    //                 ->orderBy('login_logs.id', 'DESC')
    //                 ->paginate(20);

    //     return view('page.loginLogs', compact('login_log'));

    // }

    // create
    public function store($status)
    {
        $emp_id=Auth::user()->emp_id;

        $properities="Inventory";

        $login_log = new LoginLog();
        $login_log->emp_id = $emp_id;
        $login_log->properities = $properities;
        $login_log->status = $status;
        $login_log->date_time = Carbon::now();
        $login_log->save();

        return true;

    }

    
    public function destroy(LoginLog $loginLog)
    {
        //
    }
}
