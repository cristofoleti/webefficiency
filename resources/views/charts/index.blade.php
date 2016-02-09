@extends('app')

@section('content')
    <aside id="left-nav-variables-container" class="Variables__nav">
        <ul class="Variables__list">
            @foreach($variables as $variable)
                <li class="Variables__list_item">
                    <a class="Variables__list_link" href="{{ route('charts.variables.data', [$variable->tag]) }}" data-unity="{{ $variable->unity }}" data-tag="{{ $variable->tag }}">
                        {{ $variable->description }}
                    </a>
                </li>
            @endforeach
        </ul>
    </aside>

    <div class="light-blue lighten-5 chart-wrapper with-left-nav Variable__chart_wrapper">
        <nav class="range-selector-nav Variable__chart_nav" style="display: none;">
            <div class="nav-wrapper">
                <a href="#" class="brand-logo Variable__chart_nav_title"></a>
                <ul id="nav-mobile" class="right">
                    <li>
                        <a href="#" class="range-selector" data-range-type="day" data-range-count="1">1d</a>
                    </li>
                    <li class="active">
                        <a href="#" class="range-selector" data-range-type="week" data-range-count="1">7d</a>
                    </li>
                    <li>
                        <a href="#" class="range-selector" data-range-type="day" data-range-count="15">15d</a>
                    </li>
                    <li>
                        <a href="#" class="range-selector" data-range-type="month" data-range-count="1">1m</a>
                    </li>
                    <li>
                        <a href="#" class="range-selector" data-range-type="month" data-range-count="3">3m</a>
                    </li>
                    <li>
                        <a href="#" class="range-selector" data-range-type="month" data-range-count="6">6m</a>
                    </li>
                    <li>
                        <a href="#" class="range-selector" data-range-type="year" data-range-count="1">1a</a>
                    </li>
                    <li>
                        <a href="#" class="range-selector" data-range-type="all" data-range-count="1">Tudo</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="valign-wrapper Variables__chart_wrapper"></div>
    </div>
@endsection

@section('runtime-scripts')
    <script>
        function loadChart (tag, route, title, subtitle) {
            var loaderTemplate = '<div class="preloader-wrapper big active Variable__chart_preloader">' +
                    '<div class="spinner-layer spinner-blue-only">' +
                    '<div class="circle-clipper left">' +
                    '<div class="circle"></div></div><div class="gap-patch">' +
                    '<div class="circle"></div></div><div class="circle-clipper right">' +
                    '<div class="circle"></div></div></div></div>';
            var $chartContainer = $(".Variables__chart_wrapper");
            var $rangeSelectorBar = $(".range-selector-nav");
            var timeUnits = {
                hour: 3600000,
                day: 24 * 3600000,
                week: 7 * 24 * 3600000,
                month: 30.44 * 24 * 3600000,
                year: 365.24 * 24 * 3600000
            };
            $rangeSelectorBar.hide();

            $.ajax({
                url: route,
                beforeSend: function () {
                    $chartContainer.html(loaderTemplate);
                },
                success: function (data) {
                    $rangeSelectorBar.find(".Variable__chart_nav_title").html(title + " <span class='Variable__chart_nav_subtitle'>(" + subtitle + ")</span>");
                    $rangeSelectorBar.show();
                    $chartContainer.highcharts('StockChart', {
                        chart: {
                            alignTicks: false,
                            zoomType: 'x',
                            marginTop: 40,
                            ignoreHiddenSeries: false,
                            events: {
                                load: function (e) {
                                    var $rangeNav = $("nav.range-selector-nav");
                                    $rangeNav.show();
                                    var $btnActive = $rangeNav.find("ul li.active a");
                                    var rangeType = $btnActive.data("range-type");
                                    var rangeCount = $btnActive.data("range-count");
                                    var extremes = this.xAxis[0].getExtremes();
                                    var max = ('all' === rangeType) ? extremes.dataMax : extremes.max;
                                    var min = extremes.dataMin;

                                    if ('all' !== rangeType) {
                                        var min = max - (eval("timeUnits." + rangeType) * parseInt(rangeCount));
                                    }

                                    this.xAxis[0].setExtremes(min, max);
                                }
                            }
                        },
                        rangeSelector: {
                            enabled: false
                        },
                        series: [{
                            name: tag,
                            data: data,
                            tooltip: {
                                valueDecimals: 2
                            }
                        }],
                        xAxis: {
                            events: {
                                afterSetExtremes: function (event) {
                                    this.chart.hideLoading();
                                }
                            }
                        }
                    });
                }
            });
        }

        function loadFirstChart(nav) {
            var $_first_link = nav.find(".Variables__list_item").find("a").first();
            $_first_link.addClass("Variables__list_link-current")

            if (! $_first_link.hasClass("loaded").length) {
                var tag         = $_first_link.data("tag"),
                    unity       = $_first_link.data("unity"),
                    description = $_first_link.text(),
                    url         = $_first_link.attr("href");

                loadChart(tag, url, description, unity);

                return;
            }

            return;
        }

        function resizeLeftNav(el, from_top, height) {
            el.animate({
                top: from_top
            }, 0, function () {
                el.animate({
                    height: height
                }, 0, function () {
                    $(this).perfectScrollbar({
                        suppressScrollX: true
                    });
                });
            });
        }

        var resizeTimer;

        jQuery(document).ready(function($) {
            "strict mode";

            var window_height = $(window).height(),
                header_height = $("header:visible").outerHeight(),
                main_menu_height = (null == $(".Main_Menu:visible").outerHeight()) ? 0 : $(".Main_Menu:visible").outerHeight(),
                header_total_height = parseInt(header_height + main_menu_height),
                footer_height = $("footer:visible").outerHeight(),
                variables_nav_height = window_height - parseInt(header_height + main_menu_height + footer_height);

            var $_variables_nav = $('.Variables__list');

            var timeUnits = {
                hour: 3600000,
                day: 24 * 3600000,
                week: 7 * 24 * 3600000,
                month: 30.44 * 24 * 3600000,
                year: 365.24 * 24 * 3600000
            };

            $(window).on('resize', function(e) {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    resizeLeftNav($_variables_nav, header_total_height, variables_nav_height);
                }, 250);

            });
            resizeLeftNav($_variables_nav, header_total_height, variables_nav_height);

            $(".Variables__chart_wrapper").animate({
                height: variables_nav_height
            }, 0, function () {
                loadFirstChart($_variables_nav);
            });

            $(".Variables__list_link").on("click", function (event) {
                var $btn = $(this),
                    unity = $btn.data("unity"),
                    tag = $btn.data("tag"),
                    description = $btn.text(),
                    url = $btn.attr("href"),
                    $_variables_nav = $('.Variables__list');;

                if ($btn.hasClass("Variables__list_link-current")) {
                    return false;
                }

                $_variables_nav.find(".Variables__list_link").removeClass("Variables__list_link-current");
                $btn.addClass("Variables__list_link-current", "loaded");

                loadChart(tag, url, description, unity);

                event.stopPropagation();
                event.preventDefault();
                return false;
            });

            $("nav.range-selector-nav ul li a").on("click", function (event) {
                var $btn = $(this);

                if ($btn.closest("li").hasClass('active')) {
                    return false;
                }

                var $list = $("nav.range-selector-nav ul");
                var $chart = $(".Variables__chart_wrapper").highcharts();
                $chart.showLoading("Carregando...");
                var rangeType = $btn.data("range-type");
                var rangeCount = $btn.data("range-count");
                var extremes = $chart.xAxis[0].getExtremes();
                var max = ('all' === rangeType) ? extremes.dataMax : extremes.max;
                var min = extremes.dataMin;

                if ('all' !== rangeType) {
                    min = max - (eval("timeUnits." + rangeType) * parseInt(rangeCount));
                }

                $chart.xAxis[0].setExtremes(min, max);

                $list.find("li").removeClass("active");
                $btn.closest("li").addClass("active");

                event.stopPropagation();
                event.preventDefault();
                return false;
            });
        });
    </script>
@endsection
