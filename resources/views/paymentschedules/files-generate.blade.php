<div class="row">
    <div class="col-md-12">
        <div class="panel panel-bordered">
            <div class="panel-body">
                @if ($payment_schedules_file->status == 'borrador')
                <div class="table-responsive">
                    <h3>{{ ucfirst($payment_schedules_file->type) }} @isset($draft) <label class="label label-danger">Borrador</label> @endisset </h3> <br>
                    @if ($payment_schedules_file->type == 'rc-iva')
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>N&deg;</th>
                                    <th>Nombre completo</th>
                                    <th>CI</th>
                                    <th>Sueldo</th>
                                    <th>IVA</th>
                                    <th>IUE</th>
                                    <th>IT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $cont = 1;
                                @endphp
                                @forelse ($payment_schedules_file->details as $item)
                                    @php
                                        $data = json_decode($item->details);
                                    @endphp
                                    <tr>
                                        <td>{{ $cont }}</td>
                                        <td>{{ $item->person->last_name }} {{ $item->person->first_name }}</td>
                                        <td>{{ $item->person->ci }}</td>
                                        <td>{{ number_format($data->salary, 2, ',', '.') }}</td>
                                        <td>{{ $data->iva }}</td>
                                        <td>{{ $data->iue }}</td>
                                        <td>{{ $data->it }}</td>
                                    </tr>
                                    @php
                                        $cont++;
                                    @endphp
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>N&deg;</th>
                                    <th>Nombre completo</th>
                                    <th>CI</th>
                                    <th>Faltas</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $cont = 1;
                                @endphp
                                @forelse ($payment_schedules_file->details as $item)
                                    @php
                                        $data = json_decode($item->details);
                                    @endphp
                                    <tr>
                                        <td>{{ $cont }}</td>
                                        <td>{{ $item->person->last_name }} {{ $item->person->first_name }}</td>
                                        <td>{{ $item->person->ci }}</td>
                                        <td>{{ $data->faults }}</td>
                                        <td>{{ $data->observations }}</td>
                                    </tr>
                                    @php
                                        $cont++;
                                    @endphp
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>
                @else
                    <div class="alert alert-danger">
                        <h4 class="text-center">Ya existe un archivo {{ $payment_schedules_file->type }} subido y aprobado</h4>
                    </div>
                @endif
            </div>
            <div class="panel-footer text-right">
                @if ($payment_schedules_file->status == 'borrador')
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_modal">Anular</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#store_modal">Guardar</button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- store modal --}}
<div class="modal fade" tabindex="-1" id="store_modal" role="dialog">
    <div class="modal-dialog modal-primary">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-edit"></i> Desea guardar los registros?</h4>
            </div>
            <div class="modal-body">
                <p class="text-muted">Si realiza esta acción no podrá deshacer los cambios, desea continuar?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('paymentschedules.files.store') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $payment_schedules_file->id }}">
                    <input type="submit" class="btn btn-primary pull-right delete-confirm" value="Sí, guardar">
                </form>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

{{-- delete modal --}}
<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-trash"></i> Desea anular el registro?</h4>
            </div>
            <div class="modal-footer">
                <form action="{{ route('paymentschedules.files.delete') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $payment_schedules_file->id }}">
                    <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, anular">
                </form>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* th{
        font-size: 10px !important
    }
    td{
        font-size: 13px !important
    } */
</style>