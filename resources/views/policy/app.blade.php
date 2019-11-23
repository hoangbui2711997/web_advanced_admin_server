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
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <link href="{{ mix('css/landing.css') }}" rel="stylesheet">

  @if ( App::getLocale() == 'vi')

  @endif
  <style lang="css">
  * {
    font-family: Arial, Helvetica, sans-serif !important;
  }
  
  #header {
    min-height: auto !important;
    min-width: 1776px;
  }
  .agreement .agreementTitle {
    font-size: 30px;
    color: #272f35;
    height: 100px;
    line-height: 100px;
    border-bottom: 1px solid #e5e5e5;
  }
  .agreement .agreementCon p.agreementConFooter {
    color: #222;
    font-size: 14px;
  }
  .agreement .agreementCon p {
      font-size: 14px;
      color: #333;
      line-height: 24px;
      margin-bottom: 24px;
  }
  .agreement .agreementCon p.agreementConSubHeader {
    color: #333;
    font-weight: bold;
    font-size: 14px;
  }
  #app .user-nav .navbar-header .navbar-brand img {
    width: 60px;
    height: auto;
    margin-top: 10px;
  }
  .agreement .agreementCon p.agreementConHeader {
    color: #333;
    font-weight: bold;
  }
  </style>
  

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="tradingview/charting_library/charting_library.min.js"></script>
  <script type="text/javascript" src="tradingview/charting_library/datafeed/udf/datafeed.js"></script>
</head>
<body>
<div id="app">
  <message></message>
  <div id="header">
    @if ( Auth::check() )
      <nav class="user-nav">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('/') }}">
              <img src="/images/bijkoex_brand_logo.png?v=2">
            </a>
          </div>
          <div id="top-menu" class="collapse navbar-collapse">
            <li><a href="/trading">@lang('Trading')</a></li>
            <li><a href="/wallet">@lang('Wallet')</a></li>
            <li><a href="/transaction">@lang('Funds')</a></li>
            <li><a href="/home">@lang('Premium Gap')</a></li>
            <li><a href="/market_history">@lang('History')</a></li>
            <li><a href="/support">@lang('Support')</a></li>
          </div>

          <!-- Right Side Of Navbar -->
          <div class="user-info pull-right">
            <ul>
              <li v-cloak>
                <span class="mail">{{Auth::user()->email}}</span>
                <a class="btn-my-page" href="/account">
                  @lang('My Page')
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                  {{ csrf_field() }}
                  <button type="submit" class="logout btn-logout">
                    @lang('Logout')
                  </button>
                </form>
              </li>
            </ul>
          </div><!--end .user-info -->
        </div><!--end .container-fluid -->
      </nav>
    @else
      <nav class="user-nav">
        <div class="container-fluid">
          <div class="navbar-header">
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
              <img src="/images/bijkoex_brand_logo.png">
            </a>
          </div>
          <div id="top-menu" class="collapse navbar-collapse">
            <li><a href="/trading">@lang('Trading')</a></li>
            <li><a href="/wallet">@lang('Wallet')</a></li>
            <li><a href="/transaction">@lang('Funds')</a></li>
            <li><a href="/home">@lang('Premium Gap')</a></li>
            <li><a href="/market_history">@lang('History')</a></li>
            <li><a href="/support">@lang('Support')</a></li>
            
          </div>

          <!-- Right Side Of Navbar -->
          <div class="user-info pull-right">
            <ul>
              <li><a class="btn btn-login show-login-form" href="{{ route('login') }}">@lang('LOGIN')</a></li>
              <li><a class="btn btn-login" href="{{ route('register') }}">@lang('SIGNUP')</a></li>
            </ul>
          </div>
        </div>
      </nav>
    @endif
    <div class="main-content row" style="display:none">
      <p class="description">@lang('Trading-Optimized Exchange')</p>
      <div class="app-logo">
        <img src="/images/bijkoex_large_logo.png">
      </div>

      @if ( Auth::check() )
        <div class="actions">
          <a href="{{ route('home') }}" class="btn">@lang('TRADING')</a>
        </div>
      @else
        <div class="actions">
          <a class="btn show-login-form" href="#">@lang('Login')</a>
          <a class="btn" href="{{ route('register') }}">@lang('Sign Up')</a>
        </div>
        <div class="col-md-12 col-md-push-0 col-sm-12 col-sm-push-0 col-xs-8 col-xs-push-2">
          <form class="form-login form-inline" id="login-form" role="form" method="POST"
                action="{{ url('/login') }}">
            {{ csrf_field() }}
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
              <input type="text" class="input-form form-control" name="email" placeholder="@lang('ID-EMAIL')">

            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
              <input type="password" class='input-form form-control' name="password"
                     placeholder="@lang('PASSWORD')">

            </div>
            <div class="form-group">
              <button class="btn-login btn primary-btn">@lang('LOGIN')</button>
            </div>
          </form>
        </div>
      @endif
    </div>
  </div>
  <div id="content">
    @yield('content')
  </div>
  <div id="footer">
    <div id="footer_content">
      <ul>
        <li><a href="#">@lang('News')</a></li>
        <li><a href="/terms" target="_blank">@lang('Terms of Use')</a></li>
        <li><a href="/policy" target="_blank" >@lang('Privacy Policy')</a></li>
        <li><a href="#">@lang('Funds Guidline')</a></li>
        <li><a href="#">@lang('FAQ')</a></li>
      </ul>
      <div class="clear"></div>
      <div class="info">
        <div class="left">
          <p class="description">@lang('Cryptocurrency Trading')</p>
          <div class="app-logo">
            <img src="/images/bijkoex_large_logo.png">
          </div>
        </div>
        <div class="right">
          <p>@lang('Address: 50 Hung Thai 2, Tan Phong, Phu My Hung, Q7, HCM city, Vietnam')</p>
          <p>@lang('Contact Us: Working Hours: Weekdays, 09:00 to 20:00')</p>
          <p>@lang('Customer Support: cs@vcc.exchange, FAQ: faq@vcc.exchange, Mobile: +84 915673038')</p>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="{{ mix('js/socket.io.js') }}"></script>
<script>
    // this url is used (in bootstrap.js) to connect to echo server
    var SOCKET_URL = "{{ env('SOCKET_URL', 'http://' . Request::getHost()) }}";
</script>
@yield('scripts')
</body>
</html>