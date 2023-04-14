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
                <table id="datatable" class="table table-bordered table-hover table-responsive-sm w-100">
                    <thead>
                        <tr>
                            <th>
                                S.No
                            </th>
                            <th>
                                @lang("accommodation::text.name")
                            </th>
                            <th>
                                Country
                            </th>
                            <th>
                                Number Of OverNights
                            </th>
                            <th>
                                Number Of Rooms
                            </th>
                            <th>
                                Hotel Stars
                            </th>
                            <th class="text-end">
                                @lang("accommodation::text.action")
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
            {
                data: 'rownum',
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'country',
                name: 'country'
            },
            {
                data: 'number_overnights',
                name: 'number_overnights'
            },
            {
                data: 'number_rooms',
                name: 'number_rooms'
            },
            {
                data: 'hotel_stars',
                name: 'hotel_stars'
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