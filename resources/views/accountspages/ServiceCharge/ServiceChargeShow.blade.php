@extends('layouts.navigation')
@section('service_charges','active')
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
                                <h4 class="header ">Service Charges</h4>
                                <a href="service-charge-Add" class="btn btn-success">Add</a>
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
                                            <th style="display: none"></th>
                                            <th>Service</th>
                                            <th>Amount</th>
                                            <th>Invoice No</th>
                                            <th>Date</th>
                                            <th>Provider</th>
                                            <th>Month</th>
                                            <th>Images</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($accountServiceCharges as $accountServiceCharge)
                                            <tr>
                                                <td style="display: none">#</td>
                                                <td>{{ $accountServiceCharge->serviceTypeName }}</td>
                                                <td style="text-align: right">{{ $accountServiceCharge->amount }}</td>
                                                <td>{{ $accountServiceCharge->invoice_no }}</td>
                                                <td>{{ $accountServiceCharge->date }}</td>
                                                <td>{{ $accountServiceCharge->name }}</td>
                                                <td>{{ $accountServiceCharge->month }}</td>

                                                @if($accountServiceCharge->image)
                                                <td style="text-align: center">
                                                    <img src="{{asset('accountServiceCharge/'.$accountServiceCharge->image)}}" alt="image" width="50" height="50">
                                                </td>

                                                @else
                                                    <td></td>
                                                @endif


                                                <td style="text-align: center">
                                                    <a href="service-charge-View/{{ $accountServiceCharge->id }}" class="btn btn-primary btn-edit"><i class="far fa-eye"></i></a>
                                                    <a href="service-charge-Edit/{{ $accountServiceCharge->id }}" class="btn btn-primary btn-edit"><i class="far fa-edit"></i></a>

                                                </td>



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
        $(document).ready(function() {

            $(function() {
                setTimeout(function() {
                    $('.fade-message').slideUp();
                }, 1000);
            });

        });
    </script>

@endsection
