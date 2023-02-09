{{-- Single delete modal --}}
<form action="{{ isset($action) ? $action : '#' }}" method="POST" class="" id="my-awesome-dropzone " enctype="multipart/form-data">
    <div class="modal fade" tabindex="-1" id="modal-upload" role="dialog">
        <div class="modal-dialog modal-success">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-upload"></i> {{ isset($title) ? $title : 'Agregar archivo' }}</h4>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" value="{{ isset($id) ? $id : 0 }}">
                    <input type="file" name="file" id="" accept="application/pdf">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Subir archivo</button>
                </div>
            </div>
        </div>
    </div>
</form>