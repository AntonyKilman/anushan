@extends('layouts.navigation')
@section('activity_log', 'active')
@section('content')

    <div class="card card-success">
        <div class="card-header">
            <h4>Activity Logs</h4>
        </div>
        <div class="card-body">
            <form action="/activity-log" method="GET">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Date From</strong>
                            <input type="date" name="from_date" value="{{ $from_date }}" class="form-control" max="9999-12-31">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Date To</strong>
                            <input type="date" name="to_date" value="{{ $to_date }}" class="form-control" max="9999-12-31">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <strong>Property Name</strong>
                            <select class="form-control" name="property">
                                <option value="">Select ..</option>
                                @foreach ($property_by as $pro)
                                    <option value="{{ $pro }}" {{ $pro == $property ? 'selected' : '' }}>
                                        {{ $pro }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <strong>Search Key</strong>
                            <input type="text" name="key" value="{{ $key }}" id="key" class="form-control" placeholder="search activity & emp name">
                        </div>
                    </div>
                    <div class="col-md-2" style="margin-top:24px">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            <br>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="100">Employee Name</th>
                            <th width="100">Property Name</th>
                            <th width="300">Activity</th>
                            <th width="100">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($activity as $row)
                            <tr>
                                <td data-th="No">{{ $i++ }}</td>
                                <td data-th="User Name">{{ $row->emp_name }}</td>
                                <td data-th="Property Name">{{ $row->props }}</td>
                                <td data-th="Activity">{{ $row->decription }}</td>
                                <td data-th="Date & Time">{{ $row->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="float-right">
                {{ $activity->appends(request()->query()) }}
            </div>
            <div class="float-left">
                <b> {{ $activity->firstItem() }} - {{ $activity->lastItem() }} of
                    {{ $activity->total() }} </b>
            </div>
        </div>
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
  $(document).ready(function () {
    $('#key').click(function (e) { 
      $(this).select();     
    });
  });
</script>
