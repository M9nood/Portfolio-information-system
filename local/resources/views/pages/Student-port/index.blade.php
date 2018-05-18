<?php
use App\func as f;
?>
@extends('layouts.app' ,['page'=>'std-portfolio'])

@section('content')
<div class="content-wrapper">
    <div class="page-title">
        <div>
           <h1><i class="fa fa-dashboard"></i> ผลงานนักศึกษา</h1>
            <p>ผลงานของนักศึกษา</p>
         </div>
         <div>
                <ul class="breadcrumb">
                  <li><a href="{{url('/')}}"><i class="fa fa-home fa-lg" ></i></a></li>
                  <li><a href="#">ผลงานนักศึกษา</a></li>
                </ul>
        </div>
    </div>
    
    @yield('subcontent')
    
</div>
<!-- Javascripts-->
    <script type="text/javascript" src="{{url('js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('js/plugins/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
    <script type="text/javascript" src="{{url('js/plugins/jquery.form.js')}}"></script>
    
    
@endsection
 
