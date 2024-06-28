<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\accountServiceProvider;
use App\Models\accountyServiceType;


class accountServiceProviderController extends Controller
{
    function serviceProviderShow(){
        $accountyServiceTypes = accountyServiceType::all();
        $accountServiceProviders = accountServiceProvider::join('account_service_type','account_service_type.id','=','account_service_providers.service_type_id')
        ->select('account_service_providers.*','account_service_type.name as serviceName')
        ->get();
        // return  $accountServiceProviders ;
        return view('accountspages.serviceProvider',compact('accountServiceProviders','accountyServiceTypes'));

    }

    function serviceProviderStore(Request $request){    

        if(($request->id)==null){
            $accountServiceProvider = new accountServiceProvider();
            $accountServiceProvider -> name = $request-> name;
            $accountServiceProvider -> account_no = $request-> account_no;
            $accountServiceProvider -> join_date = $request-> join_date;
            $accountServiceProvider -> contact_no = $request-> contact_no;
            $accountServiceProvider -> service_type_id  = $request-> service_type_id;
            $accountServiceProvider -> description = $request-> description;
            $accountServiceProvider -> save();

            $actvity = 'New Deparment Create - '. $request->name;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
        
            return redirect('service-provider-show')->with('sucess',"Successfully Recorded");
        }

        else{

            $accountServiceProvider = accountServiceProvider::find($request-> id);
            $accountServiceProvider -> name = $request-> name;
            $accountServiceProvider -> account_no = $request-> account_no;
            $accountServiceProvider -> join_date = $request-> join_date;
            $accountServiceProvider -> contact_no = $request-> contact_no;
            $accountServiceProvider -> service_type_id  = $request-> service_type_id;
            $accountServiceProvider -> description = $request-> description;
            $accountServiceProvider -> save();

            $actvity = 'New Deparment Create - '. $request->name;
                $a = app('App\Http\Controllers\ActivityLogController')->index($actvity);
        
            return redirect('service-provider-show')->with('sucess',"Successfully Updated");

        }

    }
}
