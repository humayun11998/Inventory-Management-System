<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    // Default All Function

    public function GetCategory(Request $request){

        $supplierId = $request->supplier_id;
        $allCategory = Product::with(['category'])
        ->select('category_id')
        ->where('supplier_id', $supplierId)
        ->groupBy('category_id')
        ->get();

        return response()->json($allCategory);

    }
    public function GetProduct(Request $request){

        $categoryId = $request->category_id;
        $allProduct = Product::where('category_id', $categoryId)
        ->get();

        return response()->json($allProduct);

    }
}
