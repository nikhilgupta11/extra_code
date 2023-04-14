@extends ('backend.layouts.app')

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

        <div class="row mt-4 mb-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{$$module_name_singular->user->name ?? ''}}</td>
                            </tr>
                            <tr>
                                <th>From</th>
                                <td>{{$$module_name_singular->from}}</td>
                            </tr>
                            <tr>
                                <th>To</th>
                                <td>{{$$module_name_singular->to}}</td>
                            </tr>
                            <tr>
                                <th>Peoples</th>
                                <td>{{$$module_name_singular->peoples}}</td>
                            </tr>
                            <tr>
                                <th>Transports</th>
                                <td>{{implode(', ',$$module_name_singular->transports->pluck('transport_type')->toArray())}}</td>
                            </tr>
                            <tr>
                                <th>Added At</th>
                                <td>{{$$module_name_singular->created_at->isoFormat('LLLL')}}</td>
                            </tr>
                            <tr class="text-center bg-light">
                                <th colspan="2">Accommodation</th>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{$$module_name_singular->accommodation->country}}</td>
                            </tr>
                            <tr>
                                <th>Number Of OverNights</th>
                                <td>{{$$module_name_singular->accommodation->number_overnights}}</td>
                            </tr>
                            <tr>
                                <th>Number Of Rooms</th>
                                <td>{{$$module_name_singular->accommodation->number_rooms}}</td>
                            </tr>
                            <tr>
                                <th>Hotel Stars</th>
                                <td>{{$$module_name_singular->accommodation->hotel_stars}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/col-->

        </div>
        <!--/.row-->
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