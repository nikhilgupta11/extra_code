@extends('backend.layouts.app')

@section('title')
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section('breadcrumbs')
    <x-backend-breadcrumbs>
        <x-backend-breadcrumb-item type="active" icon='{{ $module_icon }}'>{{ __($module_title) }}
        </x-backend-breadcrumb-item>
    </x-backend-breadcrumbs>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-3">
            <div class="card">
                <div class="card-header">
                    <h6>Add Coupon</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.coupons.create') }}" method="post">
                        @csrf
                        @if ($edit != '')
                            <input type="hidden" name="id" value="{{ $edit->id }}">
                        @endif
                        <div class="mb-2">
                            <label for="name" class="form-label">Coupon Name <span class="text-danger">*</span></label>
                            <input type="text" required name="coupon"
                                @if ($edit != '') value="{{ $edit->coupon }}" @endif
                                class="form-control form-control-sm" placeholder="Coupon" minlength="3" maxlength="20">
                        </div>
                        <div class="mb-2">
                            <label for="name" class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select name="type" required class="form-control form-control-sm" id="">
                                <option value="">Select Type</option>
                                <option value="percentage"
                                    {{ $edit != '' && $edit->type == 'percentage' ? 'selected' : '' }}>Percentage (%)
                                </option>
                                <option value="price" {{ $edit != '' && $edit->type == 'price' ? 'selected' : '' }}>
                                    Price</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="name" class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <input type="text" required name="value"
                                @if ($edit != '') value="{{ $edit->value }}" @endif 
                                class="form-control form-control-sm" placeholder="10.5" data-addrule='numbersonly'>
                        </div>
                        <div class="mb-2">
                            <label for="name" class="form-label">Status <span class="text-danger">*</span></label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" @if (($edit != '' && $edit->status == '1') || $edit == '') checked @endif id="customRadio2"
                                    name="status" value="1" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio2">Enable</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadio4" @if ($edit != '' && $edit->status == '0') checked @endif
                                    name="status" value="0" class="custom-control-input">
                                <label class="custom-control-label" for="customRadio4">Disable</label>
                            </div>
                        </div>
                        <div class="d-flex mt-2">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            @if ($edit != '')
                                <a href="{{ route('backend.coupons') }}" class="btn btn-sm btn-secondary ms-2">Cancel</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-9 mt-3 mt-md-0">
            <div class="card">
                <div class="card-header">
                    <h6>Coupons</h6>
                </div>
                <div class="card-body">
                    <table id="datatables" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Coupon</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th data-orderable="false">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($promos as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><b>{{ $item->coupon }}</b></td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->value }}</td>
                                    <td>
                                        @if ($item->status == 1)
                                            Enable
                                        @else
                                            Disable
                                        @endif
                                    </td>
                                    <td>{{ $item->users_count }}</td>
                                    <td>
                                        <a href="{{route("backend.$module_name.edit", $item->id)}}" class="btn btn-warning btn-sm mb-1" data-toggle="tooltip" title="Edit"><i class='fas fa-edit'></i></a>
                                        @if (Route::has("backend.$module_name.delete"))
                                        <x-buttons.delete route='{!!route("backend.$module_name.delete", $item->id)!!}' title="{{__('Delete')}} {{ ucwords(Str::singular($module_name)) }}" method='DELETE' small="true" />
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-styles')
    <!-- DataTables Core and Extensions -->
    <link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">
@endpush
@push('after-scripts')
    <!-- DataTables Core and Extensions -->
    <script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>

    <script type="text/javascript">
        $('#datatables').DataTable();
    </script>
@endpush
