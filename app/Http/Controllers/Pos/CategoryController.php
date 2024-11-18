<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // Category All Function

    public function CategoryAll(){
        $category = Category::latest()->get();
        return view('ims.category.category_all', compact('category'));
    }

    public function CategoryAdd(){
        return view('ims.category.category_add');
    }


    public function CategoryStore(Request $request){
        Category::insert([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);
        $notification = [
            'message' => "Category Inserted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('category.all')->with($notification);
    }

    public function CategoryEdit($id){
        $category = Category::findOrFail($id);
        return view('ims.category.category_edit', compact('category'));
    }

    public function CategoryUpdate(Request $request){
        $categoryId = $request->id;

        Category::findOrFail($categoryId)->update([

            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);
        $notification = [
            'message' => "Category Updated Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('category.all')->with($notification);
    }

    public function CategoryDelete($id){
        Category::findOrFail($id)->delete();

        $notification = [
            'message' => "Category Deleted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('category.all')->with($notification);
    }













}
