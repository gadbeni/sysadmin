{{-- send modal --}}
<form id="form-close" class="form-submit" action="{{ route('paymentschedules.update.status') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ isset($id) ? $id : 0  }}">
    <input type="hidden" name="status" value="pagada">
    <div class="modal fade submit-modal" tabindex="-1" id="close-modal" role="dialog">
        <div class="modal-dialog modal-primary">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-lock"></i> Desea cerrar la siguiente planilla?</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <p class="text-muted">
                            Al realizar ésta acción la planilla seleccionada podrá pasar a la instancia correspondiente, no se necesita que el total de los funcionarios que la componen hayan realizado cobro.
                        </p>
                    </div>
                    {{-- El administrador puede cerrar una planilla y cambiar el estado a pagado a los funcionarios que la incluyen --}}
                    {{-- @if (Auth::user()->role_id == 1) --}}
                    <label class="checkbox-inline"><input type="checkbox" name="pay_all" value="1" checked>Pagar todos los funcionarios que se incluyen esta planilla</label>
                    {{-- @endif --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-primary" value="Sí, cerrar">
                </div>
            </div>
        </div>
    </div>
</form>