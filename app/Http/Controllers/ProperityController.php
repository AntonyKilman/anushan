<?php

namespace App\Http\Controllers;

use App\Models\Properity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProperityController extends Controller
{
    //   show property 
    public function index()
    {
        $property = DB::table('properities')
                    ->select('*')
                    ->get();

        return view('hr.properity')
                    ->with('property', $property);


    }

    // create & update property
    public function store(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $request-> validate([
                'name' => 'required|unique:properities,name',
            ]);
    
            $properity = new Properity();
            $properity->name = $request->name;
            $properity->description = $request->description;
            $properity->status = 'Active';
            $properity->save();
    
            $code = 'Pro-0' . $properity->id;
    
            $properity->code = $code;
            $properity->save();

            $actvity = 'New Property Create - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
            return redirect()->route('property.index')
                        ->with('success', 'Property successfully Created!!');

        } else {

            $request-> validate([
                'name' => 'required|unique:properities,name,'.$id,
            ]);
    
            $properity = Properity::find($id);
            $properity->name = $request->name;
            $properity->description = $request->description;
            $properity->save();

            $actvity = 'Property Update - '. $request->name;
            $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
            return redirect()->route('property.index')
                        ->with('success', 'Property successfully Updated!!');

        }

    }

    // change status
    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        if ($status == "Active" || $status == "active") {
            $status = "Deactive";
        } else {
            $status = "Active";
        }
        
        $properity = Properity::find($id);
        $properity->status = $status;
        $properity->save();

        $actvity = 'Property Status Change - '. $properity->name;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);

        return 'Done';
        
    }

    

   
    public function destroy(Properity $properity)
    {
        //
    }
}
