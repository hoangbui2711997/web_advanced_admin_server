@extends('auth.layouts.app')
@section('title')
    @lang('Change Password')
@endsection
@section('content')
<div class="content_login_page">
  <img class="icon-logo" src="/images/logo/logo-vcc_126x46.png">
  <p class="login-title">@lang('Change Password')</p>
  <div class="important-vcc">
      <p class="vcc-title"><span class="glyphicon glyphicon-exclamation-sign icon-important"></span>@lang('Please check that you are visiting') <b>https://www.vcc.exchange</b></p>
      <img src="/images/login/vcc-exchange.png">
  </div>
  <div class="row register_page">
    <form method="POST" action="{{ route('password.request') }}">
      {{ csrf_field() }}
      <input type="hidden" name="token" value="{{ $token }}">
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="control-label">@lang('E-Mail Address')</label>
        <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
        @if ($errors->has('email'))
          <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
        @endif
      </div>
      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="control-label">@lang('New password')</label>

        <input id="password" type="password" class="form-control" name="password" required>

        @if ($errors->has('password'))
          <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
        @endif
      </div>
      <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label for="password-confirm" class="control-label">@lang('Confirm password')</label>
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        @if ($errors->has('password_confirmation'))
          <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
        @endif
      </div>
      <div class="form-group">
        <button type="submit" class="btn-login btn primary-btn btn-block">
          @lang('Change Password')
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
