@extends('voyager::master')

@section('page_title', isset($contract) ? 'Editar contrato' : 'Crear contrato')

@if (auth()->user()->hasPermission('add_contracts') || auth()->user()->hasPermission('edit_contracts'))

    @section('page_header')
        <h1 class="page-title">
            <i class="voyager-certificate"></i>
            {{ isset($contract) ? 'Editar' : 'Crear' }} contrato
        </h1>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">
            <form action="{{ isset($contract) ? route('contracts.update', ['contract' => $contract->id]) : route('contracts.store') }}" method="post">
                @csrf
                @isset($contract)
                    @method('PUT')
                @endisset
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos principales</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="procedure_type_id">Tipo de planilla</label>
                                        <select name="procedure_type_id" id="select-procedure_type_id" class="form-control" required>
                                            <option value="" disabled selected>-- Selecciona el tipo de planilla --</option>
                                            @foreach ($procedure_type as $item)
                                            <option value="{{ $item->id }}" data-planilla_id="{{ $item->planilla_id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="person_id">Persona</label>
                                        <select name="person_id" class="form-control select2" required>
                                            <option value="">-- Selecciona a la persona --</option>
                                            @foreach ($people as $item)
                                            <option @if(isset($contract) && $contract->person->id == $item->id) selected @endif value="{{ $item->id }}">{{ $item->first_name }} {{ $item->last_name }} - {{ $item->ci }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="direccion_administrativa_id">Dirección administrativa</label>
                                        <select name="direccion_administrativa_id" id="select-direccion_administrativa_id" class="form-control select2"></select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="unidad_administrativa_id">Unidad administrativa</label>
                                        <select name="unidad_administrativa_id" id="select-unidad_administrativa_id" class="form-control select2"></select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="program_id">Programa</label>
                                        <select name="program_id" id="select-program_id" class="form-control select2"></select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cargo_id">Cargo</label>
                                        <select name="cargo_id" id="select-cargo_id" class="form-control select2" required></select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="start">Inicio de contrato</label>
                                        <input type="date" name="start" value="{{ isset($contract) ? $contract->start : '' }}" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="finish">Fin de contrato</label>
                                        <input type="date" name="finish" id="input-finish" value="{{ isset($contract) ? $contract->finish : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="requested_by">Persona o unidad solicitante (Opcional)</label>
                                        <input type="text" name="requested_by" value="{{ isset($contract) ? $contract->requested_by : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 div-hidden">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos de complementarios</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="preventive_number">N&deg; de preventivo</label>
                                        <input type="text" name="preventive_number" value="{{ isset($contract) ? $contract->preventive_number : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="organizational_source">Fuente organizacional</label>
                                        <input type="text" name="organizational_source" value="{{ isset($contract) ? $contract->organizational_source : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 div-hidden div-5">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos de complementarios</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="details_work">Funciones generales</label>
                                        <textarea class="form-control richTextBox" name="details_work">
                                            {{
                                                isset($contract) ?
                                                $contract->details_work :
                                                '<ul>
                                                <li>Coordinar la planificaci&oacute;n, ejecuci&oacute;n y seguimiento de las actividades del &aacute;rea.</li>
                                                <li>Proporcionar apoyo t&eacute;cnico en la ejecuci&oacute;n de pol&iacute;ticas y objetivos de la Entidad.</li>
                                                <li>Informar, recomendar y emitir criterios t&eacute;cnico-administrativos a su inmediato superior en lo que corresponde al &aacute;rea de su especialidad.</li>
                                                </ul>'
                                            }}
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row div-hidden div-2">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos de autorización</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="date_autorization">Fecha de autorización</label>
                                        <input type="date" name="date_autorization" value="{{ isset($contract) ? $contract->date_autorization : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="certification_poa">Certificación POA</label>
                                        <input type="text" name="certification_poa" value="{{ isset($contract) ? $contract->certification_poa : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="certification_pac">Certificación PAC</label>
                                        <input type="text" name="certification_pac" value="{{ isset($contract) ? $contract->certification_pac : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row div-hidden div-2">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos de invitación</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="date_invitation">Fecha de invitación</label>
                                        <input type="date" name="date_invitation" value="{{ isset($contract) ? $contract->date_invitation : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="date_limit_invitation">Fecha límite de invitación</label>
                                        <input type="date" name="date_limit_invitation" value="{{ isset($contract) ? $contract->date_limit_invitation : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="date_response">Fecha de respuesta a invitación</label>
                                        <input type="date" name="date_response" value="{{ isset($contract) ? $contract->date_response : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row div-hidden div-2">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos de memorandum</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="date_statement">Fecha de declaración jurada</label>
                                        <input type="date" name="date_statement" value="{{ isset($contract) ? $contract->date_statement : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="date_memo">Fecha de memorandum</label>
                                        <input type="date" name="date_memo" value="{{ isset($contract) ? $contract->date_memo : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="date_memo_res">Fecha de respuesta de memorandum</label>
                                        <input type="date" name="date_memo_res" value="{{ isset($contract) ? $contract->date_memo_res : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="workers_memo">Comisión de contratación</label>
                                        <select name="workers_memo[]" id="select-workers_memo" class="form-control" multiple>
                                            @foreach ($contracts->sortBy('person.last_name') as $item)
                                            <option value="{{ $item->id }}">{{ $item->person->first_name }} {{ $item->person->last_name }} - {{ $item->cargo_id ? $item->cargo->Descripcion : $item->job->name }}</option>                                                
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="signature_id">Firma autorizada <i class="voyager-question" data-toggle="tooltip" title="En caso de que alguien más deba firmar el contrato"></i></label>
                                        <select name="signature_id" id="select-signature_id" class="form-control select2">
                                            <option value="">--Seleccione la firma autorizada--</option>
                                            @foreach ($contracts->sortBy('person.last_name') as $item)
                                            <option value="{{ $item->id }}">{{ $item->person->first_name }} {{ $item->person->last_name }} - {{ $item->cargo_id ? $item->cargo->Descripcion : $item->job->name }}</option>                                                
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="signature_code">Resolución administrativa <i class="voyager-question" data-toggle="tooltip" title="Si agrega una firma autorizada debe ingresar el código de resolución administrativa"></i></label>
                                        <input type="text" name="signature_code" value="{{ isset($contract) ? $contract->signature_code : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row div-hidden div-2">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Datos complementarios</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="date_note">Fecha de nota de adjudicación</label>
                                        <input type="date" name="date_note"  value="{{ isset($contract) ? $contract->date_note : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="date_report">Fecha de informe de evaluación</label>
                                        <input type="date" name="date_report" value="{{ isset($contract) ? $contract->date_report : '' }}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="date_presentation">Fecha de Presentación de documentos</label>
                                        <input type="date" name="date_presentation" value="{{ isset($contract) ? $contract->date_presentation : '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row div-hidden div-2">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Cuadro de nivel de consultoría</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {{-- <label for="table_report"></label> --}}
                                        <textarea class="form-control richTextBox" name="table_report">
                                            {{
                                                isset($contract) ?
                                                $contract->table_report :
                                                '<p>&nbsp;</p>
                                                <table style="color: #000000; font-family: Arial, sans-serif; width: 612px; font-size: 11px;" border="1" cellspacing="0" cellpadding="3">
                                                <tbody>
                                                <tr>
                                                <th rowspan="2">NIVEL DE CONSULTOR&Iacute;A</th>
                                                <th colspan="2">REQUISITOS</th>
                                                </tr>
                                                <tr>
                                                <th>FORMACI&Oacute;N ACAD&Eacute;MICA</th>
                                                <th>MEDIOS DE VERIFICACI&Oacute;N</th>
                                                </tr>
                                                <tr>
                                                <td rowspan="3"><strong>TECNICO IV PARA LA DIRECCI&Oacute;N DE INTERACCI&Oacute;N SOCIAL</strong></td>
                                                <td>
                                                <ul style="padding-left: 20px;">
                                                <li>Descripci&oacute;n</li>
                                                </ul>
                                                </td>
                                                <td>
                                                <ul style="padding-left: 20px;">
                                                <li>Descripción</li>
                                                </ul>
                                                </td>
                                                </tr>
                                                <tr>
                                                <td><strong>EXPERIENCIA LABORAL REQUERIDA</strong></td>
                                                <td><strong>MEDIOS DE VERIFICACI&Oacute;N</strong></td>
                                                </tr>
                                                <tr>
                                                <td>
                                                <ul style="padding-left: 20px;">
                                                <li>No aplica</li>
                                                </ul>
                                                </td>
                                                <td>
                                                <ul style="padding-left: 20px;">
                                                <li>No aplica</li>
                                                </ul>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                <p>&nbsp;</p>
                                                <p style="text-align: justify; color: #000000; font-family: Arial, sans-serif; font-size: 13px;">As&iacute; mismo se procedi&oacute; a la Evaluaci&oacute;n mediante la metodolog&iacute;a CUMPLE y NO CUMPLE, tal como se detalla en el siguiente cuadro:</p>
                                                <p>&nbsp;</p>
                                                <table style="color: #000000; font-family: Arial, sans-serif; width: 612px; font-size: 11px;" border="1" cellspacing="0" cellpadding="3">
                                                <tbody>
                                                <tr>
                                                <th rowspan="2">NIVEL DE CONSULTOR&Iacute;A</th>
                                                <th colspan="2">VERIFICACI&Oacute;N</th>
                                                </tr>
                                                <tr>
                                                <th>FORMACI&Oacute;N ACAD&Eacute;MICA</th>
                                                <th>MEDIOS DE VERIFICACI&Oacute;N</th>
                                                </tr>
                                                <tr>
                                                <td rowspan="3"><strong>TECNICO IV PARA LA DIRECCI&Oacute;N DE INTERACCI&Oacute;N SOCIAL</strong></td>
                                                <td>
                                                <ul style="padding-left: 20px;">
                                                <li>Cumple</li>
                                                </ul>
                                                </td>
                                                <td>
                                                <ul style="padding-left: 20px;">
                                                <li>Cumple</li>
                                                </ul>
                                                </td>
                                                </tr>
                                                <tr>
                                                <td><strong>EXPERIENCIA LABORAL REQUERIDA</strong></td>
                                                <td><strong>MEDIOS DE VERIFICACI&Oacute;N</strong></td>
                                                </tr>
                                                <tr>
                                                <td>
                                                <ul style="padding-left: 20px;">
                                                <li>No aplica</li>
                                                </ul>
                                                </td>
                                                <td>
                                                <ul style="padding-left: 20px;">
                                                <li>No aplica</li>
                                                </ul>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>'
                                            }}
                                        </textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{-- <label for="table_report"></label> --}}
                                        <textarea class="form-control richTextBox" name="details_report">
                                            {{
                                                isset($contract) ?
                                                $contract->details_report :
                                                '<p><span style="color: #000000; font-family: Arial, sans-serif; text-align: justify;">El monto total a cancelar ser&aacute; de </span><strong style="color: #000000; font-family: Arial, sans-serif; text-align: justify;">Bs.- 11.550,00 (Once mil quinientos cincuenta 00/100 Bolivianos)</strong><span style="color: #000000; font-family: Arial, sans-serif; text-align: justify;">, mismos que ser&aacute;n cancelados en cuatro (04) cuotas mensuales: la primera correspondiente a nueve (09) d&iacute;as del mes de septiembre por&nbsp;</span><strong style="color: #000000; font-family: Arial, sans-serif; text-align: justify;">Bs.- 1.200.00</strong><span style="color: #000000; font-family: Arial, sans-serif; text-align: justify;">&nbsp;(Un mil doscientos 00/100 bolivianos), la segunda, tercera y cuarta cuota correspondiente a los meses de octubre, noviembre y diciembre por un monto mensual de&nbsp;</span><strong style="color: #000000; font-family: Arial, sans-serif; text-align: justify;">Bs.- 4.000.00</strong><span style="color: #000000; font-family: Arial, sans-serif; text-align: justify;">&nbsp;(cuatro mil 00/100 bolivianos).</span></p>'
                                            }}
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    @stop

    @section('css')
        <style>

        </style>
    @endsection

    @section('javascript')
        <script>
            var direccion_administrativas = @json($direccion_administrativas);
            var unidad_administrativas = @json($unidad_administrativas);
            var programs = @json($programs);
            var cargos = @json($cargos);
            var jobs = @json($jobs);
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
                $('.div-hidden').fadeOut('fast');
                var additionalConfig = {
                    selector: '.richTextBox',
                }

                $('#select-procedure_type_id').select2();

                $.extend(additionalConfig, {})
                tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
                
                @isset($contract)
                    let workers_memo = '{!! $contract->workers_memo_alt !!}' ? JSON.parse('{!! $contract->workers_memo_alt !!}') : [];
                    $('#select-workers_memo').val(workers_memo);
                    $('.div-{{ $contract->procedure_type_id }}').fadeIn('fast');
                    setTimeout(() => {
                        $('#select-procedure_type_id').val("{{ $contract->procedure_type_id }}").trigger('change');
                        $('#select-cargo_id').val("{{ $contract->job_id ?? $contract->cargo_id }}").trigger('change');
                        $('#select-signature_id').val("{{ $contract->signature_id }}").trigger('change');
                    }, 0);
                @endisset
                $('#select-workers_memo').select2();

                $('#select-direccion_administrativa_id').change(function(){
                    $('#select-unidad_administrativa_id').html(`<option value="">Seleccione una unidad administrativa</option>`);
                    unidad_administrativas.map(item => {
                        if($('#select-direccion_administrativa_id option:selected').val() == item.direccion_id){
                            $('#select-unidad_administrativa_id').append(`<option value="${item.id}">${item.nombre}</option>`);
                        }
                    });
                    $('#select-program_id').html(`<option value="">Seleccione un programa</option>`);
                    programs.map(item => {
                        if($('#select-direccion_administrativa_id option:selected').val() == item.direccion_administrativa_id && $('#select-procedure_type_id option:selected').val() == item.procedure_type_id){
                            $('#select-program_id').append(`<option value="${item.id}">${item.name} - ${item.programatic_category}</option>`);
                        }
                    });
                });

                $('#select-procedure_type_id').change(function(){
                    let id = $('#select-procedure_type_id').val();
                    $('.div-hidden').fadeOut('fast', () => {
                        $(`.div-${id}`).fadeIn('fast');
                    });

                    $('#select-cargo_id').html(`<option value="">Seleccione un cargo</option>`);
                    if(id == 1){
                        $('#select-direccion_administrativa_id').empty();
                        $('#select-unidad_administrativa_id').empty();
                        $('#select-program_id').empty();
                        // $('#select-program_id').attr('disabled', true);
                        // $('#select-program_id').attr('required', false);
                        $('#select-direccion_administrativa_id').attr('disabled', true);
                        $('#select-direccion_administrativa_id').attr('required', false);
                        $('#select-unidad_administrativa_id').attr('disabled', true);
                        $('#select-unidad_administrativa_id').attr('required', false);
                        $('#input-finish').attr('required', false);
                        
                        programs.map(item => {
                            if($('#select-procedure_type_id option:selected').val() == item.procedure_type_id){
                                $('#select-program_id').append(`<option value="${item.id}">${item.name}</option>`);
                            }
                        });

                        jobs.map(item => {
                            $('#select-cargo_id').append(`<option value="${item.id}">Item ${item.item} - ${item.name}</option>`);
                        });
                    }else{
                        $('#select-direccion_administrativa_id').attr('disabled', false);
                        $('#select-direccion_administrativa_id').attr('required', true);
                        $('#select-unidad_administrativa_id').attr('disabled', false);
                        $('#select-unidad_administrativa_id').attr('required', true);
                        $('#select-program_id').attr('disabled', false);
                        $('#select-program_id').attr('required', true);
                        $('#input-finish').attr('required', true);
                        $('#select-direccion_administrativa_id').html(`<option value="">Selecciona la dirección administrativa</option>`);
                        direccion_administrativas.map(item => {
                            $('#select-direccion_administrativa_id').append(`<option value="${item.id}">${item.nombre}</option>`);
                        });

                        setTimeout(() => {
                            $('#select-direccion_administrativa_id').val("{{ isset($contract) ? $contract->direccion_administrativa_id : '' }}");
                            $('#select-direccion_administrativa_id').trigger('change');

                            $('#select-unidad_administrativa_id').val("{{ isset($contract) ? $contract->unidad_administrativa_id : '' }}");
                            $('#select-unidad_administrativa_id').trigger('change');

                            $('#select-program_id').val("{{ isset($contract) ? $contract->program_id : '' }}");
                            $('#select-program_id').trigger('change');
                        }, 0);

                        cargos.map(item => {
                            if($('#select-procedure_type_id option:selected').data('planilla_id') == item.idPlanilla){
                                let nivel = {};
                                item.nivel.map(data => {
                                    if(data.IdPlanilla == item.idPlanilla && data.Estado == 1){
                                        nivel = data;
                                    }
                                });
                                $('#select-cargo_id').append(`<option value="${item.ID}">${item.Descripcion} | Nivel ${nivel.NumNivel} | Bs. ${nivel.Sueldo}</option>`);
                            }
                        });
                    }
                });
            });
        </script>
    @stop

@endif
