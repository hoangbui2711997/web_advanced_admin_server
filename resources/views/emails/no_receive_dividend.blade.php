@extends('emails.template')
@section('content')
    <div style="margin-bottom: 20px">
        <p style="margin-bottom:10px; margin-top: 0">@lang('emails.no_receive_dividend.name', [ 'name' => $name ] , $locale)</p>
        <p style="margin-bottom:10px; margin-top: 0">@lang('emails.no_receive_dividend.text2', [], $locale): {{ $date }}</p>
        <p style="margin-bottom:10px; margin-top: 0">@lang('emails.no_receive_dividend.text5', [], $locale): {{ $campaign }}</p>
        @if(!$isSendAdmin)
            <p style="margin-bottom:5px; margin-top: 0">@lang('emails.no_receive_dividend.text1', ['campaign' => $campaign], $locale)</p>
            <p style="margin-bottom:5px; margin-top: 0">@lang('emails.no_receive_dividend.text3', [], $locale): @lang('emails.no_receive_dividend.'.$reason, ['campaign' => $campaign], 'en')</p>
            <p style="margin-bottom:10px; margin-top: 0">@lang('emails.no_receive_dividend.text4', [], $locale)</p>
        @else
            <p style="margin-bottom:5px; margin-top: 0">@lang("emails.no_receive_dividend.".$reason, ['campaign' => $campaign, 'user' => $relatedUser], $locale)</p>
        @endif
    </div>
@endsection
