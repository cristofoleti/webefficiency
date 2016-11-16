@extends('app')

@section('content')
    <div class="Chart__wrapper">
        <nav class="range-selector-nav Chart__selector_nav" style="display: none;">
            <div class="nav-wrapper">
                <a href="#" class="brand-logo">
                    <div class="tooltip">
                        <i><span class="tooltiptext">Comparativo do CAG em todas empresas. Utilize os botões ao lado para ajustar o período.</span></i> 
                        Benchmarking - <span>Índice de Performance da CAG (Kw/Tr)</span>
                    </div>
                </a>
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
        <div class="chart-container valign-wrapper">
            <div class="preloader-wrapper big active valign Chart__preloader">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('runtime-scripts')
    <script type="text/javascript">
        $(function () {
            "strict mode";

            var pageTopbarHeight = $(".page-topbar:visible").outerHeight();
            var horizontalNavHeight = $("#horizontal-nav:visible").outerHeight();
            var footerHeight = $("footer.page-footer:visible").outerHeight();
            var windowHeight = $(window).innerHeight();
            var contentHeight = windowHeight - (pageTopbarHeight + horizontalNavHeight + footerHeight + 72);

            var timeUnits = {
                hour: 3600000,
                day: 24 * 3600000,
                week: 7 * 24 * 3600000,
                month: 30.44 * 24 * 3600000,
                year: 365.24 * 24 * 3600000
            };

            $(".chart-container").animate({
                height: contentHeight
            }, 0, function () {
                var seriesOptions = [];
                var seriesCounter = 0;
                var createChart = function () {
                    $('.chart-container').highcharts('StockChart', {
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
                        xAxis: {
                            events: {
                                afterSetExtremes: function (event) {
                                    this.chart.hideLoading();
                                }
                            }
                        },
                        tooltip: {
                            valueDecimals: 2
                        },
                        series: seriesOptions,
                        plotOptions: {
                            series: {
                                dataGrouping: {
                                    enabled: true
                                }
                            }
                        }
                    });
                    $("nav.range-selector-nav ul li a").trigger("click");
                };

                var companies = {!! $companies->toJson() !!};

                $.each(companies, function (i, company) {
                    //console.log(company.id+' - '+company.name);
                    $.getJSON("/dashboard/" + company.id, function (data) {
                        //console.log(data);
                        if(data != null && data.length > 0){
                            seriesOptions.push({
                                name: company.name,
                                data: data
                            });
                        }

                        seriesCounter++;

                        if (seriesCounter === Object.keys(companies).length) {
                            createChart();
                        }
                        
                    });
                });
            });

            $("nav.range-selector-nav ul li a").on("click", function (event) {
                var $btn = $(this);

                if ($btn.closest("li").hasClass('active')) {
                    return false;
                }

                var $list = $("nav.range-selector-nav ul");
                var $chart = $(".chart-container").highcharts();
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
@stop
