var elixir = require('laravel-elixir');

elixir(function (mix) {
    mix.sass('materialize.scss')
        .sass('style.scss')
        .sass('theme-components/layouts/style-fullscreen.scss')
        .sass('theme-components/layouts/style-horizontal.scss')
        .sass('custom/custom-style.sass', 'public/css/custom/custom-style.css')
        .scripts(['login.js'], 'public/js/login.js')
        .scripts(['app.js'], 'public/js/app.js')
        .scripts(['cadastro.js'], 'public/js/cadastro.js');
});
