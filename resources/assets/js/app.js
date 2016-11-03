$(function () {
    "use strict";

    $(".App__change_company_agree").on("click", function(event) {
        var $this = $(this),
            $combo = $(".App__change_company_combo"),
            url_to_change = $combo.val();

        $this.addClass("disabled");

        window.location = url_to_change;

        event.preventDefault();
        event.stopPropagation();
        return false;
    });


    // HighStock Global Options
    Highcharts.setOptions({
        global: {
            timezoneOffset: 3 * 60
        },
        lang: {
            months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            loading: ['Atualizando o gráfico...aguarde'],
            contextButtonTitle: 'Exportar gráfico',
            decimalPoint: ',',
            thousandsSep: '.',
            downloadJPEG: 'Baixar imagem JPEG',
            downloadPDF: 'Baixar arquivo PDF',
            downloadPNG: 'Baixar imagem PNG',
            downloadSVG: 'Baixar vetor SVG',
            printChart: 'Imprimir gráfico',
            rangeSelectorFrom: 'De',
            rangeSelectorTo: 'Para',
            rangeSelectorZoom: 'Zoom',
            resetZoom: 'Limpar Zoom',
            resetZoomTitle: 'Voltar Zoom para nível 1:1',
        }
    });

    // override pickdate default behavior
    $('.datepicker-custom').pickadate({
        selectMonths: true,
        onSet: function (context) {
            if (context.highlight) {
                return false;
            }

            var dateObj = this.get('select');
            var setDate = moment([dateObj.year, dateObj.month, dateObj.date]);

            if (null === dateObj) {
                return false;
            }

            if (setDate.isValid()) {
                this.close();
            }
        }
    });


});