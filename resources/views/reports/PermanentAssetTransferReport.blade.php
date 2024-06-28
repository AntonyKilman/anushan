@extends('layouts.navigation')
@section('permanent_transfer_report', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    ?>

    <!-- Main Content -->
    <div class="card">
        <form action="/permanent-asset-transfer-report" method="POST">
            @csrf

            <div class="card-body form">
                <h6>Permanent Asset Transfer Report</h6>

                <div class="form-row">

                    <div class="form-group col-md-3 ">
                        <label>From</label>
                        <input type="date" id="from" name="from" value="{{ $from }}" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>To</label>
                        <input type="date" max="{{ now()->format('Y-m-d') }}" id="to" name="to"
                            value="{{ $to }}" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Department</label>
                        <select id="department" name="department" class="form-control" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"                                    >
                                    {{ $department->dept_name }}</option>
                            @endforeach
                        </select>
                    </div>


                </div>
                <div align="right">
                    <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                    <button class="btn btn-success mr-1" id="submit" type="submit">Submit</button>
                </div>
            </div>

        </form>

        {{-- @if (count($Permanent_transfer) > 0) --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped " id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Department Name</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            {{-- <th>Serial Number</th> --}}
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permanent_trasfer as $permanent_trasfer)
                            <tr>
                                <td>{{ substr($permanent_trasfer->created_at, 0, 10) }}</td>
                                <td>{{ $permanent_trasfer->department_name }}</td>
                                <td>{{ $permanent_trasfer->product_code }}</td>
                                <td>{{ $permanent_trasfer->product_name }}</td>
                                <td>{{ $permanent_trasfer->quantity }}</td>
                                {{-- <td>{{ $permanent_trasfer->pur_item_qty_type }}</td> --}}
                                
                                                           
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- @endif --}}
    </div>

    <script>
      

        setMinDate();

        $('#from').change(function(e) {
            e.preventDefault();
            setMinDate();
        });

        function setMinDate() {
            var from = $('#from').val();
            if (from) {
                $('#to').attr('min', from);
            }
        }
    </script>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#inventoryTable').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort:true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Permanent Asset Transfer Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Permanent Asset Transfer Report From - ' + date_from + ' to - ' + date_to,
                    footer: true,
                    header: true
                }
            ],
        });
    });
</script>