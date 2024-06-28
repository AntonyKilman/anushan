@extends('layouts.navigation')
@section('content')
     <?php
    $Access = session()->get('Access');
    $i=1;

    ?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">
                            <h2> Stock Manage</h2>
                            @if (\Session::has('sucess'))
                            <div class="alert alert-success fade-message">
                                <p>{{ \Session::get('sucess') }}</p>
                            </div><br />

                            <script>
                                $(function() {
                                    setTimeout(function() {
                                        $('.fade-message').slideUp();
                                    }, 1000);
                                });
                            </script>
                            @endif
                            <button class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#exampleModal">
                                <i class="fas fa-plus"></i> Create New Action
                            </button>

                        </div>



                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($datas as $row)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{ $row->type }}</td>
                                            <td>{{ $row->total_purchase_price }}</td>
                                            <td>
                                                <button data-toggle="modal"
                                                    data-id="{{ $row->id }}"
                                                    data-type="{{ $row->type }}"
                                                    data-total_purchase_price="{{ $row->total_purchase_price }}"
                                                    data-target="#exampleModal"
                                                    title="edit" class="btn btn-primary btn-edit"><i
                                                        class="far fa-edit"></i></button>
                                            </td>

                                            <?php $i++; ?>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>

        $('.btn-edit').on('click', function() {
           console.log("bnbnb");
           var id = $(this).attr('data-id');
           var type = $(this).attr('data-type');
           var amount = $(this).attr('data-total_purchase_price');
           console.log(id,type,amount);

           $('#id').val(id);
            $('#type').val(type);
            $('#amount').val(amount);
        });

        $('.float-right').on('click', function() {
            $('#id').val("");
            $('#type').val("");
            $('#amount').val("");
        });
   </script>

@endsection
<!-- ----------------------- -->
@section('model')
    <!-- Modal with form -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="exampleModal"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Create Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form  method="POST" action="/stock-Store" enctype="multipart/form-data">
                        @csrf
                            <input type="hidden"  name="id" id="id">

                        <div class="row">
                            <div class="form-group col-12">
                                <label>Action</label>
                                <div >
                                    <select name="action" id="type" class="form-control">
                                        <option value="" disabled selected>Please select</option>
                                        <option value="Stock damage">Stock damage</option>
                                        <option value="Stock drawing">Stock drawing</option>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="form-group col-12">
                                <label>Amount </label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" class="form-control" id="amount"  name="amount" required>
                                </div>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary m-t-15 waves-effect" >Submit</button>
                        <button type="reset" class="btn btn-danger m-t-15 waves-effect" >Reset</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- -------------------------------------------------------------------------------------------------------------- -->
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>




