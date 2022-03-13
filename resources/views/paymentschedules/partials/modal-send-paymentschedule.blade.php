{{-- send modal --}}
<form id="form-send" action="{{ route('paymentschedules.update.status') }}" method="POST">
    @csrf
    <input type="hidden" name="id">
    <input type="hidden" name="status" value="enviada">
    <div class="modal modal-primary fade" tabindex="-1" id="send-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="glyphicon glyphicon-share-alt"></i> Desea enviar la siguiente planilla para su aprobación?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-dark" value="Sí, enviar">
                </div>
            </div>
        </div>
    </div>
</form>