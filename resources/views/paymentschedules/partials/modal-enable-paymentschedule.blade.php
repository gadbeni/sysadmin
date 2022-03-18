{{-- enable modal --}}
<form id="form-enable" class="form-submit" action="{{ route('paymentschedules.update.status') }}" method="POST">
    @csrf
    <div class="modal fade submit-modal" tabindex="-1" id="enable-modal" role="dialog">
        <div class="modal-dialog modal-success">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-dollar"></i> Desea habilitar la siguiente planilla para pago?</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="{{ isset($id) ? $id : 0  }}">
                    <input type="hidden" name="status" value="habilitada">
                    <div class="form-group">
                        <label for="afp">AFP</label>
                        <select name="afp" class="form-control select2">
                            <option value="">Todas las AFP</option>
                            <option value="1">Futuro</option>
                            <option value="2">Previsón</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="observations" class="form-control" rows="5" placeholder="Observaciones"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-success" value="Sí, Habilitar">
                </div>
            </div>
        </div>
    </div>
</form>