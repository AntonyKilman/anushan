@extends('layouts.navigation')
@section('employee_report', 'active')
@section('content')


    <div class="card card-success">
        <div class="card-header">
            <h4>Employee Report</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-9">
            </div>
            <div class="col-md-3">
                    <label>Status</label>
                    <select  class="form-control" name="status" id="status" onchange="window.location.assign('/report/emp-report?status=' + $('#status').val())">
                        <option value="All" {{ ($status == 'All') ? 'selected' : '' }}>All</option>
                        <option value="Active" {{ ($status == 'Active') ? 'selected' : '' }}>Active</option>
                        <option value="Deactive" {{ ($status == 'Deactive') ? 'selected' : '' }}>Deactive</option>
                    </select>
            </div>
        </div>

        <div class="table-responsive">
            <table id="employee_table" class="mb-0 table table-striped table">
                <thead style="background-color:#a6d7ff">
                    <tr>
                        <th>No</th>
                        <th>Employee Code</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>NIC</th>
                        <th>Department </th>
                        <th>Address</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($all_employees as $row)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $row->emp_code }}</td>
                            <td>{{ $row->f_name }}</td>
                            <td>{{ $row->l_name }}</td>
                            <td>{{ $row->role_name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->nic }}</td>
                            <td>{{ $row->department_name }}</td>
                            <td>{{ $row->address }}</td>
                            <td>{{ $row->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        </div>
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        $('#employee_table').DataTable({
            dom: 'Brtip',
            bPaginate: true,
            bSort: true,
            buttons: [{
                    extend: 'print',
                    text: 'print',
                    title: 'Jaffna Electrical ',
                    messageTop: 'Employee Report',
                    pageOrientation: 'landscape',
                    footer: true,
                    header: true,
                    customize: function(doc) {
                        $(doc.document.body).find('td').css('font-size', '11pt');
                        $(doc.document.body).find('th').css('font-size', '11pt');
                    }
                },
                {
                    extend: 'excel',
                    text: 'excel',
                    title: 'Jaffna Electrical ',
                    messageTop: 'Employee Report',
                    footer: true,
                    header: true
                },   
            ],
        });

    });
</script>
