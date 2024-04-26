<div class="modal fade" id="modalAddProvider" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_save_provider" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddProviderTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="descripcion">Tipo Documento de Identidad</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-control">
                            <option value="0">[SELECCIONE]</option>
                            @foreach ($type_documents as $type_document)
                                <option value="{{ $type_document->id }}">
                                    {{ $type_document->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="dni_ruc">N&uacute;mero</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="dni_ruc">
                            <button id="wrapper-input-reniec" class="btn btn-outline-secondary waves-effect btn-search-dniruc d-none" type="button" id="button-addon2">
                                <span class="text-search">
                                    <i class="ti ti-search" style="font-size: 15px; margin-bottom: 2px;"></i> <span class="input-text-reniec"> RENIEC</span>
                                </span>

                                <span class="spinner-border spinner-border-sm text-searching d-none" role="status" aria-hidden="true"></span>
                                <span class="ml-25 align-middle text-searching d-none" style="font-size: 14px;"> <span style="margin-left: 3px;">BUSCANDO...</span></span>
                            </button>
                          </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label" for="razon_social">Nombre o Raz&oacute;n Social</label>
                        <input type="text" class="form-control" name="razon_social">
                    </div>

                    <div class="col-12 col-md-8 mb-3">
                        <label class="form-label" for="direccion">Direcci&oacute;n</label>
                        <input type="text" class="form-control" name="direccion">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label" for="telefono">Tel&oacute;fono <small class="text-danger" style="font-size: 12px;">(Opcional)</small></label>
                        <input type="text" class="form-control" name="telefono">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label" for="departamento">Departamento</label>
                        <select name="departamento" id="departamento" class="select2 select2_department form-control"></select>
                    </div>

                    <div id="wrapper_province" class="col-12 col-md-4 mb-3 d-none">
                        <label class="form-label" for="provincia">Provincia</label>
                        <select name="provincia" id="provincia" class="select2 select2_province form-control"></select>
                    </div>

                    <div id="wrapper_district" class="col-12 col-md-4 mb-3 d-none">
                        <label class="form-label" for="distrito">Distrito</label>
                        <select name="distrito" id="distrito" class="select2 select2_district form-control"></select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-save-provider">
                            <span class="text-save-provider">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-saving-provider" role="status"
                                aria-hidden="true"></span>
                            <span class="text-saving-provider d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
