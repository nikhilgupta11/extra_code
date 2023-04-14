@extends(backpack_view('blank'))

@section('after_styles')
    <style media="screen">
        .backpack-profile-form .required::after {
            content: ' *';
            color: red;
        }
        .show_password {
            position: relative;
        }
        .show_password input.form-control {
            padding: 0.375rem 35px 0.375rem 0.75rem;
        }
        .show_password .p-viewer{
            display: inline-block;
        }
        .show_password .p-viewer{
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 22;
        }
    </style>
@endsection

@php
  $breadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      trans('backpack::base.my_account') => false,
  ];
@endphp

@section('header')
    <section class="content-header">
        <div class="container-fluid mb-3">
            <h1>{{ trans('backpack::base.my_account') }}</h1>
        </div>
    </section>
@endsection

@section('content')
    <div class="row">

        @if (session('success'))
        <div class="col-lg-8">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if ($errors->count())
        <div class="col-lg-8">
            <div class="alert alert-danger">
                <ul class="mb-1">
                    @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- UPDATE INFO FORM --}}
        <div class="col-lg-8">
            <form class="form" action="{{ route('backpack.account.info.store') }}" method="post">

                {!! csrf_field() !!}

                <div class="card padding-10">

                    <div class="card-header">
                        {{ trans('backpack::base.update_account_info') }}
                    </div>

                    <div class="card-body backpack-profile-form bold-labels">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                @php
                                    $label = trans('backpack::base.name');
                                    $field = 'name';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="text" name="{{ $field }}" value="{{ old($field) ? old($field) : $user->$field }}">
                            </div>

                            <div class="col-md-6 form-group">
                                @php
                                    $label = config('backpack.base.authentication_column_name');
                                    $field = backpack_authentication_column();
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="{{ backpack_authentication_column()==backpack_email_column()?'email':'text' }}" name="{{ $field }}" value="{{ old($field) ? old($field) : $user->$field }}">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i class="la la-save"></i> {{ trans('backpack::base.save') }}</button>
                        <a href="{{ backpack_url() }}" class="btn">{{ trans('backpack::base.cancel') }}</a>
                    </div>
                </div>

            </form>
        </div>

        {{-- CHANGE PASSWORD FORM --}}
        <div class="col-lg-8">
            <form class="form" action="{{ route('backpack.account.password') }}" method="post">

                {!! csrf_field() !!}

                <div class="card padding-10">

                    <div class="card-header">
                        {{ trans('backpack::base.change_password') }}
                    </div>

                    <div class="card-body backpack-profile-form bold-labels">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                @php
                                    $label = trans('backpack::base.old_password');
                                    $field = 'old_password';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <div class="show_password">
                                <input autocomplete="new-password" required class="form-control {{$field}}" type="password" name="{{ $field }}" id="{{ $field }}" value="">
                                <span class="p-viewer"><i class="la la-eye-slash" id="old_togglePassword" ></i></span></div>
                            </div>

                            <div class="col-md-4 form-group">
                                @php
                                    $label = trans('backpack::base.new_password');
                                    $field = 'new_password';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <div class="show_password">
                                <input autocomplete="new-password" required class="form-control {{$field}}" type="password" name="{{ $field }}" id="{{ $field }}" value="">
                                <span class="p-viewer"><i class="la la-eye-slash" id="new_togglePassword" ></i></span>
                                </div>
                            </div>

                            <div class="col-md-4 form-group">
                                @php
                                    $label = trans('backpack::base.confirm_password');
                                    $field = 'confirm_password';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <div class="show_password">
                                <input autocomplete="new-password" required class="form-control {{$field}}" type="password" name="{{ $field }}" id="{{ $field }}" value="">
                                <span class="p-viewer"><i class="la la-eye-slash" id="confirm_togglePassword" ></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                            <button type="submit" class="btn btn-success"><i class="la la-save"></i> {{ trans('backpack::base.change_password') }}</button>
                            <a href="{{ backpack_url() }}" class="btn">{{ trans('backpack::base.cancel') }}</a>
                    </div>

                </div>

            </form>
        </div>

    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
    $(function(){
        const OldTogglePassword = document.querySelector('#old_togglePassword');
        const NewTogglePassword = document.querySelector('#new_togglePassword');
        const ConfirmTogglePassword = document.querySelector('#confirm_togglePassword');
        const OldPassword = document.querySelector('#old_password');
        const NewPassword = document.querySelector('#new_password');
        const ConfirmPassword = document.querySelector('#confirm_password');
        OldTogglePassword.addEventListener('click', function (e) {
            const type = OldPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            OldPassword.setAttribute('type', type);
            this.classList.toggle('la-eye');
            this.classList.toggle('la-eye-slash');
        });
        NewTogglePassword.addEventListener('click', function (e) {
            const type = NewPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            NewPassword.setAttribute('type', type);
            this.classList.toggle('la-eye');
            this.classList.toggle('la-eye-slash');
        });
        ConfirmTogglePassword.addEventListener('click', function (e) {
            const type = ConfirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            ConfirmPassword.setAttribute('type', type);
            this.classList.toggle('la-eye');
            this.classList.toggle('la-eye-slash');
        });
    });
    </script>
