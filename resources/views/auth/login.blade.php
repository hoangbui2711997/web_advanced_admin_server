@extends('auth.layouts.app')
@section('title')
    @lang('Login')
@endsection
@section('css')
    <link href="{{ mix('css/auth/login.css') }}" rel="stylesheet">
@endsection
@section('content')
    <form class="content_login_page" id="login-form" role="form" method="POST" action="{{ url('/login') }}">
        <a href="/"><img class="icon-logo" src="/images/logo/logo-vcc_126x46.png"></a>
        {{ csrf_field() }}
        @if (session('require_otp'))
            <p class="login-title">@lang('Google Authentication')</p>
            <div class="form-group authenticator {{ session('otp_code_invalid') ? 'has-error' : '' }}">
                <input-only-number type="text" maxlength="6" id="otp-field"
                                   class="input-form form-control" name="authentication_code"
                                   v-model="authentication_code"
                                   @keypressinput="onKeyupAuthenticationCode"
                                   @input="submitWhenAuthenticationCodeMaxLength"></input-only-number>
                @if (session('otp_code_invalid'))
                    <span class="error-block">
                                <strong>{{ session('otp_code_invalid') }}</strong>
                              </span>
                @endif
                <span class=" f-fr blue-black"><a href="#"  data-toggle="modal" data-target="#guide-otp-modal">
                        @lang('Lost Your Google Authenticator')?</a></span>
            </div>
            <input type="hidden" name="email" value="{{ old('email') }}">
            <input type="hidden" name="password" value="{{ old('password') }}">
            <div class="form-group">
                <button class="btn-login btn primary-btn btn-block">@lang('Submit')</button>
            </div>
        @else
            <p class="login-title">@lang('Login')</p>
            <div class="important-vcc">
                <p class="vcc-title"><span class="glyphicon glyphicon-exclamation-sign icon-important"></span>@lang('Please check that you are visiting') <b>https://www.vcc.exchange</b></p>
                <img src="images/login/vcc-exchange.png">
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label>@lang('ID')</label>
                <span class=" f-fr blue-black">@lang('Need an account?') <a class="register" href="{{ url('register') }}">@lang('Register')</a></span>
                <input type="text" class="input-form form-control" name="email"
                       value="{{old('email')}}" tabindex=1>
                @if ($errors->has('email'))
                    <span class="error-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label>@lang('Password')</label>
                <a href="{{ url('password/reset') }}" class="register f-fr">@lang('Forgot your password?')</a>
                <input type="password" class='input-form form-control' name="password" tabindex=2>
                @if ($errors->has('password'))
                    <span class="error-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                @if ($errors->has('message'))
                    <span class="error-block">
                        <strong>{{ $errors->first('message') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <button class="btn-login btn primary-btn btn-block" tabindex=3>@lang('Login')</button>
            </div>
        @endif
    </form>
@endsection
