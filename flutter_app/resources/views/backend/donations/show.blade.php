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
            </x-slot>
        </x-backend.section-header>

        <div class="row mt-4 mb-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>OrderID</th>
                                <td>{{$$module_name_singular->order_id}}</td>
                            </tr>
                            <tr>
                                <th>Project Name</th>
                                <td>
                                  <a href="{{route('backend.projects.show',$$module_name_singular->project_id)}}" target="_blank" rel="noopener noreferrer">{{$$module_name_singular->project->name}}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Trip</th>
                                <td>
                                  <a href="{{route('backend.trips.show',$$module_name_singular->trip_id)}}" target="_blank" rel="noopener noreferrer">Trip Details</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{$$module_name_singular->user->name ?? ''}}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{$$module_name_singular->user->email}}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{$$module_name_singular->street}}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{$$module_name_singular->city}}</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{$$module_name_singular->country}}</td>
                            </tr>
                            <tr>
                                <th>Zip</th>
                                <td>{{$$module_name_singular->zip}}</td>
                            </tr>
                            <tr>
                                <th>Card Holder</th>
                                <td>{{$$module_name_singular->card_holder}}</td>
                            </tr>
                            <tr>
                                <th>Card Number</th>
                                <td>{{$$module_name_singular->card_number}}</td>
                            </tr>
                            <tr>
                                <th>Card Expiry</th>
                                <td>{{\Carbon\Carbon::parse($$module_name_singular->expiry)->format('m/y')}}</td>
                            </tr>
                            <tr>
                                <th>Discount</th>
                                <td>{{$$module_name_singular->discount}}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>{{$$module_name_singular->amount}}</td>
                            </tr>
                            <tr>
                                <th>Payment Status</th>
                                <td>
                                    @if ($$module_name_singular->payment_status == 'success')
                                    <span class="badge bg-success">{{ucwords($$module_name_singular->payment_status)}}</span>
                                    @endif
                                    @if ($$module_name_singular->payment_status == 'failed')
                                    <span class="badge bg-danger">{{ucwords($$module_name_singular->payment_status)}}</span>
                                    @endif
                                    @if ($$module_name_singular->payment_status == 'pending')
                                    <span class="badge bg-dark">{{ucwords($$module_name_singular->payment_status)}}</span>
                                    @endif
                                </td>
                            </tr>
                            @if ($$module_name_singular->certificate)
                            <tr>
                                <th>Certificate</th>
                                <td>
                                    <a href="{{route('backend.certificate.download',$$module_name_singular->certificate)}}" class="btn btn-dark btn-sm">Download PDF</a>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/col-->

        </div>
        <!--/.row-->
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