@extends('voyager::master')

@section('page_title', 'Pagos anuales')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i> Pagos anuales
                            </h1>
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <form name="form_search" id="form-search" action="{{ route('social-security.payments.group.list') }}" method="post">
                                @csrf
                                <div class="input-group">
                                    <input type="number" name="year" value="{{ date('Y') }}" class="form-control" required>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit" style="margin-top: 0px">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> 
                                            Buscar
                                        </button>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <select name="id_da" class="form-control select2">
                                        <option value="">Todas las direcciones administrativas</option>
                                        @foreach($direcciones_administrativa as $direccion)
                                            <option value="{{ $direccion->id }}">{{ $direccion->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="margin-top: 10px">
                                    <label class="radio-inline"><input type="radio" name="type" value="1" checked>Todos</label>
                                    <label class="radio-inline"><input type="radio" name="type" value="2">Agrupado por mes</label>
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
