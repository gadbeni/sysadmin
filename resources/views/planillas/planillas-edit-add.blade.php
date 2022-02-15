@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Añadir Planilla')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-logbook"></i>
        Añadir Planilla
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="tipo_da">Tipo de dirección administrativa</label>
                                <select name="tipo_da" id="select-tipo_da" class="form-control select2">
                                    <option value="">--Seleccione tipo de dirección administrativa--</option>
                                    @foreach ($tipo_planillas as $item)
                                        <option value="{{ $item->ID }}" data-da='@json($item->direcciones_administrativas)'>{{ $item->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="da_id">Dirección administrativa</label>
                                <select name="da_id" id="select-da_id" class="form-control select2" required>
                                    <option value="">--Seleccione una dirección administrativa--</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="periodo">Periodo</label>
                                <select name="periodo" id="select-periodo" class="form-control select2" required>
                                    <option value="202201">202201</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tipo_planilla">Tipo de planilla</label>
                                <select name="tipo_planilla" id="select-tipo_planilla" class="form-control select2" required>
                                    <option value="">--Seleccione el tipo de planilla--</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function(){
            $('#select-tipo_da').change(function(){
                let da = $(this).find(':selected').data('da');
                $('#select-da_id').html('<option value="">--Seleccione una dirección administrativa--</option>');
                da.map(item => {
                    $('#select-da_id').append(`<option value="${item.ID}">${item.NOMBRE}</option>`);
                });
            });

            $('#select-da_id').change(function(){
                let da_id = $(this).find(':selected').val();
                $.get('{{ url("admin/contracts/direccion-administrativa") }}/'+da_id, function(res){
                    console.log(res);
                });
            });
        });
    </script>
@stop
