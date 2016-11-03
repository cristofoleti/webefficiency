<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="robots" content="noindex">
        <meta name="googlebot" content="noindex">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <title>WebEfficiency</title>

        <link rel="icon" href="/images/favicon/favicon-32x32.png" sizes="32x32">
        <link rel="apple-touch-icon-precomposed" href="/images/favicon/apple-touch-icon-152x152.png">
        <meta name="msapplication-TileColor" content="#00bcd4">
        <meta name="msapplication-TileImage" content="/images/favicon/mstile-144x144.png">

        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link href="/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="/css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="/css/custom/custom-style.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet"
              media="screen,projection">
        <link href="/js/plugins/sweetalert/dist/sweetalert.css" type="text/css" rel="stylesheet">
    </head>

    <body>

        @include("companies.modal_cadastro")
        @include("companies.modal_change")

        <!-- START HEADER -->
        @include("partials.header")
        <!-- END HEADER -->

        <!-- START MAIN -->
        <div id="main" class="App__main">

            <!-- START WRAPPER -->
            <div class="wrapper">

                <!-- START LEFT SIDEBAR NAV-->
                @include('partials.sidebar')
                <!-- END LEFT SIDEBAR NAV-->

                <!-- START CONTENT -->
                <section id="content">

                    <!-- START CONTAINER -->
                    @yield('content')
                    <!-- END CONTAINER -->

                </section>
                <!-- END CONTENT -->

            </div>
            <!-- END WRAPPER -->
        </div>
        <!-- END MAIN -->

        <!-- START FOOTER -->
        <footer class="page-footer">
            <div class="footer-copyright">
                <div class="container">
                </div>
            </div>
        </footer>
        <!-- END FOOTER -->

        <!-- START SCRIPTS -->
        <script src="{{ asset('js/plugins/jquery-1.11.2.min.js') }}"></script>
        <script src="{{ asset('js/cadastro.js') }}"></script>
        <script src="{{ asset('js/materialize.js') }}"></script>
        <script src="{{ asset('js/plugins.js') }}"></script>
        <script src="{{ asset('js/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('js/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
        <script type="text/javascript" src="/js/plugins/jquery-validation/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/js/plugins/jquery-validation/additional-methods.min.js"></script>
        <script src="{{ asset('js/highstock-2.1.7/js/highstock.js') }}"></script>
        <script src="{{ asset('js/moment-with-locales.min.js') }}"></script>
        <script src="{{ asset('js/pickdate.js/translations/pt_BR.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>

        @yield('runtime-scripts')
        <!-- END SCRIPTS -->

    </body>

</html>
