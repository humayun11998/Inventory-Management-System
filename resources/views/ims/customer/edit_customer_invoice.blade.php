@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>



<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Customer Invoice</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="breadcrumb-item active">Customer Invoice</li>
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
                                    <h3>
                                        <img src="{{ asset('ims/assets/images/logo-sm.png') }}" alt="logo" height="24"/> Easy Shopping Mall
                                    </h3>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <a href="{{ route('credit.customer') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;"><i class="fa fa-list"> Back</i>   </a> <br>  <br>
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>Customer Invoice (Invoice No: # {{ $payment['invoice']['Invoice_no'] }})</strong></h3>
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
                                <form method="POST" action="{{ route('customer.update.invoice', $payment->Invoice_id) }}">
                                    @csrf

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
                                                        $invoiceDetails = App\Models\InvoiceDetail::where('Invoice_id',$payment->Invoice_id)->get();
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
                                                            <input type="hidden" name="new_paid_amount" value="{{ $payment->due_amount }}">
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
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-3">
                                                                    <label> Paid Status </label>
                                                                    <select name="paid_status" id="paid_status" class="form-select">
                                                                        <option value="">Select Status </option>
                                                                        <option value="full_paid">Full Paid </option>
                                                                         <option value="partial_paid">Partial Paid </option>

                                                                    </select>
                                                                    <input type="text" name="paid_amount" class="form-control paid_amount" placeholder="Enter Paid Amount" style="display:none;">
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <div class="md-3">
                                                                        <label for="example-text-input" class="form-label">Date</label>
                                                                         <input class="form-control example-date-input" name="date" type="date" id="date" placeholder="YYYY-MM-DD">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <div class="md-3 mt-4">
                                                                        <button type="submit" class="btn btn-info">Invoice Update</button>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                   </tbody>

                                            </table>
                                        </div>
                                    </form>
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

<script type="text/javascript">
    $(document).on('change','#paid_status', function(){
        var paid_status = $(this).val();
        if (paid_status == 'partial_paid') {
            $('.paid_amount').show();
        }else{
            $('.paid_amount').hide();
        }
    });

</script>

@endsection
