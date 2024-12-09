<?php

namespace App\Http\Controllers\Pos;

use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // Purchase All Function

    public function PurchaseAll(){
        $allData = Purchase::orderBy('date','desc')->orderBy('id','desc')->get();
        return view('ims.purchase.purchase_all', compact('allData'));
    }


    public function PurchaseAdd(){
        $supplier = Supplier::all();
        $category = Category::all();
        $unit     = Unit::all();
        return view('ims.purchase.purchase_add', compact('supplier','category','unit'));
    }

    public function PurchaseStore(Request $request){
        if($request->category_id == null){

            $notification = [
                'message' => "Sorry You do not select any item",
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification);
        }else{
            $countCategory = count($request->category_id);
            for($i = 0; $i < $countCategory; $i++){
                $purchase = new Purchase();
                $purchase->date = date('Y-m-d', strtotime($request->date[$i]));
                $purchase->purchase_no = $request->purchase_no[$i];
                $purchase->supplier_id = $request->supplier_id[$i];
                $purchase->category_id = $request->category_id[$i];
                $purchase->product_id = $request->product_id[$i];
                $purchase->buying_qty = $request->buying_qty[$i];
                $purchase->unit_price = $request->unit_price[$i];
                $purchase->buying_price = $request->buying_price[$i];
                $purchase->description = $request->description[$i];
                $purchase->created_by = Auth::user()->id;
                $purchase->status = '0';
                $purchase->save();

            }
        }


            $notification = [
                'message' => "Data Save Successfully",
                'alert-type' => 'success'
            ];

            return redirect()->route('purchase.all')->with($notification);



    }

    public function PurchaseDelete($id){
        Purchase::findOrFail($id)->delete();

        $notification = [
            'message' => "Purchase Item Deleted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);

    }

    public function PurchasePending(){
        $allData = Purchase::orderBy('date','desc')->orderBy('id','desc')->where('status', '0')->get();
        return view('ims.purchase.purchase_pending', compact('allData'));
    }

    public function PurchaseApprove($id){

        $purchase = Purchase::findOrFail($id);
        $product = Product::where('id', $purchase->product_id)->first();
        $purchase_qty = ((float)($purchase->buying_qty))+((float)($product->quantity));

        $product->quantity = $purchase_qty;

        if($product->save()){
            Purchase::findOrFail($id)->update([
                'status' => '1'
            ]);


        $notification = [
            'message' => "Status Approved Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('purchase.all')->with($notification);

        }

    }



















}
