@extends('app')

@section('content')
    <aside id="left-nav-variables-container" class="Variables__nav">
        <ul class="Variables__list">
            <li class="Variables__list_item">
                <a class="Variables__list_link" href="{{ route('reports.index.performance') }}">Performance</a>
            </li>
            <li class="Variables__list_item">
                <a class="Variables__list_link" href="{{ route('reports.index.variables') }}">Por variável</a>
            </li>
            <li class="Variables__list_item">
                <a class="Variables__list_link" href="{{ route('reports.report_chart',1) }}">Kw/h x Temperatura Ambiente</a>
            </li>
            <li class="Variables__list_item">
                <a class="Variables__list_link" href="{{ route('reports.report_chart',2) }}">CAG x Temperatura Ambiente</a>
            </li>
            <li class="Variables__list_item">
                <a class="Variables__list_link" href="{{ route('reports.report_chart',3) }}">Consumo total CAG</a>
            </li>
        </ul>
    </aside>

    <div class="chart-wrapper with-left-nav Variable__chart_wrapper">
        <div class="valign-wrapper Report__page">


            

        </div>
    </div>

    <div class="modal modal-fixed-footer Report__modal" id="Report__modal">
        <div class="modal-content">
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
            <a href="#!" class=" modal-action waves-effect waves-green btn-flat Report__modal_export disabled">Excel</a>
            <a href="#!" class=" modal-action waves-effect waves-green btn-flat Report__modal_pdf disabled">IMPRIMIR</a>
        </div>
    </div>
@stop

@section('runtime-scripts')
    <script type="text/javascript">
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

        function loadFirstPage(nav) {
            nav.find(".Variables__list_item:first-child a").trigger('click');
        }

        function loadPage(url, method = 'get', data = null){
            jQuery.ajax({
                url: url,
                method: method,
                data: data,
                dataType: 'html',
                success: function (data) {
                    jQuery('.Report__page').html(data);
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);

                    var response = JSON.parse(xhr.responseText);
                    var messages = [];

                    $.each(response, function (key, value) {
                        messages.push(value);
                    });

                    sweetAlert("Erro", messages.join("\r\n"), "error");

                    return false;
                }
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
            });


            $(".Variables__list_link").on("click", function (event) {
                var $btn = $(this),
                    url = $btn.attr("href"),
                    $_variables_nav = $('.Variables__list');

                if ($btn.hasClass("Variables__list_link-current")) {
                    return false;
                }

                $_variables_nav.find(".Variables__list_link").removeClass("Variables__list_link-current");
                $btn.addClass("Variables__list_link-current", "loaded");

                loadPage(url);

                event.stopPropagation();
                event.preventDefault();
                return false;
            });


            loadFirstPage($_variables_nav);

            //Generate PDF
            $("body").on("click",".Report__modal_pdf", function (event) {
                event.preventDefault();
                window.print();
            });

        });
    </script>


<style type="text/css" media="print">
    body{
        overflow: auto !important;
    }
    .Report__modal{
        width: 100%;
    }
    #App__cadastro_modal,
    #App__change_company_modal,
    #header, #left-sidebar-nav,
    .left-nav-variables-container,
    .Variables__list,
    .range-selector-nav,
    .Report__modal_pdf,
    .modal-footer{
        display: none !important;
    }
    #content{
        font-family: Tahoma, Arial, sans-serif;
        font-size: 12px;
    }
    .header-print{
        display: block !important;
    }
    .pull-left{
        display: inline-block;
        float: left;
        width: 400px;
        margin-bottom: 20px;
    }
    .pull-right{
        display: inline-block;
        float: right;
        width: 200px;
        margin-bottom: 20px;
    }
    .pull-left .card-title{
        color: #01579b;
    }
    .pull-left h1.card-title{
        margin: 10px 0;
    }
    .pull-left h2.card-title{
        font-size: 14px;
        margin-top: 0;
    }
    table{
        margin: 20px 0 20px 0;
        width: 100%;
    }
    thead{
        text-align: left;
    }
    td, th{
        border-bottom: solid 1px #eee;
        padding: 2px;
    }
    th{
        border-color: #ddd;
    }

</style>


@endsection
