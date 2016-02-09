<aside id="left-sidebar-nav">
    <ul id="slide-out" class="side-nav leftside-navigation ">
        <li class="user-details cyan darken-2">
            <div class="row">
                {{--<div class="col col s4 m4 l4">
                    <img src="images/avatar.jpg" alt=""
                         class="circle responsive-img valign profile-image">
                </div>--}}
                <div class="col col s8 m8 l8">
                    <ul id="profile-dropdown" class="dropdown-content">
                        <li>
                            <a class="waves-effect waves-light btn modal-trigger" href="#App__change_company_modal">Empresa</a>
                        </li>
                        <li>
                            <a href="{{ route('auth.logout') }}">
                                <i class="mdi-hardware-keyboard-tab"></i> Logout
                            </a>
                        </li>
                    </ul>
                    <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn"
                       href="#" data-activates="profile-dropdown">{{ Auth::user()->name }}<i
                                class="mdi-navigation-arrow-drop-down right"></i></a>
                    <p class="user-roal"></p>
                </div>
            </div>
        </li>
        <li class="bold @if(Route::currentRouteName() == 'home') active @endif">
            <a href="{{ route('home') }}" class="waves-effect waves-cyan">
                <i class="mdi-action-dashboard"></i> Dashboard
            </a>
        </li>

        <li class="bold @if(preg_match("/charts/i", Route::currentRouteName())) active @endif">
            <a href="{{ route('charts.variables') }}" class="waves-effect waves-cyan">
                <i class="mdi-editor-insert-chart"></i> Gráficos
            </a>
        </li>

        <li class="bold @if(preg_match("/reports/i", Route::currentRouteName())) active @endif">
            <a href="{{ route('reports') }}" class="waves-effect waves-cyan">
                <i class="mdi-action-assignment"></i> Relatórios
            </a>
        </li>

        {{--<li class="bold @if(preg_match("/export/i", Route::currentRouteName())) active @endif">
            <a href="{{ route('export.excel') }}" class="waves-effect waves-cyan">
                <i class="mdi-content-archive"></i> Excel
            </a>
        </li>--}}
    </ul>
    <a href="#" data-activates="slide-out"
       class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only light-blue darken-3">
        <i class="mdi-navigation-menu"></i></a>
</aside>