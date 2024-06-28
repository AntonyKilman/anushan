<?php

namespace App\Http\Controllers;

use App\Models\mukunthanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MukunthanController extends Controller
{
     public function index()
     {
        $mukunthan = DB::table('mukunthan_collection')
            ->select('mukunthan_collection.*')
            ->get();
        return view('mukunthan', compact('mukunthan'));
     }

     public function store(Request $request)
     {
        $id = $request->id; // id

        if ($id == 0) {

            $mukunthan = new mukunthanModel();
            $mukunthan->date = $request->date;
            $mukunthan->amount = $request->amount;

            $mukunthan->save();

            return redirect()->route('mukunthan.index')
                ->with('success', 'Collection successfully Created!!');
        } else {

            $mukunthan = mukunthanModel::find($id);
            $mukunthan->date = $request->date;
            $mukunthan->amount = $request->amount;

            $mukunthan->save();
            return redirect()->route('mukunthan.index')
                ->with('success', 'Collection successfully Updated!!');
        }
     }
}
