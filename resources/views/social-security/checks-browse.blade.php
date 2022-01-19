@extends('voyager::master')

@section('page_title', 'Cheques de Planilla')

@if(auth()->user()->hasPermission('browse_social-securitychecks'))

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <div class="col-md-8" style="padding: 0px">
                                <h1 class="page-title">
                                    <i class="voyager-window-list"></i> Cheques de Planilla
                                </h1>
                                {{-- <div class="alert alert-info">
                                    <strong>Información:</strong>
                                    <p>Puede obtener el valor de cada parámetro en cualquier lugar de su sitio llamando <code>setting('group.key')</code></p>
                                </div> --}}
                            </div>
                            <div class="col-md-4 text-right" style="margin-top: 30px">
                                {{-- button delete multiple --}}
                                <a href="#" id="btn-delete-multiple" style="display: none" class="btn btn-danger btn-multiple" data-toggle="modal" data-target="#delete_multiple">
                                    <i class="voyager-trash"></i> <span>Eliminar seleccionados</span>
                                </a>
                                <a href="{{ route('checks.create') }}" class="btn btn-success btn-add-new">
                                    <i class="voyager-plus"></i> <span>Añadir Cheque</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('checks.delete_multiple') }}" id="form-selection-multiple" method="POST">
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    {{-- <table id="dataTable" class="table table-hover"></table> --}}
                                    <table id="dataTable" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>ID</th>
                                                <th>Detalles de cheque</th>
                                                <th>Beneficiario</th>
                                                <th>Impresión</th>
                                                <th>Vencimiento</th>
                                                <th>Registrado por</th>
                                                <th>Registrado el</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td><div><input type="checkbox" name="id[]" onclick="checkId()" value="{{ $row->id }}" /></div></td>
                                                    <td>{{ $row->id }}</td>
                                                    <td>
                                                        @php
                                                            $planilla = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                                                                            ->join('tplanilla as tp', 'tp.ID', 'ph.Tplanilla')
                                                                            ->where('ph.ID', $row->planilla_haber_id)
                                                                            ->select('ph.*', 'tp.Nombre as tipo_planilla')->first();
                                                            $status = '';
                                                            switch ($row->status) {
                                                                case '0':
                                                                    $status = '<label class="label label-danger">anulado</label>';
                                                                    break;
                                                                case '1':
                                                                    $status = '<label class="label label-info">Pendiente</label>';
                                                                    break;
                                                                case '2':
                                                                    $status = '<label class="label label-success">Pagado</label>';
                                                                    break;
                                                                case '3':
                                                                    $status = '<label class="label label-warning">Vencido</label>';
                                                                    break;
                                                                default:
                                                                    # code...
                                                                    break;
                                                            }
                                                        @endphp
                                                        @if ($planilla)
                                                            <p>
                                                                <b><small>N&deg;:</small></b> {{ $row->number }} <br>
                                                                <b>{{ $planilla->tipo_planilla.' - '.$planilla->Periodo }}</b><br>
                                                                <b><small>Planilla:</small></b> {{ ($planilla ? $planilla->idPlanillaprocesada.' - '.($planilla->Afp == 1 ? 'Futuro' : 'Previsión') : 'No encontrada') }} <br>
                                                                <b><small>Monto:</small></b> {{ number_format($row->amount, 2, ',', '.') }} <br>
                                                                {!! $status !!}
                                                            </p>
                                                        @elseif($row->spreadsheet_id)
                                                            @php
                                                                $spreadsheet = App\Models\Spreadsheet::find($row->spreadsheet_id);
                                                            @endphp
                                                            <p>
                                                                <label class="label label-danger">Planilla manual</label> <br>
                                                                <b>{{ ($spreadsheet->tipo_planilla_id == 1 ? 'Funcionamiento' : 'Inversión').' - '.$spreadsheet->year.str_pad($spreadsheet->month, 2, "0", STR_PAD_LEFT)  }}</b><br>
                                                                <b><small>Planilla:</small></b> {{ ($spreadsheet->codigo_planilla.' - '.($spreadsheet->afp_id == 1 ? 'Futuro' : 'Previsión')) }} <br>
                                                                <b><small>Monto:</small></b> {{ number_format($row->amount, 2, ',', '.') }} <br>
                                                                {!! $status !!}
                                                            </p>
                                                        @endif
                                                    </td>
                                                    <td>{{ $row->check_beneficiary->full_name }}<br><small> {{ $row->check_beneficiary->type->name }}</small></td>
                                                    <td>{{ $row->user->name }}</td>
                                                    <td>{{ date('d/m/Y', strtotime($row->date_print)) }}<br><small>{{ \Carbon\Carbon::parse($row->date_print)->diffForHumans() }}</small></td>
                                                    <td>
                                                        @php
                                                            $date_expire = date('Y-m-d', strtotime($row->date_print.' +29 days'));
                                                        @endphp
                                                        <span style="{{ ($date_expire <= date('Y-m-d') && $row->status == 1 ? 'color: red' : '') }}">{{ date('d/m/Y', strtotime($date_expire)) }}<br><small>{{ \Carbon\Carbon::parse($date_expire)->diffForHumans() }}</small></span>
                                                    </td>
                                                    <td>{{ date('d/m/Y H:i', strtotime($row->created_at)) }}<br><small>{{ \Carbon\Carbon::parse($row->created_at)->diffForHumans() }}</small></td>
                                                    <td>
                                                        <div class="no-sort no-click bread-actions text-right">
                                                            <a href="{{ route('checks.show', ['check' => $row->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                                            </a>
                                                            <a href="{{ route('checks.edit', ['check' => $row->id]) }}" title="Editar" class="btn btn-sm btn-info edit">
                                                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                                            </a>
                                                            <button type="button" onclick="deleteItem('{{ route('checks.delete', ['check' => $row->id]) }}')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Modal delete massive --}}
                        <div class="modal modal-danger fade" tabindex="-1" id="delete_multiple" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                    aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><i class="voyager-trash"></i> ¿Estás seguro?</h4>
                                    </div>
                                    <div class="modal-body">
                                        <h4>¿Estás seguro de que quieres eliminar los cheques seleccionados?</h4>
                                    </div>
                                    <div class="modal-footer">
                                            {{ csrf_field() }}
                                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Eliminar">
                                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                                            Cancelar
                                        </button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                    </form>
                </div>
            </div>
        </div>
    @stop

    @section('css')

    @stop

    @section('javascript')
        <script src="{{ url('js/main.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable({
                    order: [[ 1, 'desc' ]],
                    language
                });

            });
        </script>
    @stop

@else
    @section('content')
        <h1>Error</h1>
    @stop
@endif