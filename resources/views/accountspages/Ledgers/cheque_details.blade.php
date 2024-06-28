@extends('layouts.navigation')
@section('cheque_details','active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;
    
    ?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">

                            <div class="card-header-bank">
                                <h4 class="header ">Cheque Details</h4>
                                {{-- <a href="" class="btn btn-success addchargetype" data-toggle="modal"
                                    data-target="#chargesType">Add</a> --}}
                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">

                                    <thead>
                                        <tr>
                                            <th>Invoice No</th>
                                            <th>Cheque No</th>
                                            <th>Cheque Date</th>
                                            <th>Amount</th>
                                            <th>Customer Name</th>
                                            <th>Phone No</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($foodcity_cheques as $foodcity_cheque)
                                            <tr>
                                                <td>{{$foodcity_cheque->invoice_no}}</td>
                                                <td>{{$foodcity_cheque->cheque_number}}</td>
                                                <td>{{$foodcity_cheque->cheque_date}}</td>
                                                <td>{{$foodcity_cheque->cheque_payment}}</td>
                                                <td>{{$foodcity_cheque->name}}</td>
                                                <td>{{$foodcity_cheque->phone_number}}</td>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

    <script>
        $(function() {
            setTimeout(function() {
                $('.fade-message').slideUp();
            }, 1000);
        });

        $(".addchargetype").on("click", function() {
            $('#id').val("");
            $('#name').val("");
            $('#description').val("");
        });

        $('.btn-edit').on('click', function() {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var description = $(this).attr('data-description');


            $('#id').val(id);
            $('#name').val(name);
            $('#description').val(description);


        });


        $(document).ready(function() {


            if (!@json($errors->isEmpty())) {
                $('#chargesType').modal();
            }

        });
    </script>
@endsection