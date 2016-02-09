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

    <div id="login-page" class="row Login__wrapper">
        <div class="col s12 z-depth-4 card-panel">
            <form class="login-form right-alert Login__form" id="Login__form" method="post" action="{{ url('/auth/login') }}">
                {{ csrf_field() }}

                <div class="row">
                    <div class="input-field col s12 center">
                        <img src="/images/favicon/apple-touch-icon-152x152.png" alt=""
                             class="circle responsive-img valign profile-image-login">
                        <p class="center login-form-text">WebEfficiency</p>
                    </div>
                </div>

                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="mdi-social-person-outline prefix"></i>
                        <input id="email" type="email" name="email" class="Login__email_input">
                        <label for="email" class="">E-mail</label>
                    </div>
                </div>

                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="mdi-action-lock-outline prefix"></i>
                        <input id="password" type="password" name="password" class="Login__password_input">
                        <label for="password" class="">Senha</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <button type="submit" class="btn waves-effect waves-light col s12 cyan">Entrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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