@extends('emails.template')
@section('content')
<p style="background-color: #404040;
    margin: 0px;
    padding: 14px 25px;
    color: white;
    text-align: center;
    font-weight: normal;
    border-radius: 5px 5px 0px 0px;
    font-size: 14px;
    display: block;
    border: 0.1px solid #404040;">VCC <span class=" font-size:11pt;">{{ __('would like to inform that') }}</span></p>
<div style="padding:35px 42px">
<p style="margin-bottom:25px">@lang('You are receiving this email because we received a password reset request for your account').</p>
<p style="margin-bottom:25px">@lang('Please click the link below to reset your password').</p>
<p style="margin-bottom:25px">
<a style="color:#0064aa" href="{{str_replace('forgot-password?', 'forgot-password/', $actionUrl)}}">{{str_replace('forgot-password?', 'forgot-password/', $actionUrl)}}</a>
</p>
<p style="margin-bottom:30px;">
@lang('If you can not click on the link, copy and paste the address into the address bar. Thank you')
</p>
@endsection
