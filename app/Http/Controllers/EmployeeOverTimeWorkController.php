<?php

namespace App\Http\Controllers;

use App\Models\EmployeeOverTimeWork;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeOverTimeWorkController extends Controller
{
    
    // show
    public function index(Request $request)
    {
        if($request->get('month')){
            $month = date('Y-m', strtotime($request->get('month')));
        }else{
            $month = date("Y-m");
        }

        $salaryDateStart = (int) DB::table('hr_settings')->where('id', 1)->value('value');
        if (!$salaryDateStart) {
            $salaryDateStart = 27;
        }

        // salary month start date
        $salary_start_date = date("Y-m-" . $salaryDateStart, strtotime('-1 month', strtotime($month)));
        // salary month end date
        $salary_end_date = date("Y-m-d", strtotime('+1 month -1 day', strtotime($salary_start_date)));

        $emp_overtime_work = DB::table('hr_employee_over_time_works as heotw')
                            ->select('heotw.*', 'employees.f_name as emp_name')
                            ->leftJoin('employees', 'employees.id', 'heotw.emp_id')
                            ->whereDate('heotw.date', '>=', $salary_start_date)
                            ->whereDate('heotw.date', '<=', $salary_end_date)
                            ->orderByDesc('heotw.id')
                            ->get();

        return view('hr.employeeOvertimeWork', compact('emp_overtime_work', 'month'));

    }

    // create & Update
    public function store(Request $request)
    {

        $rules = array(
            'emp_id' => 'required',
        );

        $customMessages = [
            'emp_id.required' => 'Select One Employee',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {  // check back end validator

            return redirect()->back()->withErrors($validator)->withInput($request->all());
        
        } else {  

            $id = $request->id;  // id

            if ($id == 0) {  // create
                
                $emp_overtime_work = new EmployeeOverTimeWork();
                $emp_overtime_work->emp_id = $request->emp_id;
                $emp_overtime_work->date = $request->date;
                $emp_overtime_work->amount = $request->amount;
                $emp_overtime_work->save();

                $actvity = 'New Employee OverTime Work Create  Emp_id - '. $request->emp_id;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

                return redirect()->route('hr.overTimeWork.index')
                        ->with('success', 'OverTime Work successfully Created!!');


            } else {   // update
                
                $emp_overtime_work = EmployeeOverTimeWork::find($id);
                $emp_overtime_work->emp_id = $request->emp_id;
                $emp_overtime_work->date = $request->date;
                $emp_overtime_work->amount = $request->amount;
                $emp_overtime_work->save();

                $actvity = 'Employee OverTime Work Update  Emp_id - '. $request->emp_id;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

                return redirect()->route('hr.overTimeWork.index')
                        ->with('success', 'OverTime Work successfully Updated!!');

            }
            
        }
        
    }

}
