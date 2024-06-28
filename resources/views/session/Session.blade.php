<?php 
  $Access=session()->get('Access'); 
  $rest=false;
  $hotel=false;
  $farm=false;
  $main=false;

  if(in_array('rest.show', $Access)){
  $rest=true;
  }

  if(in_array('hotel.show', $Access)){
  $hotel=true;
  }

  if(in_array('main.show', $Access)){
    $main=true;
  }

  if(in_array('farm.show', $Access)){
    $farm=true;
  }
?>