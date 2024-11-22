<?php

namespace App\Http\Controllers\Pos;

use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Function for All Product

    public function ProductAll(){
        $product = Product::latest()->get();
        return view('ims.product.product_all', compact('product'));

    }

    public function ProductAdd(){
        $supplier = Supplier::all();
        $category = Category::all();
        $unit = Unit::all();
        return view('ims.product.product_add', compact('supplier','category','unit'));
    }

    public function ProductStore(Request $request){

        Product::insert([

            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'unit_id' => $request->unit_id,
            'category_id' => $request->category_id,
            'quantity' => '0',
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now()

        ]);

        $notification = [
            'message' => "Product Inserted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('product.all')->with($notification);


    }

    public function ProductEdit($id){
        $supplier = Supplier::all();
        $category = Category::all();
        $unit = Unit::all();
        $product = Product::findOrFail($id);
        return view('ims.product.product_edit', compact('supplier','category','unit','product'));

    }

    public function ProductUpdate(Request $request){
        $productId = $request->id;
        Product::findOrFail($productId)->update([

            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'unit_id' => $request->unit_id,
            'category_id' => $request->category_id,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now()

        ]);
        $notification = [
            'message' => "Product Updated Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('product.all')->with($notification);
    }

    public function ProductDelete($id){
        Product::findOrFail($id)->delete();

        $notification = [
            'message' => "Product Deleted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('product.all')->with($notification);
    }








}
