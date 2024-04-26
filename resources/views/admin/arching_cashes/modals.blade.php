<div class="modal fade" id="modalArchingCash" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalArchingCashTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="cajero" class="form-label">Responsable</label>
                        <input type="hidden" name="idusuario" value="{{ Auth::user()['id'] }}" />
                        <input type="hidden" name="idcaja" value="{{ Auth::user()['idcaja'] }}" />
                        <input class="form-control text-uppercase" id="cajero" type="text" name="cajero"
                            value="{{ mb_strtoupper(Auth::user()['user']) }}" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="monto_inicial">Monto Inicial</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon11">S/</span>
                            <input type="text" id="monto_inicial" class="form-control" name="monto_inicial" autocomplete="off">
                            <div class="invalid-feedback">El campo no debe estar vacío.</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="fecha">Fecha de Emisi&oacute;n</label>
                        <input class="form-control" id="fecha" type="date"name="fecha"
                            value="{{ date('Y-m-d') }}" />
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


<div class="modal fade" id="modalDetailArchingCash" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailArchingCashTitle">Detalle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive-sm">
                            <table id="table_detail" class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th width="7%">#</th>
                                        <th class="sort">Fecha</th>
                                        <th class="sort">Hora</th>
                                        <th class="sort">Cliente</th>
                                        <th class="sort">Documento</th>
                                        <th class="sort">Número</th>
                                        <th>Monto S/</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-3">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalArchingCash" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalArchingCashTitle">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="cajero" class="form-label">Responsable</label>
                        <input type="hidden" name="idusuario" value="{{ Auth::user()['id'] }}" />
                        <input type="hidden" name="idcaja" value="{{ Auth::user()['idcaja'] }}" />
                        <input class="form-control text-uppercase" id="cajero" type="text" name="cajero"
                            value="{{ mb_strtoupper(Auth::user()['user']) }}" />
                        <div class="invalid-feedback">El campo no debe estar vacío.</div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="monto_inicial">Monto Inicial</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon11">S/</span>
                            <input type="text" id="monto_inicial" class="form-control" name="monto_inicial">
                            <div class="invalid-feedback">El campo no debe estar vacío.</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label" for="fecha">Fecha de Emisi&oacute;n</label>
                        <input class="form-control" id="fecha" type="date"name="fecha"
                            value="{{ date('Y-m-d') }}" />
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
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


<div class="modal fade" id="modalSummary" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title" id="modalSummaryTitle">Resumen</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 card-body">
                        <div class="bg-lighter p-4 rounded">
                            <p class="mb-1">Total de Ventas</p>
                            <div class="d-flex align-items-center">
                                <h1 class="text-heading display-6 mb-1 sales_amount"></h1>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <p class="mb-0">Monto inicial</p>
                                <h6 class="mb-0 starting_amount"></h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <p class="mb-0">Gastos</p>
                                <h6 class="mb-0 bill_empty"></h6>
                            </div>
                            <div id="wrapper_bills"></div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <p class="mb-0">Monto de ventas</p>
                                <h6 class="mb-0 sale_empty"></h6>
                            </div>
                            <div id="wrapper_sales"></div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <p class="mb-0">Cantidad de ventas</p>
                                <h6 class="mb-0 quantity"></h6>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mt-3 pb-1">
                                <p class="mb-0">Total</p>
                                <h6 class="mb-0 total"></h6>
                            </div>

                            <p class="mt-2 pt-2 text-justify">El <b>total</b> refiere al monto de ventas m&aacute;s el monto inicial menos los gastos.</p>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-3">
                        <button type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
