<div class="row mb-3">
    <div class="col-12 col-sm-4">
        <div class="form-group">
            <?php
            $field_name = 'name';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = 'required';
            ?>
            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group">
            <?php
            $field_name = 'slug';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = '';
            ?>
            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-12 col-sm-4">
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
    <div class="col-12 col-md-8">
        <div class="form-group">
            <?php
            $field_name = 'image';
            $field_lable = 'Image';
            $field_placeholder = $field_lable;
            $required = '';
            ?>
            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
            <div class="input-group mb-3">
                {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control file-manager-input')->attributes(["$required", 'aria-label' => 'Banner', 'aria-describedby' => 'button-image', 'readonly','data-accept'=>'png,jpg,jpeg','data-field-name'=>'Image', 'maxlength'=> '190']) }}
                <div class="input-group-append">
                    <button class="btn btn-info" type="button" id="button-image"><i class="fas fa-folder-open"></i>
                        @lang('Browse')</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group">
            <?php
            $field_name = 'page_type';
            $field_lable = label_case($field_name);
            $required = 'required';
            $select_options = [
                'page' => 'Page',
                'faq' => 'FAQ',
            ];
            ?>
            {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->class('form-control select2')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<label class="form-label">Images & Videos</label>
<div class="row mb-3" id="file-inputs">
    @if (isset($$module_name_singular) && $$module_name_singular->imgs_videos != null)
        @foreach (json_decode($$module_name_singular->imgs_videos) as $key => $file)
            <div class="col-md-4 file-input">
                <div class="input-group mb-3">
                    <input type="text" name="files[]"
                        class="form-control form-control-sm blog-file-input file-manager-input"
                        data-file="{{ $key }}" value="{{ $file }}" required
                        data-accept="gif,png,jpg,jpeg,mp4" data-field-name="image or video">
                    <button class="btn btn-outline-info btn-sm open-file-manager" type="button"><i
                            class="fa fa-folder"></i></button>
                    <button class="btn btn-outline-danger btn-sm remove-file-input" type="button"><i
                            class="fa fa-x"></i></button>
                </div>
            </div>
        @endforeach
    @endif
</div>
<button type="button" class="btn btn-sm btn-primary mb-5" id="add-files">Add Images & Videos</button>

<div class="row mb-3">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'content';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = 'required';
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
    <script type="text/javascript" src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/file-manager/ckeditor',
            language: '{{ App::getLocale() }}',
            defaultLanguage: 'en'
        });
        let inputId = '';
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('button-image').addEventListener('click', (event) => {
                event.preventDefault();
                inputId = '#image';
                window.open('/file-manager/fm-button', 'fm', 'width=800,height=600');
            });
        });
        $(document).on('click', '.open-file-manager', function(e) {
            e.preventDefault();
            inputId = $(this).prev().data('file');
            inputId = 'input[data-file="' + inputId + '"]';
            window.open('/file-manager/fm-button', 'fm', 'width=800,height=600');
        });
        // set file link
        function fmSetLink($url) {
            document.querySelector(inputId).value = $url;
        }
        $(document).on('click', '.remove-file-input', function(e) {
            $(this).closest('.file-input').remove();
        });
        $(document).on('click', '#add-files', function(e) {
            $(document).find('#file-inputs')
                .append(`<div class="col-md-4 file-input mb-3">
                    <div class="input-group">
                        <input type="text" name="files[]" class="form-control form-control-sm blog-file-input file-manager-input" data-file="${$(document).find('#file-inputs').children().length++}" required readonly data-accept="gif,png,jpg,jpeg,mp4" data-field-name="Images & Videos">
                        <button class="btn btn-outline-info btn-sm open-file-manager" type="button"><i class="fa fa-folder"></i></button>
                        <button class="btn btn-outline-danger btn-sm remove-file-input" type="button"><i class="fa fa-x"></i></button>
                    </div>
                </div>`);
            console.log($(document).find("input[data-file]").length);
        });
    </script>
@endpush
