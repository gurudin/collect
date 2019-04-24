@extends('layouts.admin')

@section('title')
Welcome {{ Auth::user()->name }}
@endsection

@section('css')
@endsection

@section('content')
<div class="jumbotron bg-white">
  <h1 class="display-4">Welcome, {{ Auth::user()->name }}!</h1>
  <p class="lead">You have successfully logged in Widget Admin.</p>
  <hr class="my-4">
  <p><i class="fas fa-exclamation-triangle"></i> We log every actions and operations, please use with care.</p>
</div>


@endsection

@section('script')
@endsection
