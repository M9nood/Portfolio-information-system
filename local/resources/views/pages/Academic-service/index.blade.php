<?php
use App\func as f;
?>
@extends('layouts.app',['page'=>'academic-service'])


@section('content')
<link rel="stylesheet" href="{{ url('css/bootstrap-datepicker.css') }}">
<div class="content-wrapper">
    <div class="page-title">
        <div>
           <h1><i class="fa fa-dashboard"></i> งานบริการวิชาการและอื่นๆ</h1>
            <p>งานบริการวิชาการและอื่นๆ</p>
         </div>
         <div>
                <ul class="breadcrumb">
                  <li><a href="{{url('/')}}"><i class="fa fa-home fa-lg" ></i></a></li>
                  <li><a href="#">งานบริการวิชาการและอื่นๆ</a></li>
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
 
