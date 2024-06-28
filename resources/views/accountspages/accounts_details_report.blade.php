@extends('layouts.navigation')
@section('Accounts Details', 'active')
@section('content')
    <!-- Main Content -->
    <form action="/account-DetailsReport" method="get">
        @csrf
        <div class="card-body form">
            <h6>Accounts Details Report</h6>

            <div class="form-row">
                <div class="form-group col-md-3 ">
                    <label>From</label>
                    <input type="date" id="from" name="from" value="{{ $from }}" class="form-control"
                        required>
                </div>
                <div class="form-group col-md-3">
                    <label>To</label>
                    <input type="date" id="to" name="to"
                        max="{{ now()->format('Y-m-d') }}" value="{{ $to }}"
                        class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Categeory</label>
                    <select class="form-control" name="categeoryId" id="categeoryId" onchange="showSubCategory()">
                        <option value="" selected>Select Categeory</option>
                        @foreach ($categeory as $row)
                            <option class="categeory"
                                value="{{ $row->id }}" {{ $row->id == $categeoryId ? 'selected' : '' }}>
                                {{ $row->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Sub Categeory</label>
                    <select class="form-control" name="subcategeoryId" id="subcategeoryId" >
                        <option value="" selected>Select Sub Categeory</option>
                        @foreach ($subcategeory as $row)
                            <option class="subcategeory subcategeory_id_{{$row->id}} categeory_id_{{ $row->categeory_id }}"
                                value="{{ $row->id }}">
                                {{ $row->name }}</option>
                        @endforeach
                    </select>
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
                            <h2>Accounts Details</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="accounts_details_table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Date</th>
                                            <th>Categeory Name</th>
                                            <th>Sub Categeory Name</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th style="text-align:right">{{ number_format($accountsDetailsReport->sum('amount'),2) }} <hr></th>
                                        </tr>
                                        <?php $i = 1; ?>
                                        @foreach ($accountsDetailsReport as $item)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $item->date }}</td>
                                                <td>{{ $item->categeory_name }}</td>
                                                <td>{{ $item->subcategeory_name }}</td>
                                                <td>{{ $item->amount }}</td>
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

        showSubCategory();

        var date_from = '{{ $from }}';
        var date_to = '{{ $to }}';

        $('#accounts_details_table').DataTable({
            dom: 'Bfrtip',
            bPaginate: true,
            bSort: true,
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ERP',
                    messageTop: 'Accounts Details Report From - ' + date_from + ' to - ' + date_to,
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



    function showSubCategory() {
        $('.subcategeory').hide();
        $('#subcategeoryId').val('');

         var categeoryId = $('#categeoryId').val();

         if (categeoryId) {
            $('.categeory_id_' + categeoryId).show();

            var subcategeoryId = '{{$subcategeoryId}}';
            if (subcategeoryId) {
                $('.subcategeory_id_' + subcategeoryId).attr('selected', true);
            }
         }
    }
</script>
