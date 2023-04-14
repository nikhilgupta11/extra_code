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
                <x-buttons.edit route='{!!route("backend.$module_name.edit", $$module_name_singular)!!}' title="{{__('Edit')}} {{ ucwords(Str::singular($module_name)) }}" class="ms-1" />
            </x-slot>
        </x-backend.section-header>

        <div class="row mt-4 mb-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <td colspan="2"><img src="{{asset($$module_name_singular->avatar)}}" class="user-profile-image img-fluid img-thumbnail" style="max-height:200px; max-width:200px;" /></td>
                        </tr>

                        <tr>
                            <th>{{ __('labels.backend.users.fields.first_name') }}</th>
                            <td>{{ $user->first_name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('labels.backend.users.fields.last_name') }}</th>
                            <td>{{ $user->last_name }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('labels.backend.users.fields.email') }}</th>
                            <td>{{ $user->email }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('labels.backend.users.fields.mobile') }}</th>
                            <td>{{ $user->mobile }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ $user->profile->gender }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('labels.backend.users.fields.status') }}</th>
                            <td>
                                @if ($user->status == 1)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>{{ __('labels.backend.users.fields.confirmed') }}</th>
                            <td>
                                {!! $user->confirmed_label !!}
                            </td>
                        </tr>

                        <tr>
                            <th>{{ __('labels.backend.users.fields.created_at') }}</th>
                            <td>{{ $user->created_at }}<br><small>({{ $user->created_at->diffForHumans() }})</small></td>
                        </tr>

                        <tr>
                            <th>{{ __('labels.backend.users.fields.updated_at') }}</th>
                            <td>
                                @if ($user->profile->updated_at != null && strtotime($user->updated_at) > strtotime($user->profile->updated_at))
                                {{ $user->updated_at }}<br>
                                <small>({{ $user->updated_at->diffForHumans() }})</small>
                                @else
                                {{ $user->profile->updated_at }}<br>
                                <small>({{ $user->profile->updated_at->diffForHumans() }})</small>
                                @endif
                            </td>
                        </tr>

                    </table>
                </div>
                <!--/table-responsive-->

                <a href="{{route('backend.users.destroy', $user)}}" class="btn btn-danger mt-1" data-method="DELETE" data-token="{{csrf_token()}}" data-toggle="tooltip" title="{{__('labels.backend.delete')}}" data-confirm="Are you sure?"><i class="fas fa-trash-alt"></i> Delete</a>
            </div>
            <!--/col-->

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