<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountsServiceType;


class AccountsServiceTypeController extends Controller
{
    public function serviceTypeShow()
    {
        $service_types = AccountsServiceType::all();
        return view('accountspages.ServiceType',compact('service_types'));
    }

    public function serviceTypeStore(Request $request)
    {
        $request-> validate([

            'name'=> 'required',
        ]);

        $service_types = new AccountsServiceType();
        $service_types->name = $request->name;
        $service_types->description = $request->description;
        $service_types->save();


        $actvity = 'New Deparment Create - '. $request->name;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
        return redirect('service-type-view')->with('success','Record Successfully');
        
    }

    public function serviceTypeUpdate(Request $request)
    {
        $request-> validate([

            'name'=> 'required',
        ]);

        $service_types = AccountsServiceType::find($request->id);
        $service_types->name = $request->name;
        $service_types->description = $request->description;
        $service_types->save();

        $actvity = 'New Deparment Create - '. $request->name;
        $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
    
        return redirect('service-type-view')->with('success','Update Successfully');
    }
    
}