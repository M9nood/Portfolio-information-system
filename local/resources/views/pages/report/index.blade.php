<?php
use App\func as f;
?>
@extends('layouts.app',['page'=>'report'])


@section('content')
<div class="content-wrapper">
    <div class="page-title">
        <div>
           <h1><i class="fa fa-dashboard"></i> รายงานสรุปผล</h1>
            <p>การเรียกดูรายงานสรุปผลของคณะ  / ภาควิชา</p>
         </div>
         <div>
                <ul class="breadcrumb">
                  <li><a href="{{url('/')}}"><i class="fa fa-home fa-lg" ></i></a></li>
                  <li><a href="#">รายงานสรุปผล</a></li>
                </ul>
        </div>
    </div>
   
    @yield('subcontent')
    
</div>

    
    
@endsection
 
