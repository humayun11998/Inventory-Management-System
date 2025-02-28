@extends('admin.admin_master')
@section('admin')



<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Customer Payment Report</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="breadcrumb-item active">Customer Payment Report</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->




        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="invoice-title">
                                    <h4 class="float-end font-size-16"><strong>Invoice No # {{ $payment['invoice']['Invoice_no'] }}</strong></h4>
                                    <h3>
                                        <img src="{{ asset('ims/assets/images/logo-sm.png') }}" alt="logo" height="24"/> Easy Shopping Mall
                                    </h3>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6 mt-4">
                                        <address>
                                            <strong>Easy Shopping Mall:</strong><br>
                                            Karachi Pakistan<br>
                                            humayun@email.com
                                        </address>
                                    </div>
                                    <div class="col-6 mt-4 text-end">
                                        <address>
                                            <strong>Invoice Date:</strong><br>
                                            {{ date('d-m-Y', strtotime($payment['invoice']['date'])) }}<br><br>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>Customer Invoice   </strong></h3>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <td><strong>Customer Name</strong></td>
                                                    <td class="text-center"><strong>Customer Mobile</strong></td>
                                                    <td class="text-center"><strong>Customer Email</strong></td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                <tr>
                                                    <td>{{ $payment['customer']['name'] }}</td>
                                                    <td class="text-center">{{ $payment['customer']['mobile_no'] }}</td>
                                                    <td class="text-center">{{ $payment['customer']['email'] }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        {{-- <h3 class="font-size-16"><strong>Customer Invoice   </strong></h3> --}}
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Sl</th>
                                                        <th class="text-center">Category</th>
                                                        <th class="text-center">Product Name</th>
                                                        <th class="text-center">Current Stock</th>
                                                        <th class="text-center">Quantity</th>
                                                        <th class="text-center">Unit Price </th>
                                                        <th class="text-center">Total Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalSum = '0';
                                                        $invoiceDetails = App\Models\InvoiceDetail::where('Invoice_id',$payment->Invoice_id)
                                                        ->get()
                                                    @endphp
                                                       @foreach($invoiceDetails as $key => $details)
                                                       <tr>

                                                        <input type="hidden" name="category_id[]" value="{{ $details->category_id }}">
                                                        <input type="hidden" name="product_id[]" value="{{ $details->product_id }}">
                                                        <input type="hidden" name="selling_qty[{{ $details->id }}]" value="{{ $details->selling_qty }}">
                                                           <td class="text-center">{{ $key+1 }}</td>
                                                           <td class="text-center">{{ $details['category']['name'] }}</td>
                                                           <td class="text-center">{{ $details['product']['name'] }}</td>
                                                           <td class="text-center">{{ $details['product']['quantity'] }}</td>
                                                           <td class="text-center">{{ $details->selling_qty }}</td>
                                                           <td class="text-center">{{ $details->unit_price }}</td>
                                                           <td class="text-center">{{ $details->selling_price }}</td>
                                                       </tr>
                                                       @php
                                                           $totalSum += $details->selling_price;
                                                       @endphp
                                                       @endforeach
                                                       <tr>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line text-center">
                                                            <strong>Subtotal</strong></td>
                                                        <td class="thick-line text-end">Rs {{ $totalSum }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                         <td class="no-line"></td>
                                                          <td class="no-line"></td>
                                                           <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Discount Amount</strong></td>
                                                        <td class="no-line text-end">Rs {{ $payment->discount_amount }}</td>
                                                    </tr>
                                                     <tr>
                                                        <td class="no-line"></td>
                                                         <td class="no-line"></td>
                                                          <td class="no-line"></td>
                                                           <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Paid Amount</strong></td>
                                                        <td class="no-line text-end">Rs {{ $payment->paid_amount }}</td>
                                                    </tr>
                                                     <tr>
                                                        <td class="no-line"></td>
                                                         <td class="no-line"></td>
                                                          <td class="no-line"></td>
                                                           <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Due Amount</strong></td>
                                                        <td class="no-line text-end">Rs {{ $payment->due_amount }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="no-line"></td>
                                                         <td class="no-line"></td>
                                                          <td class="no-line"></td>
                                                           <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line text-center">
                                                            <strong>Grand Amount</strong></td>
                                                        <td class="no-line text-end"><h4 class="m-0">Rs {{ $payment->total_amount }}</h4></td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="7" style="text-align:center; font-weight:bold;">Paid Summery</td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="4" style="text-align:center; font-weight:bold;">Date</td>
                                                        <td colspan="3" style="text-align:center; font-weight:bold;">Amount</td>
                                                    </tr>
                                                    @php
                                                        $paymentDetail = App\Models\paymentDetail::where('Invoice_id', $payment->Invoice_id)
                                                        ->get();
                                                    @endphp

                                                    @foreach ($paymentDetail as $item)


                                                    <tr>
                                                        <td colspan="4" style="text-align:center; font-weight:bold;">{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                                        <td colspan="3" style="text-align:center; font-weight:bold;">{{ $item->current_paid_amount }}</td>
                                                    </tr>

                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <div class="d-print-none">
                                                                <div class="float-end">
                                                                    <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                                                    <a href="#" class="btn btn-primary waves-effect waves-light ms-2">Download</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                   </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>



@endsection
