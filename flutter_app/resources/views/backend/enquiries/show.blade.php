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
                        <tr>
                            <th>Name</th>
                            <td>{{$$module_name_singular->name}}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{$$module_name_singular->email}}</td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td>{{$$module_name_singular->phone}}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{$$module_name_singular->description}}</td>
                        </tr>
                        <tr>
                            <th>Added At</th>
                            <td>{{$$module_name_singular->created_at->isoFormat('LLLL')}}</td>
                        </tr>
                    </table>
                </div>
                <a href="{{route("backend.$module_name.destroy", $$module_name_singular)}}" class="btn btn-danger mt-1" data-method="DELETE" data-token="{{csrf_token()}}" data-toggle="tooltip" title="{{__('labels.backend.delete')}}" data-confirm="Are you sure?"><i class="fas fa-trash-alt"></i> Delete</a>
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