@extends ('backend.layouts.app')

@section ('title', ucfirst($module_name) . ' ' . ucfirst($module_action))

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active" icon='{{ $module_icon }}'>{{ __($module_title) }}</x-backend-breadcrumb-item>
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

        <div class="row mt-4">
            <div class="col">
                <table id="datatable" class="table table-bordered table-hover table-responsive-sm w-100">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                Banner
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Category
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                Deleted At
                            </th>
                            <th class="text-end">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($$module_name as $module_name_singular)
                        <tr>
                            <td>
                                {{ $module_name_singular->id }}
                            </td>
                            <td><img src="{{ $module_name_singular->banner }}" style="width:60px;height:60px;object-fit:cover" alt=""></td>
                            <td>
                                {{ $module_name_singular->name }}
                            </td>
                            <td>
                                {{ $module_name_singular->category_name }}
                            </td>
                            <td>
                                {{  Str::of($module_name_singular->intro )->limit(25) }}
                            </td>
                            <td>
                                {{ $module_name_singular->deleted_at->diffForHumans() }}
                            </td>
                            <td class="text-end">
                                <a href="{{route("backend.$module_name.restore", $module_name_singular)}}" class="btn btn-warning btn-sm" data-method="PATCH" data-token="{{csrf_token()}}" data-toggle="tooltip" title="{{__('labels.backend.restore')}}"><i class='fas fa-undo'></i></a>
                                @if (Route::has("backend.$module_name.force_destroy"))
                                <x-buttons.delete route='{!!route("backend.$module_name.force_destroy", $module_name_singular)!!}' title="{{__('Permanent Delete')}} {{ ucwords(Str::singular($module_name)) }}" method='DELETE' small="true" />
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    Total {{ $$module_name->total() }} {{ ucwords($module_name) }}
                </div>
            </div>
            <div class="col-5">
                <div class="float-end">
                    {!! $$module_name->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection