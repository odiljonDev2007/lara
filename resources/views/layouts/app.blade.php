<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title') - {{ setting('app_name') }}</title>

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('assets/img/icons/apple-touch-icon-144x144.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ url('assets/img/icons/apple-touch-icon-152x152.png') }}" />
    <link rel="icon" type="image/png" href="{{ url('assets/img/icons/favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ url('assets/img/icons/favicon-16x16.png') }}" sizes="16x16" />
    <meta name="application-name" content="{{ setting('app_name') }}"/>
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="{{ url('assets/img/icons/mstile-144x144.png') }}" />

    <link media="all" type="text/css" rel="stylesheet" href="{{ url(mix('assets/css/vendor.css')) }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ url(mix('assets/css/app.css')) }}">

    @yield('styles')

    @hook('app:styles')
</head>
<body>
    @include('partials.navbar')

    <div class="container-fluid">
        <div class="row">
            @include('partials.sidebar.main')

            <div class="content-page">
                <main role="main" class="px-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script src="{{ url(mix('assets/js/vendor.js')) }}"></script>
    <script src="{{ url('assets/js/as/app.js') }}"></script>

    <!--<script src="https://enterprise.api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=2de9d557-e783-4037-88f7-9b43a390be20&suggest_apikey=c7e70918-9f6a-48b7-8ced-bfa07bbaf490" type="text/javascript"></script>-->
    
    <!--<script src="https://enterprise.api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=e1c22e0f-84da-4f47-acff-3eaababcebb7&suggest_apikey=dbf8600f-bbf9-4b86-9788-02f155af658a" type="text/javascript"></script>-->
    <script src="https://api-maps.yandex.ru/2.1/?apikey=2de9d557-e783-4037-88f7-9b43a390be20&suggest_apikey=546ee033-8541-45de-af6a-7eeec8821094&lang=ru_RU"></script>
    
    @yield('scripts')

    @hook('app:scripts')
</body>
</html>
