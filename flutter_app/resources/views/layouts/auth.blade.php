<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="{{asset('img/favicon.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('img/favicon.png')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ setting('meta_description') }}">
    <meta name="keyword" content="{{ setting('meta_keyword') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    @stack('before-styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('after-styles')

</head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        {{ $slot }}
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function () {
            $.validator.addMethod("specialChars", function(value, element) {
                var regex = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
                if (regex.test(value) == true) {
                    event.preventDefault();
                    return false;
                }
                return true;
            }, "please use only alphanumeric or alphabetic characters");
            jQuery.validator.addMethod('email_rule', function (value, element) {
                if (/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
                    return true;
                } else {
                    return false;
                };
            },'Please enter valid Email');

            $.validator.addMethod("strong_password", function (value, element) {
                let password = value;
                if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{6,16}$)/.test(password))) {
                    return false;
                }
                return true;
            }, function (value, element) {
                let password = $(element).val();
                if (!(/^(.{8,20}$)/.test(password))) {
                    return 'Password must be between 6 to 16 characters long & one uppercase & one lowercase & one digit & one special characters.';
                }
                else if (!(/^(?=.*[A-Z])/.test(password))) {
                    return 'Password must be between 6 to 16 characters long & one uppercase & one lowercase & one digit & one special characters.';
                }
                else if (!(/^(?=.*[a-z])/.test(password))) {
                    return 'Password must be between 6 to 16 characters long & one uppercase & one lowercase & one digit & one special characters.';
                }
                else if (!(/^(?=.*[0-9])/.test(password))) {
                    return 'Password must be between 6 to 16 characters long & one uppercase & one lowercase & one digit & one special characters.';
                }
                else if (!(/^(?=.*[@#$%&])/.test(password))) {
                    return "Password must be between 6 to 16 characters long & one uppercase & one lowercase & one digit & one special characters.";
                }
                return false;
            });

            $.validator.messages.required = function (param, input) {
                return 'The ' + input.name.replace('_',' ').replace('[]',' ').replace(' id','') + ' field is required';
            }
            $.each($(document).find('form'), function (indexInArray, form) { 
                $(form).validate({
                    errorPlacement: function(error, element) {
                        $(error).css({'color':'red'})
                        if ($(element).next().hasClass('input-group-append')) {
                            $(element).closest('.form-group').append(error);
                        } else if ($(element).hasClass('select2-category') || $(element).hasClass('select2')) {
                            $(element).closest('.form-group').append(error);
                        }else if ($(element).next().hasClass('open-file-manager')) {
                            $(element).closest('.file-input').append(error);
                        } else {
                            element.after(error);
                        }
                    }
                });
            });
            $(document).find("input[type='text']").rules("add", "specialChars");
            $(document).find("input[type='email']").rules("add", "email_rule");
            $(document).find("input[type='password']").not("input[name='old_password']").rules("add", "strong_password");
        });
    </script>
</body>

</html>