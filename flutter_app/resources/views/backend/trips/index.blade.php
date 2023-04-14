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

        </x-backend.section-header>
        
        <div class="row mt-4">
            <div class="col">
                <table id="datatable" class="table table-hover table-responsive-sm table-bordered w-100">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Round Trip</th>
                            <th>Start Date</th>
                            <th>Days</th>
                            <th>Number of Peoples</th>
                            <th>Transports</th>
                            <th>Action</th>
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
        ajax: '{{ route("backend.$module_name.index_list") }}',
        columns: [
            {
                data: 'rownum',
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'from',
                name: 'from'
            },
            {
                data: 'to',
                name: 'to'
            },
            {
                data: 'round_trip',
                name: 'round_trip'
            },
            {
                data: 'start_date',
                name: 'start_date'
            },
            {
                data: 'trip_days',
                name: 'trip_days'
            },
            {
                data: 'peoples',
                name: 'peoples'
            },
            {
                data: 'transports',
                name: 'transports',
                orderable: false,
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