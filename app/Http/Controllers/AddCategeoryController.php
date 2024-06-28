<?php

namespace App\Http\Controllers;
use App\Models\AddCategeory;
use App\Models\Accounts_Sub_Categeory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AddCategeoryController extends Controller
{
    public function Showcategory()
    {
        $Addcategory=DB::table('add_categeory')
        ->select('add_categeory.*')
        ->orderByDesc('add_categeory.id')
        ->get();
        return view('accountspages.add_category',compact('Addcategory'));
    }

    public function Addcategory(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $request-> validate([
                'name' => 'required|',
            ]);
    
            $categeory = new AddCategeory();
            $categeory->name = $request->name;
            
            $categeory->save();
    
            return redirect()->route('Accounts.Showcategory')
                        ->with('success', 'Categeory successfully Created!!');

        } else {

            $request-> validate([
                'name' => 'required|',
            ]);
    
            $categeory = AddCategeory::find($id);
            $categeory->name = $request->name;
            
            $categeory->save();
            return redirect()->route('Accounts.Showcategory')
            ->with('success', 'Categeory successfully Updated!!');
        }
    }

    public function Showsubcategory()
    {
        $AddSubcategory=DB::table('add_sub_categeory')
        ->select('add_sub_categeory.*','add_categeory.name as categeory_name')
        ->leftJoin('add_categeory','add_categeory.id','=','add_sub_categeory.categeory_id')
        ->orderByDesc('add_sub_categeory.id')
        ->get();

        $sub_categeory=DB::table('add_categeory')
        ->select('add_categeory.*')
        ->get();
        return view('accountspages.add_sub_category',compact('AddSubcategory','sub_categeory'));
    }

    public function AddSubcategory(Request $request)
    {
        $id = $request->id; // id

        if ($id == 0) {

            $request-> validate([
                'Categeory_id' => 'required|',
                'sub_name' => 'required|',
            ]);
    
            $sub_categeory = new Accounts_Sub_Categeory();
            $sub_categeory->categeory_id = $request->Categeory_id;
            $sub_categeory->name = $request->sub_name;
            
            $sub_categeory->save();
    
            return redirect()->route('Accounts.Showsubcategory')
                        ->with('success', 'Sub Categeory successfully Created!!');

        } else {

            $request-> validate([
                'Categeory_id' => 'required|',
                'sub_name' => 'required|',
            ]);
    
            $sub_categeory = Accounts_Sub_Categeory::find($id);
            $sub_categeory->categeory_id = $request->Categeory_id;
            $sub_categeory->name = $request->sub_name;
            
            $sub_categeory->save();
            return redirect()->route('Accounts.Showsubcategory')
            ->with('success', 'Sub Categeory successfully Updated!!');
        }
    }

}
