@extends('voyager::master')

@section('page_title', 'Crear Permiso/Licencia')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-calendar"></i>
        Crear Permiso/Licencia
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form role="form" class="form-submit" action="#" method="POST">
                        <div class="panel-body">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="direcciones_tipo_id">Tipo de Dirección Administrativa</label>
                                <select name="direcciones_tipo_id" id="select-direcciones_tipo_id" class="form-control select2" onchange="peopleList()">
                                    <option value="">--Seleccionar tipo de DA--</option>
                                    @foreach (App\Models\DireccionesTipo::whereHas('direcciones_administrativas', function($q){
                                                    $q->where('estado', 1);
                                                })->where('estado', 1)->get() as $item)
                                        <option value="{{ $item->id }}" data-da='@json($item->direcciones_administrativas)'>{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="direccion_administrativa_id">Dirección administrativa</label>
                                <select name="direccion_administrativa_id" id="select-direccion_administrativa_id" class="form-control select2" onchange="peopleList()">
                                    <option value="">--Seleccione una dirección administrativa--</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary btn-submit save">Guardar <i class="voyager-check"></i> </button>
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form role="form" class="form-submit" action="#" method="POST">
                        @csrf
                        <div class="panel-body">
                            <div id="div-results"></div>
                            <br>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="attendance_permit_type_id">Tipo</label>
                                <select name="attendance_permit_type_id" class="form-control select2" id="">

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="date">Fecha</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="file">Documento</label>
                                <input type="file" name="file" class="form-control" accept="image/png, image/jpeg">
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label" for="observations">Observaciones</label>
                                <textarea name="observations" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary btn-submit save">Guardar <i class="voyager-check"></i> </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        
    </style>
@stop

@section('javascript')
    <script>
        $(document).ready(function(){
            $('#select-direcciones_tipo_id').change(function(){
                let da = $(this).find(':selected').data('da');
                $('#select-direccion_administrativa_id').html('<option value="">--Seleccione una dirección administrativa--</option>');
                if(da){
                    da.map(item => {
                        $('#select-direccion_administrativa_id').append(`<option value="${item.id}">${item.nombre}</option>`);
                    });
                }
            });
        });

        function peopleList(){
            let url = '{{ url("admin/people/ajax/search/schedules") }}';
            let direcciones_tipo_id = $('#select-direcciones_tipo_id').val() ? $('#select-direcciones_tipo_id').val() : '';
            let direccion_administrativa_id = $('#select-direccion_administrativa_id').val() ? $('#select-direccion_administrativa_id').val() : '';
            if(direcciones_tipo_id){
                $('#div-results').loading({message: 'Cargando...'});
                $.ajax({
                    url: `${url}?direcciones_tipo_id=${direcciones_tipo_id}&direccion_administrativa_id=${direccion_administrativa_id}`,
                    type: 'get',
                    success: function(response){
                        $('#div-results').html(response);
                        $('#div-results').loading('toggle');
                    }
                });
            }
        }
    </script>
@stop
