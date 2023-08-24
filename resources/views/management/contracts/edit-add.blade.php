@extends('voyager::master')

@section('page_title', isset($contract) ? 'Editar contrato' : 'Crear contrato')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-certificate"></i>
        {{ isset($contract) ? 'Editar' : 'Crear' }} contrato
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <form class="form-submit" action="{{ isset($contract) ? route('contracts.update', ['contract' => $contract->id]) : route('contracts.store') }}" method="post">
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
                                <div class="form-group col-md-6" id="div-program_id">
                                    <label for="program_id">Programa</label>
                                    <select name="program_id" id="select-program_id" class="form-control select2" required></select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cargo_id">Cargo</label>
                                    <div id="div-select-cargo_id">
                                        <select name="cargo_id" id="select-cargo_id" class="form-control select2"></select>
                                    </div>
                                    <input type="text" name="job_description" id="input-job_description" class="form-control" value="{{ isset($contract) ? $contract->job_description : '' }}" style="display: none">
                                </div>
                                <div class="form-group col-md-6 div-tgn">
                                    <label for="salary">Sueldo</label>
                                    <input type="number" name="salary" id="input-salary" class="form-control" value="{{ isset($contract) ? $contract->salary : '' }}">
                                </div>
                                <div class="form-group col-md-6 div-tgn">
                                    <label for="bonus">Bono</label>
                                    <input type="text" name="bonus" value="{{ isset($contract) ? $contract->bonus : '' }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6 div-tgn">
                                    <label for="job_location">Lugar de trabajo</label>
                                    <input type="text" name="job_location" value="{{ isset($contract) ? $contract->job_location : '' }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="start">Inicio de contrato</label>
                                    <input type="date" name="start" id="input-start" value="{{ isset($contract) ? $contract->start : '' }}" class="form-control input-date" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="finish">Fin de contrato</label>
                                    <input type="date" name="finish" id="input-finish" value="{{ isset($contract) ? $contract->finish : '' }}" class="form-control input-date">
                                    <div id="label-duration"></div>
                                </div>
                                <div class="form-group col-md-12 div-signatures">
                                    <label for="requested_by">Persona o unidad solicitante (Opcional)</label>
                                    <input type="text" name="requested_by" value="{{ isset($contract) ? $contract->requested_by : '' }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6 div-signatures">
                                    <label for="signature_id">
                                        Firma autorizada 
                                        <span class="voyager-question" data-toggle="popover" data-placement="top" data-trigger="hover" title="Información" data-content="Firma autorizada que va a figurar en el contrato (Si se deja vacío firma el actual Secretario(a) de Finanzas)" style="cursor: pointer"></span>
                                    </label>
                                    <select name="signature_id" id="select-signature_id" class="form-control select2"></select>
                                </div>
                                <div class="form-group col-md-6 div-signatures">
                                    <label for="signature_alt_id">
                                        Firma autorizada de proceso de contratación RPA 
                                        <span class="voyager-question" data-toggle="popover" data-placement="top" data-trigger="hover" title="Información" data-content="Firma autorizada que va a figurar en el proceso de contratación (Solo para consultoría)" style="cursor: pointer"></span>
                                    </label>
                                    <select name="signature_alt_id" id="select-signature_alt_id" class="form-control select2"></select>
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
                                <div class="form-group col-md-6">
                                    <label for="date_statement">Fecha de declaración jurada</label>
                                    <input type="date" name="date_statement" value="{{ isset($contract) ? $contract->date_statement : '' }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_memo">Fecha de memorandum</label>
                                    <input type="date" name="date_memo" value="{{ isset($contract) ? $contract->date_memo : '' }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_memo_res">Fecha de respuesta de memorandum</label>
                                    <input type="date" name="date_memo_res" value="{{ isset($contract) ? $contract->date_memo_res : '' }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="workers_memo">Responsable(s) de evaluación</label>
                                    <select name="workers_memo[]" id="select-workers_memo" class="form-control" multiple></select>
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

            <div class="row div-hidden div-sedeges">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-heading"><h6 class="panel-title">Datos de contratos SEDEGES</h6></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="name_job_alt" value="{{ isset($contract) ? $contract->name_job_alt : '' }}" placeholder="Denominación de cargo">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" name="work_location" value="{{ isset($contract) ? $contract->work_location : '' }}" placeholder="Lugar de prestación de servicio">
                                </div>
                                <div class="form-group col-md-12">
                                    <textarea class="form-control richTextBox" name="documents_contract">
                                        {{
                                            isset($contract) ?
                                            $contract->documents_contract ?? '<ol type="a"><li><p>Declaraci&oacute;n Jurada de No Doble Percepci&oacute;n.</p></li><li><p>T&eacute;rminos de Referencia.</p></li><li><p>Informe de Evaluaci&oacute;n y Recomendaci&oacute;n del proceso de contrataci&oacute;n N&deg; 123.</p></li><li><p>Certificaci&oacute;n Presupuestaria (Preventivo N&deg; 123)</p></li><li><p>Certificaci&oacute;n Poa N&deg; 123.</p></li><li><p>Declaraci&oacute;n Jurada de No Incompatibilidad Legal.</p></li><li><p>Fotocopia de C&eacute;dula de Identidad.</p></li><li><p>Certificaci&oacute;n de Programaci&oacute;n Operativa Anual (P.O.A.)</p></li><li><p>Curriculum Vitae</p></li><li><p>Certificado de Antecedentes Penales (REJAP)</p></li><li><p>Certificado de No Violencia (Ley 1153)</p></li><li><p>Certificado de Inscripci&oacute;n NIT</p></li><li><p>Certificaci&oacute;n de No Adeudo (AFP)</p></li><li><p>Certificado del RUPE N&deg; 123.( cuando corresponda).</p></li></ol>' :
                                            '<ol type="a"><li><p>Declaraci&oacute;n Jurada de No Doble Percepci&oacute;n.</p></li><li><p>T&eacute;rminos de Referencia.</p></li><li><p>Informe de Evaluaci&oacute;n y Recomendaci&oacute;n del proceso de contrataci&oacute;n N&deg; 123.</p></li><li><p>Certificaci&oacute;n Presupuestaria (Preventivo N&deg; 123)</p></li><li><p>Certificaci&oacute;n Poa N&deg; 123.</p></li><li><p>Declaraci&oacute;n Jurada de No Incompatibilidad Legal.</p></li><li><p>Fotocopia de C&eacute;dula de Identidad.</p></li><li><p>Certificaci&oacute;n de Programaci&oacute;n Operativa Anual (P.O.A.)</p></li><li><p>Curriculum Vitae</p></li><li><p>Certificado de Antecedentes Penales (REJAP)</p></li><li><p>Certificado de No Violencia (Ley 1153)</p></li><li><p>Certificado de Inscripci&oacute;n NIT</p></li><li><p>Certificaci&oacute;n de No Adeudo (AFP)</p></li><li><p>Certificado del RUPE N&deg; 123.( cuando corresponda).</p></li></ol>'
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
                        <div class="panel-heading"><h6 class="panel-title">Cuadro de nivel de consultoría</h6></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <textarea class="form-control richTextBox" name="table_report">
                                        {{
                                            isset($contract) ?
                                            $contract->table_report :
                                            '<table style="color: #000000; font-family: Arial, sans-serif; font-size: 11px;" border="1" cellspacing="0" cellpadding="3">
                                                <tbody>
                                                    <tr>
                                                        <th rowspan="2" style="font-size: 9px;">NIVEL DE CONSULTOR&Iacute;A</th>
                                                        <th colspan="2" style="font-size: 9px;">REQUISITOS</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="font-size: 9px;">FORMACI&Oacute;N ACAD&Eacute;MICA</th>
                                                        <th style="font-size: 9px;">MEDIOS DE VERIFICACI&Oacute;N</th>
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
                                                        <th style="font-size: 9px"><strong>EXPERIENCIA LABORAL REQUERIDA</strong></th>
                                                        <th style="font-size: 9px"><strong>MEDIOS DE VERIFICACI&Oacute;N</strong></th>
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
                                            <p style="text-align: justify; color: #000000; font-family: Arial, sans-serif; font-size: 13px;">As&iacute; mismo se procedi&oacute; a la Evaluaci&oacute;n mediante la metodolog&iacute;a CUMPLE y NO CUMPLE, tal como se detalla en el siguiente cuadro:</p>
                                            <table style="color: #000000; font-family: Arial, sans-serif; font-size: 11px;" border="1" cellspacing="0" cellpadding="3">
                                                <tbody>
                                                    <tr>
                                                        <th rowspan="2" style="font-size: 9px">NIVEL DE CONSULTOR&Iacute;A</th>
                                                        <th colspan="2" style="font-size: 9px">VERIFICACI&Oacute;N</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="font-size: 9px">FORMACI&Oacute;N ACAD&Eacute;MICA</th>
                                                        <th style="font-size: 9px">MEDIOS DE VERIFICACI&Oacute;N</th>
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
                                                        <th style="font-size: 9px"><strong>EXPERIENCIA LABORAL REQUERIDA</strong></th>
                                                        <th style="font-size: 9px"><strong>MEDIOS DE VERIFICACI&Oacute;N</strong></th>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary btn-submit">Guardar</button>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .div-tgn {
            display: none
        }
    </style>
@endsection

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        var direccion_administrativas = @json($direccion_administrativas);
        var unidad_administrativas = @json($unidad_administrativas);
        var programs = @json($programs);
        var cargos = @json($cargos);
        var jobs = @json($jobs);
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            customSelect('#select-workers_memo', '{{ url("admin/contracts/ajax/search") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name);
            $('.div-hidden').fadeOut('fast');
            var additionalConfig = {
                selector: '.richTextBox',
            }
            $.extend(additionalConfig, {})
            tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
            
            // $('#select-workers_memo').select2();
            $('#select-procedure_type_id').select2();

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
                        $('#select-program_id').append(`<option value="${item.id}">${item.name} ${item.programatic_category ? ' ('+item.programatic_category+')' : ''}</option>`);
                    }
                });

                let signatures = $('#select-direccion_administrativa_id option:selected').data('signatures');
                $('#select-signature_id').html('<option value="">--Seleccione firma autorizada--</option>');
                $('#select-signature_alt_id').html('<option value="">--Seleccione firma autorizada--</option>');
                if(signatures){
                    signatures.map(item => {
                        if(item.type == 'inmediato superior'){
                            $('#select-signature_id').append(`<option value="${item.id}">${item.designation} ${item.name} - ${item.job}</option>`);
                        }else{
                            $('#select-signature_alt_id').append(`<option value="${item.id}">${item.designation} ${item.name} - ${item.job}</option>`);
                        }
                    });
                }

                // Si es SEDEGES y no es planilla TGN
                if($('#select-direccion_administrativa_id').val() == 5 && $('#select-procedure_type_id').val() != 6){
                    $('.div-sedeges').fadeIn();
                }else{
                    $('.div-sedeges').fadeOut();
                }
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
                    $('#select-direccion_administrativa_id').prop('disabled', true);
                    $('#select-direccion_administrativa_id').prop('required', false);
                    $('#select-unidad_administrativa_id').prop('disabled', true);
                    $('#select-unidad_administrativa_id').prop('required', false);
                    $('#input-finish').prop('required', false);
                    $('#div-program_id').css('display', 'block');
                    $('#select-program_id').prop('required', true);
                    $('#div-select-cargo_id').css('display', 'block');
                    $('#select-cargo_id').prop('required', true);
                    $('#input-job_description').css('display', 'none');
                    $('#input-job_description').removeAttr('required');
                    $('.div-tgn').css('display', 'none');
                    $('#input-salary').removeAttr('required');
                    $('.div-signatures').css('display', 'block');
                    
                    programs.map(item => {
                        if($('#select-procedure_type_id option:selected').val() == item.procedure_type_id){
                            $('#select-program_id').append(`<option value="${item.id}">${item.name}</option>`);
                        }
                    });

                    jobs.map(item => {
                        $('#select-cargo_id').append(`<option value="${item.id}" data-signatures='${JSON.stringify(item.direccion_administrativa.signatures)}'>Item ${item.item} - ${item.name}</option>`);
                    });
                }else{
                    $('#select-direccion_administrativa_id').prop('disabled', false);
                    $('#select-direccion_administrativa_id').prop('required', true);
                    $('#select-unidad_administrativa_id').prop('disabled', false);
                    $('#select-unidad_administrativa_id').prop('required', true);
                    $('#select-direccion_administrativa_id').html(`<option value="">Selecciona la dirección administrativa</option>`);
                    direccion_administrativas.map(item => {
                        $('#select-direccion_administrativa_id').append(`<option value="${item.id}" data-signatures='${JSON.stringify(item.signatures)}'>${item.nombre}</option>`);
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

                    // Si es planilla TGN
                    if(id == 6){
                        $('#div-program_id').css('display', 'none');
                        $('#select-program_id').removeAttr('required');
                        $('#select-cargo_id').removeAttr('required');
                        $('#div-select-cargo_id').css('display', 'none');
                        $('#input-job_description').css('display', 'block');
                        $('#input-job_description').prop('required', true);
                        $('.div-tgn').css('display', 'block');
                        $('#input-salary').prop('required', true);
                        $('#input-finish').prop('required', false);
                        $('.div-signatures').css('display', 'none');
                    }else{
                        $('#div-program_id').css('display', 'block');
                        $('#select-program_id').prop('required', true);
                        $('#select-cargo_id').prop('required', true);
                        $('#div-select-cargo_id').css('display', 'block');
                        $('#input-job_description').css('display', 'none');
                        $('#input-job_description').removeAttr('required');
                        $('.div-tgn').css('display', 'none');
                        $('#input-salary').removeAttr('required');
                        $('#input-finish').prop('required', true);
                        $('.div-signatures').css('display', 'block');
                    }
                }
            });

            $('#select-cargo_id').change(function(){
                let signatures = $('#select-cargo_id option:selected').data('signatures');
                if(signatures){
                    $('#select-signature_id').html('<option value="">--Seleccione firma autorizada--</option>');
                    signatures.map(item => {
                        $('#select-signature_id').append(`<option value="${item.id}">${item.designation} ${item.name} - ${item.job}</option>`);
                    });
                }
            });

            $('.input-date').change(function(){
                let start = $('#input-start').val();
                let finish = $('#input-finish').val();
                console
                if (start && finish) {
                    $.get("{{ url('admin/get_duration') }}/"+start+"/"+finish, function(res){
                        $('#label-duration').html(`<b class="text-primary" style="font-weight: bold !important">${(res.duration.months *30) + res.duration.days} días de duración</b>`);
                    });
                }
            });

            @isset($contract)
                let workers_memo = '{!! $contract->workers_memo_alt !!}' ? JSON.parse('{!! $contract->workers_memo_alt !!}') : [];
                $('#select-workers_memo').val(workers_memo).trigger('change');
                $('.div-{{ $contract->procedure_type_id }}').fadeIn('fast');
                setTimeout(() => {
                    $('#select-procedure_type_id').val("{{ $contract->procedure_type_id }}").trigger('change');
                    $('#select-cargo_id').val("{{ $contract->job_id ?? $contract->cargo_id }}").trigger('change');
                }, 0);
                setTimeout(() => {
                    $('#select-signature_id').val("{{ $contract->signature_id }}").trigger('change');
                    $('#select-signature_alt_id').val("{{ $contract->signature_alt_id }}").trigger('change');
                }, 500);
            @endisset
        });
    </script>
@stop
