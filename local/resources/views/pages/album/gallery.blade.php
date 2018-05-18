<?php
use App\func as f;
?>
@extends('layouts.app',['page'=>''])


@section('content')
<div class="content-wrapper">
    <div class="page-title">
        <div>
        <h1><i class="fa fa-dashboard"></i> อัลบั้ม ผลงานนักศึกษา</h1>
        <p>แกลอรี่ อัลบั้มรูปผลงานของนักศึกษา</p>
        </div>
        <div>
          <ul class="breadcrumb">
            <li><a href="{{url('/')}}"><i class="fa fa-home fa-lg" ></i></a></li>
            <li><a href="{{url('/album/dept/std-portfolio')}}">อัลบั้ม</a></li>
            <li><a href="#">{{$album->album_name}}</a></li>
          </ul>
        </div>
    </div>
    <div class="row" id="section-content" style="display:none">
      <div class='col-md-12'>
        <div class="card">
 
         <!-- Tab panes -->
         <div class="tab-content">
           <div role="tabpanel" class="tab-pane active" id="home">
              <div class="">
                  <div class="title-content" >
                      <h1 class="page-header">{{$album->album_name}}</h1>
                  </div>
                </div>
             <div class="row">
               <div class="col-sm-12">
                
                  @foreach($imgs as $key => $img)
                  <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                    <a class="thumbnail" href="#" data-image-id="" data-toggle="modal" data-caption="And if there is money left, my girlfriend will receive this car" data-image="{{'https://drive.google.com/uc?id='. $img->image_id}}" data-target="#image-gallery">
                        <img class="img-responsive" src="{{'https://drive.google.com/uc?id='. $img->image_id}}" alt="Another alt text">
                    </a>
                </div>
                  @endforeach
               </div>
             </div>
           </div>  
         </div>
       </div>
      </div>
   </div>
   <div class="row text-center" style="padding-top:10%" id="page-loading">
     <img src="{{url('img/loading-page1.gif')}}" alt="" width="200">
   </div>


<div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-gallery">
      <div class="modal-content ">
          <div class="modal-header" style="padding-bottom:0px;border-bottom:0px">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
          </div>
          <div class="modal-body">
              <img id="image-gallery-image"  class="img-responsive" src="">
          </div>
      </div>
  </div>
</div>
 
    
</div>
<!-- Javascripts-->
    <script src="{{ url('js/modal.script.js') }}"></script>
    <script src="{{ url('js/gallery.script.js') }}"></script>
<script>
$(document).ready(function(){  
  //$('#head_sort').click();
  $('#page-loading').remove();
  $('#section-content').show();
})
</script>
    
@endsection
 
