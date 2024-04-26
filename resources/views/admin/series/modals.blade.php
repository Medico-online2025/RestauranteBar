<div class="modal fade" id="modalAddSerie" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddSerieTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="serie" class="form-label">Serie</label>
                        <input type="text" id="serie" class="form-control text-uppercase" name="serie"
                            placeholder="Ejem. F001" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="correlativo" class="form-label">Correlativo</label>
                        <input type="email" id="correlativo" class="form-control text-uppercase" name="correlativo"
                            placeholder="Ejem. 00000001" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="tipo_documento">Tipo de Comprobante</label>
                        <select class="form-control" id="tipo_documento" name="tipo_documento">
                            @foreach ($type_documents as $type_document)
                                <option value="{{ $type_document->id }}">{{ $type_document->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="idcaja">Caja</label>
                        <select class="form-control" id="idcaja" name="idcaja">
                            @foreach ($cashes as $cash)
                                <option value="{{ $cash->id }}">{{ $cash->descripcion }}</option>
                            @endforeach
                        </select>
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

<div class="modal fade" id="modalEditSerie" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_edit" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditSerieTitle">Actualizar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="serie" class="form-label">Serie</label>
                        <input type="hidden" name="id">
                        <input type="text" id="serie" class="form-control text-uppercase" name="serie"
                            placeholder="Ejem. F001" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="correlativo" class="form-label">Correlativo</label>
                        <input type="email" id="correlativo" class="form-control text-uppercase" name="correlativo"
                            placeholder="Ejem. 00000001" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="tipo_documento">Tipo de Comprobante</label>
                        <select class="form-control" id="tipo_documento" name="tipo_documento">
                            @foreach ($type_documents as $type_document)
                                <option value="{{ $type_document->id }}">{{ $type_document->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="idcaja">Caja</label>
                        <select class="form-control" id="idcaja" name="idcaja">
                            @foreach ($cashes as $cash)
                                <option value="{{ $cash->id }}">{{ $cash->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-store">
                            <span class="text-store">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-storing" role="status"
                                aria-hidden="true"></span>
                            <span class="text-storing d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
