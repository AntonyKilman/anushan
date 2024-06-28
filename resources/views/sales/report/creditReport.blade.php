@extends('layouts.navigation')
@section('creditReport', 'active')
@section('content')
    <!-- Main Content -->
    <form action="/sales/credit-report" method="get">
        <div class="card-body form">
            <div class="form-row">
                <div class="form-group col-md-3 ">
                    <label>From</label>
                    <input type="date" id="from_date" name="from_date" value="{{ $from_date }}" class="form-control"
                        required>
                </div>
                <div class="form-group col-md-3">
                    <label>To</label>
                    <input type="date" id="to_date" name="to_date" value="{{ $to_date }}" class="form-control"
                        required>
                </div>

                <div class="form-group col-md-3" style="margin-top:30px">
                    <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </form>

        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div style="padding: 10px;">
                                <h2>Customer Credit Reports</h2>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="customer">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Date</th>
                                                <th>Invoice No</th>
                                                <th>Customer Name</th>
                                                <th>Mobile</th>
                                                <th class="text-right">Total Amount</th>
                                                <th class="text-right">Total Discount</th>
                                                <th class="text-right">Total Credit</th>
                                                <th class="text-right">Total Paid</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th class="text-right">
                                                    {{ number_format($data->sum('amount') - $data->sum('discount_amount'), 2) }}
                                                    <hr>
                                                </th>
                                                <th class="text-right">
                                                    {{ number_format($data->sum('discount_amount'), 2) }}
                                                    <hr>
                                                </th>
                                                <th class="text-right">
                                                    {{ number_format($data->sum('credit_payment'), 2) }}
                                                    <hr>
                                                </th>
                                                <th class="text-right">
                                                    {{ number_format($data->sum('cash_payment') + $data->sum('card_payment') + $data->sum('loyality_payment') + $data->sum('cheque_payment'), 2) }}
                                                    <hr>
                                                </th>
                                            </tr>
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->billing_date }}</td>
                                                    <td>{{ $item->invoice_no }}</td>
                                                    <td>{{ $item->customer_name }}</td>
                                                    <td>{{ $item->phone_number }}</td>
                                                    <td class="text-right">
                                                        {{ number_format($item->amount - $item->discount_amount, 2) }}</td>
                                                    <td class="text-right">{{ number_format($item->discount_amount, 2) }}
                                                    </td>
                                                    <td class="text-right">{{ number_format($item->credit_payment, 2) }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($item->cash_payment + $item->card_payment + $item->loyality_payment + $item->cheque_payment, 2) }}
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

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var fdate = '{{ $from_date }}';
        var tdate = '{{ $to_date }}';

        $('#customer').DataTable({
            "pageLength": 10,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    text: 'excel',
                    titleAttr: 'Jaffna Electrical ERP',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Customer Credit Report :- ' + fdate + ' to  ' + tdate,
                    footer: true,
                    autoPrint: true
                },
                {
                    extend: 'print',
                    text: 'print',
                    titleAttr: 'Jaffna Electrical ERP',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Customer Credit Report :- ' + fdate + ' to  ' + tdate,
                    footer: true,
                    autoPrint: true
                }
            ]
        });
    });
</script>
