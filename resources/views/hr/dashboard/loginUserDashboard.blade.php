@extends('layouts.navigation')
@section('auth_dashboard', 'active')
@section('content')


    <h1>HR</h1>
    <h3>Login User Dashboard</h3>
    <br>
    <br>

    <div class="row">
        @foreach ($leave_counts as $leave)
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-2" style="font-weight: 900;font-size: 20px">{{ $leave->leave_type_name }}
                        </div>
                        <span class="widget-numbers text-success"
                            style="font-weight: 900; width: 50px; font-size: 30px">{{ $leave->no_of_days }}</span>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-2" style="font-weight: 900;font-size: 20px">Lieu Leave
                    </div>
                    <span class="widget-numbers text-success"
                        style="font-weight: 900; width: 50px; font-size: 30px">{{ $lieu_leave_count }}</span>
                </div>
            </div>
        </div>
    </div>





@endsection
