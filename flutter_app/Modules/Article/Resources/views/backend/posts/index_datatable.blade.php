@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

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
                <x-buttons.create route='{{ route("backend.$module_name.create") }}' title="{{__('Create')}} {{ ucwords(Str::singular($module_name)) }}" />

                <div class="btn-group">
                    <a class="btn btn-secondary " href="{{ route("backend.$module_name.trashed") }}" >
                        <i class="fas fa-trash"></i> Trash
                    </a>
                </div>
            </x-slot>
        </x-backend.section-header>

        <div class="row mt-4">
            <div class="col">
                <table id="datatable" class="table align-middle table-bordered table-hover table-responsive-sm w-100">
                    <thead>
                        <tr>
                            <th>
                                S.No
                            </th>
                            <th style="width:35%">
                                Name
                            </th>
                            <th style="width:10%">
                                Image
                            </th>
                            <th style="width:15%">
                                Category
                            </th>
                            <th style="width:15%">
                                Intro
                            </th>
                            <th style="width:10%">
                                Status
                            </th>
                            <th class="text-start" style="width:30%">
                                Action
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push ('after-styles')
<!-- DataTables Core and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">

@endpush

@push ('after-scripts')
<!-- DataTables Core and Extensions -->
<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>

<script type="text/javascript">
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: '{{ route("backend.$module_name.index_data") }}',
        columns: [
            {data: 'rownum', name: 'rownum'},
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'banner',
                name: 'banner'
            },
            {
                data: 'category_name',
                name: 'category_name'
            },
            {
                data: 'intro',
                name: 'intro'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });
</script>
@endpush