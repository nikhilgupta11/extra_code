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
            <i class="{{ $module_icon }}"></i> {{ __('Profile') }} <small class="text-muted">{{ __($module_action) }}</small>

            <x-slot name="toolbar">
                <x-backend.buttons.return-back />
            </x-slot>
        </x-backend.section-header>

        <hr>
        
        <div class="row mt-4 mb-4">
            <div class="col">
                {{ html()->modelForm($userprofile, 'PATCH', route('backend.users.profileUpdate', $$module_name_singular->id))->class('form-horizontal')->attributes(['enctype'=>"multipart/form-data"])->open() }}
                <div class="form-group row">
                    {{ html()->label('Profile Pic')->class('col-md-2 form-control-label')->for('name') }}

                    <div class="col-md-5">
                        <img src="{{asset($$module_name_singular->avatar)}}" class="user-profile-image img-fluid img-thumbnail" style="max-height:200px; max-width:200px;" />
                    </div>
                    <div class="col-md-5">
                        <input id="file-multiple-input" name="avatar" type="file" accept="image/*">
                    </div>
                </div>
                <!--form-group-->

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'first_name';
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "required";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required",'data-addrule'=>'lettersonly'])->value($user->first_name) }}
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'last_name';
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "required";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required",'data-addrule'=>'lettersonly'])->value($user->last_name) }}
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'email';
                            $field_lable = label_case('email');
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            {{ html()->email($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"])->value($user->email)->disabled() }}
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'mobile';
                            $field_lable = label_case('mobile');
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            {{ html()->number($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required","maxlength"=>14,"minlength"=>8])->value($user->mobile) }}
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-12 col-sm-6">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'gender';
                            $field_lable = label_case($field_name);
                            $field_placeholder = "-- Select an option --";
                            $required = "";
                            $select_options = [
                                'Female' => 'Female',
                                'Male' => 'Male',
                                'Other' => 'Other',
                            ];
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"])->value($userprofile->gender ?? ' ') }}
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'date_of_birth';
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            <div class="input-group date datetime" id="{{$field_name}}" data-target-input="nearest">
                                {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control form-control-sm datetimepicker-input')->attributes(["$required", 'data-target'=>"#$field_name"])->value($userprofile->date_of_birth ?? ' ') }}
                                <div class="input-group-append" data-target="#{{$field_name}}" data-toggle="datetimepicker">
                                    <div class="input-group-text rounded-0 py-2"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'address';
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            {{ html()->textarea($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"])->value($userprofile->address ?? '') }}
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'bio';
                            $field_lable = label_case($field_name);
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            {{ html()->textarea($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"])->value($userprofile->bio ?? '') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control">
                                <option value="1" {{((old('status') && old('status') == 1) || (isset($$module_name_singular) && $$module_name_singular->status == 1)) ? 'selected' : ''}}>Active</option>
                                <option value="0" {{((old('status') && old('status') == 0) || (isset($$module_name_singular) && $$module_name_singular->status == 0)) ? 'selected' : ''}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'url_website';
                            $field_lable = ucwords('website Url');
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            <input type="url" name="{{$field_name}}" class='form-control' id="" value="{{$userprofile->url_website ?? ''}}" placeholder="{{$field_placeholder}}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'url_facebook';
                            $field_lable = ucwords('facebook Url');
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            <input type="url" name="{{$field_name}}" class='form-control' id="" value="{{$userprofile->url_facebook ?? ''}}" placeholder="{{$field_placeholder}}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'url_instagram';
                            $field_lable = ucwords('instagram Url');
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            <input type="url" name="{{$field_name}}" class='form-control' id="" value="{{$userprofile->url_instagram ?? ''}}" placeholder="{{$field_placeholder}}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'url_twitter';
                            $field_lable = ucwords('twitter Url');
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            <input type="url" name="{{$field_name}}" class='form-control' id="" value="{{$userprofile->url_twitter ?? ''}}" placeholder="{{$field_placeholder}}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group mb-3">
                            <?php
                            $field_name = 'url_linkedin';
                            $field_lable = ucwords('linkedin Url');
                            $field_placeholder = $field_lable;
                            $required = "";
                            ?>
                            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
                            <input type="url" name="{{$field_name}}" class='form-control' id="" value="{{$userprofile->url_linkedin ?? ''}}" placeholder="{{$field_placeholder}}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {{ html()->submit($text = icon('fas fa-save')." Save")->class('btn btn-success') }}
                        </div>
                    </div>
                </div>
                {{ html()->closeModelForm() }}
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-end text-muted">
                    Updated: {{$user->updated_at->diffForHumans()}},
                    Created at: {{$user->created_at->isoFormat('LLLL')}}
                </small>
            </div>
        </div>
    </div>
</div>

@endsection



@push('after-styles')

<!-- Select2 Bootstrap 4 Core UI -->
<link href="{{ asset('vendor/select2/select2-coreui-bootstrap4.min.css') }}" rel="stylesheet" />

<!-- Date Time Picker -->
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-4-datetime-picker/css/tempusdominus-bootstrap-4.min.css') }}" />

@endpush

@push ('after-scripts')
<!-- Select2 Bootstrap 4 Core UI -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap",
            placeholder: "-- Select an option --",
            allowClear: true,
        });
    });
</script>

<!-- Date Time Picker & Moment Js-->
<script type="text/javascript" src="{{ asset('vendor/moment/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/bootstrap-4-datetime-picker/js/tempusdominus-bootstrap-4.min.js') }}"></script>

<script type="text/javascript">
    $(function() {
        ImagePreview("#file-multiple-input",'.user-profile-image');

        $('.datetime').datetimepicker({
            format: 'YYYY-MM-DD',
            maxDate: new Date("{{now()->toDateString()}}"),
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar-alt',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'far fa-calendar-check',
                clear: 'far fa-trash-alt',
                close: 'fas fa-times'
            }
        });
    });
</script>
@endpush