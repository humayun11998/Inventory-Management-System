<?php

namespace App\Http\Controllers\Pos;

use Carbon\Carbon;
use App\Models\payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\paymentDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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


    public function CreditCustomer(){

        $allData = Payment::whereIn('paid_status',['full_due','partial_paid'])->get();
        return view('ims.customer.customer_credit',compact('allData'));
    }

    public function CreditCustomerPdf(){
        $allData = Payment::whereIn('paid_status',['full_due','partial_paid'])->get();
        return view('ims.pdf.customer_credit_pdf',compact('allData'));
    }

    public function CustomerEditInvoice($Invoice_id){
        $payment = payment::where('Invoice_id',$Invoice_id)->first();
        return view('ims.customer.edit_customer_invoice',compact('payment'));

    }

    public function CustomerUpdateInvoice(Request $request, $Invoice_id){

        if($request->new_paid_amount < $request->paid_amount){

            $notification = [
                'message' => "Sorry You Paid Maximum Value.",
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification);

        }else{

            $payment = payment::where('Invoice_id', $Invoice_id)->first();
            $paymentDetails = new paymentDetail();
            $payment->paid_status = $request->paid_status;

            if($request->paid_status == 'full_paid'){
                $payment->paid_amount = payment::where('Invoice_id',$Invoice_id)
                ->first()['paid_amount']+$request->new_paid_amount;
                $payment->due_amount = '0';

                $paymentDetails->current_paid_amount = $request->new_paid_amount;
            }elseif($request->paid_status == 'partial_paid'){
                $payment->paid_amount = payment::where('Invoice_id',$Invoice_id)->first()['paid_amount']+$request->paid_amount;
                $payment->due_amount = payment::where('Invoice_id',$Invoice_id)->first()['due_amount']-$request->paid_amount;

                $paymentDetails->current_paid_amount = $request->paid_amount;

            }

            $payment->save();
            $paymentDetails->invoice_id = $Invoice_id;
            $paymentDetails->date = date('Y-m-d', strtotime($request->date));
            $paymentDetails->updated_by = Auth::user()->id;
            $paymentDetails->save();

            $notification = [
                'message' => "Invoice Updated Successfully",
                'alert-type' => 'success'
            ];

            return redirect()->route('credit.customer')->with($notification);


        }

    }


    public function CustomerInvoiceDetails($Invoice_id){
        $payment = payment::where('Invoice_id', $Invoice_id)->first();
        return view('ims.pdf.invoice_details_pdf', compact('payment'));

    }















}
