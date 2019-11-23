@extends('emails.template')
@section('content')
    <div style="margin-bottom: 20px">
        <p style="margin-bottom:10px; margin-top: 0">@lang('emails.kyc_notification.name', [ 'name' => $name ] , $locale)</p>
        <p style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text1', [], $locale)</p>
        <p style="margin-bottom:10px; margin-top: 0">@lang('emails.kyc_notification.text2', [], $locale)</p>
        <p style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text3', [], $locale)</p>
        <p style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text4', [], $locale)</p>
        <p style="margin-bottom:10px; margin-top: 0">
            <a href="{{ env('CLIENT_URL') }}/login">
                {{ env('CLIENT_URL') }}/login
            </a>
        </p>
        <div style="margin-bottom: 10px;">
            <div>
                <ul>
                    <li style="margin-bottom:10px; margin-top: 0">@lang('emails.kyc_notification.text5', [], $locale)</li>
                    <li style="margin-bottom:10px; margin-top: 0">@lang('emails.kyc_notification.text6', [], $locale)</li>
                </ul>
            </div>
        </div>
        <p style="margin-bottom:10px; margin-top: 0">@lang('emails.kyc_notification.text7', [], $locale)</p>
        <div style="margin-bottom: 10px;">
            <div>
                <ul>
                    <li style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text8', [], $locale)</li>
                    <li style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text9', [], $locale)</li>
                    <li style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text10', [], $locale)</li>
                    <li style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text11', [], $locale)</li>
                    <li style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text12', [], $locale)</li>
                    <li style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text13', [], $locale)</li>
                    <li style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text14', [], $locale)</li>
                    <li style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text15', [], $locale)</li>
                </ul>
            </div>
        </div>
        <p style="margin-bottom:10px; margin-top: 0">@lang('emails.kyc_notification.text16', [], $locale)</p>
        <p style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text17', [], $locale)</p>
        <p style="margin-bottom:5px; margin-top: 0">@lang('emails.kyc_notification.text18', [], $locale)</p>
        <p style="margin-bottom:10px; margin-top: 0">@lang('emails.kyc_notification.text19', [], $locale)</p>
    </div>
@endsection
