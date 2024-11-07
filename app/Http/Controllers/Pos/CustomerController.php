<?php

namespace App\Http\Controllers\Pos;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // Function for All Customer

    public function CustomerAll(){
        $customer = Customer::latest()->get();
        return view('ims.customer.customer_all', compact('customer'));
    }

    public function CustomerAdd(){
        return view('ims.customer.customer_add');
    }

    public function CustomerStore(Request $request){

        if($request->file('customer_image')){

            $manager = new ImageManager(new Driver());

            $nameGen = hexdec(uniqid()) . '.' . $request->file('customer_image')
            ->getClientOriginalExtension();

            $image = $manager->read($request->file('customer_image'));

            $image = $image->resize(200,200);
            $image->toJpeg(80)->save(public_path('upload/customer/' . $nameGen));

            $saveUrl = 'upload/customer/' . $nameGen;

            Customer::insert([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'address' => $request->address,
                'customer_image' => $saveUrl,
                'created_by' => Auth::user()->id,
                'created_at' => Carbon::now()

            ]);

        } // End If Condition



        $notification = [
            'message' => "Customer Inserted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('customer.all')->with($notification);

    }


    public function CustomerEdit($id){
        $customer = Customer::findOrFail($id);
        return view('ims.customer.customer_edit', compact('customer'));
    }


    public function CustomerUpdate(Request $request){
        $customerId = $request->id;

        if($request->file('customer_image')){

            $manager = new ImageManager(new Driver());

            $nameGen = hexdec(uniqid()) . '.' . $request->file('customer_image')
            ->getClientOriginalExtension();

            $image = $manager->read($request->file('customer_image'));

            $image = $image->resize(200,200);
            $image->toJpeg(80)->save(public_path('upload/customer/' . $nameGen));

            $saveUrl = 'upload/customer/' . $nameGen;

            Customer::findOrFail($customerId)->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'address' => $request->address,
                'customer_image' => $saveUrl,
                'created_by' => Auth::user()->id,
                'created_at' => Carbon::now()

            ]);


        $notification = [
            'message' => "Customer Updated With Image Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('customer.all')->with($notification);


        }else{
            Customer::findOrFail($customerId)->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'address' => $request->address,
                'created_by' => Auth::user()->id,
                'created_at' => Carbon::now()

            ]);


        $notification = [
            'message' => "Customer Updated Without Image Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('customer.all')->with($notification);

        }


    }



    public function CustomerDelete($id){

        $customer = Customer::findOrFail($id);
        $image = $customer->customer_image;
        unlink($image);

        Customer::findOrFail($id)->delete();
        
        $notification = [
            'message' => "Customer Deleted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('customer.all')->with($notification);
    }




}
