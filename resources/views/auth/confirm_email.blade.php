@extends('auth.layouts.app')

@section('content')
<div class="content_register_page">
    <div class="row register_page">
        <div class="register-success">
            <h3 class="content-title">@lang('Email Confirmation')</h3>
            @if ($result)
                <p style="text-align: center;">@lang('Your email has been confirmed!')</p>
            @else
                <p style="text-align: center;">@lang('Your confirmation link is invalid or expired!')</p>
            @endif
            <p><a href="{{ url('/') }}" class="btn-login btn primary-btn">@lang('GO HOME')</a></p>
        </div>
    </div>
</div>
@endsection
