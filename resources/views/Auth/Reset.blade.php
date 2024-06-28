@extends('layouts.navigation')
@section('content')
<?php 
  $Access=session()->get('Access'); 
?>


    <?php
    $Access = session()->get('Access');
    $r = false;
    
    if (in_array('main_reset.store', $Access)) {
        $r = true;
    }
    
    ?>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div style="padding: 10px;">


                            <h2>
                                Reset Password

                            </h2>



                        </div>
                        <div class="card-body">
                            <form class="___class_+?19___" method="POST" action="{{ route('main_reset.store') }}">
                                @csrf


                                <div class="form-group">
                                    <label>Old Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-text-width"></i>
                                            </div>
                                        </div>
                                        <input type="password" required class="form-control ta_title"
                                            placeholder="Old Password" name="old_password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>New Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-text-width"></i>
                                            </div>
                                        </div>
                                        <input type="password" required class="form-control en_title"
                                            placeholder="New Password" name="new_password">

                                    </div>
                                    <span class="text-danger">
                                        @error('new_password')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>



                                <div class="form-group">
                                    <label>Conform Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-text-width"></i>
                                            </div>
                                        </div>
                                        <input type="password" required class="form-control en_title"
                                            placeholder="Conform Password" name="conform_password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="error meg">
                                        <span class="text-danger">
                                            @if (isset($msg))
                                                {{ $msg }}
                                            @endif

                                        </span>
                                    </label>
                                </div>
                                @if ($r = true)

                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
