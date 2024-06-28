@extends('layouts.navigation')
@section('salary_payable', 'active')
@section('content')

    <div class="app-main__inner">
        <div class="tab-content">
            <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
                <div class="main-card mb-3 card">
                    <div class="card-body">

                        <form class="needs-validation" novalidate="" method="post"
                            action="{{ route('hr.salaryPayable.store') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label class="form-label" for="title">Date</label>
                                        <input type="date" name="date" required class="form-control" id="date" />
                                    </div>
                                    <div class="form-group ">
                                        <label class="form-label" for="title">Salary Month</label>
                                        <input type="month" name="salary_month" required class="form-control"
                                            id="salary_month" />
                                    </div>
                                    <div class="form-group">
                                        <label for="lname">Employee</label>
                                        <div>
                                            <input type="text" name="emp_name" id="emp_name" data-toggle="modal"
                                                data-target="#searchEmp_" placeholder="Click here to select employee"
                                                required readonly class="form-control" />
                                            <input hidden type="text" name="emp_id" id="emp_id"
                                                class="form-control" />
                                            <p style="color:Tomato"> @error('emp_id')
                                                    {{ $message }}
                                                @enderror
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="lname">Employee Code</label>
                                        <div>
                                            <input type="text" id="emp_code" required readonly class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="Basic Salary">Basic Salary</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input id="basic_salary" readonly type="number" class="form-control"
                                                    name="basic_salary" />
                                            </div>
                                        </div>
                                    </div>

                                    <div style="background-color:#E9ECEF;" class="col-md-12">
                                        <br>
                                        @foreach ($allowance as $row)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>{{ $row->name }}</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div>
                                                                <input hidden type="text"
                                                                    class="form-control budget_input"
                                                                    value="{{ $row->id }}"
                                                                    name="allowance_type_id[]" />
                                                                <input type="number" min="0" step="0.1"
                                                                    id="allowance_type_id_{{ $row->id }}"
                                                                    class="form-control budget_input b_val allowance-class"
                                                                    value="0.00" name="allowance_type_val[]" onkeyup="salaryChange()"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                        @endforeach

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="No pay Leaves">Extra Work Amount</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input id="over_time_work" type="number" min="0.0"
                                                        step="0.1" value="0.00" class="form-control"
                                                        name="over_time_work" onkeyup="salaryChange()"/>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="Gross Salary">Gross Salary</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input id="gross_salary" readonly type="number" class="form-control"
                                                    name="gross_salary" step="0.01"/>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="Company EPF">Company EPF</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input id="company_epf" readonly type="number" class="form-control"
                                                    name="company_epf" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="Company ETF">Company ETF</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input id="company_etf" readonly type="number" class="form-control"
                                                    name="company_etf" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="EPF">EPF</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input id="epf" readonly type="number" class="form-control"
                                                    name="epf" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="Advance Payment">Advance Payment</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input id="advance_payment_amount" readonly type="number"  value="0.00" class="form-control" name="advance_payment_amount" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Others (+ or -)</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input id="others" type="number" step="0.1" value="0.00"
                                                    class="form-control" name="others" onkeyup="salaryChange()"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="Net Salary">Net Salary</label>
                                            </div>
                                            <div class="col-md-6">
                                                <input id="net_salary" readonly type="number" step="0.01"
                                                    class="form-control" name="net_salary" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer d-block clearfix">
                                <div class="float-right">
                                    <button type="reset" class="btn btn-danger" id="showtoast_">Reset</button>
                                    <button type="submit" class="btn btn-success" id="showtoast">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')

    {{-- search employee --}}
    <div class="modal fade" id="searchEmp_" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Search Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Custom Search..." id="employee-Search">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <br>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="20%">Code</th>
                                    <th width="20%">Employee Name</th>
                                    <th width="20%">Department Name</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="employee_">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        getEmployee();

        var today = new Date();
        document.getElementById("date").value = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1))
            .slice(-2) + '-' + ('0' + today.getDate()).slice(-2);

        document.getElementById("salary_month").value = today.getFullYear() + '-' + ('0' + (today.getMonth() +
            1)).slice(-2);

        $('input[type="number"]').on('click', function() {
            $(this).select();
        });

        // search employee
        $(document).on('keyup', '#employee-Search', function() {
            getEmployee();
        });

        function getEmployee() {
            var query = $('#employee-Search').val();

            $.ajax({
                url: "{{ route('common.search-employee') }}",
                method: 'GET',
                data: {
                    query: query
                },
                dataType: 'json',
                contentType: "application/json",
                success: function(res) {

                    $('#employee_').html('');

                    if (res != '') {
                        $.each(res, function(index, row) {

                            employee_row = ` <tr>
                                                <td >` + row.emp_code + `</td>
                                                <td >` + row.f_name + `</td>
                                                <td >` + row.department_name + `</td>
                                                <td style="text-align:center">
                                                    <a class="btn btn-small btn-success empSelect" href="#" style="padding:0px 20px" 
                                                    data-emp_id=" ` + row.id + `"data-emp_code="` + row.emp_code +
                                `" data-emp_name =" ` + row.f_name + `" 
                                                    >Select</a>
                                                </td>
                                            </tr>`;

                            $('#employee_').append(employee_row);

                        });

                    } else {

                        employee_row =
                            '<tr><td align="center" colspan="4">No Data Found</td></tr>';

                        $('#employee_').append(employee_row);
                    }

                    // select employee
                    $('.empSelect').on('click', function() {

                        $(':input[type="number"]').val(0.00);

                        $('#emp_id').val($(this).attr('data-emp_id'));
                        $('#emp_name').val($(this).attr('data-emp_name'));
                        $('#emp_code').val($(this).attr('data-emp_code'));

                        $('#searchEmp_').modal('hide'); // model hide

                        var emp_id = $(this).attr('data-emp_id');
                        var salary_month = $('#salary_month').val();
                        $.ajax({
                            method: 'get',
                            url: '{{ route('hr.getDetailForSalary') }}',
                            dataType: 'json',
                            data: {
                                'emp_id': emp_id,
                                'salary_month': salary_month,
                            },
                            success: function(res) {
                                console.log(res);
                                var basic_salary = res[0].basic_salary;
                                var allowance = res[0].allowance;
                                var over_time_work_amount = res[0].over_time_work_amount;
                                var advance_payment_amount = res[0].advance_payment_amount;
                                
                                if (!basic_salary || basic_salary ==null) { // emp salary
                                    basic_salary = 0.00;

                                    epf = 0.00;
                                    $("#epf").val(epf);
                                    $("#company_epf").val(0.00);
                                    $("#company_etf").val(0.00);

                                } else {
                                    basic_salary = basic_salary;

                                    epf = basic_salary * 0.08;
                                    $("#epf").val(epf);
                                    $("#company_epf").val(basic_salary * 0.12);
                                    $("#company_etf").val(basic_salary * 0.03);
                                }
                                $("#basic_salary").val(
                                basic_salary); // emp basic salary
                                $('#over_time_work').val(
                                over_time_work_amount); // over time work amount
                                $('#advance_payment_amount').val(
                                advance_payment_amount);  // advance payment amount
                                var total_allowance = 0;
                                if (allowance) { // have allowance
                                    allowance.forEach( element => { // allowance value add
                                        var allowance_type_id = element.allowance_type_id;
                                        var allowance_amount = element.amount;

                                        $('#allowance_type_id_' + allowance_type_id).val(allowance_amount);

                                        total_allowance += parseFloat(allowance_amount);

                                    });
                                }

                                var gross_salary = parseFloat(basic_salary) + parseFloat(total_allowance) + parseFloat(over_time_work_amount);
                                $('#gross_salary').val(gross_salary);

                                var net_salary = (parseFloat(gross_salary) - [parseFloat(epf) + parseFloat(advance_payment_amount)]).toFixed(2);
                                $('#net_salary').val(net_salary);

                            }
                        });

                    });

                }
            })
        }

    });

    function salaryChange() {
        var now_allowance = 0;
        var basic_salary = $('#basic_salary').val() ? $('#basic_salary').val() : 0;
        var epf = $('#epf').val() ? $('#epf').val() : 0;
        var over_time_work = $('#over_time_work').val() ? $('#over_time_work').val() : 0;
        var others = $('#others').val() ? $('#others').val() : 0;
        var advance_payment_amount = $('#advance_payment_amount').val() ? $('#advance_payment_amount').val() : 0;

        $('.allowance-class').each(function() {
            if (parseFloat($(this).val())) {
                value = parseFloat($(this).val());
            } else {
                value = 0;
            }
            now_allowance = parseFloat(now_allowance) + parseFloat(value);
        });

        $('#gross_salary').val((parseFloat(basic_salary) + parseFloat(now_allowance) + parseFloat(over_time_work)).toFixed(2));

        $('#net_salary').val((parseFloat(basic_salary) + parseFloat(now_allowance) + parseFloat(over_time_work) - parseFloat(epf) - parseFloat(advance_payment_amount)  + parseFloat(others)).toFixed(2));

    }
</script>
