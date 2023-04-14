@extends('errors.layout')

@php
  $error_number = 498;
@endphp

@section('title')
  This link has expired!
@endsection

@section('description')
  @php
    $default_error_message = "Please try again.";
  @endphp
  {!! isset($exception)? ($exception->getMessage()?e($exception->getMessage()):$default_error_message): $default_error_message !!}
  <div class="text-center" style="margin-top: 13px"><a href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a>
/ <a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div>
@endsection
