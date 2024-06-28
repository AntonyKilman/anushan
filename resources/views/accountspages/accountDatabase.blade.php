@extends('layouts.navigation')
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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="database-account">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>dept_id</th>
                                            <th>date</th>
                                            <th>account_type</th>
                                            <th>category</th>
                                            <th>sub_category</th>
                                            <th>description</th>
                                            <th>connected_id</th>
                                            <th>customer_id</th>
                                            <th>table_id</th>
                                            <th>cash</th>
                                            <th>card</th>
                                            <th>cheque</th>
                                            <th>credit_amount</th>
                                            <th>purchase_amount</th>
                                            
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($accountData as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->credit }}</td>
                                                <td>{{ $item->debit }}</td>
                                                <td>{{ $item->dept_id }}</td>
                                                <td>{{ $item->date }}</td>
                                                <td>{{ $item->account_type }}</td>
                                                <td>{{ $item->category }}</td>
                                                <td>{{ $item->sub_category }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->connected_id }}</td>
                                                <td>{{ $item->customer_id }}</td>
                                                <td>{{ $item->table_id }}</td>
                                                <td>{{ $item->cash }}</td>
                                                <td>{{ $item->card }}</td>
                                                <td>{{ $item->cheque }}</td>
                                                <td>{{ $item->credit_amount }}</td>
                                                <td>{{ $item->purchase_amount }}</td>
                                                
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
            $('#database-account').DataTable();
        });
    </script>
@endsection