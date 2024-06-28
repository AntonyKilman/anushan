@extends('layouts.navigation')
@section('return_reason', 'active')
@section('content')
    <?php
    $Access = session()->get('Access');
    $returnReasonAdd = false;
    $returnReasonEdit = false;
    if (in_array('inventory.reasonAddProcess', $Access)) {
        $returnReasonAdd = true;
    }
    if (in_array('inventory.reasonUpdateProcess', $Access)) {
        $returnReasonEdit = true;
    }
    ?>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4 class="header">Return Reason</h4>
            @if ($returnReasonAdd)
                <button data-toggle="modal" data-target="#reason_add" title="edit" class="btn btn-success">Add</button>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-1">
                    <thead>
                        <tr>
                        
                            <th style="display: none">#</th>
                            <th>Name</th>
                            <th>Description</th>
                            @if ($returnReasonEdit)
                                <th class='action'>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reasons as $reason)
                            <tr>
                             
                                <td style="display: none">#</td>
                                <td>{{ $reason->reason_name }}</td>
                                <td>{{ $reason->reason_des }}</td>
                                @if ($returnReasonEdit)
                                    <td class='action'>
                                        <button data-toggle="modal" data-id="{{ $reason->id }}"
                                            data-name="{{ $reason->reason_name }}" data-des="{{ $reason->reason_des }}"
                                            data-target="#reason_edit" title="edit" class="btn btn-primary btn-edit"><i
                                                class="far fa-edit"></i></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection


@section('model')

{{-- update modal start --}}
<div class="modal fade" id="reason_edit" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Return Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/reason-update-process" class="needs-validation" novalidate="" method="post">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{old('id')}}">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control" required>
                        @error('name')
                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                    </div>


                    <div align="right">
                        <button type="reset" id="editReset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" type="submit">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
{{-- update modal end --}}




{{-- add modal start --}}
<div class="modal fade" id="reason_add" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Return Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/reason-add-process" class="needs-validation" novalidate="" method="post">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control" required>
                        @error('name')
                            <span style="color: rgb(151, 4, 4); font-weight:bolder">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                    </div>


                    <div align="right">
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button class="btn btn-success mr-1" type="submit">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
{{-- add modal end --}}
@endsection

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        if (!@json($errors->isEmpty())) {
            console.log("erorr");
            var id = $('#id').val();
            console.log(id);
            if (id) {
                $('#reason_edit').modal();

            } else {
                
                $('#reason_add').modal();
            }
        }
    });

    $(document).on('click', '.btn-edit', function() {
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var des = $(this).attr('data-des');

        $('#id').val(id);
        $('#name').val(name);
        $('#description').val(des);


        $('#editReset').click(function(e) {
            e.preventDefault();
            $('#id').val(id);
            $('#name').val(name);
            $('#description').val(des);

        });
    });
</script>
