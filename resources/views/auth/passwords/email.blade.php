@extends('auth.layouts.app')
@section('title')
    @lang('Reset Password')
@endsection
@section('content')
  <div class="content_login_page">
    <img class="icon-logo" src="/images/logo/logo-vcc_126x46.png">
    <p class="login-title">@lang('Reset Password')</p>
    <div class="important-vcc">
        <p class="vcc-title"><span class="glyphicon glyphicon-exclamation-sign icon-important"></span>@lang('Please check that you are visiting') <b>https://www.vcc.exchange</b></p>
        <img src="/images/login/vcc-exchange.png">
    </div>
    <div class="row register_page">
      <form method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}
        @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
        @else
          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="control-label">@lang('E-Mail Address')</label>
            <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}">
            @if ($errors->has('email'))
              <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
            @endif
          </div>
          <div class="form-group">
            <button type="submit" class="btn-login btn primary-btn btn-block">
              @lang('Send Password Reset Link')
            </button>
          </div>
        @endif
      </form>
    </div>
  </div>
@endsection
