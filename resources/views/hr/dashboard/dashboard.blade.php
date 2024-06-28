@extends('layouts.navigation')
@section('dashboard', 'active')
@section('content')


    <h1> HR</h1>
    <h3>Dashboard</h3>
    <br>
    <br>


    {{-- properry --}}
    <div class="card">
        <div class="no-gutters row">
            <div class="col-md-12 col-lg-4">
                <ul class="list-group list-group-flush">
                    <li class="bg-transparent list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-outer">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading"></div>
                                        <div style="font-size:15px;font-weight: bold;" class="widget-subheading">Total
                                            Properties</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div style="font-size:30px;font-weight: bold;" class="widget-numbers text-warning">
                                            {{ $total_properities }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-12 col-lg-4">
                <ul class="list-group list-group-flush">
                    <li class="bg-transparent list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-outer">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading"></div>
                                        <div style="font-size:15px;font-weight: bold;" class="widget-subheading">Active
                                            Properties</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div style="font-size:30px;font-weight: bold;" class="widget-numbers text-success">
                                            {{ $active_properities }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-12 col-lg-4">
                <ul class="list-group list-group-flush">
                    <li class="bg-transparent list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-outer">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading"></div>
                                        <div style="font-size:15px;font-weight: bold;" class="widget-subheading">Inactive
                                            Properties</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div style="font-size:30px;font-weight: bold;" class="widget-numbers  text-danger">
                                            {{ $inactive_properities }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Departments --}}
    <div class="card">
        <div class="no-gutters row">
            <div class="col-md-12 col-lg-4">
                <ul class="list-group list-group-flush">
                    <li class="bg-transparent list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-outer">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading"></div>
                                        <div style="font-size:15px;font-weight: bold;" class="widget-subheading">Total
                                            Departments</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div style="font-size:30px;font-weight: bold;" class="widget-numbers text-warning">
                                            {{ $total_departments }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-12 col-lg-4">
                <ul class="list-group list-group-flush">
                    <li class="bg-transparent list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-outer">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading"></div>
                                        <div style="font-size:15px;font-weight: bold;" class="widget-subheading">Active
                                            Departments</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div style="font-size:30px;font-weight: bold;" class="widget-numbers text-success">
                                            {{ $active_departments }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-12 col-lg-4">
                <ul class="list-group list-group-flush">
                    <li class="bg-transparent list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-outer">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading"></div>
                                        <div style="font-size:15px;font-weight: bold;" class="widget-subheading">Inactive
                                            Departments</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div style="font-size:30px;font-weight: bold;" class="widget-numbers  text-danger">
                                            {{ $inactive_departments }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- employees --}}
    <div class="card">
        <div class="no-gutters row">
            <div class="col-md-12 col-lg-4">
                <ul class="list-group list-group-flush">
                    <li class="bg-transparent list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-outer">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading"></div>
                                        <div style="font-size:15px;font-weight: bold;" class="widget-subheading">Total
                                            Employees</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div style="font-size:30px;font-weight: bold;" class="widget-numbers text-warning">
                                            {{ $total_employee }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-12 col-lg-4">
                <ul class="list-group list-group-flush">
                    <li class="bg-transparent list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-outer">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading"></div>
                                        <div style="font-size:15px;font-weight: bold;" class="widget-subheading">Active
                                            Employees</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div style="font-size:30px;font-weight: bold;" class="widget-numbers text-success">
                                            {{ $active_employee }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-12 col-lg-4">
                <ul class="list-group list-group-flush">
                    <li class="bg-transparent list-group-item">
                        <div class="widget-content p-0">
                            <div class="widget-content-outer">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading"></div>
                                        <div style="font-size:15px;font-weight: bold;" class="widget-subheading">Inactive
                                            Employees</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div style="font-size:30px;font-weight: bold;" class="widget-numbers  text-danger">
                                            {{ $inactive_employee }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection
