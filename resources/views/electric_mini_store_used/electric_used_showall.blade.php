@extends('layouts.navigation')
@section('electric_used', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $electric_add = false;
    $electric_edit = false;
    
    if (in_array('inventory.electricUseAdd', $Access)) {
        $electric_add = true;
    }
    if (in_array('inventory.electricUseEdit', $Access)) {
        $electric_edit = true;
    }
    ?>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('inventory.electricUseShowall') }}" method="GET" class="needs-validation" novalidate="">
                <div class="form-row">
                    <div class="form-group col-md-3 ">
                        <label>From</label>
                        <input type="date" id="from" value="{{ $from }}" name="from" class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>To</label>
                        <input type="date" id="to" name="to" value="{{ $to }}"
                            class="form-control" required>
                    </div>

                    <div class="form-group col-md-3">
                        <div class="form-group col-md-3"></div>
                        <button type="reset" id="reset" class="btn btn-danger">Reset</button>
                        <button type="submit" id="" class="btn btn-success">Submit</button>
                        {{-- <button class="btn btn-success mr-1" id="submit"
                            onclick="window.location.assign('/electric-use-showall?from=' +$(`#from`).val();+'&to='+$(`#to`).val();)">Submit</button> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Electric & Electronic Use</h4>
            @if ($electric_add)
                <a href="/electric-use-add" class="btn btn-success">Add</a>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped print_table">
                    <thead>
                        <tr>
                            {{-- <th style="display: none"></th> --}}
                            <th>No</th>
                            <th>Date</th>
                            <th>Product Name</th>
                            <th>Qty Type</th>
                            <th>Qty</th>
                            <th>Return Qty</th>
                            <th>Reason</th>
                            @if ($electric_edit)
                                <th class="action">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        @foreach ($electric_uses as $electric_use)
                            <tr>
                                {{-- <td >{{$electric_use->id}}</td> --}}
                                {{-- <td>{{$electric_use->purchase_id}}</td> --}}
                                {{-- <td style="display: none"></td> --}}
                                <td >{{ $i++}}</td>

                                <td>{{ $electric_use->date }}</td>
                                <td>{{ $electric_use->product_name }}</td>
                                <td>{{ $electric_use->qty_type }}</td>
                                <td>{{ $electric_use->used_qty }}</td>
                                <td>{{ $electric_use->return_qty }}</td>
                                <td>
                                    <textarea cols="20" rows="1" class="form-control" readonly>{{ $electric_use->reason }}</textarea>
                                </td>
                                @if ($electric_edit)
                                    <td class="action">
                                        @if ($electric_edit)
                                            <a href="/electric-use-edit?date={{ $electric_use->date }}" title="edit"
                                                class="btn btn-primary btn-edit"><i class="far fa-edit"></i></a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click', '.btn-edit', function() {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var reg_no = $(this).attr('data-reg');
            var address = $(this).attr('data-address');
            var mobile_no = $(this).attr('data-mobileno');
            var contact_no = $(this).attr('data-contactno');

            $('#id').val(id);
            $('#name').val(name);
            $('#description').val(reg_no);
            $('#description').val(address);
            $('#description').val(mobile_no);
            $('#description').val(contact_no);

        });
    </script>

    <script>
        $('#reset').click(function(e) {
            e.preventDefault();
            $('#from').val('');
            $('#to').val('');
            $('#filter_by').val('');
        });


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
