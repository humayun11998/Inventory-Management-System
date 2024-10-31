<?php

namespace App\Http\Controllers\Pos;

use Carbon\Carbon;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    // Function for all Supplier

    public function SuppliersAll(){
        $suppliers = Supplier::latest()->get();
        return view('ims.supplier.supplier_all', compact('suppliers'));
    }

    public function SuppliersAdd(){
        return view('ims.supplier.supplier_add');
    }

    public function SupplierStore(Request $request){
        Supplier::insert([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);
        $notification = [
            'message' => "Supplier Inserted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('suppliers.all')->with($notification);

    }

    public function SuppliersEdit($id){
        $suppliers = Supplier::findOrFail($id);
        return view('ims.supplier.supplier_edit', compact('suppliers'));
    }

    public function SupplierUpdate(Request $request){
        $suppliersId = $request->id;
        Supplier::findOrFail($suppliersId)->update([

            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);
        $notification = [
            'message' => "Supplier Updated Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('suppliers.all')->with($notification);
    }

    public function SuppliersDelete($id){
        Supplier::findOrFail($id)->delete();

        $notification = [
            'message' => "Supplier Deleted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('suppliers.all')->with($notification);
    }




}
