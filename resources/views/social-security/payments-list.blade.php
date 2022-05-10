<div class="col-md-12">
    <div id="dataTable" class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Planilla</th>
                    <th>Nro de FPC</th>
                    <th>Nro de GTC-11</th>
                    <th>Nro de deposito</th>
                    <th>Registrado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $row)
                    <tr>
                        <td><div><input type="checkbox" name="id[]" onclick="checkId()" value="{{ $row->id }}" {{ $row->spreadsheet_id ? 'disabled' : '' }} /></div></td>
                        <td>{{ $row->id }}</td>
                        <td>
                            @if($row->planilla_haber)
                                <b> {{ $row->planilla_haber->tipo ? $row->planilla_haber->tipo->Nombre : 'No definido' }} - {{ $row->planilla_haber->Periodo }}</b> <br>
                                <small>Planilla: </small> {{ $row->planilla_haber->idPlanillaprocesada }} - {{ $row->planilla_haber->Afp == 1 ? 'Futuro' : 'Previsión' }} <br>
                                <small>Total ganado: </small> {{ number_format($row->planilla_haber->planilla_procesada ? $row->planilla_haber->planilla_procesada->Monto : 0, 2, ',', '.') }}
                            @elseif($row->paymentschedule)
                                <b> {{ $row->paymentschedule->procedure_type->name }} - {{ $row->paymentschedule->period->name }}</b> <br>
                                <small>Planilla: </small>{{ str_pad($row->paymentschedule->id, 6, "0", STR_PAD_LEFT) }} - {{ $row->afp == 1 ? 'Futuro' : 'Previsión' }} <br>
                                <small>Total ganado: </small> {{ number_format($row->paymentschedule->details->sum('partial_salary') + $row->paymentschedule->details->sum('seniority_bonus_amount'), 2, ',', '.') }}
                            @elseif($row->spreadsheet)
                                <label class="label label-danger">Planilla manual</label> <br> <b> {{ $row->spreadsheet->tipo_planilla_id == 1 ? 'Funcionamiento' : 'Inversión' }} - {{ $row->spreadsheet->year.str_pad($row->spreadsheet->month, 2, "0", STR_PAD_LEFT) }} </b> <br>
                                {{ $row->spreadsheet->codigo_planilla }} - {{ $row->spreadsheet->afp_id == 1 ? 'Futuro' : 'Previsión' }} <br>
                                <small>Total ganado: </small> {{ number_format($row->spreadsheet->total, 2, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            @if($row->fpc_number)
                                @php
                                    $date = $row->date_payment_afp ? date('d/m/Y', strtotime($row->date_payment_afp)) : 'Pendiente';
                                @endphp
                                {{ $row->fpc_number }} <br> {{ $date }}
                            @endif
                        </td>
                        <td>{{ $row->gtc_number }}</td>
                        <td>
                            @if($row->deposit_number)
                                @php
                                    $date = $row->date_payment_cc ? date('d/m/Y', strtotime($row->date_payment_cc)) : 'Pendiente';
                                @endphp
                                {{ $row->deposit_number }} <br> {{ $date }}
                            @endif
                        </td>
                        <td>{{ $row->user->name }} <br> {{ date('d/m/Y H:i', strtotime($row->created_at)) }}<br><small>{{ \Carbon\Carbon::parse($row->created_at)->diffForHumans() }}</small></td>
                        <td class="no-sort no-click bread-actions text-right">
                            <a href="{{ route('payments.show', ['payment' => $row->id]) }}" title="Ver" class="btn btn-sm btn-warning view">
                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                            </a>
                            <a href="{{ route('payments.edit', ['payment' => $row->id]) }}" title="Editar" class="btn btn-sm btn-info edit">
                                <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                            </a>
                            <button type="button" onclick="deleteItem('{{ route('payments.delete', ['payment' => $row->id]) }}')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9"><h5 class="text-center">No se encontraron resultados</h5></td>
                    </tr>
                @endforelse
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

<style>

</style>

<script>
    var page = "{{ request('page') }}";
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