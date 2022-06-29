@extends('voyager::master')

@section('page_title', 'Reporte de aniversarios')

@if(auth()->user()->hasPermission('browse_reportshumans-resourcesaniversarios'))

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <div class="col-md-8" style="padding: 0px">
                                <h1 class="page-title">
                                    <i class="voyager-calendar"></i> Reporte de aniversarios
                                </h1>
                                {{-- <div class="alert alert-info">
                                    <strong>Información:</strong>
                                    <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                                </div> --}}
                            </div>
                            <div class="col-md-4" style="margin-top: 30px">
                                <form name="form_search" id="form-search" action="{{ route('reports.humans_resources.aniversarios.list') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="print">
                                    <div class="form-group">
                                        <select name="procedure_type_id" class="form-control select2" required>
                                            <option selected disabled value="">Tipo de planilla</option>
                                            <option value="1">Permanente</option>
                                            <option value="5">Inversión</option>
                                            <option value="2">Consultores de línea</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select name="month" class="form-control select2" required>
                                            <option value="">--Seleccione el mes--</option>
                                            <option value="1">Enero</option>
                                            <option value="2">Febrero</option>
                                            <option value="3">Marzo</option>
                                            <option value="4">Abril</option>
                                            <option value="5">Mayo</option>
                                            <option value="6">Junio</option>
                                            <option value="7">Julio</option>
                                            <option value="8">Agosto</option>
                                            <option value="9">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                    </div>
                                    {{-- <div class="form-group">
                                        <select name="afp" class="form-control select2">
                                            <option value="">Todas las AFP</option>
                                            <option value="1">Futuro</option>
                                            <option value="2">Previsión</option>
                                        </select>
                                    </div> --}}
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                    </div>
                                    <br>
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

            function report_print(){
                $('#form-search').attr('target', '_blank');
                $('#form-search input[name="print"]').val(1);
                window.form_search.submit();
                $('#form-search').removeAttr('target');
                $('#form-search input[name="print"]').val('');
            }
        </script>
    @stop

@else
    @section('content')
        @include('errors.403')
    @stop
@endif