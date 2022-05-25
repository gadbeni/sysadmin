@extends('voyager::master')

@section('page_title', 'Reporte de pagos  de planillas manuales')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-7" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i> Reporte de pagos de planillas manuales
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-5" style="margin-top: 30px">
                            <form id="form-search" name="form_search" action="{{ route('reports.social_security.spreadsheets.payments.list') }}" method="post">
                                @csrf
                                {{-- Opciones que se despliegan cuando se hace check en la opción "No centralizada" --}}
                                <div class="input-no-centralizada">
                                    <input type="hidden" name="type">
                                    <div class="form-group col-md-12">
                                        <select name="id_da" class="form-control select2">
                                            <option value="">Todas las direcciones administrativas</option>
                                            @foreach ($direcciones_administrativa as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
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
                                        <input type="text" name="codigo_planilla" class="form-control" placeholder="Código de planilla">
                                    </div>
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
    <style>
        .col-md-12, .col-md-6, .col-md-4{
            padding: 0px 3px
        }
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#form-search').on('submit', function(e){
                e.preventDefault();
                $('#div-results').empty();
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
