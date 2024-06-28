@extends('layouts.navigation')
@section('salecancelReport', 'active')
@section('content')
    <!-- Main Content -->
    <form action="/sales/sales-cancel-report" method="get">
        @csrf
        <div class="card-body form">
            <h6>Sales Cancel Report</h6>

            <div class="form-row">
                <div class="form-group col-md-3 ">
                    <label>From</label>
                    <input type="date" id="from" name="from" value="{{ $from }}" class="form-control"
                        required>
                </div>
                <div class="form-group col-md-3">
                    <label>To</label>
                    <input type="date" id="to" name="to" max="{{ now()->format('Y-m-d') }}"
                        value="{{ $to }}" class="form-control" required>
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
                            <h2>Sales Cancel</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="salesCancelTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Date & Time</th>
                                            <th>Name</th>
                                            <th>Invoice No</th>
                                            <th>Return Quantity</th>
                                            <th>Amount</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($salesCancelReport as $item)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ $item->invoice_no }}</td>
                                                <td>{{ $item->return_quantity }}</td>
                                                <td>{{ $item->return_amount }}</td>
                                                <td>{{ $item->return_reson }}</td>
                                            </tr>
                                            <?php $i++; ?>
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

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#salesCancelTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort: true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Sales Cancel Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Sales Cancel Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>
