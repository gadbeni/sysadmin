<form action="{{ route('checks.delete_multiple') }}" id="form-selection-multiple" method="POST">
    <div class="col-md-12">
        <div id="dataTable" class="table-responsive">
            <table class="table table-bordered table-hover">
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
                                    $status = '';
                                    switch ($row->status) {
                                        case '0':
                                            $status = '<label class="label label-danger">Anulado</label>';
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
                                @if ($row->planilla_haber)
                                    <p>
                                        <b><small>N&deg;:</small></b> {{ $row->number }} <br>
                                        <b>{{ $row->planilla_haber->tipo->Nombre.' - '.$row->planilla_haber->Periodo }}</b><br>
                                        <b><small>Planilla:</small></b> {{ ($row->planilla_haber ? $row->planilla_haber->idPlanillaprocesada.' - '.($row->planilla_haber->Afp == 1 ? 'Futuro' : 'Previsión') : 'No encontrada') }} <br>
                                        <b><small>Monto:</small></b> {{ number_format($row->amount, 2, ',', '.') }} <br>
                                        {!! $status !!}
                                    </p>
                                @elseif($row->spreadsheet)
                                    <p>
                                        <label class="label label-danger">Planilla manual</label> <br>
                                        <b>{{ ($row->spreadsheet->tipo_planilla_id == 1 ? 'Funcionamiento' : 'Inversión').' - '.$row->spreadsheet->year.str_pad($row->spreadsheet->month, 2, "0", STR_PAD_LEFT)  }}</b><br>
                                        <b><small>Planilla:</small></b> {{ ($row->spreadsheet->codigo_planilla.' - '.($row->spreadsheet->afp_id == 1 ? 'Futuro' : 'Previsión')) }} <br>
                                        <b><small>Monto:</small></b> {{ number_format($row->amount, 2, ',', '.') }} <br>
                                        {!! $status !!}
                                    </p>
                                @endif
                            </td>
                            <td>{{ $row->beneficiary->full_name }}<br><small> {{ $row->beneficiary->type->name }}</small></td>
                            <td>{{ date('d/m/Y', strtotime($row->date_print)) }}<br><small>{{ \Carbon\Carbon::parse($row->date_print)->diffForHumans() }}</small></td>
                            <td>
                                @php
                                    $date_expire = date('Y-m-d', strtotime($row->date_print.' +29 days'));
                                @endphp
                                <span style="{{ ($date_expire <= date('Y-m-d') && $row->status == 1 ? 'color: red' : '') }}">{{ date('d/m/Y', strtotime($date_expire)) }}<br><small>{{ \Carbon\Carbon::parse($date_expire)->diffForHumans() }}</small></span>
                            </td>
                            <td>{{ $row->user->name }}</td>
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
    <div class="col-md-12">
        <div class="col-md-4" style="overflow-x:auto">
            @if(count($data)>0)
                <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
            @endif
        </div>
        <div class="col-md-8" style="overflow-x:auto">
            <nav class="text-right">
                {{ $data->links() }}
            </nav>
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
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</form>



<script>
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });
    });
</script>