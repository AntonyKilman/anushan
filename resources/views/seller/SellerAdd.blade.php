@extends('layouts.navigation')
@section('seller','active')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>

      <!-- Main Content -->
      <div class="card">
    <form action="/seller-add-process" method="post" class="needs-validation" novalidate="" enctype="multipart/form-data">
      @csrf
        <div class="card-header">
            <h4>Supplier</h4>
        </div>
        
        <div class="card-body form" style="width:100%">
            
             {{-- first row --}}
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Name</label>
              <input type="text" id="name" value="{{old('name')}}" name="name" class="form-control" pattern="^[a-zA-Z.\\-\\/+=@_ ]*$" required="">
              @error('name')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-4">
              <label>Register No</label>
              <input type="text" id="reg_no" value="{{old('reg_no')}}" name="reg_no" class="form-control">
              @error('reg_no')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-4">
              <label>Supplier Type</label>
              <select class="form-control" name="seller_type_id" required>
                <option value="" disabled selected>Select Supplier Type</option>
                @foreach ($seller_types as $seller_type)
                  <option value="{{$seller_type->id}}"
                    {{ $seller_type->id == old('seller_type_id') ? 'selected' : '' }}>
                    {{$seller_type->seller_type_name}}</option>
                @endforeach
              </select>
            </div>
          </div>

           {{-- second row --}}
           <div class="form-row">
            <div class="form-group col-md-3">
              <label>Mobile No</label>
                <input type="text" id="mobile_no" value="{{old('mobile_no')}}" name="mobile_no" pattern="^\d{2}\d{3}\d{4}$" placeholder="771234567" class="form-control">
                @error('mobile_no')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-3">
              <label>Office Contact No</label>
                <input type="text" id="office_no" value="{{old('office_no')}}" name="office_no" pattern="^\d{2}\d{3}\d{4}$" placeholder="212261234" class="form-control">
                @error('office_no')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-6">
              <label>Address</label>
              <textarea class="form-control" id="address" name="address" required>{{old('address')}}</textarea>
            </div>
          </div>

          {{-- third row --}}
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Image 1</label><br>
                  {{-- <img src="/images/{{$seller->seller_img_1}}" class="css-class" alt="Null" style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;"> --}}
                  <input type="file" class="form-control" value="{{old('image_1')}}" id="image" name="image_1">
                  <span>Max 2Mb</span>
                  @error('image_1')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-4">
              <label>Image 2</label><br>
                  {{-- <img src="/images/{{$seller->seller_img_2}}" class="css-class" alt="Null" style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;"> --}}
                  <input type="file" class="form-control" value="{{old('image_2')}}" id="image" name="image_2" >
                  <span>Max 2Mb</span>
                  @error('image_2')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
            <div class="form-group col-md-4">
              <label>Image 3</label><br>
                  {{-- <img src="/images/{{$seller->seller_img_3}}" class="css-class" alt="Null" style="width: 100px; height:100px; border:solid 1px white; border-radius:15px;"> --}}
                  <input type="file" class="form-control" value="{{old('image_3')}}" id="image" name="image_3" >
                  <span>Max 2Mb</span>
                  @error('image_3')<span style="color: rgb(151, 4, 4); font-weight:bolder">{{$message}}</span>@enderror
            </div>
          </div>


            {{-- <div class="form-group">
              <label>Image</label>
                <div >
                  <button type="button" class="btn btn-primary" id="img"><i class="fas fa-plus"></i>&nbsp Add</button>
                </div>
            </div>

            <div class="form-group img">
              <input type="file" id="image" name="image[]" style="width:50%;">
                
                
              
            </div> --}}
           
            <div align="right">
                <button type="reset" class="btn btn-danger">Reset</button>
                <button class="btn btn-success mr-1" type="submit">Submit</button>
            </div>
        </div>
    </form>   
      </div> 
    
    <script>
      var b=1;
        $(document).ready(function() {
          $('#img').on('click',function(){
            var html='<div class="form-group" style="width:50%"><input type="file" id="image" name="image[]" style="width:50%;"><button type="button" id="remove" class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button></div>';
            if (b<4) {
              $('.img').append(html);
              console.log(b);
              b++;
            } else {
              alert('Maximum three image allowed');
            }
          });
        });

      $(document).on('click','#remove',function(){
        $(this).closest('div').remove();
        b--;
        console.log(b);
      });
    </script>
@endsection