@extends('layouts.navigation')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>

      <!-- Main Content -->
      <div class="card">
    <form action="/seller-type-update-process" method="post">
      @csrf
        <div class="card-header">
            <h4>Supplier Type</h4>
        </div>
        <input type="hidden" name="id" value="{{$seller_type->id}}">
        <div class="card-body form">
            <div class="form-group">
              <label>Name</label>
              <input type="text" value="{{$seller_type->seller_type_name}}" id="name" name="name" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" required>
              @error('name')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            
            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" id="description" name="description">{{$seller_type->seller_type_des}}</textarea>
            </div>
           
           
            <div align='right'>
              <button type="reset" class="btn btn-danger">Reset</button>
              <button class="btn btn-primary mr-1" type="submit">Submit</button>
            </div>
        </div>
    </form>        
      </div>
@endsection