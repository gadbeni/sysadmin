{{-- Modal add file --}}
<form class="form-submit" id="add-file-form" action="#" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal modal-primary fade" tabindex="-1" id="modal-add-file" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-file-text"></i> Agregar documentación</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Título</label>
                        <input type="text" name="title" class="form-control" required >
                    </div>
                    <div class="form-group">
                        <label>Archivo</label>
                        <input type="file" name="file" class="form-control" accept="application/pdf" required>
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