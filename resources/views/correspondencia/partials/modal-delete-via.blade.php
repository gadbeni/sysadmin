<div class="modal modal-danger fade" tabindex="-1" id="delete-via-modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="voyager-trash"></i> Desea Anular esta  Via</h4>
            </div>
            <div class="modal-footer">
                <p></p>
                <form id="delete_via_form" action="{{ route('via.nulled') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}">
                    <input type="hidden" name="entrada_id" value="{{ isset($entrada_id) ? $entrada_id : '' }}">
                    <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                </form>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>