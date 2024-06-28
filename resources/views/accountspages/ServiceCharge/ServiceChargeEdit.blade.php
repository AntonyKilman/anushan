@extends('layouts.navigation')
@section('service_charges','active')
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
                                <h4 class="header ">Service Charges</h4>
                            </div>

                            @if (\Session::has('sucess'))
                                <div class="alert alert-success fade-message">
                                    <p>{{ \Session::get('sucess') }}</p>
                                </div><br />
                            @endif

                        </div>


                        <div class="card-body">
                            <form action="/service-charge-Store" method="post" class="needs-validation" novalidate=""
                                enctype="multipart/form-data">

                                <div class="card-body form">
                                    @csrf

                                   <input type="hidden" class="form-control" name="id" value="{{ $editDatas->id }}">

                                    <div class="row">

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Service</label>
                                                <select class="form-control" name="service_type_id" id="editService"
                                                    required>
                                                    <option value="" disabled selected>Select Service</option>
                                                    @foreach ($accountyServiceTypes as $accountyServiceType)
                                                        <option value="{{ $accountyServiceType->id }}"
                                                            {{ $editDatas->service_type_id == $accountyServiceType->id ? 'selected' : '' }}>
                                                            {{ $accountyServiceType->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">
                                                    @error('service_type_id')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>


                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="number" class="form-control editAmount" name="amount" step="0.001"
                                                    id="editAmount" value="{{ $editDatas->amount }}" required>
                                                <span class="text-danger">
                                                    @error('amount')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>

                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Invoice No</label>
                                                <input type="text" class="form-control" name="invoice_no"
                                                    id="editinvoice_no" value="{{ $editDatas->invoice_no }}" required>
                                                <span class="text-danger">
                                                    @error('invoice_no')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label> Date</label>
                                                <input type="date" class="form-control" name="payment_date" id="editDate"
                                                    value="{{ $editDatas->date }}" required>
                                                <span class="text-danger">
                                                    @error('payment_date')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>

                                        </div>



                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Service Provider</label>
                                                <select class="form-control" name="service_provider_id" id="servicePro"
                                                    required>
                                                    <option value="" disabled selected>Select Provider</option>
                                                    @foreach ($accountServiceProviders as $accountServiceProvider)
                                                        <option value="{{ $accountServiceProvider->id }}"
                                                            {{ $editDatas->service_provider_id == $accountServiceProvider->id ? 'selected' : '' }}>
                                                            {{ $accountServiceProvider->name }}</option>
                                                    @endforeach

                                                </select>
                                                <span class="text-danger">
                                                    @error('service_provider_id')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>


                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Month</label>
                                                <input type="month" class="form-control"  value="{{ $editDatas->month }}"  max="{{Carbon::now()->format('Y-m')}}" name="month">
                                                <span class="text-danger">
                                                    @error('month')
                                                        {{ $message }}
                                                    @enderror
                                                </span>


                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Image</label>
                                                <input type="file" name="image" value="fileupload">

                                                @if($editDatas->image){
                                                    <img src="{{ asset('accountServiceCharge/' . $editDatas->image) }}"
                                                    width="100" height="80">
                                                }
                                                @endif



                                                <span class="text-danger">
                                                    @error('image')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>


                                        <div class="col-8">

                                            <div class="form-group">
                                                <label>Note</label>
                                                <textarea class="form-control" id="editNote"
                                                    name="note">{{ $editDatas->note }}</textarea>
                                                <span class="text-danger">
                                                    @error('note')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>

                                    </div>



                                  {{-- ------------- account departments -------------------------- --}}

                                  <h4 class="header ">Departments</h4>
                                  <div class="row">

                                      @foreach ($account_departments as $department)
                                          <div class="col-6 form-group">

                                              <div class="row">

                                                <input type="hidden" class="form-control"
                                                value="{{ $department->id }}" name="account_dept_service_charge_id[]"
                                                readonly>

                                                  <input type="hidden" class="form-control"
                                                          value="{{ $department->dept_id }}" name="departments_id[]"
                                                          readonly>

                                                  <div class="col-8">
                                                      <input type="text" class="form-control"
                                                          value="{{ $department->dept_name }}" name="departments[]"
                                                          readonly>
                                                  </div>

                                                  <div class="col-4">
                                                      <input type="number" step="0.01" class="form-control dept_amount" value="{{ $department->charge }}"
                                                          name="dept_charge[]">
                                                  </div>

                                              </div>

                                          </div>
                                      @endforeach

                                  </div>

                                    <div align="right">

                                        <button class="btn btn-success mr-1" id="add" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
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


            $("#reset").on("click", function() {
                console.log("reset");
                $(".editAmount").val("");

                $("#editService").val("");
                $("#editAmount").val("");
                $("#editinvoice_no").val("");
                $("#editDate").val("");
                $("#servicePro").val("");
                $("#editMonth").val("");
                $("#editNote").val("");

            });





        });
    </script>
@endsection
