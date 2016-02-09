@extends('app')

@section('content')
    <div class="container">
        <div class="section Export__container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="card-panel">
                        <div class="row">
                            <form class="col s12 Export__form" method="post" action="{{ route('export.download') }}">
                                {{ csrf_field() }}
                                <h4>Exportar Dados</h4>
                                <h6>Período máximo permitido: 30 dias</h6>
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input type="date" class="datepicker-custom" id="start_date" name="start_date" value="@if(isset($start_date)){{ $start_date }}@endif">
                                        <label for="start_date">Data Inicial</label>
                                    </div>
                                    <div class="input-field col s3">
                                        <input type="date" class="datepicker-custom" id="end_date" name="end_date" value="@if(isset($end_date)){{ $end_date }}@endif">
                                        <label for="end_date">Data Final</label>
                                    </div>
                                    <div class="input-field col s3">
                                        <div class="input-field col s12">
                                            <button type="submit" class="waves-effect waves-light btn Export__action">
                                                <i class="mdi-content-save left"></i>Exportar Dados
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('runtime-scripts')
    <script>
        $(function($) {
            "strict mode";

            var error_messages = '';

            @if(session('error'))
                sweetAlert("Erro", "{!! session('error') !!}", "error");
            @endif

            @if (count($errors) > 0)
                @foreach ($errors->all() as $error)
                    error_messages += "{!! $error !!} \r\n";
                @endforeach
                sweetAlert("Erro", error_messages, "error");
            @endif
        });
    </script>
@stop