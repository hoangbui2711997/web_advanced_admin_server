<!DOCTYPE html>
@if(isset($userLocale))
<html lang="{{ $userLocale }}">
@else
<html lang="{{ app()->getLocale() }}">
@endif
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="/favicon.ico?v=8">

  <link rel="manifest" href="/favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if(isset($dataVersion))
    <meta name="masterdata-version" content="{{ $dataVersion }}">
  @endif

  <title>@yield('title')</title>

  <!-- Styles -->
  <link href="{{ mix('css/auth/main.css') }}" rel="stylesheet">
  <link href="{{ mix('css/common.css') }}" rel="stylesheet">
  
  @yield('css')
</head>
<body>
<div id="auth_container">
  <div id="app_auth">
    <div id="content">
      <div class="wrapper_auth wrapper_auth_responsive">
        <div class="sub_content">
          @yield('content')
        </div>
        <div id="footer_auth">
          <p class="link_page">
            <a @if(Route::current()->getName() == 'home') class="fo-bold" @endif href="{{ url('/') }}">@lang('Home')</a>
            <a href="{{ url('/support') }}" target="_blank">@lang('Support')</a>
            <a @if(App::getLocale() == 'en') class="fo-bold" @endif href="?lang=en">English</a>
            <a @if(App::getLocale() == 'vi') class="fo-bold" @endif href="?lang=vi">Tiếng Việt</a>
          </p>
          <p class="copy_right">&copy; 2017-2018 Vcc.exchange All Rights Reserved<p>
        </div>
      </div>
    </div>
  </div>
  @include('auth.modals.guideOTPModal')
  @include('auth.modals.agreementOTPModal')
</div>
<script src="{{ mix('js/desktop/auth_container.js') }}"></script>
@yield('scripts')
</body>
</html>
