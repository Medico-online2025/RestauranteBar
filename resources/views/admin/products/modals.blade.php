<div class="modal fade" id="modalEditProduct" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_edit_product" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditProductTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label" for="codigo_interno">C&oacute;digo Interno</label>
                        <input type="hidden" name="id">
                        <input type="text" id="codigo_interno" class="form-control" name="codigo_interno" />
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label" for="codigo_barras">C&oacute;digo Barras</label>
                        <input type="text" id="codigo_barras" class="form-control" name="codigo_barras" />
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label" for="idunidad">Unidad</label>
                        <select name="idunidad" id="idunidad" class="form-control">
                            <option value=""></option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label" for="descripcion">Descripci&oacute;n</label>
                        <input type="text" id="descripcion" class="form-control text-uppercase" name="descripcion" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="marca">Marca</label>
                        <input type="text" id="marca" class="form-control text-uppercase" name="marca" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="presentacion">Presentaci&oacute;n</label>
                        <input type="text" id="presentacion" class="form-control text-uppercase"
                            name="presentacion" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label" for="operacion">Operaci&oacute;n</label>
                        <select name="operacion" id="operacion" class="form-control">
                            @foreach ($type_inafects as $type_inafect)
                                <option value="{{ $type_inafect->id }}">{{ $type_inafect->descripcion }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="precio_compra">Precio Compra</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon11">S/</span>
                            <input type="text" id="precio_compra" class="form-control" name="precio_compra">
                            <div class="invalid-feedback">El campo no debe estar vacío.</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="precio_venta">Precio Venta</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon11">S/</span>
                            <input type="text" id="precio_venta" class="form-control" name="precio_venta">
                            <div class="invalid-feedback">El campo no debe estar vacío.</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" class="checkbox-inline" for="stock"> ¿Controlar stock? <input type="checkbox"
                                class="align-middle" name="check_stock"></label>
                        <input type="text" class="form-control" id="stock" name="stock" disabled>
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="fecha_vencimiento">Fecha de vencimiento</label>
                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                    </div>

                    <div class="col-12 mb-3">
                        <small class="fw-medium d-block"><label class="form-label">Tipo Producto</label></small>
                        <div class="form-check form-check-inline mt-3">
                            <input class="form-check-input" type="radio" name="opcion" id="producto_u" value="1" checked>
                            <label class="form-check-label" for="producto_u">Producto</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcion" id="servicio_u" value="2">
                            <label class="form-check-label" for="servicio_u">Servicio</label>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-store-product">
                            <span class="text-store-product">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-storing-product" role="status"
                                aria-hidden="true"></span>
                            <span class="text-storing-product d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Offcanvas to add new user -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="modalDetailProduct"
    aria-labelledby="modalDetailProductLabel">
    <div class="offcanvas-header">
        <h5 id="modalDetailProductLabel" class="offcanvas-title">INFORMACI&Oacute;N DE PRODUCTO</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
        <div class="price-details">
            <ul class="list-unstyled">
                <li class="price-detail">
                    <div class="detail-title fw-semibold detail-total">C&oacute;digo</div>
                    <div class="detail-code"></div>
                </li>
            </ul>
            <ul class="list-unstyled">
                <li class="price-detail">
                    <div class="detail-title fw-semibold detail-total">Nombre</div>
                    <div class="detail-description"></div>
                </li>
            </ul>
            <ul class="list-unstyled">
                <li class="price-detail">
                    <div class="detail-title fw-semibold detail-total">Marca</div>
                    <div class="detail-brand"></div>
                </li>
            </ul>
            <ul class="list-unstyled">
                <li class="price-detail">
                    <div class="detail-title fw-semibold detail-total">Presentaci&oacute;n</div>
                    <div class="detail-presentation"></div>
                </li>
            </ul>
            <ul class="list-unstyled">
                <li class="price-detail">
                    <div class="detail-title fw-semibold detail-total">Precio Compra</div>
                    <div class="detail-buy"></div>
                </li>
            </ul>
            <ul class="list-unstyled">
                <li class="price-detail">
                    <div class="detail-title fw-semibold detail-total">Precio Venta</div>
                    <div class="detail-sale"></div>
                </li>
            </ul>
            <ul class="list-unstyled">
                <li class="price-detail">
                    <div class="detail-title fw-semibold detail-total">Total Stock</div>
                    <div class="detail-stock"></div>
                </li>
            </ul>
            <ul class="list-unstyled">
                <li class="price-detail">
                    <div class="detail-title fw-semibold detail-total">Fecha Vencimiento</div>
                    <div class="detail-expiration"></div>
                </li>
            </ul>
            <hr>
        </div>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="modalUpload" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_excel" class="modal-content" onsubmit="event.preventDefault()" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalUploadTitle">Cargar Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label" for="excel">Importar documento (.xlsx)</label>
                        <input type="file" id="excel" class="form-control" name="excel" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-6 text-first mb-3">
                        <a href="{{ route('admin.download_excel') }}" class="btn btn-success btn-download-excel">
                            <i class="ti ti-download me-sm-1"></i>
                            <span class="text-download-product">Descargar Excel</span>
                            <span class="spinner-border spinner-border-sm text-downloads-product d-none"
                                role="status" aria-hidden="true"></span>
                            <span class="ml-25 align-middle text-downloads-product d-none">Descargando...</span>
                        </a>
                    </div>

                    <div class="col-6 text-end mb-3">
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary btn-upload-product">
                            <span class="text-upload-product">Guardar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-uploads-product" role="status"
                                aria-hidden="true"></span>
                            <span class="text-uploads-product d-none">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
