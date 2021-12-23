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
                                <div class="input-group">
                                    <input type="number" name="ci" class="form-control" placeholder="Carnet de identidad" required>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit" style="margin-top: 0px">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> 
                                            Buscar
                                        </button>
                                    </span>
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
    </script>
@stop
