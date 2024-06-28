@extends('layouts.navigation')
@section('seller','active')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>

      <!-- Main Content -->
      <div class="card">
       

    <form>
      
      @csrf
        <div class="card-header">
          <div>
            <a href="/seller-show-all" class="btn btn-warning fas fa-arrow-left">Back</a>
        </div>
            <h4>Supplier</h4>
        </div>
        <input type="hidden" name="id" value="{{$seller->id}}">
        <div class="card-body form" style="width: 100%">

        {{-- first row --}}
        <div class="form-row">
          <div class="form-group col-md-4">
            <label>Name</label>
              <input type="text" value="{{$seller->seller_name}}" id="name" name="name" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Register No</label>
                <input type="text" value="{{$seller->seller_reg_no}}" id="reg_no" name="reg_no" class="form-control" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>Supplier Type</label>
                <input type="text" value="{{$seller->seller_type_name}}" id="reg_no" name="reg_no" class="form-control" readonly>
          </div>
        </div>


        {{-- second row --}}
        <div class="form-row">
          <div class="form-group col-md-3">
            <label>Mobile No</label>
                <input type="text" value="{{$seller->mobile_no}}" id="mobile_no" name="mobile_no" pattern="^\d{2}\d{3}\d{4}$" class="form-control" readonly>
          </div>
          <div class="form-group col-md-3">
            <label>Office Contact No</label>
                <input type="text" value="{{$seller->contact_no}}" id="office_no" name="office_no" pattern="^\d{2}\d{3}\d{4}$" class="form-control" readonly>
          </div>
          <div class="form-group col-md-6">
            <label>Address</label>
              <textarea class="form-control" id="address" name="address" readonly>{{$seller->seller_address}}</textarea>
          </div>
        </div>

        @php
          $file_1 = Str::after($seller->seller_img_1, '.');
          $file_2 = Str::after($seller->seller_img_2, '.');
          $file_3 = Str::after($seller->seller_img_3, '.');
        @endphp
        
        {{-- third row --}}
        <div class="form-row">
          @if ($file_1=='jpg' )
              
          @endif
          <div class="form-group col-md-4">
            <label>Image 1</label><br>
                <img src="/images/{{$seller->seller_img_1}}" class="css-class" alt="Null" style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;">
          </div>
          <div class="form-group col-md-4">
            <label>Image 2</label><br>
                <img src="/images/{{$seller->seller_img_2}}" class="css-class" alt="Null" style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;">
          </div>
          <div class="form-group col-md-4">
            <label>Image 3</label><br>
                <img src="/images/{{$seller->seller_img_3}}" class="css-class" alt="Null" style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;">
          </div>
        </div>

            <div class="card-footer text-right">
              <a href="/seller-edit/{{$seller->id}}" title="edit" class="btn btn-primary btn-edit"><i class="far fa-edit"></i></a>
                {{-- <a href="/seller-edit/{{$seller->id}}" class="btn btn-warning">Edit</a> --}}
                
            </div>
        </div>
    </form>
      </div>    
    
@endsection