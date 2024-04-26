<div class="modal fade" id="modalAddBill" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddBillTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="fecha_emision">Fecha de Emisi&oacute;n</label>
                        <input class="form-control" id="fecha_emision" type="date"name="fecha_emision"
                            value="{{ date('Y-m-d') }}" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="idpurchase_description">Motivo</label>
                        <select class="form-control" id="idpurchase_description" name="idpurchase_description">
                            @foreach ($purchase_descriptions as $purchase_description)
                                <option value="{{ $purchase_description->id }}">{{ $purchase_description->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="cuenta">Cuenta</label>
                        <select class="form-control" id="cuenta" name="cuenta" disabled>
                            <option value="Cuenta General">Cuenta General</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="monto">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon11">S/</span>
                            <input type="text" id="monto" class="form-control" name="monto">
                            <div class="invalid-feedback">El campo no debe estar vacío.</div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="detalle">Detalle</label>
                        <textarea name="detalle" id="detalle" cols="8" rows="3" class="form-control text-uppercase"></textarea>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save">
                            <span class="text-save">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving" role="status"
                                aria-hidden="true"></span>
                            <span class="text-saving d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
