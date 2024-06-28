@extends('layouts.navigation')
@section('content')
    <?php
    use Carbon\Carbon;
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
                                <h4 class="header ">Service Expenses</h4>
                                <a href="" class="btn btn-success addchargetype" data-toggle="modal"
                                    data-target="#createModal">Add</a>
                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif

                        </div>


                        {{-- --- -----------view tables start------------------- --}}
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">

                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th>Service Type</th>
                                            <th>Service Provider</th>
                                            <th>Month</th>
                                            <th>Cash</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($account_service_expense_new as $data)
                                            <tr>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->provider }}</td>
                                                <td>{{ $data->month }}</td>
                                                <td>{{ $data->cash }}</td>
                                                <td>
                                                    <button data-toggle="modal"  data-target="#editModal" title="edit" class="btn btn-primary btn-edit"><i class="far fa-edit"></i></button>
                                                    <button data-toggle="modal"  data-target="#viewModal" title="view" class="btn btn-primary view"
                                                    data-id = "{{ $data->id }}"
                                                    data-name = "{{ $data->name }}"
                                                    data-provider = "{{ $data->provider }}"
                                                    data-month = "{{ $data->month }}"><i class="fa fa-eye"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                            </div>
                        </div>

                        {{-- -----------view tables end------------------- --}}
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

        $(document).ready(function() {


            if (!@json($errors->isEmpty())) {
                $('#chargesType').modal();
            }

        });
        $('.tot_charge').hide();




        function getchargesAmount() {
            let serviceType = $('.service_type').val();
            let serviceProvider = $('.service_provider').val();
            let month = $('.month').val();

            if (serviceType && serviceProvider && month) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: "/get-service-expense-amount",
                    dataType: "json",
                    data: {
                        serviceType: serviceType,
                        serviceProvider: serviceProvider,
                        month: month
                    },


                    success: function(response) {
                        console.log(response);

                        if (response.message) {
                            console.log("error");
                            var text = `<h4 style="text-align:center">No Records for this month</h4>`
                            $('#getData').empty().append(text);
                            $('.tot_charge').hide();

                        } else {


                            var total = 0;
                            var total_paid = 0;
                            var html = "";


                            for (const key in response) {

                                html += `<tr>`;
                                    html +=
                                    `<input type="hidden" class="form-control" value="${response[key]['acc_dept_id']}" name="acc_dept_id[]">`;
                                html +=
                                    `<input type="hidden" class="form-control" value="${response[key]['dept_id']}" name="departments_id[]" readonly>`;
                                html += `<td>`;
                                html +=
                                    ` <input type="text" class="form-control" value="${response[key]['dept_name']}" name="departments[]" readonly>`;
                                html += `</td>`;
                                html += `<td>`;
                                html +=
                                    ` <input type="text" class="form-control" value="${response[key]['charge']}" name="dept_charge[]" readonly>`;
                                html += `</td>`;
                                html += `<td>`;
                                html +=
                                    ` <input type="number" step="0.01" class="form-control dept_amount" value="${response[key]['TotalDeptPay']}"  readonly>`;
                                html += ` </td>`;
                                html += `<td>`;
                                html +=
                                    ` <input type="number" step="0.01" class="form-control dept_amount" value="0.00" name="dept_pay[]" max="${response[key]['charge']-response[key]['TotalDeptPay']}" required>`;
                                html += ` </td>`;
                                html += ` </tr>`;


                                total += response[key]['charge'];
                                total_paid += response[key]['TotalDeptPay'];

                            }

                            $('#getData').empty().append(html);
                            $('.totalcharge').val(total);
                            $('.alreadyPaid').val(total_paid);
                            $(".cash").attr({
                                "max": total - total_paid
                            });
                            $('.tot_charge').show();
                        }
                    },


                    error: function(response) {
                        console.log(response);
                    },

                });

            }

        }

        $('.checkSubmit').click(function(e) {
            let cash = $('.cash').val();
            let dept_total = 0;
            var dept_amount = document.getElementsByName('dept_pay[]');

            for (var i = 0; i < dept_amount.length; i++) {
                dept_total = Number(dept_total) + Number(dept_amount[i].value);
            }

            if (!(dept_total == cash)) {
                alert(".Please consider the amount.Your total amount not equal");
                e.preventDefault();
            }


        });


        // function for view model

        $(".view").click(function(){
            console.log("view");
            let name = $(this).attr("data-name");
            let provider = $(this).attr("data-provider");
            let month = $(this).attr("data-month");
            let id = $(this).attr("data-id");

            $('.viewService_type').val(name);
            $('.viewService_provider').val(provider);
            $('.viewmonth').val(month);
        });
    </script>
@endsection



    <!-----------------------------------Create Modal start--------------------------------------------->
{{-- @section('model') --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Service Expenses</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Main Content -->
                    <form action="/dept-service-expense-amount" method="post" class="needs-validation" novalidate="">

                        <div class="card-body form">
                            @csrf


                            <input type="hidden" class="form-control" name="id" id="id">
                            <div class="row">
                                <div class="form-group col-4">
                                    <label>Service Type</label>
                                    <select class="form-control service_type" name="service_type_id"
                                        value="{{ old('service_provider_id') }}" onchange="getchargesAmount()" required>
                                        <option value="" class="" disabled selected>Select
                                            Service Type</option>
                                        @foreach ($accountyServiceTypes as $type)
                                            <option value="{{ $type->id }}">
                                                {{ $type->name }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group col-4">
                                    <label>Service Provider</label>
                                    <select class="form-control service_provider" name="service_provider_id"
                                        onchange="getchargesAmount()" value="{{ old('service_provider_id') }}" required>
                                        <option value="" class="" disabled selected>Select
                                            Service Provide</option>
                                        @foreach ($accountServiceProvider as $provider)
                                            <option value="{{ $provider->id }}">
                                                {{ $provider->name }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Month</label>
                                        <input type="month" class="form-control month"
                                            max="{{ Carbon::now()->format('Y-m') }}" name="month"
                                            onchange="getchargesAmount()" required>
                                        <span class="text-danger">
                                            @error('month')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row tot_charge">

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Total Charge</label>
                                        <input type="number" class="form-control totalcharge" step="0.01"
                                            name="total_charge" readonly>
                                        <span class="text-danger">
                                            @error('total_charge')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Already Paid</label>
                                        <input type="number" class="form-control alreadyPaid" step="0.01" name="" readonly>
                                    </div>
                                </div>


                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Cash</label>
                                        <input type="number" class="form-control cash" step="0.01" name="cash" required>
                                        <span class="text-danger">
                                            @error('cash')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                            </div>




                            {{-- ------------- account departments start-------------------------- --}}

                            <div class="table-responsive">
                                <table class="table table-striped">

                                    <thead>
                                        <tr>

                                            <th>Department</th>
                                            <th>Charge</th>
                                            <th>Already paid</th>
                                            <th>Amount</th>

                                        </tr>
                                    </thead>

                                    <tbody id="getData">

                                    </tbody>

                                </table>

                            </div>

                            {{-- ------------- account departments end-------------------------- --}}


                            <div align="right">
                                <button class="btn btn-danger" id="reset" type="reset">Reset</button>
                                <button class="btn btn-success mr-1 checkSubmit" id="add" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
{{-- @endsection --}}
<!-------------------------------------------Create Modal end---------------------------->



   <!-----------------------------------Edit Modal start--------------------------------------------->
   {{-- @section('model') --}}
   <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
       aria-hidden="true">
       <div class="modal-dialog modal-xl" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">Create Service Expenses</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <!-- Main Content -->
                   <form action="/dept-service-expense-amount" method="post" class="needs-validation" novalidate="">

                       <div class="card-body form">
                           @csrf


                           <input type="hidden" class="form-control" name="id" id="id">
                           <div class="row">
                               <div class="form-group col-4">
                                   <label>Service Type</label>
                                   <select class="form-control service_type" name="service_type_id"
                                       value="{{ old('service_provider_id') }}" onchange="getchargesAmount()" required>
                                       <option value="" class="" disabled selected>Select
                                           Service Type</option>
                                       @foreach ($accountyServiceTypes as $type)
                                           <option value="{{ $type->id }}">
                                               {{ $type->name }}</option>
                                       @endforeach

                                   </select>
                               </div>

                               <div class="form-group col-4">
                                   <label>Service Provider</label>
                                   <select class="form-control service_provider" name="service_provider_id"
                                       onchange="getchargesAmount()" value="{{ old('service_provider_id') }}" required>
                                       <option value="" class="" disabled selected>Select
                                           Service Provide</option>
                                       @foreach ($accountServiceProvider as $provider)
                                           <option value="{{ $provider->id }}">
                                               {{ $provider->name }}</option>
                                       @endforeach

                                   </select>
                               </div>

                               <div class="col-4">
                                   <div class="form-group">
                                       <label>Month</label>
                                       <input type="month" class="form-control month"
                                           max="{{ Carbon::now()->format('Y-m') }}" name="month"
                                           onchange="getchargesAmount()" required>
                                       <span class="text-danger">
                                           @error('month')
                                               {{ $message }}
                                           @enderror
                                       </span>
                                   </div>
                               </div>
                           </div>

                           <div class="row tot_charge">

                               <div class="col-4">
                                   <div class="form-group">
                                       <label>Total Charge</label>
                                       <input type="number" class="form-control totalcharge" step="0.01"
                                           name="total_charge" readonly>
                                       <span class="text-danger">
                                           @error('total_charge')
                                               {{ $message }}
                                           @enderror
                                       </span>
                                   </div>
                               </div>

                               <div class="col-4">
                                   <div class="form-group">
                                       <label>Already Paid</label>
                                       <input type="number" class="form-control alreadyPaid" step="0.01" name="" readonly>
                                   </div>
                               </div>


                               <div class="col-4">
                                   <div class="form-group">
                                       <label>Cash</label>
                                       <input type="number" class="form-control cash" step="0.01" name="cash" required>
                                       <span class="text-danger">
                                           @error('cash')
                                               {{ $message }}
                                           @enderror
                                       </span>
                                   </div>
                               </div>

                           </div>




                           {{-- ------------- account departments start-------------------------- --}}

                           <div class="table-responsive">
                               <table class="table table-striped">

                                   <thead>
                                       <tr>

                                           <th>Department</th>
                                           <th>Charge</th>
                                           <th>Already paid</th>
                                           <th>Amount</th>

                                       </tr>
                                   </thead>

                                   <tbody id="getData">

                                   </tbody>

                               </table>

                           </div>

                           {{-- ------------- account departments end-------------------------- --}}


                           <div align="right">
                               <button class="btn btn-danger" id="reset" type="reset">Reset</button>
                               <button class="btn btn-success mr-1 checkSubmit" id="add" type="submit">Submit</button>
                           </div>
                       </div>
                   </form>

               </div>

           </div>
       </div>
   </div>
{{-- @endsection --}}
<!-------------------------------------------Edit Modal end---------------------------->

 <!-----------------------------------View Modal start--------------------------------------------->
   {{-- @section('model') --}}
   <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
       aria-hidden="true">
       <div class="modal-dialog modal-xl" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">View Service Expenses</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <!-- Main Content -->


                       <div class="card-body form">
                           @csrf


                           <input type="hidden" class="form-control" name="id" id="id">
                           <div class="row">
                               <div class="form-group col-4">
                                   <label>Service Type</label>
                                  <input type="text" class="form-control viewService_type" readonly>
                               </div>

                               <div class="form-group col-4">
                                   <label>Service Provider</label>
                                   <input type="text" class="form-control viewService_provider" readonly>

                               </div>

                               <div class="col-4">
                                   <div class="form-group">
                                       <label>Month</label>
                                       <input type="month" class="form-control viewmonth"
                                           max="{{ Carbon::now()->format('Y-m') }}" name="month"
                                            readonly>

                                   </div>
                               </div>
                           </div>

                           <div class="row tot_charge">

                               <div class="col-4">
                                   <div class="form-group">
                                       <label>Total Charge</label>
                                       <input type="number" class="form-control totalcharge" step="0.01"
                                           name="total_charge" readonly>
                                       <span class="text-danger">
                                           @error('total_charge')
                                               {{ $message }}
                                           @enderror
                                       </span>
                                   </div>
                               </div>

                               <div class="col-4">
                                   <div class="form-group">
                                       <label>Already Paid</label>
                                       <input type="number" class="form-control alreadyPaid" step="0.01" name="" readonly>
                                   </div>
                               </div>


                               <div class="col-4">
                                   <div class="form-group">
                                       <label>Cash</label>
                                       <input type="number" class="form-control cash" step="0.01" name="cash" required>
                                       <span class="text-danger">
                                           @error('cash')
                                               {{ $message }}
                                           @enderror
                                       </span>
                                   </div>
                               </div>

                           </div>




                           {{-- ------------- account departments start-------------------------- --}}

                           <div class="table-responsive">
                               <table class="table table-striped">

                                   <thead>
                                       <tr>

                                           <th>Department</th>
                                           <th>Charge</th>
                                           <th>Amount</th>

                                       </tr>
                                   </thead>

                                   <tbody id="getData">

                                   </tbody>

                               </table>

                           </div>

                           {{-- ------------- account departments end-------------------------- --}}

                       </div>


               </div>

           </div>
       </div>
   </div>
{{-- @endsection --}}
<!-------------------------------------------View Modal end---------------------------->
