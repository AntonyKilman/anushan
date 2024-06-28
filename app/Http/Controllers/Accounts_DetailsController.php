<?php

namespace App\Http\Controllers;

use App\Models\AddCategeory;
use App\Models\Accounts_Sub_Categeory;
use App\Models\Accounts_Details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Accounts_DetailsController extends Controller
{
    public function index()
    {

        $Account_details=DB::table('accounts_details')
        ->select('accounts_details.*','add_sub_categeory.name as sub_name','add_categeory.name as cat_name')
        ->leftJoin('add_categeory','add_categeory.id','=','accounts_details.categeory_id')
        ->leftJoin('add_sub_categeory','add_sub_categeory.id','=','accounts_details.sub_categeory_id')
        ->get();

        $add_categeory=DB::table('add_categeory')
        ->select('add_categeory.*','add_categeory.name as cat_name')
        ->get();

        $sub_categeory=DB::table('add_sub_categeory')
        ->select('add_sub_categeory.*','add_sub_categeory.name as sub_name')
        ->get();

        return view('accountspages.accounts_details',compact('Account_details','sub_categeory','add_categeory'));
    }

    public function AddAccountsDeatils(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

           
    
            $Accounts_Details = new Accounts_Details();
            $Accounts_Details->Categeory_id = $request->Categeory_id;
            $Accounts_Details->sub_categeory_id = $request->Sub_Categeory_id;
            $Accounts_Details->amount = $request->amount;
            $Accounts_Details->date = $request->date;
            
            $Accounts_Details->save();
    
            return redirect()->route('Accounts.index')
                        ->with('success', 'Accounts Details successfully Created!!');

        } else {

            
            $Accounts_Details = Accounts_Details::find($id);
            $Accounts_Details->Categeory_id = $request->Categeory_id;
            $Accounts_Details->sub_categeory_id = $request->Sub_Categeory_id;
            $Accounts_Details->amount = $request->amount;
            $Accounts_Details->date = $request->date;
            
            $Accounts_Details->save();
            return redirect()->route('Accounts.index')
            ->with('success', 'Accounts Details successfully Updated!!');
        }
    }
}
