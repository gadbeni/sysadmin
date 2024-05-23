@extends('voyager::master')

@section('page_title', 'Crear Permiso')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-certificate"></i>
        Crear Permiso
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <form role="form" class="form-submit" id="form-attendance_permit" action="{{ route('attendances-permits.store.personal') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="radio-inline"><input type="radio" name="category" class="radio-category" value="1" checked>Licencia</label>
                                <label class="radio-inline"><input type="radio" name="category" class="radio-category" value="2">Comisión</label>
                                <label class="radio-inline"><input type="radio" name="category" class="radio-category" value="3">Permiso especial</label>
                            </div>
                            <div class="form-group div-form div-1">
                                <label for="attendance_permit_type_id">Tipo</label>
                                <select name="attendance_permit_type_id" id="select-attendance_permit_type_id" class="form-control">
                                    <option value="" selected disabled>Seleccionar tipo</option>
                                    @foreach (App\Models\AttendancePermitType::where('status', 1)->get() as $item)
                                    <option value="{{ $item->id }}" data-item='@json($item)'>{{ $item->name }}</option>
                                    @endforeach
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date">Fecha de solicitud</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="start">Desde</label>
                                    <input type="date" name="start" id="input-start" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="finish">Hasta</label>
                                    <input type="date" name="finish" id="input-finish" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 div-form div-3">
                                    <label for="date_permit">Día</label>
                                    <input type="date" name="date_permit" id="input-date_permit" class="form-control">
                                </div>
                                <div class="form-group col-md-6 div-form div-3">
                                    <label for="hour_permit">A partir de las</label>
                                    <input type="time" name="hour_permit" id="input-hour_permit" class="form-control">
                                </div>
                            </div>
                            <div class="row div-form div-1">
                                <div class="form-group col-md-6">
                                    <label for="start">De horas</label>
                                    <input type="time" name="time_start" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="finish">A horas</label>
                                    <input type="time" name="time_finish" class="form-control">
                                </div>
                            </div>
                            @if ($type == 'group')
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="control-label" for="direcciones_tipo_id">Tipo de Dirección Administrativa</label>
                                        <select name="direcciones_tipo_id" id="select-direcciones_tipo_id" class="form-control select2" onchange="peopleList()" required>
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-right">
                                            <a href="#list-view" class="btn btn-link" data-toggle="collapse">Lista de personas <i class="voyager-sort-desc"></i></a>
                                        </div>
                                        <div id="list-view" class="collapse">
                                            <div id="div-results" style="max-height: 500px; overflow-y: auto"></div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="contract_id">Funcionario</label>
                                    <select name="contract_id[]" id="select-contract_id" class="form-control" required></select>
                                </div>
                            @endif
                            <div class="form-group div-form div-2">
                                <label for="purpose">Objetivo de la actividad</label>
                                <textarea name="purpose" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="form-group div-form div-2">
                                <label for="justification">Justificación de Viaje y/o comisión</label>
                                <textarea name="justification" class="form-control" rows="5"></textarea>
                            </div>
                            {{-- <div class="form-group div-form div-2">
                                <label for="type_transport">Tipo de Transporte</label>
                                <select name="type_transport" class="form-control" id="select-type_transport">
                                    <option value="">Seleccione el tipo de transporte</option>
                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label for="observations">Observaciones</label>
                                <textarea name="observations" id="textarea-observations" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary btn-submit save">Guardar <i class="voyager-check"></i> </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function(){

            customSelect('#select-contract_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, null);

            $(`.div-2`).fadeOut();
            $(`.div-3`).fadeOut();
            $('#select-direcciones_tipo_id').change(function(){
                let da = $(this).find(':selected').data('da');
                $('#select-direccion_administrativa_id').html('<option value="">--Seleccione una dirección administrativa--</option>');
                if(da){
                    da.map(item => {
                        $('#select-direccion_administrativa_id').append(`<option value="${item.id}">${item.nombre}</option>`);
                    });
                }
            });

            $('.radio-category').click(function(){
                let value = $('.radio-category:checked').val();
                $('.div-form').fadeOut();
                $(`.div-${value}`).fadeIn();

                // si es de tipo 3
                console.log(value)
                if(value == 3){
                    $('#input-start').parent().parent().fadeOut('fast');
                    $('#input-start').prop('required', false);
                    $('#input-date_permit').prop('required', true);
                    $('#input-hour_permit').prop('required', true);
                    $('#textarea-observations').prop('required', true);
                }else{
                    $('#input-start').parent().parent().fadeIn('fast');
                    $('#input-start').prop('required', true);
                    $('#input-date_permit').prop('required', false);
                    $('#input-hour_permit').prop('required', false);
                    $('#textarea-observations').prop('required', false);
                }
            });

            $('#form-attendance_permit input[name="finish"]').change(function(){
                if($(this).val() != $('#form-attendance_permit input[name="start"]').val()){
                    $('#form-attendance_permit input[name="time_start"]').val('');
                    $('#form-attendance_permit input[name="time_start"]').prop('disabled', true);
                    $('#form-attendance_permit input[name="time_finish"]').val('');
                    $('#form-attendance_permit input[name="time_finish"]').prop('disabled', true);
                }else{
                    $('#form-attendance_permit input[name="time_start"]').prop('disabled', false);
                    $('#form-attendance_permit input[name="time_finish"]').prop('disabled', false);
                }
            });

            $('#select-attendance_permit_type_id').change(function(){
                calculateDateAttendancePermit();
            });

            $('#form-attendance_permit input[name="start"]').change(function(){
                calculateDateAttendancePermit();
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

        function calculateDateAttendancePermit(){
            let type = $('#select-attendance_permit_type_id option:selected').data('item');
            let start = $('#form-attendance_permit input[name="start"]').val();
            if(type && start){
                if (type.default_days > 1) {
                    $('#form-attendance_permit input[name="finish"]').val(moment(start, "YYYY-MM-DD").add(type.default_days -1, 'days').format('YYYY-MM-DD'));
                }else{
                    $('#form-attendance_permit input[name="finish"]').val('');
                }
            }
        }
    </script>
@stop
