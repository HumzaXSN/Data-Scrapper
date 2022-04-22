<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

         <!-- Scripts -->

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
    <!-- Place favicon.ico in the root directory -->


    <!-- CSS here -->
     <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
     <link href="https://cdn.datatables.net/1.11.2/css/dataTables.bootstrap4.min.css" rel="stylesheet">
     <link href="{{ asset('assets/vendor/lobicard/css/lobicard.css') }}" rel="stylesheet">
     <link href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
     <link href="{{ asset('assets/vendor/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet">
     <link href="{{ asset('assets/vendor/themify-icons/css/themify-icons.css') }}" rel="stylesheet">
     <link href="{{ asset('assets/vendor/weather-icons/css/weather-icons.min.css') }}" rel="stylesheet">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">

     <!--custom css-->
     <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

</head>
<body class="app header-fixed left-sidebar-light left-sidebar-light-alt left-sidebar-fixed right-sidebar-fixed right-sidebar-overlay right-sidebar-hidden">

    @yield('content')

    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui-touch/jquery.ui.touch-punch-improved.js') }}"></script>
    <script class="include" type="text/javascript" src="{{ asset('assets/vendor/jquery.dcjqaccordion.2.7.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery.scrollTo.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- <!--echarts-->
    <script type="text/javascript" src="{{asset('assets/vendor/echarts/echarts-all-3.js') }}"></script>
    <!--init echarts-->
    <script type="text/javascript" src="{{asset('assets/vendor/dashboard4-echarts-init.js') }}"></script> --}}


    <!--datatables-->
    <script src="{{asset('assets/vendor/data-tables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/vendor/data-tables/dataTables.bootstrap4.min.js')}}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script> --}}
    {{-- <script src="/vendor/datatables/buttons.server-side.js"></script> --}}


    <!--init scripts-->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    @include('partials.messages')
    <script>
        $(document).ready(function(){
            var input = $('.dataTables_filter input').attr({"placeholder":"Search"});
            $(".dataTables_filter").html('');
            $(".dataTables_filter").append(input);
        });
    </script>
    @stack('scripts')

</body>
</html>
