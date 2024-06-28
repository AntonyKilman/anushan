@extends('layouts.navigation')
@section('monthly_report','active')
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
                                <h4 class="header ">Reports</h4>
                               
                            </div>
                        </div>
                        <div class="card-body">

                            <form action="">

                            <div class="row">

                                <div class="col-3">
                                    <label>Select the Year</label>
                                    <input type="text" class="yearpicker form-control" value="{{$year}}"  name="year" required>
                                </div>

                                <div class="col-3" style="margin-top:30px">
                                  
                                    <button class="btn btn-success mr-1" id="add" type="submit" onchange="window.location.assign('/account-monthlyReport?year=' + this.value)">Submit</button>
                                </div>
                            </div><br>

                        </form>
                           
                           
                            <div class="table-responsive">
                                <table class="table table-striped">

                                    <thead>
                                        <tr>
                                            <th style="display: none"></th>
                                            <th  width="20%" >Month</th>
                                            <th  width="20%"  style="text-align: center">Total Income</th>
                                            <th width="20%"  style="text-align: center">Total Expenses</th>                                          
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($totalExpenseMonth as $months)

                                        <tr>
                                            <td style="display: none">#</td>
                                            <td>{{$months->month_name}}</td>
                                            <td style="text-align: right">{{$months->totalSaleAmount}}</td>
                                            <td style="text-align: right">{{$months->totalExpenseAmount}}</td>

                                            
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

@endsection


