@extends('voyager::master')

@section('page_title', 'Reporte de pagos al seguro social')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i> Reporte de pagos al seguro social
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            {{-- <form name="form_search" id="form-search" action="{{ route('reports.social_security.payments.list') }}" method="post">
                                @csrf
                            </form> --}}
                            <form id="form-search" name="form_search" action="{{ route('reports.social_security.payments.list') }}" method="post">
                                @csrf
                                <div class="form-group text-right">
                                    <label class="radio-inline"><input type="radio" name="tipo_planilla" class="radio-tipo_planilla" value="1" checked>No centralizada</label>
                                    <label class="radio-inline"><input type="radio" name="tipo_planilla" class="radio-tipo_planilla" value="2">Centralizada</label>
                                    <label class="radio-inline"><input type="radio" name="tipo_planilla" class="radio-tipo_planilla" value="3">Detallado</label>
                                </div>
                                {{-- Opciones que se despliegan cuando se hace check en la opción "No centralizada" --}}
                                <div class="input-no-centralizada">
                                    <input type="hidden" name="type">
                                    <div class="form-group col-md-12">
                                        <select name="id_da" class="form-control select2">
                                            <option value="">Todas las direcciones administrativas</option>
                                            @foreach ($direcciones_administrativa as $item)
                                            <option value="{{ $item->ID }}">{{ $item->NOMBRE }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{-- Nota: En caso de obtener estos datos en más de una consulta se debe hacer un metodo para hacerlo --}}
                                        <select name="t_planilla" class="form-control select2">
                                            <option selected disabled>Todos los tipos de planilla</option>
                                            <option value="1">Funcionamiento</option>
                                            <option value="2">Inversión</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <select name="afp" class="form-control select2">
                                            <option value="">Todas las AFP</option>
                                            <option value="1">Futuro</option>
                                            <option value="2">Previsión</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="periodo" class="form-control" placeholder="Periodo">
                                        <small>Por rango Ej: 202101-202105</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="id_planilla" class="form-control" placeholder="Código de planilla">
                                    </div>
                                </div>
                                
                                {{-- Opciones que se despliegan cuando se hace check en la opción "centralizada" --}}
                                <div class="input-centralizada" style="display: none">
                                    <div class="form-group col-md-12">
                                        {{-- Nota: En caso de obtener estos datos en más de una consulta se debe hacer un metodo para hacerlo --}}
                                        <select name="t_planilla_alt" class="form-control select2">
                                            <option selected disabled>Tipo de planilla</option>
                                            <option value="1">Funcionamiento</option>
                                            <option value="2">Inversión</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="number" min="0" name="periodo_alt" class="form-control" placeholder="Periodo"  />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <select name="afp_alt" class="form-control select2">
                                            <option value="">Todas las AFP</option>
                                            <option value="1">Futuro</option>
                                            <option value="2">Previsión</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Opciones que se despliegan cuando se hace check en la opción "centralizada" --}}
                                <div class="input-detallada" style="display: none">
                                    <div class="form-group col-md-12">
                                        <select name="id_da_detallada[]" class="form-control select2" multiple>
                                            @foreach ($direcciones_administrativa as $item)
                                            <option value="{{ $item->ID }}">{{ $item->NOMBRE }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        {{-- Nota: En caso de obtener estos datos en más de una consulta se debe hacer un metodo para hacerlo --}}
                                        <select name="t_planilla_detallada" class="form-control select2">
                                            <option value="">Todos los Tipos de planilla</option>
                                            <option value="1">Funcionamiento</option>
                                            <option value="2">Inversión</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" name="periodo_detallada" class="form-control" placeholder="Periodo">
                                        <small>Por rango Ej: 202101-202105</small>
                                    </div>
                                </div>

                                <div class="form-group col-md-12 text-right group_afp">
                                    <label class="checkbox-inline"><input type="checkbox" name="group_afp" value="1" checked>Agrupar por AFP</label>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div id="div-results" style="min-height: 100px"></div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.radio-tipo_planilla').click(function(){
                let val = $('.radio-tipo_planilla:checked').val();
                switch (val) {
                    case "1":
                        $('.input-no-centralizada').fadeIn('fast');
                        $('.input-centralizada').fadeOut('fast');
                        $('.input-detallada').fadeOut('fast');
                        $('.group_afp').fadeIn('fast');
                        break;
                    case "2":
                        $('.input-centralizada').fadeIn('fast');
                        $('.input-no-centralizada').fadeOut('fast');
                        $('.input-detallada').fadeOut('fast');
                        $('.group_afp').fadeIn('fast');
                        break;
                    case "3":
                        $('.input-detallada').fadeIn('fast');
                        $('.input-centralizada').fadeOut('fast');
                        $('.input-no-centralizada').fadeOut('fast');
                        $('.group_afp').fadeOut('fast');
                        break;
                    default:
                        break;
                }
            });
            $('#form-search').on('submit', function(e){
                e.preventDefault();
                $('#div-results').loading({message: 'Cargando...'});
                $.post($('#form-search').attr('action'), $('#form-search').serialize(), function(res){
                    $('#div-results').html(res);
                })
                .fail(function() {
                    toastr.error('Ocurrió un error!', 'Oops!');
                })
                .always(function() {
                    $('#div-results').loading('toggle');
                    $('html, body').animate({
                        scrollTop: $("#div-results").offset().top - 70
                    }, 500);
                });
            });
        });

        function report_export(type){
            $('#form-search').attr('target', '_blank');
            $('#form-search input[name="type"]').val(type);
            window.form_search.submit();
            $('#form-search').removeAttr('target');
            $('#form-search input[name="type"]').val('');
        }
    </script>
@stop
