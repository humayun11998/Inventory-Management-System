<?php

namespace App\Http\Controllers\Pos;

use App\Models\Unit;
use App\Models\Invoice;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Models\payment;
use App\Models\paymentDetail;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    // Function for All Invoice

    public function InvoiceAll(){
        $allData = Invoice::orderBy('date','desc')->orderBy('id','desc')->get();
        return view('ims.invoice.invoice_all', compact('allData'));
    }


    public function InvoiceAdd(){

        $category = Category::all();
        $customer = Customer::all();
        $invoiceData = Invoice::orderBy('id','desc')->first();
        if($invoiceData == null){
            $firstReg = '0';
            $invoice_no = $firstReg + 1;
        }else{
            $invoiceData = Invoice::orderBy('id','desc')->first()->Invoice_no;
            $invoice_no = $invoiceData + 1;
        }

        $date = date('Y-m-d');
        return view('ims.invoice.invoice_add', compact('invoice_no','category','date','customer'));

    }

    public function InvoiceStore(Request $request){

        if($request->category_id == null){
            $notification = [
                'message' => "Sorry You do not select any item.",
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification);

        }else{
            if($request->paid_amount > $request->estimated_amount){
                $notification = [
                    'message' => "Sorry Paid Amount is Maximum the total price.",
                    'alert-type' => 'error'
                ];

                return redirect()->back()->with($notification);
            }else{

                $invoice = new Invoice();
                $invoice->invoice_no = $request->invoice_no;
                $invoice->date =  date('Y-m-d',strtotime($request->date));
                $invoice->description = $request->description;
                $invoice->status = '0';
                $invoice->created_by = Auth::user()->id;

                DB::transaction(function() use($request,$invoice){
                    if($invoice->save()){
                        $countCategory = count($request->category_id);
                        for($i=0; $i < $countCategory; $i++){
                            $invoiceDetails = new InvoiceDetail();
                            $invoiceDetails->date =  date('Y-m-d',strtotime($request->date));
                            $invoiceDetails->invoice_id = $invoice->id;
                            $invoiceDetails->category_id = $request->category_id[$i];
                            $invoiceDetails->product_id = $request->product_id[$i];
                            $invoiceDetails->selling_qty = $request->selling_qty[$i];
                            $invoiceDetails->unit_price = $request->unit_price[$i];
                            $invoiceDetails->selling_price = $request->selling_price[$i];
                            $invoiceDetails->status = '1';
                            $invoiceDetails->save();
                        }

                        if($request->customer_id == '0'){
                            $customer = new Customer();
                            $customer->name = $request->name;
                            $customer->mobile_no = $request->mobile_no;
                            $customer->email = $request->email;
                            $customer->save();
                            $customerId = $customer->id;
                        }else{
                             $customerId = $request->customer_id;
                        }

                        $payment = new payment();
                        $paymentDetails = new paymentDetail();

                        $payment->invoice_id = $invoice->id;
                        $payment->customer_id = $customerId;
                        $payment->paid_status = $request->paid_status;
                        $payment->discount_amount = $request->discount_amount;
                        $payment->total_amount = $request->estimated_amount;

                        if($request->paid_status == 'full_paid'){
                            $payment->paid_amount = $request->estimated_amount;
                            $payment->due_amount = '0';
                            $paymentDetails->current_paid_amount = $request->estimated_amount;

                        }elseif($request->paid_status == 'full_due'){
                            $payment->paid_amount = '0';
                            $payment->due_amount = $request->estimated_amount;
                            $paymentDetails->current_paid_amount = '0';

                        }elseif($request->paid_status == 'partial_paid'){
                            $payment->paid_amount = $request->paid_amount;
                            $payment->due_amount  = $request->estimated_amount - $request->paid_amount;
                            $paymentDetails->current_paid_amount = $request->paid_amount;
                        }
                        $payment->save();

                        $paymentDetails->invoice_id = $invoice->id;
                        $paymentDetails->date =  date('Y-m-d',strtotime($request->date));
                        $paymentDetails->save();
                    }

                });




            }

        } // End else

        $notification = [
            'message' => "Invoice Data Inserted Successfully",
            'alert-type' => 'success'
        ];

        return redirect()->route('invoice.all')->with($notification);

    }



















}
