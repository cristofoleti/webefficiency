<div class="container">
    <div class="section">

        <div class="row">
            <div class="col s12 m12 l12">
                <form class="col s12 Report__form" method="post" action="{{ route('reports.data.variables') }}">
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-content">
                            <h4 class="card-title">
                                <div class="tooltip">
                                    <i><span class="tooltiptext">Relatório da empresa que compara temperatura, umidade, consumo e performance do ano atual e anterior.</span></i> 
                                    Relatório de performance
                                </div>
                            </h4>
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

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        "strict mode";

        var $Report__form = $(".Report__form");
        var $Report__modal = $(".Report__modal");
        var $Report__modal_content = $Report__modal.find('.modal-content');
        var $Report__button_run = $(".Report__button_run");
        var $Report__modal_export = $(".Report__modal_export");
        var $Report__modal_pdf = $(".Report__modal_pdf");

        //Gerar relatório
        $Report__button_run.on("click", function (event) {

            //abre modal
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
        });


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
                            //console.log(data);
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