<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ language_direction() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/favicon.png') }}">
    <meta name="keyword" content="{{ setting('meta_keyword') }}">
    <meta name="description" content="{{ setting('meta_description') }}">

    <!-- Shortcut Icon -->
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="icon" type="image/ico" href="{{ asset('img/favicon.png') }}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    @stack('before-styles')

    <link rel="stylesheet" href="{{ mix('css/backend.css') }}">

    <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+Bengali+UI&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: Ubuntu, "Noto Sans Bengali UI", Arial, Helvetica, sans-serif
        }

        .select2-container--bootstrap .select2-selection__clear:hover {
            color: #2c2c2c !important;
        }

        table.table>thead>tr th {
            font-weight: bolder !important;
        }
    </style>

    @stack('after-styles')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    {{-- <x-google-analytics /> --}}

    @livewireStyles

</head>

<body>
    <!-- Sidebar -->
    @include('backend.includes.sidebar')
    <!-- /Sidebar -->

    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <!-- Header -->
        @include('backend.includes.header')
        <!-- /Header -->

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">

                @include('flash::message')

                <!-- Errors block -->
                @include('backend.includes.errors')
                <!-- / Errors block -->

                <!-- Main content block -->
                @yield('content')
                <!-- / Main content block -->

            </div>
        </div>

        <!-- Footer block -->
        @include('backend.includes.footer')
        <!-- / Footer block -->

    </div>

    <!-- Scripts -->
    @stack('before-scripts')

    <script src="{{ mix('js/backend.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
        integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @livewireScripts

    <script>
        function ImagePreview(onChange = "#file-multiple-input", targetChange = '.user-profile-image') {
            $(document).on('change', onChange, function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $(targetChange).attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
        $(document).ready(function() {
            $.extend($.validator.prototype, {
                checkForm: function() {
                    this.prepareForm();
                    for (var i = 0, elements = (this.currentElements = this.elements()); elements[
                            i]; i++) {
                        if (this.findByName(elements[i].name).length != undefined && this.findByName(
                                elements[i].name).length > 1) {
                            for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
                                this.check(this.findByName(elements[i].name)[cnt]);
                            }
                        } else {
                            this.check(elements[i]);
                        }
                    }
                    return this.valid();
                }
            });

            $.validator.addMethod("specialChars", function(value, element) {
                var regex = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
                if (regex.test(value) == true) {
                    event.preventDefault();
                    return false;
                }
                return true;
            }, "please use only alphanumeric or alphabetic characters");

            $.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) || /^[a-zA-Z_ ]*$/i.test(value);
            }, "Please enter only characters");

            $.validator.addMethod("acceptFiles", function(value, element) {
                let ext = $(element).val().split('.').pop();
                let allows = $(element).attr('data-accept').split(',');
                if (jQuery.inArray(ext, allows) != -1) {
                    return true;
                }
                if(!$(element).attr('required')){
                    return true;
                }
                return false;
            }, function(value, element) {
                return "Only " + $(element).attr('data-accept') + " file types allowed";
            });

            jQuery.validator.addMethod('email_rule', function(value, element) {
                if (/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
                    return true;
                } else {
                    return false;
                };
            }, 'Please enter valid Email');

            $.validator.addMethod("strong_password", function(value, element) {
                let password = value;
                if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{6,16}$)/.test(password))) {
                    return false;
                }
                return true;
            }, function(value, element) {
                let password = $(element).val();
                if (!(/^(.{8,20}$)/.test(password))) {
                    return 'The password must contain a minimum of eight characters, including at least one uppercase, one lowercase, one number, and one special character';
                } else if (!(/^(?=.*[A-Z])/.test(password))) {
                    return 'The password must contain a minimum of eight characters, including at least one uppercase, one lowercase, one number, and one special character';
                } else if (!(/^(?=.*[a-z])/.test(password))) {
                    return 'The password must contain a minimum of eight characters, including at least one uppercase, one lowercase, one number, and one special character';
                } else if (!(/^(?=.*[0-9])/.test(password))) {
                    return 'The password must contain a minimum of eight characters, including at least one uppercase, one lowercase, one number, and one special character';
                } else if (!(/^(?=.*[@#$%&])/.test(password))) {
                    return "The password must contain a minimum of eight characters, including at least one uppercase, one lowercase, one number, and one special character";
                }
                return false;
            });
            $.validator.addMethod("maxDate", function(value, element) {
                var curDate = new Date();
                if($(element).attr('maxdate')){
                    curDate = new Date($(element).attr('maxdate'));
                }
                var inputDate = new Date(value);
                if (inputDate < curDate){
                    return true;
                }
                return false;
            }, "Invalid Date!");
            $.validator.addMethod("minDate", function(value, element) {
                var curDate = new Date();
                if($(element).attr('mindate')){
                    curDate = new Date($(element).attr('mindate'));
                }
                var inputDate = new Date(value);
                inputDate.setTime(inputDate.getTime() + (15 * 60 * 1000))
                console.log($(element).attr('mindate'));
                if (inputDate >= curDate){
                    return true;
                }
                return false;
            }, "Invalid Date!");
            $.validator.messages.required = function(param, input) {
                let field = input.name.replace('_', ' ').replace('[]', ' ').replace(' id', '');
                if ($(input).attr('data-field-name')) {
                    field = $(input).attr('data-field-name');
                }
                return 'The ' + field + ' field is required';
            }
            if (typeof(CKEDITOR) !== "undefined") {
                for (var instanceName in CKEDITOR.instances) {
                    CKEDITOR.instances[instanceName].on('instanceReady', function() {
                        this.document.on("keyup", function(event) {
                            if (CKEDITOR.instances[instanceName].getData().replace(/<[^>]*>/gi, '')
                                .length > 0) {
                                $(document).find(`#${instanceName}-error`).remove();
                            }
                        });
                    });
                }
            }

            $.each($(document).find('form'), function(indexInArray, form) {
                $(form).validate({
                    errorPlacement: function(error, element) {
                        $(error).addClass('text-danger')
                        if ($(element).next().hasClass('input-group-append')) {
                            $(element).closest('.form-group').append(error);
                        } else if ($(element).hasClass('select2-category') || $(element)
                            .hasClass('select2')) {
                            $(element).closest('.form-group').append(error);
                        } else if ($(element).next().hasClass('open-file-manager')) {
                            $(element).closest('.file-input').append(error);
                        } else {
                            element.after(error);
                        }
                    },
                    submitHandler: function(form) {
                        if (typeof(CKEDITOR) !== "undefined") {
                            for (var instanceName in CKEDITOR.instances) {
                                var totalcontentlength = CKEDITOR.instances[instanceName].getData().replace(/<[^>]*>/gi, '').length;
                                if (totalcontentlength <= 1) {
                                    $("[name='" + instanceName + "']").next().after(
                                        `<span class="text-danger" id="${instanceName}-error">The ${instanceName} field is required</span>`
                                    );
                                    return false;
                                } else {
                                    $(document).find(`#${instanceName}-error`).remove();
                                }
                            }
                        }
                        return true;
                    }
                });
            });

            function applyRuleElements(element, ruleType, rule) {
                $.each($(document).find(element), function(indexInArray, valueOfElement) {
                    $(valueOfElement).rules(ruleType, rule);
                });
            }
            // applyRuleElements("input[type='text']:not(.datetimepicker-input,.file-manager-input,input[name='slug'])", 'add', 'specialChars');
            applyRuleElements("input[data-addrule='lettersonly']", 'add', 'lettersonly');
            applyRuleElements("input[data-addrule='numbersonly']", 'add', { number: true });
            applyRuleElements("input[data-accept]", 'add', 'acceptFiles');
            applyRuleElements("input[type='email']", 'add', 'email_rule');
            applyRuleElements("input[type='text'][mindate]", 'add', {required: true,date: true, minDate : true});
            $(document).find("input[type='password']").not("input[name='old_password']").rules("add", "strong_password");
        });
    </script>
    @stack('after-scripts')
    <!-- / Scripts -->
</body>

</html>
