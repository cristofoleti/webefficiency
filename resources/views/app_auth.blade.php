<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WebEfficiency</title>

    <!-- Favicons-->
    <link rel="icon" href="/images/favicon/favicon-32x32.png" sizes="32x32">
    <!-- Favicons-->
    <link rel="apple-touch-icon-precomposed" href="/images/favicon/apple-touch-icon-152x152.png">
    <!-- For iPhone -->
    <meta name="msapplication-TileColor" content="#00bcd4">
    <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
    <!-- For Windows Phone -->

    <link href="/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="/css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="/css/custom/custom-style.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="/css/layouts/page-center.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="/js/plugins/sweetalert/dist/sweetalert.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet"
          media="screen,projection">
</head>

<body class="cyan Login">

    @yield('content')

    <script src="/js/plugins/jquery-1.11.2.min.js"></script>
    <script src="/js/materialize.js"></script>
    <script src="/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script type="text/javascript" src="/js/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/js/plugins/jquery-validation/additional-methods.min.js"></script>
    <script type="text/javascript" src="/js/plugins/sweetalert/dist/sweetalert.min.js"></script>
    <script src="/js/plugins.js"></script>

    <script src="/js/custom-script.js"></script>
    <script src="/js/login.js"></script>

    @if($errors->has('email'))
        <script>
            swal("Oops!", "{!! $errors->first('email') !!}", "error");
        </script>
    @endif

</body>

</html>