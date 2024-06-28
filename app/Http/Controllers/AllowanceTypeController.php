<?php

namespace App\Http\Controllers;

use App\Models\AllowanceType;
use Illuminate\Http\Request;

class AllowanceTypeController extends Controller
{
    
    // show
    public function index()
    {
        $allowance_type = AllowanceType::orderByDesc('id')->get();

        return view('hr.allowanceType', compact('allowance_type'));
    }

    // create & update
    public function store(Request $request)
    {
        $id  = $request->id;  // id

        if ($id == 0) {  // create
            
            $allowance_type = new AllowanceType();
            $allowance_type->name = $request->name;
            $allowance_type->description = $request->description;
            $allowance_type->save();

            $actvity = 'New Allowance  Type Create  Name - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect()->route('hr.allowanceType.index')
                        ->with('success', 'Allowance  Type successfully Created!!');

        } else {  // update
            
            $allowance_type = AllowanceType::find($id);
            $allowance_type->name = $request->name;
            $allowance_type->description = $request->description;
            $allowance_type->save();

            $actvity = 'Allowance  Type Update  Name - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

            return redirect()->route('hr.allowanceType.index')
                        ->with('success', 'Allowance  Type successfully Updated!!');

        }
        
    }

    
    public function destroy(AllowanceType $allowanceType)
    {
        //
    }
}
