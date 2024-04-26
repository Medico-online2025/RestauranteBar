<div class="modal fade" id="modalChangeTable" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_change_table" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col mb-3">
                            <label for="idtable_up" class="form-label">Mesas Disponibles</label>
                            <input type="hidden" name="idtable" value="{{ $idtable }}">
                            <select name="idtable_up" id="idtable_up" class="form-control"></select>
                        </div>
    
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button class="btn btn-primary btn-save-change-table">
                                <span class="text-change-table">Guardar</span>
                                <span class="spinner-border spinner-border-sm me-1 d-none text-saving-change-table" role="status" aria-hidden="true"></span>
                                <span class="text-saving-change-table d-none">Guardando...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>