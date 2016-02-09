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
            <a href="#!" class=" modal-action waves-effect waves-green btn-flat Report__modal_export">Excel</a>
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

            var $Report__form = $(".Report__form");
            var $Report__modal = $(".Report__modal");
            var $Report__modal_content = $Report__modal.find('.modal-content');
            var $Report__button_run = $(".Report__button_run");
            var $Report__modal_export = $(".Report__modal_export");
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

                $btn.addClass('disabled');
                $btn.prop('disabled', true);
                $btn.html("Carregando...");

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
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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

                            $btn.html("Arquivo Gerado");
                        }
                    }
                });

                event.preventDefault();
                event.stopPropagation();
                return false;
            });

            $Report__button_run.on("click", function (event) {
                if (- 1 === ['performance', 'variables'].indexOf($Report__type.val())) {
                    sweetAlert("Erro", "Selecion o Tipo do Relatório", "error");

                    return false;
                }

                if ('variables' === $Report__type.val()) {
                    $("#Report__modal").openModal({
                        dismissible: true,
                        opacity: .5,
                        in_duration: 300,
                        out_duration: 200,
                        ready: function () {
                            getVariableData();
                        },
                        complete: function () {
                            $Report__modal_content.html('<div class="progress"><div class="indeterminate"></div></div>');
                            $(".Report__modal_download").remove();
                            $Report__modal_export.removeClass('disabled');
                            $Report__modal_export.prop('disabled', false);
                            $Report__modal_export.html("Excel");
                        }
                    });
                }

                return;
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
@endsection
