<div class="row mb-3">
    <div class="col-5">
        <div class="form-group">
            <?php
            $field_name = 'name';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required",'minlength'=>3,'maxlength'=>100]) }}
        </div>
    </div>

    <div class="col">
        <div class="form-group">
            <?php
            $field_name = 'slug';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>

    <div class="col-3">
        <div class="form-group">
            <?php
            $field_name = 'status';
            $field_lable = label_case($field_name);
            $field_placeholder = __("Select an option");
            $required = "required";
            $select_options = [
                '1' => 'Active',
                '0' => 'Inactive',
            ];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col">
        <div class="form-group">
            <?php
            $field_name = 'banner';
            $field_lable = label_case('Image');
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            <div class="input-group mb-3">
                {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control file-manager-input')->attributes(["$required", 'aria-label'=>'Image', 'aria-describedby'=>'button-image','readonly','data-accept'=>'jpg,png,jpeg','data-field-name'=>'Image']) }}
                <div class="input-group-append">
                    <button class="btn btn-info" type="button" id="button-image"><i class="fas fa-folder-open"></i> @lang('Browse')</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="form-group">
            <?php
            $field_name = 'category_id';
            $field_lable = label_case('Category');
            $field_relation = "category";
            $field_placeholder = __("Select an option");
            $required = "required";
            ?>
            @php
                $oldvalue = old('category_id');
                if($oldvalue){
                    $oldvalue = Modules\Article\Entities\Category::find($oldvalue);
                }else if(isset($$module_name_singular)){
                    $oldvalue = $$module_name_singular->$field_relation;
                }
            @endphp
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, isset($oldvalue)?optional($oldvalue)->pluck('name', 'id'):'')
                ->placeholder($field_placeholder)
                ->class('form-control select2-category')
                ->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-3">
        <div class="form-group">
            <?php
            $field_name = 'sku';
            $field_lable = strtoupper($field_name);
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required","maxlength='10'"]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php
            $field_name = 'price';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->number($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required","min='0'"]) }}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php
            $field_name = 'sale_price';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            ?>
            {{ html()->label($field_lable, $field_name) }}
            {{ html()->number($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["min='0'"]) }}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php
            $field_name = 'stock';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }}{!! fielf_required($required) !!}
            {{ html()->number($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required","min"=>1]) }}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php
            $field_name = 'threshold';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            ?>
            {{ html()->label($field_lable, $field_name) }}
            {{ html()->number($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(['min'=>0]) }}
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-3">
        <div class="form-group">
            <?php
            $field_name = 'certification_start_txt';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required","maxlength='10'"]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php
            $field_name = 'certification_end_txt';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required","maxlength='10'"]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php
            $field_name = 'certification_start_number';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }}
            {{ html()->number($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required","min='0'","maxlength='10'"]) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php
            $field_name = 'certification_end_number';
            $field_lable = label_case($field_name);
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }}
            {{ html()->number($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required","min='0'","maxlength='10'"]) }}
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
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->textarea($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col">
        <div class="form-group">
            <?php
            $field_name = 'tags_list[]';
            $field_lable = 'Tags';
            $field_relation = "tags";
            $field_placeholder = __("Select an option");
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->multiselect($field_name,
                isset($$module_name_singular)?optional($$module_name_singular->$field_relation)->pluck('name', 'id'):'',
                isset($$module_name_singular)?optional($$module_name_singular->$field_relation)->pluck('id')->toArray():''
                )->class('form-control select2-tags')->attributes(["$required"]) }}
        </div>
    </div>
</div>

<!-- Select2 Library -->
<x-library.select2 />
<x-library.datetime-picker />

@push('after-styles')
<!-- File Manager -->
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endpush

@push ('after-scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
            document.querySelector('.select2-container--open .select2-search__field').focus();
        });

        $('.select2-category').select2({
            theme: "bootstrap",
            placeholder: '@lang("Select an option")',
            minimumInputLength: 2,
            allowClear: true,
            ajax: {
                url: '{{route("backend.categories.index_list")}}',
                dataType: 'json',
                data: function(params) {
                    let term = params.term;
                    return {
                        q: $.trim(term)
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('.select2-tags').select2({
            theme: "bootstrap",
            placeholder: '@lang("Select an option")',
            minimumInputLength: 2,
            allowClear: true,
            ajax: {
                url: '{{route("backend.tags.index_list")}}',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });
</script>

<!-- Date Time Picker & Moment Js-->
<script type="text/javascript">
    $(function() {
        $('.datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar-alt',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'far fa-calendar-check',
                clear: 'far fa-trash-alt',
                close: 'fas fa-times'
            }
        });
    });
</script>

<script type="text/javascript" src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>

<script type="text/javascript">
    CKEDITOR.replace('description', {
        filebrowserImageBrowseUrl: '/file-manager/ckeditor',
        language: '{{App::getLocale()}}',
        defaultLanguage: 'en'
    });

    document.addEventListener("DOMContentLoaded", function() {

        document.getElementById('button-image').addEventListener('click', (event) => {
            event.preventDefault();

            window.open('/file-manager/fm-button', 'fm', 'width=800,height=600');
        });
    });

    // set file link
    function fmSetLink($url) {
        document.getElementById('banner').value = $url;
    }
</script>
@endpush
