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
                                    <i><span class="tooltiptext">Relatório completo de medições por variável.</span></i> 
                                    Relatório por variável
                                </div>
                            </h4>
                            
                            <div class="row Report__type_variable_filters">
                                <div class="col s6">
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
                                    <input type="date" class="datepicker-custom" id="start_date" name="start_date" 
                                           value="{{ $end_date or '' }}">
                                    <label for="start_date">Data Inicial</label>
                                </div>
                                <div class="input-field col s3">
                                    <input type="date" class="datepicker-custom" id="end_date" name="end_date"
                                           value="{{ $end_date or '' }}">
                                    <label for="end_date">Data Final</label>
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

<script type="text/javascript">
    
    jQuery(document).ready(function ($) {
        "strict mode";

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


        var $Report__form = $(".Report__form");
        var $Report__modal = $(".Report__modal");
        var $Report__modal_content = $Report__modal.find('.modal-content');
        var $Report__button_run = $(".Report__button_run");
        var $Report__modal_export = $(".Report__modal_export");
        var $Report__modal_pdf = $(".Report__modal_pdf");


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
                        //console.log(data);
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


        $Report__button_run.on("click", function (event) {

            $Report__modal_export.hide(0);
            $Report__modal_pdf.hide(0);

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

    });
</script>