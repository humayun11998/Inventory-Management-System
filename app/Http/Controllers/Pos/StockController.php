<?php

namespace App\Http\Controllers\Pos;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    // Stock All Function

    public function StockReport(){
        $allData = Product::orderBy('supplier_id','ASC')->orderBy('category_id', 'asc')->get();
        return view('ims.stock.stock_report', compact('allData'));
    }

    public function StockReportPdf(){
        $allData = Product::orderBy('supplier_id','ASC')->orderBy('category_id', 'asc')->get();
        return view('ims.pdf.stock_report_pdf', compact('allData'));
    }

    public function StockSupplierWise(){
        $supplier = Supplier::all();
        $category = Category::all();

        return view('ims.stock.supplier_product_wise_report', compact('supplier', 'category'));
    }

    public function SupplierWisePdf(Request $request){
          $allData = Product::orderBy('supplier_id','ASC')->orderBy('category_id', 'asc')->where('supplier_id', $request->supplier_id)->get();
        return view('ims.pdf.supplier_wise_report__pdf', compact('allData'));
    }
    
    public function ProductWisePdf(Request $request){
        $product = Product::where('category_id', $request->category_id)->where('id', $request->product_id)->first();
        return view('ims.pdf.product_wise_report__pdf', compact('product'));
    }






















}
