@extends('backend.layouts.app')

@section('title') @lang("Dashboard") @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs />
@endsection

@section('content')
<div class="card mb-4 ">
    <div class="card-body">

        <x-backend.section-header>
            Welcome to <b>{{config('app.name')}}</b> Dashboard.
        </x-backend.section-header>
        <hr>
        <!-- Dashboard Content Area -->

    </div>
</div>
<!-- / card -->

@include("backend.includes.dashboard_demo_data")

@endsection