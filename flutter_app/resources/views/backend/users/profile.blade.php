@extends ('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.$module_name.index")}}' icon='{{ $module_icon }}'>
        {{ __($module_title) }}
    </x-backend-breadcrumb-item>

    <x-backend-breadcrumb-item type="active">Profile</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <x-backend.section-header>
            <i class="{{ $module_icon }}"></i> {{ __('Profile') }} <small class="text-muted">{{ __($module_action) }}</small>

            <x-slot name="toolbar">
                <x-backend.buttons.return-back />
                <x-buttons.edit route='{!!route("backend.$module_name.profileEdit", $$module_name_singular)!!}' title="{{__('Edit')}}" class="ms-1" />
            </x-slot>
        </x-backend.section-header>


        <div class="row mt-4 mb-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <td colspan="2"><img src="{{asset($user->avatar)}}" class="user-profile-image img-fluid img-thumbnail" style="max-height:200px; max-width:200px;" /></td>
                        </tr>

                        <?php $fields_array = [
                            ['name' => 'name'],
                            ['name' => 'email'],
                            ['name' => 'mobile'],
                        ];
                        $fields_array2 = [
                            // ['name' => 'gender'],
                            // ['name' => 'date_of_birth', 'type' => 'date'],
                            // ['name' => 'url_website', 'type' => 'url'],
                            // ['name' => 'url_facebook', 'type' => 'url'],
                            // ['name' => 'url_twitter', 'type' => 'url'],
                            // ['name' => 'url_linkedin', 'type' => 'url'],
                            // ['name' => 'profile_privacy'],
                            // ['name' => 'address'],
                            // ['name' => 'bio'],
                            // ['name' => 'login_count'],
                            // ['name' => 'last_login', 'type' => 'datetime'],
                            // ['name' => 'last_ip'],
                        ];
                        ?>
                        @foreach ($fields_array as $field)
                        <tr>
                            @php
                            $field_name = $field['name'];
                            @endphp

                            <th>{{ ucwords(str_replace('_',' ',$field_name)) }}</th>

                            <td>{{ $user->$field_name ?? ''}}</td>
                        </tr>
                        @endforeach

                        <tr>
                            <th>Password</th>
                            <td>
                                <a href="{{ route('backend.users.changeProfilePassword', $user->id) }}" class="btn btn-outline-primary btn-sm">Change password</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{!! $user->status_label !!}</td>
                        </tr>

                        <tr>
                            <th>Confirmed</th>
                            <td>{!! $user->confirmed_label !!}</td>
                        </tr>

                        <tr>
                            <th>Created At</th>
                            <td>{{ $user->created_at->isoFormat('llll') }}<br><small>({{ $user->created_at->diffForHumans() }})</small></td>
                        </tr>

                        <tr>
                            <th>Updated At</th>
                            <td>{{ $user->updated_at->isoFormat('llll') }}<br /><small>({{ $user->updated_at->diffForHumans() }})</small></td>
                        </tr>

                    </table>
                </div>
                <!--table-responsive-->
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->
    </div>
    <div class="card-footer">
        <div class="row">
            {{-- <div class="col-12 col-md-6">
                <a href="{{route('backend.users.destroy', $user)}}" class="btn btn-danger mt-1" data-method="DELETE" data-token="{{csrf_token()}}" data-toggle="tooltip" title="{{__('labels.backend.delete')}}" data-confirm="Are you sure?"><i class="fas fa-trash-alt"></i> Delete</a>
            </div> --}}
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