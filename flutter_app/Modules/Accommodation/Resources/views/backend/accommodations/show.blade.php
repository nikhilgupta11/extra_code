@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.$module_name.index")}}' icon='{{ $module_icon }}'>
        {{ __($module_title) }}
    </x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __($module_action) }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-body">

        <x-backend.section-header>
            <i class="{{ $module_icon }}"></i> {{ __($module_title) }} <small class="text-muted">{{ __($module_action) }}</small>

            <x-slot name="toolbar">
                <x-backend.buttons.return-back />
            </x-slot>
        </x-backend.section-header>

        <hr>

        <div class="row mt-4">
            <div class="col-12">
                @php
                    $excludes = ['trip_id'];
                @endphp
                @include('backend.includes.show',compact('excludes'))
                <table class="table table-responsive-sm table-hover table-bordered">
                    <thead>
                        <tr class="text-center bg-light">
                            <th colspan="2">Trip Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>From</th>
                            <td>{{$$module_name_singular->trip->from}}</td>
                        </tr>
                        <tr>
                            <th>To</th>
                            <td>{{$$module_name_singular->trip->to}}</td>
                        </tr>
                        <tr>
                            <th>Number of Peoples</th>
                            <td>{{$$module_name_singular->trip->peoples}}</td>
                        </tr>
                        <tr>
                            <th>Transports</th>
                            <td>{{implode(', ',$$module_name_singular->trip->transports->pluck('transport_type')->toArray())}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-end text-muted">
                    Updated: {{$$module_name_singular->updated_at->diffForHumans()}},
                    Created at: {{$$module_name_singular->created_at->isoFormat('LLLL')}}
                </small>
            </div>
        </div>
    </div>
</div>

@endsection