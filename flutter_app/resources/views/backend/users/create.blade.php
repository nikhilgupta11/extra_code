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
            <div class="col">

                {{ html()->form('POST', route('backend.users.store-user'))->class('form-horizontal')->open() }}

                <div class="form-group row mb-2">
                    <div class="col-sm-2">
                        {{ html()->label(__('labels.backend.users.fields.first_name'))->class('form-control-label')->for('first_name') }} <span class="text-danger">*</span>
                    </div>
                    <div class="col-sm-10">
                        {{ html()->text('first_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.first_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <div class="col-sm-2">
                        {{ html()->label(__('labels.backend.users.fields.last_name'))->class('form-control-label')->for('last_name') }} <span class="text-danger">*</span>
                    </div>
                    
                    <div class="col-sm-10">
                        {{ html()->text('last_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.last_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <div class="col-sm-2">
                        {{ html()->label(__('labels.backend.users.fields.email'))->class('form-control-label')->for('email') }} <span class="text-danger">*</span>
                    </div>

                    <div class="col-sm-10">
                        {{ html()->email('email')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.email'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <div class="col-sm-2">
                        {{ html()->label(__('labels.backend.users.fields.password'))->class('form-control-label')->for('password') }} <span class="text-danger">*</span>
                    </div>

                    <div class="col-sm-10">
                        {{ html()->password('password')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.password'))
                                ->required() }}
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <div class="col-sm-2">
                        {{ html()->label(__('labels.backend.users.fields.password_confirmation'))->class('form-control-label')->for('password_confirmation') }}<span class="text-danger">*</span>
                    </div>

                    <div class="col-sm-10">
                        {{ html()->password('password_confirmation')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.users.fields.password_confirmation'))
                                ->required() }}
                    </div>
                </div>

                <div class="form-group row mb-2">
                    {{ html()->label(__('labels.backend.users.fields.status'))->class('col-6 col-sm-2 form-control-label')->for('status') }}

                    <div class="col-6 col-sm-10">
                        {{ html()->checkbox('status', true, '1') }} @lang('Active')
                    </div>
                </div>

                <div class="form-group row mb-2">
                    {{ html()->label(__('labels.backend.users.fields.confirmed'))->class('col-6 col-sm-2 form-control-label')->for('confirmed') }}

                    <div class="col-6 col-sm-10">
                        {{ html()->checkbox('confirmed', true, '1') }} @lang('Email Confirmed')
                    </div>
                </div>

                <div class="form-group row mb-2">
                    {{ html()->label(__('labels.backend.users.fields.email_credentials'))->class('col-6 col-sm-2 form-control-label')->for('confirmed') }}

                    <div class="col-6 col-sm-10">
                        {{ html()->checkbox('email_credentials', true, '1') }} @lang('Email Credentials')
                    </div>
                </div>
                <!--form-group-->

                <div class="d-flex flex-wrap mt-4">
                    {{ html()->button($text = "<i class='fas fa-plus-circle'></i> " . ucfirst($module_action) . "", $type = 'submit')->class('btn btn-success') }}
                    <a href="{{ route("backend.$module_name.index") }}" class="btn btn-warning ms-2" data-toggle="tooltip" title="{{__('labels.backend.cancel')}}"><i class="fas fa-reply"></i> Cancel</a>
                </div>
                {{ html()->form()->close() }}

            </div>
        </div>

    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-end text-muted">

                </small>
            </div>
        </div>
    </div>
</div>

@endsection