@extends('voyager::master')

@section('page_title', 'Pagos individuales')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-person"></i> Pagos individuales
                            </h1>
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <form name="form_search" id="form-search" action="{{ route('reports.social_security.personal.payments.list') }}" method="post">
                                @csrf
                                <input type="hidden" name="type">
                                <div class="form-group col-md-12">
                                    <select name="contribuyente_id" class="form-control select2" required>
                                        <option value="">--Seleccione a un funcionario--</option>
                                        @foreach($contribuyentes as $contribuyente)
                                            <option value="{{ $contribuyente->ID }}">{{ str_replace('  ', ' ', $contribuyente->NombreCompleto) }} - {{ $contribuyente->N_Carnet }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="number" name="ci" class="form-control" placeholder="Carnet de identidad" required> --}}
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-picker" name="start" required/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-picker" name="end" required/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 text-right">
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
    <link href="{{ asset('vendor/datepicker/datepicker.min.css') }}" rel="stylesheet">
@stop

@section('javascript')
    <script src="{{ asset('vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/datepicker/bootstrap-datepicker.es.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".input-picker").datepicker( {
                format: "mm-yyyy",
                startView: "months", 
                minViewMode: "months",
                language: 'es'
            });
            
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
