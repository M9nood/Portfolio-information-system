
@extends('layouts.auth',['page'=>'index'])
@section('content')
<div class="login-box text-center">
        @if(session('status')!==null) <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{session('status')}}    
        </div>
        @endif
        <img src="{{url('img/Google-Drive-Icon.png')}}" width="200" alt="">
        <div class="title">Portfolio Information System</div>
        <a  href="{{ url('auth/google') }}" style="padding:17px 10px;" class="btn btn-info btn-block">Login With Google</a>
</div>

       
@endsection


 <!-- -->