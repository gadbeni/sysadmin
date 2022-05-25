@extends('voyager::master')

@section('page_title', 'Reporte de cheques')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-tag"></i> Reporte de cheques
                            </h1>
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <form name="form_search" id="form-search" action="{{ route('reports.social_security.personal.checks.list') }}" method="post">
                                @csrf
                                <input type="hidden" name="type">
                                <div class="form-group col-md-6" style="padding: 0px 5px">
                                    <label>Desde</label>
                                    <input type="date" name="start" class="form-control">
                                </div>
                                <div class="form-group col-md-6" style="padding: 0px 5px">
                                    <label>hasta</label>
                                    <input type="date" name="finish" class="form-control">
                                </div>
                                <div class="form-group col-md-12" style="padding: 0px 5px">
                                    {{-- <label>Dirección administrativa</label> --}}
                                    <select name="d_a" class="form-control select2">
                                        <option value="">--Seleccione la dirección administrativa--</option>
                                        @foreach ($direcciones_administrativa as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6" style="padding: 0px 5px">
                                    {{-- <label>hasta</label> --}}
                                    <input type="text" name="periodo" class="form-control" placeholder="Periodo">
                                </div>
                                <div class="form-group col-md-6" style="padding: 0px 5px">
                                    {{-- <label>hasta</label> --}}
                                    <select name="status" id="select-status" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="0">Anulado</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">Pagado</option>
                                        <option value="3">Vencido</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12" style="padding: 0px 5px">
                                    {{-- <label>hasta</label> --}}
                                    <input type="text" name="planilla_id" class="form-control" placeholder="N&deg; de planilla">
                                </div>
                                <div class="form-group col-md-12 text-right" style="padding: 8px 5px">
                                    <button type="submit" class="btn btn-primary">Generar <i class="voyager-settings"></i></button>
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
    <script>
        $(document).ready(function() {            
            $('#form-search').submit(function(e) {
                e.preventDefault();
                $('#div-results').empty();
                $('#div-results').loading({message: 'Cargando...'});
                $.post($(this).attr('action'), $(this).serialize(), function(data) {
                    $('#div-results').html(data);
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
