<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('build/assets/favicon.png') }}">

<!-- Core Plugins & Styles -->
<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('build/assets/app-C6eltDXH.css') }}">

@stack('vendor-styles')
@yield('vendor-styles')


@stack('page-styles')
@stack('style')