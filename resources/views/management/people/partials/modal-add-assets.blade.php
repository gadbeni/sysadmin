{{-- Modal add file --}}
<form class="form-submit" id="add-assets-form" action="#" method="post">
    @csrf
    <div class="modal modal-primary fade modal-option" tabindex="-1" id="modal-add-assets" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-tag"></i> Agregar custodio</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>N&deg; de acta</label>
                            <input type="text" name="code" class="form-control" placeholder="001/2023" required >
                        </div>
                        <div class="form-group col-md-6">
                            <label>Fecha</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="signature_id">Entrega conforme</label>
                            <select name="signature_id[]" id="select-signature_id" class="form-control" multiple required></select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Lista de activos</label>
                            <select name="" id="select-asset_id" class="form-control"></select>
                        </div>
                        <div class="form-group col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>N&deg;</th>
                                        <th style="width: 100px">Código</th>
                                        <th>Detalle</th>
                                        <th style="width: 115px">Estado</th>
                                        <th style="width: 250px">Observaciones</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="assets-list">
                                    <tr id="tr-empty">
                                        <td colspan="5" class="text-center">Ningun activo seleccionado</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Observaciones</label>
                            <textarea name="observations" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group col-md-12 text-right">
                            <label class="checkbox-inline"><input type="checkbox" value="1" name="check" required> Aceptar y guardar</label>
                        </div>
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