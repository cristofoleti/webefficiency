@extends('app_auth')

@section('content')

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

            <p>
                <a href="{{ url('/password/email') }}" class="link">Esqueci minha senha</a>
            </p>

        </div>
    </div>

@endsection