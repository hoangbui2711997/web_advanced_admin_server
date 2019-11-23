@extends('auth.layouts.app')
@section('title')
  @lang('Sign Up')
@endsection
@section('css')
    <link href="{{ mix('css/auth/register.css') }}" rel="stylesheet">
@endsection
@section('content')
  <div class="content_login_page {{isset($success) ? 'success' : ''}}">
    <a href="/"><img class="icon-logo" src="/images/logo/logo-vcc_126x46.png"></a>
    <div class="row register_page">
      @if (!isset($success))
        <p class="login-title">@lang('Sign Up')</p>
        <form role="form" id="register-form" class="form-login" method="post" action="{{ url('/register') }}">
          {{ csrf_field() }}
          <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label>Email</label>
            <input type="text" class="input-form form-control" name="email" placeholder="Email"
                   value="{{ old('email') }}">
            @if ($errors->has('email'))
              <span class="error-block">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
            @endif
          </div>
          <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <label>@lang('Password')</label>
            <input type="password" class='input-form form-control' name="password" data-trigger="focus"
                   data-toggle="tooltip" data-placement="top"
                   title="@lang('Passwords must contain at least 8 characters, uppercase letters, lowercase letters, and numbers.')" placeholder="@lang('Password')">
            @if ($errors->has('password'))
              <span class="error-block">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
            @endif
          </div>
          <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            <label>@lang('Confirm Password')</label>
            <input type="password" class='input-form form-control' name="password_confirmation"
                   placeholder="@lang('Confirm Password')">
            @if ($errors->has('password_confirmation'))
              <span class="error-block">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
              </span>
            @endif
          </div>
          <div class="form-group">
            <label>@lang('Referral Code')</label>
            <input type="text" class="input-form form-control" name="referrer_code" placeholder="@lang('Referral Code') (@lang('optional'))"
                   value="{{ old('referrer_code') ?? (session('referrer_code') ?? '') }}">
            @if ($errors->has('referrer_code'))
              <span class="error-block">
                <strong>{{ $errors->first('referrer_code') }}</strong>
              </span>
            @endif
          </div>
          <div class="form-group">
            <label class="form-group term" >
              <input type="checkbox" name="agree_term" id="agree"
                     v-model="agree" {{ (! empty(old('agree_term')) ? 'checked="checked"' : '') }}>
              <span></span>
              @lang("I agree to") <a target="_blank" href="/terms">@lang('Terms of Use and Privacy Policy')</a>
            </label>
            @if ($errors->has('agree_term'))
              <span class="error-block">
                <strong>{{ $errors->first('agree_term') }}</strong>
              </span>
            @endif
          </div>
          <div class="form-group">
            <button class="btn-login btn primary-btn btn-block">@lang('Sign Up')</button>
          </div>
          <div class="form-group float-right" >
            @lang('Registered?') <a href="/login">@lang('Login')</a>
          </div>
          
        </form>
        <div class="clearfix"></div>
      @elseif(!isset($resend_email))      
        <div class="register-success">
           <div class="messageBox-title">
             <span class="ng-binding">
               @lang('Email Confirm')
             </span>
           </div>
           <div class="messageBox-con">
             @lang('We sent a confirmation email to ')
             <strong>{{$registered_email}}</strong>
             <p class="follow">@lang('Please follow the instructions to complete your registration.')
             </p>
             <p>
              <a href="{{route('resendConfirmEmail')}}">@lang('Resend The Mail >>')</a>
             </p>
           </div>
           <div class="messageBox-tip">
             <strong> @lang("If you haven't received the email, do the following:")</strong>
              <ul>
                <li>@lang('Make sure provided email address is correct.')</li>
                <li>@lang('Check spam or other folders.')</li>
                <li>@lang('Set email address whitelist.')</li>
                <li>@lang('Check the mail client works normally.')</li>
              </ul>
           </div>
        </div>
      @else
        <div class="resend_email">
          @lang('Resent confirmation email to') <strong>{{$registered_email}}</strong>
        </div>
      @endif
    </div>
  </div>
@endsection
