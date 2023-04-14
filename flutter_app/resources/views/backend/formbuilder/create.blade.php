@extends('backend.layouts.app')

@section('title')
    {{$module_action}} Form Builder
@endsection

@section('breadcrumbs')
    <x-backend-breadcrumbs>
        <x-backend-breadcrumb-item route='{{ route("backend.$module_name.index") }}' icon='{{ $module_icon }}'>
            {{ __($module_title) }}
        </x-backend-breadcrumb-item>
        <x-backend-breadcrumb-item type="active" icon='fa fa-form'>{{$module_action}}</x-backend-breadcrumb-item>
    </x-backend-breadcrumbs>
@endsection

@section('content')
    <form action="{{ route("backend.$module_name.store") }}" method="post" id="saveForm">
        @csrf
        @if (isset($formEdit))
            <input type="hidden" name="id" value="{{ $formEdit->id }}">
        @endif
        <div class="row mb-4 align-items-start">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="" class="form-label">Transport Type <span class="text-danger">*</span></label>
                    <select name="transportation_id" id="" class="form-control" required data-field-name="transport type">
                        <option value="">Select Transport</option>
                        @foreach ($transports as $transport)
                            @if($transport->form_count == 0 || (isset($formEdit) && $formEdit->transportation_id == $transport->id))
                            <option value="{{ $transport->id }}"
                                @if ((old('transportation_id') && old('transportation_id') == $transport->id)|| (isset($formEdit) && $formEdit->transportation_id == $transport->id)) selected @endif>
                                {{ $transport->transport_type }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <input type="hidden" required id="jsonData" name="form_data" @if(old('form_data')) value="{{ old('form_data') }}" @elseif(isset($formEdit)) value="{{ $formEdit->form_data }}" @endisset>
            </div>
            <div class="col-12 col-md-2">
                <div class="form-group">
                    <label for="" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                        <option value="1" {{((old('status') && old('status') == 1) || (isset($formEdit) && $formEdit->status == 1)) ? 'selected' : ''}}>Active</option>
                        <option value="0" {{((old('status') && old('status') == 0) || (isset($formEdit) && $formEdit->status == 0)) ? 'selected' : ''}}>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <label for="" class="form-label"></label><br>
                <button id="submitBtn" type="button" class="btn btn-primary mt-2">Save</button>
            </div>
            <div class="col-12 mt-2">
                <span class="text-danger" id="formDataError"></span>
            </div>
        </div>
    </form>
    <div class="build-wrap"></div>
@endsection
@push('after-styles')
@endpush
@push('after-scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
    <script>
        $(document).ready(function() {
            var FormData = $("#jsonData").val();
            let widthOption = {
                width: {
                    label: 'Field Width', // i18n support by passing and array eg. ['optionCount', {count: 3}]
                    multiple: false, // optional, omitting generates normal <select>
                    options: {
                        'w-full': 'Full Width',
                        'w-half': 'Half Width',
                    },
                    style: 'border: 1px solid blue'
                },
                controlOrder : 2
            };
            var options = {
                controlOrder: [
                    'text',
                    'textarea'
                ],
                subtypes: {
                    text: ['location']
                },
                controlPosition: 'right',
                formData: FormData,
                disabledActionButtons: ['data','save'],
                disabledAttrs: ['access', 'className', 'other','maxlength'],
                disableFields: ['autocomplete', 'file', 'button'],
                typeUserAttrs: {
                    text: widthOption,
                    select: widthOption,
                    number: widthOption,
                    textarea: widthOption,
                    date: widthOption,
                }
            };
            var formBuilder = $('.build-wrap').formBuilder(options);

            document.getElementById('submitBtn').addEventListener('click', function() {
                if(formBuilder.actions.getData().length > 0){
                    $('#jsonData').val(formBuilder.actions.getData('json', true));
                    $("#saveForm").submit();
                }else{
                    $('#formDataError').html('Please Insert Fields in Form Builder <i class="fa fa-x ms-2" id="removeError"></i>');
                }
            });
            $(document).on('click','#removeError',function(){
                $('#formDataError').html('');
            });
        });
    </script>
@endpush
