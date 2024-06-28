@extends('layouts.navigation')
@section('purchaseRequest', 'active')
@section('content')

    <?php
        $Access = session()->get('Access');
    ?>

    {{-- style for delete modal --}}
    <style>
        .modal-confirm {
            color: #636363;
            width: 400px;
        }

        .modal-confirm .modal-content {
            padding: 20px;
            border-radius: 5px;
            border: none;
            text-align: center;
            font-size: 14px;
        }

        .modal-confirm .modal-header {
            border-bottom: none;
            position: relative;
        }

        .modal-confirm h4 {
            text-align: center;
            font-size: 26px;
            margin: 30px 0 -10px;
        }

        .modal-confirm .close {
            position: absolute;
            top: -5px;
            right: -2px;
        }

        .modal-confirm .modal-body {
            color: #999;
        }

        .modal-confirm .modal-footer {
            border: none;
            text-align: center;
            border-radius: 5px;
            font-size: 13px;
            padding: 10px 15px 25px;
        }

        .modal-confirm .modal-footer a {
            color: #999;
        }

        .modal-confirm .icon-box {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            border-radius: 50%;
            z-index: 9;
            text-align: center;
            border: 3px solid #f15e5e;
        }

        .modal-confirm .icon-box i {
            color: #f15e5e;
            font-size: 46px;
            display: inline-block;
            margin-top: 13px;
        }

        .modal-confirm .btn,
        .modal-confirm .btn:active {
            color: #fff;
            border-radius: 4px;
            background: #60c7c1;
            text-decoration: none;
            transition: all 0.4s;
            line-height: normal;
            min-width: 120px;
            border: none;
            min-height: 40px;
            border-radius: 3px;
            margin: 0 5px;
        }

        .modal-confirm .btn-secondary {
            background: #c1c1c1;
        }

        .modal-confirm .btn-secondary:hover,
        .modal-confirm .btn-secondary:focus {
            background: #a8a8a8;
        }

        .modal-confirm .btn-danger {
            background: #f15e5e;
        }

        .modal-confirm .btn-danger:hover,
        .modal-confirm .btn-danger:focus {
            background: #ee3535;
        }

        .trigger-btn {
            display: inline-block;
            margin: 100px auto;
        }

    </style>





    <!---------------------------- Main Content start ------------------------------>
    <div class="card">

        <div class="card-header">
            <h4 class="header">Update Purchase Order Request </h4>
        </div>


        <div class="card-body">

            <form action="/PurchaseRequestChangeUpdate" method="post" class="needs-validation" novalidate=""
                enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-3">
                        <label>Request No</label>
                        <input type="number" id="name" value="{{ $datas->id }}" name="request_no" class="form-control"
                            readonly>
                    </div>

                    <div class="col-3">
                        <label>Department</label>
                        <input type="text" id="name" value="{{ $department }}" name="department" class="form-control"
                            readonly>
                    </div>

                    <div class="col-6">
                        <label>Reason</label>
                        <textarea class="form-control" id="description" name="reason">{{ $datas->reason }}</textarea>
                    </div>

                </div><br><br>


                {{-- ---------------- Add Product table start ------------------------ --}}


                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="display: none">id</th>
                                <th>Product</th>
                                <th>Quantity Type</th>
                                <th>Quantity</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($items as $item)
                                <tr class="{{ $item->id }}">
                                    <td style="display: none">
                                        <input type="hidden" id="name" name="id[]" class="form-control"
                                            value="{{ $item->id }}">
                                    </td>
                                    <td>
                                        <select id="" name="product[]" class="form-control" required>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    @if ($product->id == $item->product) selected @endif>
                                                    {{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" id="name" name="qty_type[]" class="form-control"
                                            value="{{ $item->quantity_type }}">
                                    </td>

                                    <td>
                                        <input type="number" id="name" min="0" name="quantity[]" class="form-control"
                                            value="{{ $item->quantity }}">
                                    </td>

                                    
                                    <td>
                                        <button type="button" id="delete" onclick="confirmDeleteModal({{ $item->id }})"
                                            class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button>
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>


                    </table>
                    <div class="addNewProduct"></div>
                    <button type="button" id="addProduct" class="btn btn-primary"><i class="fas fa-plus"></i>&nbsp Add
                        Product</button>


                </div> <br>

                {{-- ---------------- Add Product table end ------------------------ --}}

                <input type="hidden" name="dropId[]" id="dropId">

                <div align="right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button class="btn btn-success mr-1" id="addSubmit" type="submit">Submit</button>
                </div>

            </form>
        </div>
    </div>

    <!---------------------------- Main Content end ------------------------------>

    <script>
        // validation for no product when click submit button
        $('#addSubmit').click(function(e) {
          
            var input = document.getElementsByName('product[]');

            if (input.length == 0) {
                e.preventDefault();
                alert("Please select any product");
            }
        });


        // function for add product row
        $('#addProduct').click(function() {
            let line = `<hr></hr>`;

            let html = "";
            html += `<tr>
                   
                       <td>
                           <select id="" name="product[]" class="form-control" required>
                               <option value="" disabled selected>Select Product</option>
                               @foreach ($products as $product)
                                   <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                               @endforeach
                           </select>
                       </td>

                       <td>
                           <select name="qty_type[]" id="qty_type" class="form-control" required>
                               <option value="" disabled selected>Select Quantity Type</option>
                               <option value="count">count</option>
                               <option value="liter">liter</option>
                               <option value="kg">kg</option>
                               <option value="meter">meter</option>
                             </select>
                       </td>

                       <td>
                           <input type="number" id="name" name="quantity[]" class="form-control" placeholder="Enter Quantity"  required>
                       </td>

                       <td>
                           <button type="button" id="remove"  class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button>
                       </td>
                   </tr>`;

            $('tbody').append(html);
        });



        $(document).on('click', '#remove', function() {
            $(this).closest('tr').remove();

        });



        // function for confirm delete modal
        function confirmDeleteModal(id) {

            $("#deleteModal").modal('show');
            let html = "";
            html += '<button type="button" class="btn btn-danger" onclick="deleteData(' + id + ')">Delete</button>';
            $('#deleteTag').html(html);
        }



        // function for delete
        let dropId = [];
        function deleteData(id) {
            dropId.push(id);
            console.log(dropId);
            $('#dropId').val(dropId);
            $(`.${id}`).hide();
            $("#deleteModal").modal('hide');

        }
    </script>

@endsection


@section('model')
    <!----delete modal starts here--->

    <div id="deleteModal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header flex-column">
                    <div class="icon-box">
                        <i class="material-icons">&#xE5CD;</i>
                    </div>
                    <h4 class="modal-title w-100">Are you sure?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to delete these records? This process cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <div id="deleteTag">

                    </div>
  
                </div>
            </div>
        </div>
    </div>
    <!--delete Modal ends here--->

@endsection
