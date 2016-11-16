<div class="container">
    <div class="section">

        <div class="header-print hide">
            <div class="pull-left">
                <h1 class="card-title">WEB Efficiency</h1>
                <h2 class="card-title">{{ $title or '' }}</h2>
            </div>
            <div class="logo pull-right">
                <img src="{{ asset('images/logo.png') }}" alt="WebEfficiency" />
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th width="200">Informações Gerais</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Cliente:</strong></td>
                        <td>{{ $company->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Data/Hora:</strong></td>
                        <td>{{ date('d/m/Y H:i:s') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="Chart__wrapper">
            <nav class="range-selector-nav Chart__selector_nav Variable__chart_nav" style="display: none;">
                <div class="nav-wrapper">
                    <!-- <a href="#" class="brand-logo Variable__chart_nav_title">{{ $title or '' }}</a> -->

                    <a href="#" class="brand-logo Variable__chart_nav_title">
                    <div class="tooltip">
                        <i><span class="tooltiptext">{{ $tooltip or '' }}</span></i> 
                        {{ $title or '' }}
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

        <div class="right-align margin">
            <button type="button"
                    class="waves-effect waves-light btn Report__modal_pdf"
                    data-target="Report__modal">
                <i class="mdi-action-assessment left"></i>Imprimir
            </button>
        </div>

    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function ($) {
        "strict mode";

        var $Report__modal_pdf = $(".Report__modal_pdf");

        //Gerar relatório
        $Report__modal_pdf.html("CARREGANDO...").addClass('disabled').prop('disabled', true);
                
        $.ajax({
            cache: false,
            url: "{{ route('reports.report_chart.graph', $report) }}",
            type: 'get',
            dataType: 'json',
            success: function(data){
                //console.log(data);

                if(data != null && data.length > 0){

                    var chartData = [];
                    for(var i = 0; i < data.length; i++){
                        chartData.push({
                            name: data[i]["name"],
                            data: data[i]["data"],
                            tooltip: { valueSuffix: data[i]["suffix"] }
                        });
                    }

                
                    if(createChart(chartData)){
                        $Report__modal_pdf.removeClass('disabled').prop('disabled', false).html("IMPRIMIR");
                    }

                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);

                var response = JSON.parse(xhr.responseText);
                var messages = [];

                $.each(response, function (key, value) {
                    messages.push(value);
                });

                sweetAlert("Erro", messages.join("\r\n"), "error");
            }
        });


        var pageTopbarHeight = $(".page-topbar:visible").outerHeight();
        var horizontalNavHeight = $("#horizontal-nav:visible").outerHeight();
        var footerHeight = $("footer.page-footer:visible").outerHeight();
        var windowHeight = $(window).innerHeight();
        var contentHeight = windowHeight - (pageTopbarHeight + horizontalNavHeight + footerHeight + 72) -60;

        $(".chart-container").animate({
            height: contentHeight
        });


        var timeUnits = {
            hour: 3600000,
            day: 24 * 3600000,
            week: 7 * 24 * 3600000,
            month: 30.44 * 24 * 3600000,
            year: 365.24 * 24 * 3600000
        };


        function createChart(dados) {
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
                series: dados,
                plotOptions: {
                    series: {
                        dataGrouping: {
                            enabled: true
                        }
                    }
                }
            });
            $("nav.range-selector-nav ul li a").trigger("click");
            return true;
        }
                

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