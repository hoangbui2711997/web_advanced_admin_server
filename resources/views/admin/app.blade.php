<!DOCTYPE html>
<html lang="{{ $userLocale }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(isset($dataVersion))
    <meta name="masterdata-version" content="{{ $dataVersion }}">
    @endif

    <title>{{ config('app.name', 'bitCastle') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="skin-blue sidebar-mini">
<div id="admin-app"></div>

<!-- Scripts -->
<script>
    // this url is used (in bootstrap.js) to connect to echo server
    var API_URL = "{{ env('API_URL', 'http://' . Request::getHost()) }}";
</script>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
