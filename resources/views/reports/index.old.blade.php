@extends('app')

@section('content')
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

    <div class="container">
        <div class="section Report__page">
            <div class="row">
                <div class="col s12 m12 l12">
                    <form class="col s12 Report__form" method="post" action="{{ route('reports.data.variables') }}">
                        {{ csrf_field() }}
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <h4>Gerador de Relatórios</h4>
                                    <div class="row">
                                        <div class="col s3">
                                            <label for="report_type">Tipo do Formulário</label>
                                            <select class="browser-default Report__type" id="report_type"
                                                    name="report_type">
                                                <option value="0" disabled selected>---------</option>
                                                <option value="variables">Por Variável</option>
                                                <option value="performance">Performance</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row Report__type_variable_filters">
                                        <div class="col s3">
                                            <label for="variable_tag">Selecione uma Variável</label>
                                            <select class="browser-default" id="variable_tag" name="variable_tag">
                                                <option value="" disabled selected>----------</option>
                                                @foreach($variables as $variable)
                                                    <option value="{{ $variable->tag }}"
                                                            @if(isset($variable_tag) && $variable_tag == $variable->tag) selected @endif>{{ $variable->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-field col s3">
                                            <input type="date" class="datepicker-custom" id="start_date"
                                                   name="start_date"
                                                   value="@if(isset($start_date)){{ $start_date }}@endif">
                                            <label for="start_date">Data Inicial</label>
                                        </div>
                                        <div class="input-field col s3">
                                            <input type="date" class="datepicker-custom" id="end_date" name="end_date"
                                                   value="@if(isset($end_date)){{ $end_date }}@endif">
                                            <label for="end_date">Data Final</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action right-align">
                                <button type="button"
                                        class="waves-effect waves-light btn Report__button_run"
                                        data-target="Report__modal">
                                    <i class="mdi-action-assessment left"></i>Gerar Relatório
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('runtime-scripts')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            "strict mode";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var $Report__form = $(".Report__form");
            var $Report__modal = $(".Report__modal");
            var $Report__modal_content = $Report__modal.find('.modal-content');
            var $Report__button_run = $(".Report__button_run");
            var $Report__modal_export = $(".Report__modal_export");
            var $Report__modal_pdf = $(".Report__modal_pdf");
            var $Report__type = $(".Report__type");


            $Report__type.on("change", function (event) {
                if ('performance' === $(this).val()) {
                    $(".Report__type_variable_filters").hide();
                } else {
                    $(".Report__type_variable_filters").show();
                }

                return;
            });

            $Report__modal_export.on("click", function (event) {
                var $btn = $(this);

                $Report__modal_export.prop('disabled', true).addClass('disabled').html("GERANDO ARQUIVO...").show(0);

                var $start_date = $("#start_date").pickadate();
                var $end_date = $("#end_date").pickadate();
                var $variable_tag = $("#variable_tag");
                var picker = $start_date.pickadate('picker');

                var start = picker.get('select', 'yyyy-mm-dd');

                picker = $end_date.pickadate('picker');
                var end = picker.get('select', 'yyyy-mm-dd');

                $.ajax({
                    url: "{{ route('export.generate') }}",
                    method: "post",
                    data: {
                        tag: $variable_tag.val(),
                        start: start,
                        end: end
                    },
                    success: function (data) {
                        if (data && data.success) {
                            console.log(data);
                            $("<a href='" + data.message + "' class='waves-effect waves-green btn-flat Report__modal_download' target='_blank'>Download</a>")
                                    .insertAfter($btn);

                            $btn.remove();
                        }
                    }
                });

                event.preventDefault();
                event.stopPropagation();
                return false;
            });

            //Generate PDF
            $Report__modal_pdf.on("click", function (event) {
                event.preventDefault();
                window.print();
            });

            $Report__button_run.on("click", function (event) {
                if (- 1 === ['performance', 'variables'].indexOf($Report__type.val())) {
                    sweetAlert("Erro", "Selecion o Tipo do Relatório", "error");

                    return false;
                }

                $Report__modal_export.hide(0);
                $Report__modal_pdf.hide(0);

                if ('variables' === $Report__type.val()) {
                    $("#Report__modal").openModal({
                        dismissible: true,
                        opacity: .5,
                        in_duration: 300,
                        out_duration: 200,
                        ready: function () {
                            $Report__modal_export.html("CARREGANDO...").show(0);
                            getVariableData();
                        },
                        complete: function () {
                            $Report__modal_content.html('<div class="progress"><div class="indeterminate"></div></div>');
                            $(".Report__modal_download").remove();
                        }
                    });
                }

                if ('performance' === $Report__type.val()) {
                    $("#Report__modal").openModal({
                        dismissible: true,
                        opacity: .5,
                        in_duration: 300,
                        out_duration: 200,
                        ready: function () {
                            $Report__modal_export.remove();
                            $Report__modal_pdf.html("CARREGANDO...").show(0);
                            getPerformanceData();
                        },
                        complete: function () {
                            $Report__modal_content.html('<div class="progress"><div class="indeterminate"></div></div>');
                            $(".Report__modal_download").remove();
                        }
                    });
                }

                return true;
            });

            function getVariableData() {
                $.ajax({
                    url: "{{ route('reports.data.variables') }}",
                    method: 'post',
                    data: $Report__form.serialize(),
                    dataType: 'html',
                    beforeSend: function () {
                        $Report__modal_content.html('<div class="progress"><div class="indeterminate"></div></div>');
                    },
                    success: function (data) {
                        $Report__modal_content.html(data);
                        $Report__modal_export.removeClass('disabled').prop('disabled', false).html("EXCEL").show(0);
                    },
                    error: function (xhr, status, error) {
                        var response = JSON.parse(xhr.responseText);
                        var messages = [];

                        $.each(response, function (key, value) {
                            messages.push(value);
                        });

                        sweetAlert("Erro", messages.join("\r\n"), "error");

                        $("#Report__modal").closeModal();

                        return false;
                    }
                });
            }

            function getPerformanceData(){
                $.ajax({
                    url: "{{ route('reports.data.performance') }}",
                    method: 'post',
                    data: $Report__form.serialize(),
                    dataType: 'html',
                    beforeSend: function () {
                        $Report__modal_content.html('<div class="progress"><div class="indeterminate"></div></div>');
                    },
                    success: function (data) {
                        $Report__modal_content.html(data);

                        //load Graph
                        $.ajax({
                            cache: false,
                            url: "{{ route('reports.data.performance.graph') }}",
                            type: 'get',
                            dataType: 'json',
                            success: function(data){
                                console.log(data);
                                if(getPerformanceGraph(data)){
                                    $Report__modal_pdf.removeClass('disabled').prop('disabled', false).html("IMPRIMIR");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log(xhr.responseText);
                            }
                        });

                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);

                        var response = JSON.parse(xhr.responseText);
                        var messages = [];

                        $.each(response, function (key, value) {
                            messages.push(value);
                        });

                        sweetAlert("Erro", messages.join("\r\n"), "error");

                        $("#Report__modal").closeModal();


                        return false;
                    }
                });
            }

            function getPerformanceGraph(data){
                $('#grafico').highcharts({
                    chart: { zoomType: 'xy' },
                    title: { text: '' },
                    subtitle: { text: '' },
                    xAxis: [{
                        categories: data.periodos,
                        crosshair: true
                    }],
                    yAxis: [
                        { // Primary yAxis
                            labels: {
                                format: '{value}°C',
                                style: { color: '#c40815' }
                            },
                            title: {
                                text: 'Temperatura do Ar Externo',
                                style: { color: '#c40815' }
                            },
                            opposite: true

                        },
                        { // Secondary yAxis
                            gridLineWidth: 0,
                            title: {
                                text: 'Consumo',
                                style: { color: '#01579b' }
                            },
                            labels: {
                                format: '{value} kWh',
                                style: { color: '#01579b' }
                            }

                        }, 
                        { // Tertiary yAxis
                            gridLineWidth: 0,
                            title: {
                                text: 'Performance',
                                style: { color: '#10ac37' }
                            },
                            labels: {
                                format: '{value} kW/Tr',
                                style: { color: '#10ac37' }
                            },
                            opposite: true
                        }
                    ],
                    tooltip: { shared: true },
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        backgroundColor: '#FFFFFF',
                        itemDistance: 40
                    },
                    series: [
                        {
                            name: 'Consumo - Ano atual',
                            type: 'column',
                            yAxis: 1,
                            data: data.consumo.ano_atual,
                            tooltip: { valueSuffix: ' kWh' },
                            color: '#01579b'
                        }, 
                        {
                            name: 'Consumo - Ano anterior',
                            type: 'column',
                            yAxis: 1,
                            data: data.consumo.ano_anterior,
                            tooltip: { valueSuffix: ' kWh' },
                            color: '#389df0'
                        },
                        {
                            name: 'Performance - Ano atual',
                            type: 'spline',
                            yAxis: 2,
                            data: data.performance.ano_atual,
                            tooltip: { valueSuffix: ' kW/Tr' },
                            color: '#10ac37'
                        }, 
                        {
                            name: 'Performance - Ano anterior',
                            type: 'spline',
                            yAxis: 2,
                            data: data.performance.ano_anterior,
                            tooltip: { valueSuffix: ' kW/Tr' },
                            color: '#24e957'
                        },
                        {
                            name: 'Temperatura do Ar Externo - Ano atual',
                            type: 'spline',
                            data: data.temperatura.ano_atual,
                            tooltip: { valueSuffix: ' °C' },
                            color: '#c40815'
                        },
                        {
                            name: 'Temperatura do Ar Externo - Ano anterior',
                            type: 'spline',
                            data: data.temperatura.ano_anterior,
                            tooltip: { valueSuffix: ' °C' },
                            color: '#ff2232'
                        }
                    ]
                });

                return true;
            }

        });
    </script>
@endsection
