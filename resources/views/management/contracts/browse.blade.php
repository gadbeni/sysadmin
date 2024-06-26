@extends('voyager::master')

@section('page_title', 'Viendo Contratos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-certificate"></i> Contratos
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            @if (auth()->user()->hasPermission('add_contracts'))
                                <a href="{{ route('contracts.create') }}" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Crear</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-custom">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm select-filter">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>
                            <div class="col-sm-3 text-right">
                                <input type="text" id="input-search" placeholder="Buscar..." class="form-control">
                                @if (!Auth::user()->direccion_administrativa_id)
                                <a href="#more-options" class="btn btn-link" data-toggle="collapse"> <i class="fa fa-plus"></i> Más opciones</a>
                                @endif
                            </div>
                            <div class="col-sm-12">
                                <div id="more-options" class="collapse">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="procedure_type_id">Tipo de contrato</label>
                                            <select name="procedure_type_id" class="form-control select2 select-filter" id="select-procedure_type_id">
                                                <option value="">Todos</option>
                                                <option value="1">Permanente</option>
                                                <option value="5">Eventual</option>
                                                <option value="2">Consultoría de línea</option>
                                                <option value="6">TGN</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="direccion_administrativa_id">Dirección administrativa</label>
                                            <select name="direccion_administrativa_id" class="form-control select2 select-filter" id="select-direccion_administrativa_id">
                                                <option value="">Todas</option>
                                                @foreach (App\Models\Direccion::where('estado', 1)->where('deleted_at', null)->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="status">Estado</label>
                                            <select name="status" class="form-control select2 select-filter" id="select-status">
                                                <option value="">Todos</option>
                                                <option value="elaborado">Elaborado</option>
                                                <option value="enviado">Enviado</option>
                                                <option value="firmado">Firmado</option>
                                                <option value="concluido">Concluido</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="addendums">Filtro de adendas</label>
                                            <select name="addendums" class="form-control select2 select-filter" id="select-addendums">
                                                <option value="">Todos</option>
                                                <option value="1">Con adenda</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="user_id">Registrado por</label>
                                            <select name="user_id" class="form-control select2 select-filter" id="select-user_id">
                                                <option value="">Todos los usuarios</option>
                                                @foreach (App\Models\User::where('deleted_at', null)->whereRaw("id in (select user_id from contracts where deleted_at is null)")->whereRaw(Auth::user()->role_id != 1 ? 'role_id > 1' : 1)->withTrashed()->get() as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- change status modal --}}
    <form action="{{ route('contracts.status') }}" id="form-status" class="form-submit" method="POST">
        {{ csrf_field() }}
        <div class="modal modal-primary fade" tabindex="-1" id="status-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-check"></i> Desea promover el contrato a la siguiente instancia?</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="id">
                            <input type="hidden" name="status">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Finish modal --}}
    <form class="form-submit" action="#" id="form-finish" method="POST">
        {{ csrf_field() }}
        <div class="modal fade" tabindex="-1" id="finish-modal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-calendar"></i> Resolución de contrato</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <input type="hidden" name="status" value="concluido">
                        <div class="form-group">
                            <label for="">Fecha de resolución</label>
                            <input type="date" name="finish" class="form-control" required>
                        </div>
                        <div class="form-group type-1">
                            <label for="observations">Motivo</label>
                            <textarea name="observations" class="form-control textarea-type-1" rows="5" placeholder="" required></textarea>
                        </div>
                        <div class="form-group type-2">
                            <label for="technical_report">Informe técnico</label>
                            <textarea name="technical_report" class="form-control textarea-type-2" rows="5" placeholder="SByA Nº 001/2023 D.B. y A..- RR.HH., de 16 de maro de 2023 procede a la resolución de contrato emitido por..." required></textarea>
                        </div>
                        <div class="form-group type-2">
                            <label for="nci">NCI</label>
                            <textarea name="nci" class="form-control textarea-type-2" rows="5" placeholder="I-DRRHH/AAA-93/2023 de 03 de abril de 2023, emitida por..." required></textarea>
                        </div>
                        <div class="form-group type-2">
                            <label for="legal_report">Informe legal</label>
                            <textarea name="legal_report" class="form-control textarea-type-2" rows="5" placeholder="Nº 001/2023 de 05 de abril de 2023, la Abog..." required></textarea>
                        </div>
                        <div class="form-group type-2">
                            <label for="details">Inciso mencionado</label>
                            <textarea name="details" class="form-control textarea-type-2" rows="5" placeholder=". e)  Inasistencia injustificada de tres (3) días hábiles consecutivos o seis (6) días hábiles discontinuos en un (1) mes" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark btn-submit" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Finish modal --}}
    <form class="form-submit" action="{{ route('contracts.reassignment.store') }}" id="form-position_reassignment" method="POST">
        @csrf
        <div class="modal fade" tabindex="-1" id="position_reassignment-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-documentation"></i> Reasignación de cargo</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="job_id">Item</label>
                            <select name="job_id" id="select-job_id-reassignment" class="form-control" required>
                                <option value="">--Seleccionar Item--</option>
                                @php
                                    $jobs = App\Models\Job::where('status', 1)->where('deleted_at', NULL)
                                                // Mostrar solo cargos que estén acefalos
                                                ->whereRaw(!setting('auxiliares.enable_all_jobs_for_contract') ? "id not in (select job_id from contracts where job_id is not NULL and status <> 'concluido' and deleted_at is null)" : 1)
                                                ->get();
                                @endphp
                                @foreach ($jobs as $item)
                                <option value="{{ $item->id }}" data-item='@json($item)'>ITEM {{ $item->item }} : {{ $item->name }} - Bs.{{ $item->salary }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Fecha de reasignación</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="signature_date">Fecha de suscripción</label>
                            <input type="date" name="signature_date" class="form-control" required>
                            <small>Fecha en la que se suscribe la reasignación</small>
                        </div>
                        <div class="form-group">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark btn-submit" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Ratificate modal --}}
    <form action="{{ route('contracts.ratificate') }}" id="form-ratificate" class="form-submit" method="POST">
        {{ csrf_field() }}
        <div class="modal fade" tabindex="-1" id="ratificate-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-calendar"></i> Desea ratificar el siguiente contrato?</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="">Fecha de ratificación</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Transfer modal --}}
    <form action="{{ route('contracts.transfer.store') }}" id="form-transfer" class="form-submit" method="POST">
        @csrf
        <div class="modal modal-primary fade" tabindex="-1" id="transfer-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-check"></i> Registrar transferencia</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="contract_id">
                        <div class="form-group">
                            <label for="job_id">Item</label>
                            <select name="job_id" id="select-job_id" class="form-control" required>
                                <option value="">--Seleccionar Item--</option>
                                @php
                                    $jobs = App\Models\Job::where('status', 1)->where('deleted_at', NULL)
                                                // Mostrar solo cargos que estén acefalos
                                                ->whereRaw(!setting('auxiliares.enable_all_jobs_for_contract') ? "id not in (select job_id from contracts where job_id is not NULL and status <> 'concluido' and deleted_at is null)" : 1)
                                                ->get();
                                @endphp
                                @foreach ($jobs as $item)
                                <option value="{{ $item->id }}" data-item='@json($item)'>ITEM {{ $item->item }} : {{ $item->name }} - Bs.{{ $item->salary }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Fecha de transferencia</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Promotion modal --}}
    <form action="{{ route('contracts.promotion.store') }}" id="form-promotion" class="form-submit" method="POST">
        @csrf
        <div class="modal modal-primary fade" tabindex="-1" id="promotion-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-check"></i> Registrar Promoción</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="contract_id">
                        <div class="form-group">
                            <label for="job_id">Item</label>
                            <select name="job_id" id="select-job_id-alt" class="form-control" required>
                                <option value="">--Seleccionar Item--</option>
                                @php
                                    $jobs = App\Models\Job::where('status', 1)->where('deleted_at', NULL)
                                                // Mostrar solo cargos que estén acefalos
                                                ->whereRaw(!setting('auxiliares.enable_all_jobs_for_contract') ? "id not in (select job_id from contracts where job_id is not NULL and status <> 'concluido' and deleted_at is null)" : 1)
                                                ->get();
                                @endphp
                                @foreach ($jobs as $item)
                                <option value="{{ $item->id }}" data-item='@json($item)'>ITEM {{ $item->item }} : {{ $item->name }} - Bs.{{ $item->salary }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Fecha de promoción</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-dark" value="Aceptar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Addendum create modal --}}
    <form action="{{ route('contracts.addendum.store') }}" id="form-addendum" class="form-submit" method="POST">
        @csrf
        <input type="hidden" name="id">
        <div class="modal modal-primary fade" tabindex="-1" id="addendum-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-certificate"></i> Crear adenda</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 id="label-duration-contract" class="text-primary"></h4>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="start">Inicio</label>
                                <input type="date" name="start" id="input-start" class="form-control" readonly required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="finish">Fin</label>
                                <input type="date" name="finish" id="input-finish" class="form-control" required>
                                <div id="label-duration"></div>
                            </div>
                            <div class="form-group col-md-12 div-eventual-consultor_sedeges">
                                <label for="applicant_id">Solicitante</label>
                                <select name="applicant_id" id="select-applicant_id" class="form-control"></select>
                            </div>

                            {{-- Eventual central --}}
                            <div class="form-group col-md-6 div-eventual">
                                <label for="nci_code">NCI</label>
                                <input type="text" name="nci_code" class="form-control" >
                            </div>
                            <div class="form-group col-md-6 div-eventual">
                                <label for="nci_date">Fecha de NCI</label>
                                <input type="date" name="nci_date" class="form-control" >
                            </div>
                            <div class="form-group col-md-6 div-eventual">
                                <label for="certification_code">Certificación presupuestaria</label>
                                <input type="text" name="certification_code" class="form-control" >
                            </div>
                            <div class="form-group col-md-6 div-eventual">
                                <label for="certification_date">Fecha de certificación presupuestaria</label>
                                <input type="date" name="certification_date" class="form-control" >
                            </div>

                            {{-- Consultor SEDEGES --}}
                            <div class="form-group col-md-6 div-consultor_sedeges">
                                <label for="request_date">Fecha de solicitud</label>
                                <input type="date" name="request_date" class="form-control" >
                            </div>
                            <div class="form-group col-md-6 div-consultor_sedeges">
                                <label for="legal_report_date">Fecha de informe legal</label>
                                <input type="date" name="legal_report_date" class="form-control" >
                            </div>
                            

                            <div class="form-group col-md-12">
                                <label for="signature_id">Firma autorizada</label>
                                <select name="signature_id" id="select-signature_id" class="form-control">
                                    <option value="">Secretario(a) de Administración y Finanzas</option>
                                    @foreach (App\Models\Signature::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                    <option @if($item->direccion_administrativa_id == Auth::user()->direccion_administrativa_id) selected @endif value="{{ $item->id }}">{{ $item->designation }} {{ $item->name }} - {{ $item->job }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="signature_date">Fecha de suscripción de adenda</label>
                                <input type="date" name="signature_date" class="form-control" required>
                                <span id="alert-weekend" class="text-danger" style="font-weight: bold !important">Fin de semana</span>
                            </div>
                            <div class="form-group col-md-12 text-right" style="margin-bottom: 0px">
                                <div class="checkbox">
                                    <label><input type="checkbox" required> Aceptar y guardar</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark btn-submit">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Addendum show modal --}}
    <div class="modal modal-primary fade" tabindex="-1" id="addendum-show-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-certificate"></i> Viendo adenda</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p><b>Contrato original</b></p>
                            <p id="label-date-contract"></p>
                        </div>
                        <div class="col-md-12">
                            <p><b>Detalles de adenda(s)</b></p>
                            <div id="details-addendum"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Addendum status modal --}}
    <div class="modal fade" tabindex="-1" id="addendum-status-modal" role="dialog">
        <div class="modal-dialog modal-success">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-certificate"></i> Desea firmar la adenda?</h4>
                </div>
                <div class="modal-footer text-right">
                    <form action="{{ route('contracts.addendum.status') }}" id="addendum-status-form" class="form-submit" method="POST">
                        @csrf
                        <input type="hidden" name="id">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-success" value="Sí, firmar">
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete signature modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="downgrade-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar la firma del contrato?</h4>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('contracts.status') }}" id="downgrade-form" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id">
                        <input type="hidden" name="status">
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete modal --}}
    <form action="#" id="delete_form_alt" method="POST">
        <div class="modal modal-danger fade" tabindex="-1" id="delete-modal-alt" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular el siguiente contrato?</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="observations">Observaciones</label>
                            <textarea name="observations" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Restore modal --}}
    <form action="#" class="form-submit" id="restore-form" method="POST">
        <div class="modal modal-danger fade" tabindex="-1" id="restore-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea restaurar el siguiente contrato?</h4>
                    </div>
                    <div class="modal-footer">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Agreagr descuento adicional modal --}}
    <form action="{{ route('contracts.additional.discount.store') }}" class="form-submit" id="add-discount-form" method="POST">
        @csrf
        <input type="hidden" name="contract_id">
        <input type="hidden" name="salary_amount">
        <div class="modal modal-dark fade" tabindex="-1" id="add-discount-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-edit"></i> Crear descuento adicional</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="type">Tipo de descuento</label>
                            <select name="type" class="form-control" required>
                                <option value="1">Monto fijo</option>
                                <option value="2">Porcentaje</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Monto</label>
                            <input type="number" name="amount" min="1" step="0.1" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="reason">Motivo</label>
                            <textarea name="reason" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="details">Detalles adicionales</label>
                            <textarea name="details" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark btn-submit">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Modal rotation --}}
    @include('management.people.partials.modal-rotation')
@stop

@section('css')
    {{-- <style>
        @media (max-width: 767px) {
            .table-responsive .dropdown-menu {
                position: static !important;
            }
        }
        @media (min-width: 768px) {
            .table-responsive {
                overflow: visible;
            }
        }
    </style> --}}
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';
        var procedure_type_id = '';
        var direccion_administrativa_id = '';
        var status = '';
        var user_id = '';
        var addendums = '';
        var main_contract_duration = null;

        $(document).ready(() => {
            $('#select-job_id').select2({dropdownParent: $('#transfer-modal')});
            $('#select-job_id-alt').select2({dropdownParent: $('#promotion-modal')});
            $('#select-job_id-reassignment').select2({dropdownParent: $('#position_reassignment-modal')});
            $('#select-signature_id').select2({dropdownParent: $('#addendum-modal')});
            $('#select-destiny_id').select2({dropdownParent: $('#modal-rotation')});
            $('#select-destiny_dependency').select2({dropdownParent: $('#modal-rotation')});
            $('#select-responsible_id').select2({dropdownParent: $('#modal-rotation')});
            list();

            customSelect('#select-applicant_id', '{{ url("admin/contracts/search/ajax") }}', formatResultContracts, data => data.person.first_name+' '+data.person.last_name, $('#addendum-modal'));
            
            $('#input-search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('.select-filter').change(function(){
                countPage = $('#select-paginate option:selected').val();
                procedure_type_id = $('#select-procedure_type_id option:selected').val();
                direccion_administrativa_id = $('#select-direccion_administrativa_id option:selected').val();
                status = $('#select-status option:selected').val();
                user_id = $('#select-user_id option:selected').val();
                addendums = $('#select-addendums option:selected').val();
                list();
            });

            $('.form-submit').submit(function(e){
                $('.modal').modal('hide');
                e.preventDefault();
                $('#div-results').loading({message: 'Cargando...'});
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    if(res.success){
                        toastr.success(res.message);
                        list(page);
                        if(res.rotation){
                            window.open(`{{ url('admin/people/rotation') }}/${res.rotation.id}`, '_blank').focus();
                        }
                    }else{
                        toastr.error(res.message);
                        $('#div-results').loading('toggle');
                    }

                    $('.form-submit').trigger('reset');
                    $('.btn-submit').removeAttr('disabled');
                    $('#select-applicant_id').val(null).trigger('change');
                    $('#select-job_id-reassignment').val(null).trigger('change');
                });
            });

            $('#input-finish').change(function(){
                let start = $('#input-start').val();
                let finish = $(this).val();
                $.get("{{ url('admin/get_duration') }}/"+start+"/"+finish, function(res){
                    let duration = (res.duration.months *30) + res.duration.days;
                    $('#label-duration').html(`<b class="text-${duration <= main_contract_duration ? 'success' : 'danger'}" style="font-weight: bold !important">${duration} días de duración</b>`);
                    if(duration <= main_contract_duration){
                        $('#form-addendum .btn-submit').removeAttr('disabled');
                    }else{
                        $('#form-addendum .btn-submit').attr('disabled', 'disabled');
                    }
                });
            });

            $('#downgrade-form').submit(function(e){
                $('#downgrade-modal').modal('hide');
                e.preventDefault();
                $('#div-results').loading({message: 'Cargando...'});
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    if(res.message){
                        toastr.success(res.message);
                        list(page);
                    }else{
                        toastr.error(res.error);
                        $('#div-results').loading('toggle');
                    }
                });
            });

            $('#form-position_reassignment input[name="date"]').change(function(){
                if(!$('#form-position_reassignment input[name="signature_date"]').val()){
                    $('#form-position_reassignment input[name="signature_date"]').val($('#form-position_reassignment input[name="date"]').val())
                }
            });

            $('#delete_form_alt').submit(function(e){
                $('#delete-modal-alt').modal('hide');
                e.preventDefault();
                $('#div-results').loading({message: 'Cargando...'});
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    if(res.message){
                        toastr.success(res.message);
                        list(page);
                    }else{
                        toastr.error(res.error);
                        $('#div-results').loading('toggle');
                    }
                });
            });
        });

        function list(page = 1){
            $('#div-results').loading({message: 'Cargando...'});
            let url = '{{ url("admin/contracts/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';
            $.ajax({
                url: `${url}?search=${search}&procedure_type_id=${procedure_type_id}&direccion_administrativa_id=${direccion_administrativa_id}&addendums=${addendums}&user_id=${user_id}&status=${status}&paginate=${countPage}&page=${page}`,
                type: 'get',
                success: function(response){
                    $('#div-results').html(response);
                    $('#div-results').loading('toggle');
                }
            });
        }

        function changeStatus(id, status) {
            $('#form-status input[name="id"]').val(id);
            $('#form-status input[name="status"]').val(status);
        }

        function finishContract(contract) {
            $('#form-finish').trigger('reset');
            $('#form-finish input[name="id"]').val(contract.id);
            $('#form-finish input[name="finish"]').val(contract.finish);
            $('#form-finish input[name="finish"]').attr("max", contract.procedure_type_id);
            if(contract.procedure_type_id == 1){
                $('#form-finish .type-1').fadeIn('fast');
                $('#form-finish .type-2').fadeOut('fast');
                $('#form-finish .textarea-type-1').attr('required', 'required');
                $('#form-finish .textarea-type-2').removeAttr('required');
            }else{
                $('#form-finish .type-2').fadeIn('fast');
                $('#form-finish .type-1').fadeOut('fast');
                $('#form-finish .textarea-type-2').attr('required', 'required');
                $('#form-finish .textarea-type-1').removeAttr('required');
            }

            // Si el contrato ya se finalizó, quiere decir que lo que queremos es editar
            if (contract.finished) {
                $('#form-finish').attr('action', "{{ route('contracts.resolution.update') }}");
                $('#form-finish input[name="finish"]').prop('disabled', true);
                $('#form-finish textarea[name="technical_report"]').val(contract.finished.technical_report);
                $('#form-finish textarea[name="nci"]').val(contract.finished.nci);
                $('#form-finish textarea[name="legal_report"]').val(contract.finished.legal_report);
                $('#form-finish textarea[name="details"]').val(contract.finished.details);
            }else{
                $('#form-finish').attr('action', "{{ route('contracts.status') }}");
                $('#form-finish input[name="finish"]').prop('disabled', false);
            }
        }

        function ratificateContract(id) {
            $('#form-ratificate input[name="id"]').val(id);
        }

        function downgradeContract(id, status) {
            $('#downgrade-form input[name="id"]').val(id);
            $('#downgrade-form input[name="status"]').val(status);
        }

        function setFormAction(url, form){
            $(form).attr('action', url);
        }
    </script>
@stop
