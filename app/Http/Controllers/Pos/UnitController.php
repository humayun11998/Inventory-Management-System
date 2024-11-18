<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;


class UnitController extends Controller
{
    // Function for All Unit

    public function UnitAll(){
        $units = Unit::latest()->get();
        return view('ims.unit.unit_all', compact('units'));
    }

    public function UnitAdd(){
        return view('ims.unit.unit_add');
    }


    public function UnitStore(Request $request){
        Unit::insert([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);
        $notification = [
            'message' => "Unit Inserted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('unit.all')->with($notification);

    }

    public function UnitEdit($id){
        $unit = Unit::findOrFail($id);
        return view('ims.unit.unit_edit', compact('unit'));
    }

    public function UnitUpdate(Request $request){
        $unitId = $request->id;

        Unit::findOrFail($unitId)->update([

            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);
        $notification = [
            'message' => "Unit Updated Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('unit.all')->with($notification);
    }

    public function UnitDelete($id){

        Unit::findOrFail($id)->delete();

        $notification = [
            'message' => "Unit Deleted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('unit.all')->with($notification);
    }


    
}
