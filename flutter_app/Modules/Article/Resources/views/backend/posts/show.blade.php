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
                <a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary" data-toggle="tooltip" title="{{ ucwords($module_name) }} List"><i class="fas fa-list"></i> List</a>
                <x-buttons.edit route='{!!route("backend.$module_name.edit", $$module_name_singular)!!}' title="{{__('Edit')}} {{ ucwords(Str::singular($module_name)) }}" class="ms-1" />
            </x-slot>
        </x-backend.section-header>

        <hr>

        <div class="row mt-4">
            <div class="col-12">
                @php
                    $excludes = ['deleted_at','category_id','imgs_videos','meta_title','meta_keywords','meta_description','meta_og_image','meta_og_url','slug'];
                @endphp
                <table class="table table-responsive-sm table-hover table-bordered">
                    <?php
                    $all_columns = $$module_name_singular->getTableColumns();
                    ?>
                    <thead>
                        <tr>
                            <th scope="col">
                                <strong>
                                    @lang('Name')
                                </strong>
                            </th>
                            <th scope="col">
                                <strong>
                                    @lang('Value')
                                </strong>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_columns as $column)
                        @php
                        if (isset($excludes) && in_array($column->Field,$excludes) || $column->Field == 'deleted_at') {
                            continue;
                        }
                        if($column->Field == 'status'){
                            $isActive = '';
                            $isInactive = '';
                            $isActiveRoute = '';
                            $isInactiveRoute = '';
                            if($$module_name_singular->status == 1){
                                $btn = 'btn-success';
                                $text = 'Active';
                                $isActive = 'active';
                                $isInactiveRoute = 'href="'.route('backend.'.$module_name.'.status',[$$module_name_singular->id,0]).'"';
                            }else{
                                $btn = 'btn-danger';
                                $text = 'Inactive';
                                $isInactive = 'active';
                                $isActiveRoute = 'href="'.route('backend.'.$module_name.'.status',[$$module_name_singular->id,1]).'"';
                            }
                            $$module_name_singular->status = 
                            '<div class="btn-group">
                                <button class="btn btn-sm '.$btn.' dropdown-toggle" type="button"
                                    data-coreui-toggle="dropdown" aria-expanded="false">'.$text.'</button>
                                <ul class="dropdown-menu" style="">
                                    <li><a class="dropdown-item '.$isActive.'" '.$isActiveRoute.'>Active</a></li>
                                    <li><a class="dropdown-item '.$isInactive.'" '.$isInactiveRoute.'>Inactive</a></li>
                                </ul>
                            </div>'; 
                        }
                        @endphp
                        <tr>
                            <td>
                                <strong>
                                    {{ __(label_case(($column->Field != 'banner')?$column->Field : 'Image')) }}
                                </strong>
                            </td>
                            <td>
                                {!! show_column_value($$module_name_singular, $column) !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if (!empty($$module_name_singular->imgs_videos) && count(json_decode($$module_name_singular->imgs_videos)) > 0)
            <div class="col-12">
                <h6><b>Images & Videos</b></h6>
                <div class="d-flex mx-0">
                    @foreach (json_decode($$module_name_singular->imgs_videos) as $key => $item)
                        @if(pathinfo($item,PATHINFO_EXTENSION) != "mp4")
                        <img src="{{$item}}" alt="" class="me-2 border" style="width:150px;height:150px;object-fit:contain">
                        @else
                        <video class="me-2 border" controls="controls" style="width:auto;height:150px;object-fit:contain">
                            <source src="{{$item}}" type="video/mp4">
                        </video>
                        @endif 
                    @endforeach
                </div>
            </div>
            @endif
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