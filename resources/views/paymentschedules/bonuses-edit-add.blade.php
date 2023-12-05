@extends('voyager::master')

@section('page_title', 'Generar Aguinaldos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-dollar"></i> Generar Aguinaldos
                            </h1>
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <form action="{{ route('bonuses.generate') }}" id="form" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="direccion_id">Dirección administrativa</label>
                                    <select name="direccion_id" class="form-control select2" required>
                                        <option value="">--Seleccione la dirección administrativa--</option>
                                        @foreach ($direcciones as $item)
                                            @if ($item->bonuses->count() <= 1)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="procedure_type_id">Tipo de planilla</label>
                                    <select name="procedure_type_id" class="form-control select2" @if(Auth::user()->direccion_administrativa_id) required @endif>
                                        <option value="">Todas</option>
                                        <option value="1" @if(Auth::user()->direccion_administrativa_id) disabled @endif>Permanente</option>
                                        <option value="5">Eventual</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="year">Gestión</label>
                                    <input type="number" name="year" min="2022" step="1" value="{{ date('Y') }}" class="form-control" id="input-year" placeholder="Año" readonly>
                                </div>
                                <div class="form-group text-right">
                                    <button class="btn btn-primary btn-sm" style="margin: 0px">
                                        Generar <i class="voyager-settings"></i>
                                    </button>
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
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row" id="div-results" style="min-height: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('javascript')
    {{-- <script src="{{ url('js/main.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            $('#form').submit(function(e){
                e.preventDefault();
                $('#div-results').loading({message: 'Cargando...'});
                $.post($(this).attr('action'), $(this).serialize(), function(response){
                    if(response.error){
                        toastr.error(response.error);
                        $('#div-results').empty();
                    }else{
                        $('#div-results').html(response);
                    }
                    $('#div-results').loading('toggle');
                });
            });
        });
    </script>
@stop
