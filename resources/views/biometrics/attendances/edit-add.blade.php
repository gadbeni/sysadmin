@extends('voyager::master')

@section('page_title', 'Añadir marcaciones')

@section('page_header')
    <h1 class="page-title">
        <i class="fa fa-clock"></i>
        Añadir marcaciones
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form id="form-generate" action="{{ route('attendances.generate') }}" method="post">
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
                                    <select name="da_id" id="select-da_id" class="form-control select2">
                                        <option value="">--Seleccione una dirección administrativa--</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="start">Inicio</label>
                                    <input type="date" name="start" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="finish">Fin</label>
                                    <input type="date" name="finish" class="form-control" required>
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

@section('css')
    <style>

    </style>
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
                }
            });

            // $('#form-generate').submit(function(e){
            //     $('#div-results').loading({message: 'Cargando...'});
            //     e.preventDefault();
            //     let form = $('#form-generate');
            //     let data = form.serialize();
            //     $.post(form.attr('action'), data, function(res){
            //         $('#div-results').html(res);
            //         $('#div-results').loading('toggle');
            //     });
            // });
        });
    </script>
@stop
