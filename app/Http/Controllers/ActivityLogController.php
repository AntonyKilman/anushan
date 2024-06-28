<?php

namespace App\Http\Controllers;

use App\Models\Activity_log;
use Illuminate\Http\Request;
use DB;
use Auth;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($decription)
    {
        //
        $emp_id=Auth::user()->emp_id;
        $props="Inventory";
        $data = new Activity_log();
        $data->emp_id = $emp_id;
        $data->props = $props;
        $data->decription = $decription;
        $data->save(); 
        return true;
    }

    public function view(Request $request)
    {
        $from_date = isset($request->from_date) ? $request->from_date : null;
        $to_date = isset($request->to_date) ? $request->to_date : null;
        $property = isset($request->property) ? $request->property : null; 
        $key = isset($request->key) ? $request->key : "";


        $property_by = ['Hr', 'Inventory', 'Main', 'Restaurant'];


        $activity = DB::table('activity_logs as al')
            ->select('al.*', 'employees.f_name as emp_name')
            ->leftJoin('employees', 'employees.id', 'al.emp_id');
            if ($from_date) {
        $activity = $activity->whereDate('al.created_at', '>=', $from_date);
            }
            if ($to_date) {
        $activity = $activity->whereDate('al.created_at', '<=', $to_date);
            }
            if ($property) {
        $activity = $activity->where('al.props', '=', $property);
            }
            if ($key) {
        $activity = $activity->where(function($q) use($key){
                $q->where('al.decription', 'like', '%' . $key . '%')
                    ->orWhere('employees.f_name', 'like', '%' . $key . '%');
                });
            }
        $activity = $activity->orderBy('al.id', 'DESC')
        ->paginate(20);

        return view('hr.activityLogs', compact('activity', 'from_date', 'to_date', 'property', 'property_by', 'key'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity_log  $activity_log
     * @return \Illuminate\Http\Response
     */
    public function show(Activity_log $activity_log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activity_log  $activity_log
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity_log $activity_log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity_log  $activity_log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity_log $activity_log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity_log  $activity_log
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity_log $activity_log)
    {
        //
    }
}
