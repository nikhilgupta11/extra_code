@extends('backend.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('breadcrumbs')
    <x-backend-breadcrumbs>
        <x-backend-breadcrumb-item route='{{ route("backend.$module_name.index") }}' icon='{{ $module_icon }}'>
            {{ __($module_title) }}
        </x-backend-breadcrumb-item>
        <x-backend-breadcrumb-item type="active">{{ __($module_action) }}</x-backend-breadcrumb-item>
    </x-backend-breadcrumbs>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <x-backend.section-header>
                <i class="{{ $module_icon }}"></i> {{ __($module_title) }} <small
                    class="text-muted">{{ __($module_action) }}</small>

                <x-slot name="toolbar">
                    <x-backend.buttons.return-back />
                </x-slot>
            </x-backend.section-header>

            <hr>

            <div class="row mt-4">
                <div class="col">
                    {{ html()->form('POST', route("backend.$module_name.store"))->acceptsFiles()->attributes(['id' => 'formValidate'])->class('form')->open() }}

                    @include ("$module_path.$module_name.form")

                    <div class="d-flex flex-wrap">
                        {{ html()->button($text = "<i class='fas fa-plus-circle'></i> " . ucfirst($module_action) . '', $type = 'submit')->class('btn btn-success') }}
                        <a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary ms-2" data-toggle="tooltip" title="{{__('labels.backend.cancel')}}"><i class="fas fa-reply"></i> Cancel</a>
                    </div>
                    {{ html()->form()->close() }}

                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col">

                </div>
            </div>
        </div>
    </div>
@endsection
