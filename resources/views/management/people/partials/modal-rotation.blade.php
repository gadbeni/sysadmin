@php
    $people =   App\Models\Person::whereHas('contracts', function($query){
                        $query->where('status', 'firmado')->where('deleted_at', NULL);
                    })->where('deleted_at', NULL)->get();
@endphp
<form class="form-submit" id="rotation-form" action="#" method="post">
    @csrf
    <div class="modal modal-primary fade modal-option" tabindex="-1" id="modal-rotation" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-forward"></i> Realizar rotación</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Fecha de rotación</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required >
                    </div>
                    <div class="form-group">
                        <label>Solicitante</label>
                        <select name="destiny_id" id="select-destiny_id" class="form-control" required>
                            <option selected disabled value="">--Seleccione al funcionario--</option>
                            @foreach ($people as $item)
                                <option value="{{ $item->id }}">{{ $item->first_name }} {{ $item->last_name }} - CI:{{ $item->ci }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Destino</label>
                        <select name="destiny_dependency" id="select-destiny_dependency" class="form-control" required>
                            <option selected disabled value="">--Seleccione el destino--</option>
                            @foreach (\App\Models\Direccion::where('Estado', 1)->get() as $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                            @foreach (\App\Models\Unidad::where('Estado', 1)->get() as $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Responsable</label>
                        <select name="responsible_id" id="select-responsible_id" class="form-control">
                            <option selected disabled value="">--Seleccione al reponsable--</option>
                            @foreach ($people as $item)
                                <option value="{{ $item->id }}">{{ $item->first_name }} {{ $item->last_name }} - CI:{{ $item->ci }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observations" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-dark btn-submit" value="Guardar">
                </div>
            </div>
        </div>
    </div>
</form>