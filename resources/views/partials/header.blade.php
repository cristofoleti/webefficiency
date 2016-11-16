@if(!Auth::guest())
<header id="header" class="page-topbar">
    <!-- start header nav-->
    <div class="navbar-fixed">
        <ul id="logged-user" class="dropdown-content">
            <li>
                <a href="{{ route('auth.logout') }}"><i class="mdi-action-exit-to-app left"></i> Sair</a>
            </li>
        </ul>
        <nav class="navbar-color">
            <div class="nav-wrapper">
                <ul class="left">
                    <li>
                        <h1 class="logo-wrapper">
                            <a href="{{ route('home') }}" class="Page__logo_link brand-logo darken-1">
                                <img src="{{ asset('images/logo.png') }}" alt="WebEfficiency"
                                     class="Page__logo">
                            </a>
                            <span class="logo-text">Materialize</span>
                        </h1>
                    </li>
                </ul>
                <ul class="right hide-on-med-and-down">
                    <li>
                        <a href="javascript:void(0);"
                           class="waves-effect waves-block waves-light toggle-fullscreen"
                           title="Exibir em Tela Inteira">
                            <i class="mdi-action-settings-overscan"></i>
                        </a>
                    </li>
                    @if(Auth::user()->is_admin == 1)
                    <li>
                        <a class="modal-trigger" href="#App__cadastro_modal"
                           onclick="javascript: {{ Auth::user()->isGroupAdmin() ? 'loadCompanies()' : 'loadUsers()' }}"
                           >Cadastro</a>
                    </li>
                    @endif
                    <li>
                        <a class="modal-trigger" href="#App__change_company_modal">Empresa</a>
                    </li>
                    <li>
                        <a class="dropdown-button" href="#!" data-activates="logged-user" data-hover="true">
                            {{ Auth::user()->name }} <i class="mdi-navigation-arrow-drop-down right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- HORIZONTAL NAV START -->
        <nav id="horizontal-nav" class="white hide-on-med-and-down Main_Menu">
            <div class="nav-wrapper">
                <ul id="nav-mobile" class="left hide-on-med-and-down">
                    <li class="@if(Route::currentRouteName() == 'home') active @endif">
                        <a href="{{ route('home') }}" class="cyan-text">
                            <i class="mdi-action-dashboard"></i><span>Benchmarking</span>
                        </a>
                    </li>

                    <li class="@if(preg_match("/charts/i", Route::currentRouteName())) active @endif">
                        <a href="{{ route('charts.variables') }}" class="cyan-text">
                            <i class="mdi-editor-insert-chart"></i><span>Variáveis</span>
                        </a>
                    </li>

                    <li class="@if(preg_match("/reports/i", Route::currentRouteName())) active @endif">
                        <a href="{{ route('reports') }}" class="cyan-text">
                            <i class="mdi-action-assignment"></i><span>Relatórios</span>
                        </a>
                    </li>

                    {{--<li class="@if(preg_match("/export/i", Route::currentRouteName())) active @endif">
                        <a href="{{ route('export.excel') }}" class="cyan-text">
                            <i class="mdi-content-archive"></i><span>Excel</span>
                        </a>
                    </li>--}}
                </ul>
            </div>
        </nav>
        <!-- HORIZONTAL NAV END-->
    </div>
    <!-- end header nav-->
</header>
@endif