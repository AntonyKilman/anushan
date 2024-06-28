<?php

namespace App\Http\Controllers;

use App\Models\WorkType;
use Illuminate\Http\Request;

class WorkTypeController extends Controller
{
    // show 
    public function index()
    {
        $workType = WorkType::get();

        return view('hr.workType')
                ->with('workType', $workType);

    }

    //  create & update work type
    public function store(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) { // create

            $request->validate([
                'name' => 'required|unique:work_types,name',
            ]);
    
            $workType = new WorkType();
            $workType->name = $request->name;
            $workType->description = $request->description;
            $workType->save();

            $actvity = 'New Work Type Create - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
            return redirect()->route('worktype.index')
                        ->with('success', 'worktype successfully Create!!');

        } else { // update

            $request->validate([
                'name' => 'required|unique:work_types,name,'.$id,
            ]);
    
            $workType = WorkType::find($id);
            $workType->name = $request->name;
            $workType->description = $request->description;
            $workType->save();

            $actvity = 'Work Type Update - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
            return redirect()->route('worktype.index')
                        ->with('success', 'worktype successfully Updated!!');

        }

    }

}
