@extends(backpack_view('layouts.plain'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-4">
            <h3 class="text-center mb-4">{{ trans('backpack::base.register') }}</h3>
            <div class="card">
                <div class="card-body">
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.register') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="control-label" for="name">{{ trans('backpack::base.name') }} <span class="required-tag">*</span></label>

                            <div>
                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="name" value="{{ old('name') }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="{{ backpack_authentication_column() }}">{{ config('backpack.base.authentication_column_name') }} <span class="required-tag">*</span></label>

                            <div>
                                <input type="{{ backpack_authentication_column()==backpack_email_column()?'email':'text'}}" class="form-control{{ $errors->has(backpack_authentication_column()) ? ' is-invalid' : '' }}" name="{{ backpack_authentication_column() }}" id="{{ backpack_authentication_column() }}" value="{{ old(backpack_authentication_column()) }}" required>

                                @if ($errors->has(backpack_authentication_column()))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first(backpack_authentication_column()) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password">{{ trans('backpack::base.password') }} <span class="required-tag">*</span></label>

                            <div>
                                <div class="show_password">
                                <input type="password" class="form-control password {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" required>
                                <span class="p-viewer"><i class="la la-eye-slash" id="togglePassword" ></i></span>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password_confirmation">{{ trans('backpack::base.confirm_password') }} <span class="required-tag">*</span></label>

                            <div>
                                <div class="show_password">
                                <input type="password" class="form-control password_confirmation {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" id="password_confirmation" required>
                                <span class="p-viewer"><i class="la la-eye-slash" id="confirmtogglePassword" ></i></span></div>

                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    {{ trans('backpack::base.register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if (backpack_users_have_email() && backpack_email_column() == 'email' && config('backpack.base.setup_password_recovery_routes', true))
                <div class="text-center"><a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div>
            @endif
            <div class="text-center">Already Have An Account? <a href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a></div>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
    $(function(){
        const TogglePassword = document.querySelector('#togglePassword');
        const ConfirmTogglePassword = document.querySelector('#confirmtogglePassword');
        const Password = document.querySelector('#password');
        const ConfirmPassword = document.querySelector('#password_confirmation');
        TogglePassword.addEventListener('click', function (e) {
            const type = Password.getAttribute('type') === 'password' ? 'text' : 'password';
            Password.setAttribute('type', type);
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