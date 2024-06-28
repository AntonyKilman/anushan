@extends('layouts.navigation')
@section('seller', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $sellerAdd = false;
    $sellerEdit = false;
    $sellerView = false;
    if (in_array('inventory.sellerAdd', $Access)) {
        $sellerAdd = true;
    }
    if (in_array('inventory.sellerEdit', $Access)) {
        $sellerEdit = true;
    }
    if (in_array('inventory.sellerView', $Access)) {
        $sellerView = true;
    }
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Supplier</h4>
            @if ($sellerAdd)
                <a href="/seller-add" class="btn btn-success">Add</a>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                            <th style="display: none">#</th>
                            <th>Name</th>
                            <th>Register No</th>
                            <th>Address</th>
                            <th>Contact No</th>
                            <th>Mobile No</th>
                            <th>Supplier Type</th>
                            @if ($sellerEdit || $sellerView)
                                <th width="15%" class="action">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sellers as $seller)
                            <tr>
                                <td style="display: none">#</td>
                                <td>{{ $seller->seller_name }}</td>
                                <td>{{ $seller->seller_reg_no }}</td>
                                <td>{{ $seller->seller_address }}</td>
                                <td>{{ $seller->contact_no }}</td>
                                <td>{{ $seller->mobile_no }}</td>
                                <td>{{ $seller->seller_type_name }}</td>
                                @if ($sellerEdit || $sellerView)
                                    <td width="15%" class="action">


                                        @if ($sellerView)
                                            <a href="seller-view/{{ $seller->id }}" title="view"
                                                class="btn btn-info  btn-edit"><i class="far fa-eye"></i></a>
                                        @endif
                                        @if ($sellerEdit)
                                            <a href="seller-edit/{{ $seller->id }}" title="edit"
                                                class="btn btn-primary  btn-edit"><i class="far fa-edit"></i></a>
                                        @endif

                                        <button title="histroy" data-toggle="modal" data-target="#purchaseModal"
                                            data-seller="{{ $seller->id }}" class="btn btn-dark purchases btn-edit"><i
                                                class="far fa-file"></i></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
    
            $(document).on('click', '.btn-edit', function() {
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');
                var reg_no = $(this).attr('data-reg');
                var address = $(this).attr('data-address');
                var mobile_no = $(this).attr('data-mobileno');
                var contact_no = $(this).attr('data-contactno');
    
                $('#id').val(id);
                $('#name').val(name);
                $('#description').val(reg_no);
                $('#description').val(address);
                $('#description').val(mobile_no);
                $('#description').val(contact_no);
            });
    
            $(".purchases").click(function(e) {
                e.preventDefault();
                console.log("clickj");
                let seller_id = $(this).attr("data-seller");
                $.ajax({
                    type: 'GET',
                    url: `/seller-histroy/${seller_id}`,
    
                    success: function(data) {
                        console.log(data);
                        let html = "";
    
                        $.each(data, function(indexInArray, valueOfElement) {
                            let credit = valueOfElement.pur_ord_credit;
                            if (credit === null) {
                                credit = 0.00;
                            }
                            html += `
                     <tr>
                                  <td>${valueOfElement.GR_No}</td>
                                  <td>${valueOfElement.pur_ord_bill_no}</td>
                                  <td>${valueOfElement.seller_name}</td>
                                  <td>${valueOfElement.date}</td>
                                  <td  style="text-align: right">${parseFloat(valueOfElement.pur_ord_amount).toFixed(2)}</td>
                                  <td  style="text-align: right">${parseFloat(valueOfElement.pur_ord_cash).toFixed(2)}</td>
                                  <td  style="text-align: right">${parseFloat(credit).toFixed(2)}</td>
                                  <td style="text-align: center">
                                    <a href="/purchase-order-view/${valueOfElement.GR_No}" class="btn btn-info btn-sm purchases" title="view"
                                         ><i
                                            class="far fa-eye"></i></a>
                                    </td>
                              </tr>
                     `;
                        });
    
                        $("#purchaseTbody").empty().append(html);
    
    
                    },
    
                    error: function(data) {
                        console.log(data);
                    },
    
    
                });
    
            });
    
        });
    </script>

@endsection

@section('model')

    {{-- purchase modal start --}}
    <div class="modal fade" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Good Receive</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="ModalTable">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">GR No</th>
                                        <th style="text-align: center">Bill</th>
                                        <th style="text-align: center">Supplier</th>
                                        <th style="text-align: center">Date</th>
                                        <th style="text-align: center">Amount</th>
                                        <th style="text-align: center">Cash</th>
                                        <th style="text-align: center">Credit</th>
                                        <th style="text-align: center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="purchaseTbody">

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- purchase modal end --}}
@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>

