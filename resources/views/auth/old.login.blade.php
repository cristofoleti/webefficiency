@extends('app')

@section('content')
    <div id="login-page" class="row Login__page">
        <div class="col s12 z-depth-4 card-panel">

            <form class="login-form Login__form" method="post" action="{{ url('/auth/login') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                @if($errors->has('email'))
                    <div class="row red darken-1 Login__error_message">
                        <div class="col s12 center">
                            <p class="center-align">{{ $errors->first('email') }}</p>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="input-field col s12 center">
                        <img src="{{ asset('images/favicon/apple-touch-icon-152x152.png') }}" alt="" class="circle responsive-img valign profile-image-login">
                        <p class="center login-form-text">WebEfficiency</p>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="mdi-social-person-outline prefix"></i>
                        <input type="email" class="validate {{ ($errors->has('email')) ? 'invalid' : '' }}" id="email" name="email" value="{{ old('email') }}">
                        <label for="email" class="center-align">E-mail</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="mdi-action-lock-outline prefix"></i>
                        <input type="password" class="validate {{ ($errors->has('email')) ? 'invalid' : '' }}" name="password" id="password">
                        <label for="password">Senha</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m12 l12 login-text">
                        <input type="checkbox" id="remember" />
                        <label for="remember">Manter Logado</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <button type="submit" class="btn waves-effect waves-light col s12 cyan">Entrar</button>
                    </div>
                </div>

                {{--<div class="row">
                    <div class="input-field col s6 m6 l6">
                    </div>
                    <div class="input-field col s6 m6 l6">
                        <p class="margin right-align medium-small">
                            <a href="{{ url('/password/email') }}">Esqueceu a Senha?</a>
                        </p>
                    </div>
                </div>--}}

            </form>
        </div>
    </div>
@endsection
