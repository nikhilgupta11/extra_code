<div class="row mb-3">
    <div class="col-12 col-sm-4">
        <div class="form-group">
            <?php
            $field_name = 'transport_type';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = 'required';
            ?>
            {{ html()->label('Transport Name', $field_name)->class('form-label') }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder('Transport Name')->class('form-control select2')->attributes(["$required",'data-addrule'=>'lettersonly']) }}
        </div>
    </div>
    <div class="col-12 col-sm-5">
        <div class="form-group">
            <?php
            $field_name = 'image';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = 'required';
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            <div class="input-group mt-2">
                {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control file-manager-input')->attributes(["$required", 'aria-label' => 'Image', 'id' => 'image', 'aria-describedby' => 'button-image', 'data-accept'=>'jpg,png,jpeg,gif,svg','data-field-name'=>'Image'])}}
                <div class="input-group-append">
                    <button class="btn btn-info" type="button" id="button-image"><i class="fas fa-folder-open"></i>
                        @lang('Browse')</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-3">
        <div class="form-group">
            <?php
            $field_name = 'status';
            $field_lable = label_case($field_name);
            $field_placeholder = 'Select an option';
            $required = 'required';
            $select_options = [
                '1' => 'Active',
                '0' => 'Inactive',
            ];
            ?>
            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'description';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = '';
            ?>
            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
            {{ html()->textarea($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
@push('after-styles')
    <!-- File Manager -->
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endpush

@push('after-scripts')
    <script type="text/javascript" src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.getElementById('button-image').addEventListener('click', (event) => {
                event.preventDefault();

                window.open('/file-manager/fm-button', 'fm', 'width=800,height=600');
            });
        });

        // set file link
        function fmSetLink($url) {
            document.getElementById('image').value = $url;
        }
    </script>
@endpush
