@extends('layouts.navigation')
{{-- @section('product_in', 'active') --}}
@section('content')
     <?php
    $Access = session()->get('Access');
    $c = true;
    $u = true;
    $d = true;
    
    // if (in_array('main_our_team.store', $Access)) {
    //     $c = true;
    // }
    
    // if (in_array('main_our_team.update', $Access)) {
    //     $u = true;
    // }
    
    // if (in_array('main_our_team.destroy', $Access)) {
    //     $d = true;
    // }
    
    ?> 
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">
                            <h2> Product In</h2>                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Date</th>
                                            <th>Batch ID</th>
                                            <th>Transaction ID</th>
                                            <th>Total Qty</th>
                                            <th>Expiry Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1;?>
                                    
                                    @foreach ($unique_products as $row)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->created_at)); }}</td>
                                            <td>{{ $row->purchase_order_id }}</td>
                                            <td>{{ $row->transection_id }}</td>
                                            <td>{{ $row->quantity }}</td>
                                            <td>{{ $row->expire_date }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-primary"
                                                    href="{{route('product.viewProductIn', $row->transection_id)}}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
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
