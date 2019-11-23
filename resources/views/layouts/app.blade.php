<!DOCTYPE html>
<html lang="{{ $userLocale }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="masterdata-version" content="{{ $dataVersion }}">
    @if (Auth::check())
        <meta name="authenticated" content="1">
    @endif

    <title>{{ config('app.name', 'bitCastle') }}</title>
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="icon" href="/images/favicon.ico">
    <script src="{{ mix('js/socket.io.js') }}"></script>
    {{--<link href="https://unpkg.com/nprogress@0.2.0/nprogress.css" rel="stylesheet" />--}}
    {{--<script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>--}}
</head>
<body>
    <div id="admin-app"></div>
    <script>
        var SOCKET_URL = "{{ env('SOCKET_URL', 'http://' . Request::getHost() . '6001') }}";
        var API_URL = "{{ env('API_URL') }}";
    </script>
    <script src="http://cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
