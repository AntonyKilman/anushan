@extends('layouts.navigation')
@section('all_expense','active')
@section('content')
     <?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;
    ?> 


    <input type="hidden" id="bank-expense" value="{{$bankExpenseMonth->pluck('totalSaleAmount')}}">
    <input type="hidden" id="service-expense" value="{{$serviceExpenseMonth->pluck('totalSaleAmount')}}">
    <input type="hidden" id="other-expense" value="{{$otherExpenseMonth->pluck('totalSaleAmount')}}">
    <input type="hidden" id="inventory-expense" value="{{$inventeryExpenseMonth->pluck('totalSaleAmount')}}">
    <input type="hidden" id="hr-expense" value="{{$hrExpenseMonth->pluck('totalSaleAmount')}}">
    <input type="hidden" id="total-expense" value="{{$totalExpenseMonth->pluck('totalSaleAmount')}}">
    <input type="hidden" id="total-expense-pie" value="{{collect($totalAnualExpense)}}">
    <section class="section">
        <form action="/expenses" method="POST">
            @csrf
            <div class="card-body form">
                <h6>Expense Report</h6>
    
                <div class="form-row">

                    <div class="form-group col-md-3">
                        <label>Select the Year</label>
                        <input type="text" class="yearpicker form-control" placeholder="{{$yearExpense}}" value="" name="year" required>
                    </div>
                    <div class="form-group col-md-3" style="margin-top:30px">
                        <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="section-body">
            <div class="row">
                {{-- All income bar chart start --}}
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Total Expense</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="totalExpenseChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($inventeryExpenseToday->totalSaleAmount + $hrExpenseToday->totalSaleAmount + $otherExpenseToday->totalSaleAmount + $bankExpenseToday->totalSaleAmount + $serviceExpenseToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($inventeryExpenseWeek->totalSaleAmount + $hrExpenseWeek->totalSaleAmount + $otherExpenseWeek->totalSaleAmount + $bankExpenseWeek->totalSaleAmount + $serviceExpenseWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($totalExpenseMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($totalExpenseMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                    </div>
                </div>
                {{-- All income bar chart end --}}

                {{-- All income pie chart start --}}
                <div class="col-lg-6 col-md-6 col-6 col-sm-6">
                    <div class="card mt-sm-5 mt-md-0">
                      <div class="card-header">
                        <h4>Total Expense by year</h4>
                      </div>
                      <div class="card-body">
                        <canvas id="donutExpenseChart"></canvas>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-6 col-sm-6">
                                <ul class="p-t-30 list-unstyled">
                                    <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-black"></i></span>Inventory<span
                                        class="float-right">{{ number_format(($inventeryExpenseMonth->sum('totalSaleAmount')),2)}}</span></li>
                                    <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-green"></i></span>HR<span
                                        class="float-right">{{ number_format(($hrExpenseMonth->sum('totalSaleAmount')),2)}}</span></li>
                                    <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-orange"></i></span>Other Expense<span
                                        class="float-right">{{ number_format(($otherExpenseMonth->sum('totalSaleAmount')),2)}}</span></li>
                                </ul>
                            </div>
                            <div class="col-lg-6 col-md-6 col-6 col-sm-6">
                                <ul class="p-t-30 list-unstyled">
                                    <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-blue"></i></span>Bank<span
                                        class="float-right">{{ number_format(($bankExpenseMonth->sum('totalSaleAmount')),2)}}</span></li>
                                    <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-red"></i></span>Service Charges<span
                                        class="float-right">{{ number_format(($serviceExpenseMonth->sum('totalSaleAmount')),2)}}</span></li>
                                </ul>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
                {{-- All income pie chart end --}}
            </div>
            <div class="row">
                {{-- Inventory Expense start --}}
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Inventory</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="inventoryChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($inventeryExpenseToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($inventeryExpenseWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($inventeryExpenseMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($inventeryExpenseMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div>
                {{-- Inventory Expense end --}}

                {{-- HR Expense start --}}
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>HR</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="hrChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($hrExpenseToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($hrExpenseWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($hrExpenseMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($hrExpenseMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div>
                {{-- HR Expense end --}}
            </div>
            <div class="row">
                {{-- Other Expense start --}}
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Other Expense</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="otherChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($otherExpenseToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($otherExpenseWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($otherExpenseMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($otherExpenseMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div>
                {{-- Other Expense end --}}

                {{-- Bank Expense start --}}
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bank</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="bankChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($bankExpenseToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($bankExpenseWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($bankExpenseMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($bankExpenseMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div>
                {{-- Bank Expense end --}}
            </div>
            <div class="row">
                {{-- Service Expense start --}}
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Service Charges</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="serviceChart" height="180"></canvas>
                            <div class="statistic-details mt-1">
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($serviceExpenseToday->totalSaleAmount),2)}}</div>
                                <div class="detail-name">Today</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($serviceExpenseWeek->totalSaleAmount),2)}}</div>
                                <div class="detail-name">This Week</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{number_format(($serviceExpenseMonth->last()['totalSaleAmount']),2)}}</div>
                                <div class="detail-name">This Month</div>
                            </div>
                            <div class="statistic-details-item">
                                <div class="detail-value">{{ number_format(($serviceExpenseMonth->sum('totalSaleAmount')),2)}}</div>
                                <div class="detail-name">This Year</div>
                            </div>
                            </div>   
                        </div>
                        
                    </div>
                </div>
                {{-- Service Expense end --}}

            </div>
        </div>
    </section>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<Script>
    //Script for charts
    $( document ).ready(function() {
        //Inventory Expense
        var ctx = document.getElementById("inventoryChart").getContext('2d');
        var inventoryExpense = document.getElementById("inventory-expense").value;
        if(inventoryExpense){
            inventoryExpense = inventoryExpense.replace('[', '').replace(']', '').replace(/"/g, '');
            var inventoryExpenseList = inventoryExpense.split(',');
            var maxSales = (Math.max.apply(Math,inventoryExpenseList))/10;
            var roundSales = Math.round(maxSales/10000)*10000;
        }
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                label: 'Statistics',
                data: inventoryExpenseList,
                borderWidth: 2,
                backgroundColor: 'rgba(71,65,98,.9)',
                borderColor: 'transparent',
                borderWidth: 0,
                pointBackgroundColor: '#999',
                pointRadius: 4
                }]
            },
            options: {
                legend: {
                display: false
                },
                scales: {
                yAxes: [{
                    gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                    },
                    ticks: {
                    beginAtZero: true,
                    stepSize: roundSales,
                    fontColor: "#9aa0ac", // Font Color
                    }
                }],
                xAxes: [{
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    fontColor: "#9aa0ac", // Font Color
                    }
                }]
                },
            }
        });
        //HR Expense
        var ctx = document.getElementById("hrChart").getContext('2d');
        var hrExpense = document.getElementById("hr-expense").value;
        if(hrExpense){
            hrExpense = hrExpense.replace('[', '').replace(']', '').replace(/"/g, '');
            var hrExpenseList = hrExpense.split(',');
            var maxSales = (Math.max.apply(Math,hrExpenseList))/10;
            var roundSales = Math.round(maxSales/10000)*10000;
        }
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                label: 'Statistics',
                data: hrExpenseList,
                borderWidth: 2,
                backgroundColor: 'rgba(71,65,98,.9)',
                borderColor: 'transparent',
                borderWidth: 0,
                pointBackgroundColor: '#999',
                pointRadius: 4
                }]
            },
            options: {
                legend: {
                display: false
                },
                scales: {
                yAxes: [{
                    gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                    },
                    ticks: {
                    beginAtZero: true,
                    stepSize: roundSales,
                    fontColor: "#9aa0ac", // Font Color
                    }
                }],
                xAxes: [{
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    fontColor: "#9aa0ac", // Font Color
                    }
                }]
                },
            }
        });
        //Other Expense
        var ctx = document.getElementById("otherChart").getContext('2d');
        var bankExpense = document.getElementById("other-expense").value;
        if(bankExpense){
            bankExpense = bankExpense.replace('[', '').replace(']', '').replace(/"/g, '');
            var bankExpenseList = bankExpense.split(',');
            var maxSales = (Math.max.apply(Math,bankExpenseList))/10;
            var roundSales = Math.round(maxSales/10000)*10000;
        }
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                label: 'Statistics',
                data: bankExpenseList,
                borderWidth: 2,
                backgroundColor: 'rgba(71,65,98,.9)',
                borderColor: 'transparent',
                borderWidth: 0,
                pointBackgroundColor: '#999',
                pointRadius: 4
                }]
            },
            options: {
                legend: {
                display: false
                },
                scales: {
                yAxes: [{
                    gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                    },
                    ticks: {
                    beginAtZero: true,
                    stepSize: roundSales,
                    fontColor: "#9aa0ac", // Font Color
                    }
                }],
                xAxes: [{
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    fontColor: "#9aa0ac", // Font Color
                    }
                }]
                },
            }
        });
        //Bank Expense
        var ctx = document.getElementById("bankChart").getContext('2d');
        var bankExpense = document.getElementById("bank-expense").value;
        if(bankExpense){
            bankExpense = bankExpense.replace('[', '').replace(']', '').replace(/"/g, '');
            var bankExpenseList = bankExpense.split(',');
            var maxSales = (Math.max.apply(Math,bankExpenseList))/10;
            var roundSales = Math.round(maxSales/10000)*10000;
        }
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                label: 'Statistics',
                data: bankExpenseList,
                borderWidth: 2,
                backgroundColor: 'rgba(71,65,98,.9)',
                borderColor: 'transparent',
                borderWidth: 0,
                pointBackgroundColor: '#999',
                pointRadius: 4
                }]
            },
            options: {
                legend: {
                display: false
                },
                scales: {
                yAxes: [{
                    gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                    },
                    ticks: {
                    beginAtZero: true,
                    stepSize: roundSales,
                    fontColor: "#9aa0ac", // Font Color
                    }
                }],
                xAxes: [{
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    fontColor: "#9aa0ac", // Font Color
                    }
                }]
                },
            }
        });
        //Service Expense
        var ctx = document.getElementById("serviceChart").getContext('2d');
        var serviceExpense = document.getElementById("service-expense").value;
        if(serviceExpense){
            serviceExpense = serviceExpense.replace('[', '').replace(']', '').replace(/"/g, '');
            var serviceExpenseList = serviceExpense.split(',');
            var maxSales = (Math.max.apply(Math,serviceExpenseList))/10;
            var roundSales = Math.round(maxSales/10000)*10000;
        }
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                label: 'Statistics',
                data: serviceExpenseList,
                borderWidth: 2,
                backgroundColor: 'rgba(71,65,98,.9)',
                borderColor: 'transparent',
                borderWidth: 0,
                pointBackgroundColor: '#999',
                pointRadius: 4
                }]
            },
            options: {
                legend: {
                display: false
                },
                scales: {
                yAxes: [{
                    gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                    },
                    ticks: {
                    beginAtZero: true,
                    stepSize: roundSales,
                    fontColor: "#9aa0ac", // Font Color
                    }
                }],
                xAxes: [{
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    fontColor: "#9aa0ac", // Font Color
                    }
                }]
                },
            }
        });

        //Total Expense
        var ctx = document.getElementById("totalExpenseChart").getContext('2d');
        var totalExpense = document.getElementById("total-expense").value;
        if(totalExpense){
            totalExpense = totalExpense.replace('[', '').replace(']', '').replace(/"/g, '');
            var totalExpenseList = totalExpense.split(',');
            var maxSales = (Math.max.apply(Math,totalExpenseList))/10;
            var roundSales = Math.round(maxSales/10000)*10000;
        }
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                label: 'Statistics',
                data: totalExpenseList,
                borderWidth: 2,
                backgroundColor: 'rgba(71,65,98,.9)',
                borderColor: 'transparent',
                borderWidth: 0,
                pointBackgroundColor: '#999',
                pointRadius: 4
                }]
            },
            options: {
                legend: {
                display: false
                },
                scales: {
                yAxes: [{
                    gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                    },
                    ticks: {
                    beginAtZero: true,
                    stepSize: roundSales,
                    fontColor: "#9aa0ac", // Font Color
                    }
                }],
                xAxes: [{
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    fontColor: "#9aa0ac", // Font Color
                    }
                }]
                },
            }
        });

        //total expense pie chart
        var ctx = document.getElementById("donutExpenseChart").getContext('2d');
        var totalPieExpense = document.getElementById("total-expense-pie").value;
        if(totalPieExpense){
            totalPieExpense = totalPieExpense.replace('[', '').replace(']', '').replace(/"/g, '');
            var totalPieExpenseList = totalPieExpense.split(',');
        }
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                data:totalPieExpenseList,
                backgroundColor: [
                    '#191d21',
                    '#63ed7a',
                    '#ffa426',
                    '#007bff',
                    '#f44336',
                    
                ],
                label: 'Dataset 1'
                }],
                labels: [
                'Inventory',
                'HR',
                'Other Expense',
                'Bank',
                'Service Charges',
                ],
            },
            options: {
                responsive: true,
                legend: {
                position: 'bottom',
                display: false
                },
            }
        });
    });
</Script>
