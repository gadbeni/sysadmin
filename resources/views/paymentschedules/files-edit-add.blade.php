@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Añadir Archivo')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-file-text"></i>
        Añadir Archivo
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form id="form-generate" action="{{ route('paymentschedules.files.generate') }}" method="post" enctype="multipart/form-data">
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
                                <div class="form-group col-md-6">
                                    <label for="file_type">Tipo de archivo</label>
                                    <select name="file_type" id="select-file_type" class="form-control select2" required>
                                        <option value="">--Seleccione el tipo de archivo--</option>
                                        <option value="rc-iva">RC IVA</option>
                                        <option value="biométrico">Biométrico</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="file">Archivo</label>
                                    <input type="file" name="file" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
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
                if(da){
                    // Obtener DA
                    $('#select-da_id').html('<option value="">--Seleccione una dirección administrativa--</option>');
                    da.map(item => {
                        $('#select-da_id').append(`<option value="${item.ID}">${item.NOMBRE}</option>`);
                    });
                    $('#select-procedure_type_id').html('<option value="">--Seleccione el tipo de planilla--</option>');

                    // Obtener periodos
                    $('#select-period_id').html('<option value="">--Seleccione el perido--</option>');
                    $.get('{{ url("admin/periods/tipo_direccion_adminstrativa") }}/'+type, function(res){
                        res.map(item => {
                            $('#select-period_id').append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    });
                }else{
                    $('#select-da_id').html('<option value="">--Seleccione una dirección administrativa--</option>');
                    $('#select-period_id').html('<option value="">--Seleccione el perido--</option>');
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
                let formData = new FormData(document.getElementById("form-generate"));
                formData.append("dato", "valor");
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(res){
                        if(res.error){
                            toastr.error(res.error, 'Error');
                        }else{
                            $('#div-results').html(res);
                        }
                        $('#div-results').loading('toggle');
                    },
                    error: function() {
                        toastr.error('Ocurrió un error al cargar el archivo', 'Error');
                    }
                })
            });
        });
    </script>
@stop
