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
                    <form id="form-generate" action="{{ route('paymentschedules.generate') }}" method="post">
                        @csrf
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="tipo_da">Tipo de dirección administrativa</label>
                                    <select name="tipo_da" id="select-tipo_da" class="form-control select2" required>
                                        <option value="">--Seleccione tipo de dirección administrativa--</option>
                                        @foreach ($tipo_da as $item)
                                            <option value="{{ $item->id }}" data-da='@json($item->direcciones_administrativas)'>{{ $item->nombre }}</option>
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
                                    <label for="period_id">Periodo</label>
                                    <select name="period_id" id="select-period_id" class="form-control select2" required>
                                        <option value="">--Seleccione el perido--</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="procedure_type_id">Tipo de planilla</label>
                                    <select name="procedure_type_id" id="select-procedure_type_id" class="form-control select2" required>
                                        <option value="">--Seleccione el tipo de planilla--</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 text-right">
                                    <button type="submit" class="btn btn-success">Generar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="div-results" style="min-height: 120px"></div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function(){
            $('#select-tipo_da').change(function(){
                let type = $(this).find(':selected').val();
                let da = $(this).find(':selected').data('da');

                $('#select-da_id').html('<option value="">--Seleccione una dirección administrativa--</option>');
                $('#select-period_id').html('<option value="">--Seleccione el perido--</option>');

                if(da){
                    // Obtener DA
                    da.map(item => {
                        $('#select-da_id').append(`<option value="${item.id}">${item.nombre}</option>`);
                    });
                    $('#select-procedure_type_id').html('<option value="">--Seleccione el tipo de planilla--</option>');

                    // Obtener periodos
                    $.get('{{ url("admin/periods/tipo_direccion_adminstrativa") }}/'+type, function(res){
                        res.map(item => {
                            $('#select-period_id').append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    });
                }
            });

            $('#select-da_id').change(function(){
                let da_id = $(this).find(':selected').val();
                $('#select-procedure_type_id').html('<option value="">--Seleccione el tipo de planilla--</option>');
                $.get('{{ url("admin/contracts/direccion-administrativa") }}/'+da_id, function(res){
                    res.map(item => {
                        $('#select-procedure_type_id').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                });
            });

            $('#form-generate').submit(function(e){
                $('#div-results').loading({message: 'Cargando...'});
                e.preventDefault();
                let form = $('#form-generate');
                let data = form.serialize();
                $.post(form.attr('action'), data, function(res){
                    $('#div-results').html(res);
                    $('#div-results').loading('toggle');
                });
            });
        });
    </script>
@stop
