@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', isset($contract) ? 'Editar contrato' : 'Crear contrato')

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
                                <div class="form-group col-md-12">
                                    <label for="person_id">Persona</label>
                                    <select name="person_id" class="form-control select2" required>
                                        <option value="">-- Selecciona a la persona --</option>
                                        @foreach ($people as $item)
                                        <option @if(isset($contract) && $contract->person->id == $item->id) selected @endif value="{{ $item->id }}">{{ $item->first_name }} {{ $item->last_name }} - {{ $item->ci }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="unidad_adminstrativa_id">Unidad administrativa</label>
                                    <select name="unidad_adminstrativa_id" class="form-control select2" required>
                                        <option value="">-- Selecciona la dirección administrativa --</option>
                                        @foreach ($direccion_administrativas as $item)
                                        <option @if(isset($contract) && $contract->unidad_adminstrativa_id == $item->ID) selected @endif value="{{ $item->ID }}">{{ $item->NOMBRE }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="program_id">Programa</label>
                                    <select name="program_id" class="form-control select2" required>
                                        <option value="">-- Selecciona el programa --</option>
                                        @foreach ($programs as $item)
                                        <option @if(isset($contract) && $contract->program_id == $item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cargo_id">Cargo</label>
                                    <select name="cargo_id" class="form-control select2" required>
                                        <option value="">-- Selecciona el cargo --</option>
                                        @foreach ($cargos as $item)
                                        <option @if(isset($contract) && $contract->cargo_id == $item->ID) selected @endif value="{{ $item->ID }}">{{ $item->Descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="salary">Salario</label>
                                    <input type="number" name="salary" min="1" step="1" value="{{ isset($contract) ? $contract->salary : '' }}" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="start">Inicio de contrato</label>
                                    <input type="date" name="start" value="{{ isset($contract) ? $contract->start : '' }}" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="finish">Fin de contrato</label>
                                    <input type="date" name="finish" value="{{ isset($contract) ? $contract->finish : '' }}" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
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
            
            <div class="row">
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

            <div class="row">
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
                                <div class="form-group col-md-12">
                                    <label for="workers_memo">Funcionarios encargados</label>
                                    <select name="workers_memo[]" id="select-workers_memo" class="form-control" multiple>
                                        @foreach ($funcionarios as $item)
                                        <option @if(isset($contract) && $contract->cargo_id == $item->ID) selected @endif value="{{ $item->ID }}">{{ str_replace('  ', ' ', $item->NombreCompleto) }} - {{ $item->Cargo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
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
                                    <label for="date_report">Fecha de informe</label>
                                    <input type="date" name="date_report" value="{{ isset($contract) ? $contract->date_report : '' }}" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="date_presentation">Fecha de Presentación</label>
                                    <input type="date" name="date_presentation" value="{{ isset($contract) ? $contract->date_presentation : '' }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
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
                                            <li>&nbsp;</li>
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
                                            '<p><strong style="color: #000000; font-family: Arial, sans-serif; font-size: 13px; text-align: justify;">3. MONTO Y FORMA DE PAGO</strong><br style="color: #000000; font-family: Arial, sans-serif; font-size: 13px; text-align: justify;" /><span style="color: #000000; font-family: Arial, sans-serif; font-size: 13px; text-align: justify;">El monto total a cancelar ser&aacute; de&nbsp;</span><strong style="color: #000000; font-family: Arial, sans-serif; font-size: 13px; text-align: justify;">Bs.- 11.550,00 (Once mil quinientos cincuenta 00/100 Bolivianos)</strong><span style="color: #000000; font-family: Arial, sans-serif; font-size: 13px; text-align: justify;">, mismos que ser&aacute;n cancelados en cuatro (04) cuotas mensuales: la primera correspondiente a nueve (09) d&iacute;as del mes de septiembre por&nbsp;</span><strong style="color: #000000; font-family: Arial, sans-serif; font-size: 13px; text-align: justify;">Bs.- 1.200.00</strong><span style="color: #000000; font-family: Arial, sans-serif; font-size: 13px; text-align: justify;">&nbsp;(Un mil doscientos 00/100 bolivianos), la segunda, tercera y cuarta cuota correspondiente a los meses de octubre, noviembre y diciembre por un monto mensual de&nbsp;</span><strong style="color: #000000; font-family: Arial, sans-serif; font-size: 13px; text-align: justify;">Bs.- 4.000.00</strong><span style="color: #000000; font-family: Arial, sans-serif; font-size: 13px; text-align: justify;">&nbsp;(cuatro mil 00/100 bolivianos), mismas que ser&aacute;n canceladas previa presentaci&oacute;n y aprobaci&oacute;n de informe de actividades.</span></p>'
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

@section('javascript')
    <script>
        $(document).ready(function(){
            var additionalConfig = {
                selector: '.richTextBox',
            }

            $.extend(additionalConfig, {})

            tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
            
            @isset($contract)
                let workers_memo = '{!! $contract->workers_memo !!}' ? JSON.parse('{!! $contract->workers_memo !!}') : [];
                $('#select-workers_memo').val(workers_memo);
            @endisset
            $('#select-workers_memo').select2();
        });
    </script>
@stop
