@extends('voyager::master')

@section('page_title', 'Marcaciones')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa fa-clock"></i> Marcaciones
                            </h1>
                            {{-- <div class="alert alert-info">
                                <strong>Información:</strong>
                                <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                            </div> --}}
                        </div>
                        <div class="col-md-4" style="margin-top: 30px">
                            <form name="form_search" id="form-search" action="{{ route('attendances.generate') }}" method="post">
                                @csrf
                                <input type="hidden" name="type">
                                <div class="row">
                                    <div class="form-group col-md-12 text-right">
                                        <label class="radio-inline"><input type="radio" value="personal" name="type" class="radio-type" checked>Personal</label>
                                        <label class="radio-inline"><input type="radio" value="group" name="type" class="radio-type">General</label>
                                    </div>
                                    <div class="form-group col-md-12 div-personal">
                                        <select name="person_id" id="select-person_id" class="form-control" required></select>
                                    </div>
                                    <div class="form-group col-md-12 div-group">
                                        <select name="direccion_administrativa_id" id="select-direccion_id" class="form-control select2">
                                            <option value="">Todas las secretarías</option>
                                            @foreach (App\Models\Direccion::where('estado', 1)->whereRaw(Auth::user()->direccion_administrativa_id ? "id = ".Auth::user()->direccion_administrativa_id : 1)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="date" name="start" value="{{ date('Y-m') }}-01" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="date" name="finish" value="{{ date('Y-m') }}-{{ date('t') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary" style="padding: 5px 10px"> <i class="voyager-settings"></i> Generar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal editar marcaciones --}}
    <form action="#" class="form-submit" id="form-edit" method="post">
        @csrf
        <input type="hidden" name="contract_id">
        <input type="hidden" name="update_range" value="1">
        <div class="modal fade" tabindex="-1" id="edit-modal" role="dialog">
            <div class="modal-dialog modal-primary">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-clock"></i> Editar marcaciones</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel panel-bordered" style="border-left: 5px solid #62A8Ea">
                            <div class="panel-body" style="padding: 10px">
                                <div class="col-md-12">
                                    <p class="text-info">Solo se aplica a los días en los que no se ha registrado marcación.</p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start">Desde</label>
                            <input type="date" name="start" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="finish">Hasta</label>
                            <input type="date" name="finish" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="reason">Motivo</label>
                            <textarea name="reason" class="form-control" rows="5" placeholder="Problemas en el biométrico" required></textarea>
                        </div>
                        {{-- <div class="form-group">
                            <label for="file">Archivo</label>
                            <input type="file" name="file" accept="application/pdf">
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-primary btn-submit" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Modal attendance permit --}}
    <form action="#" class="form-submit" id="form-attendance_permit" method="post">
        @csrf
        <input type="hidden" name="contract_id">
        <input type="hidden" name="update_range" value="1">
        <div class="modal fade" tabindex="-1" id="attendance_permit-modal" role="dialog">
            <div class="modal-dialog modal-primary">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-certificate"></i> Agregar permiso</h4>
                    </div>
                    <div class="modal-body">
                        {{-- <div class="panel panel-bordered" style="border-left: 5px solid #62A8Ea">
                            <div class="panel-body" style="padding: 10px">
                                <div class="col-md-12">
                                    <p class="text-info">Solo se aplica a los días en los que no se ha registrado marcación.</p>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label class="radio-inline"><input type="radio" name="category" class="radio-category" value="1" checked>Licencia</label>
                            <label class="radio-inline"><input type="radio" name="category" class="radio-category" value="2">Comisión</label>
                        </div>
                        <div class="form-group div-form div-1">
                            <label for="attendance_permit_type_id">Tipo</label>
                            <select name="attendance_permit_type_id" id="select-attendance_permit_type_id" class="form-control" required>
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
                                <input type="date" name="start" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="finish">Hasta</label>
                                <input type="date" name="finish" class="form-control" required>
                            </div>
                        </div>
                        <div class="row div-form div-1">
                            <div class="form-group col-md-6">
                                <label for="start">De horas</label>
                                <input type="date" name="start_hour" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="finish">A horas</label>
                                <input type="date" name="finish_hour" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group div-form div-1">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group div-form div-2">
                            <label for="purpose">Objetivo de la actividad</label>
                            <textarea name="purpose" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group div-form div-2">
                            <label for="justification">Justificación de Viaje y/o comisión</label>
                            <textarea name="justification" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group div-form div-2">
                            <label for="type_transport">Tipo de Transporte</label>
                            <select name="type_transport" class="form-control" id="select-type_transport">
                                <option value="">Seleccione el tipo de transporte</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-primary btn-submit" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>
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
    <style>
        .div-group{
            display: none;
        }
    </style>
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        $(document).ready(function() {

            // $('#attendance_permit-modal').modal('show');
            $(`.div-2`).fadeOut();
            $('#select-type_transport').select2({dropdownParent: $("#attendance_permit-modal")});

            customSelect('#select-person_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, null);

            $('.radio-type').change(function(){
                let value = $(this).val();
                if(value == 'personal'){
                    $('.div-personal').fadeIn();
                    $('.div-group').fadeOut();
                    $('#select-person_id').prop('required', true);
                }else{
                    $('.div-group').fadeIn();
                    $('.div-personal').fadeOut();
                    $('#select-person_id').prop('required', false);
                }
                $('#select-person_id').val('').trigger('change');
            });

            $('#form-search').on('submit', function(e){
                e.preventDefault();
                $('#div-results').empty();
                $('#div-results').loading({message: 'Cargando...'});
                $.post($('#form-search').attr('action'), $('#form-search').serialize(), function(res){
                    $('#div-results').html(res);
                })
                .fail(function() {
                    toastr.error('Ocurrió un error!', 'Oops!');
                })
                .always(function() {
                    $('#div-results').loading('toggle');
                    $('html, body').animate({
                        scrollTop: $("#div-results").offset().top - 70
                    }, 500);
                });
            });

            $('#form-edit').submit(function(e){
                e.preventDefault()
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    $('#form-search').trigger('submit');
                    $('.form-submit .btn-submit').removeAttr('disabled');
                    $('#edit-modal').modal('hide');
                    if(res.success){
                        toastr.success('Marcación editada', 'Bien hecho');
                        $('#form-edit').trigger('reset');
                    }else{
                        toastr.error('No se realizó la edición', 'Error');
                    }
                });
            });

            $('#select-attendance_permit_type_id').change(function(){
                let type = $('#select-attendance_permit_type_id option:selected').data('item');
                console.log(type)
            });

            $('.radio-category').click(function(){
                let value = $('.radio-category:checked').val();
                $('.div-form').fadeOut();
                $(`.div-${value}`).fadeIn();
                console.log(value)
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