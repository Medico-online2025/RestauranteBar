<div class="modal fade" id="modalConfirmSale" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form_save" class="modal-content" onsubmit="event.preventDefault()">
            @csrf
            <div class="modal-header">
                <h4 class="modal-title" id="modalConfirmSaleTitle">Procesar Pago</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-save-sale" method="POST">
                  @csrf
                  <input type="hidden" name="idorder">
                  <div class="row">
                    <div class="col-xl-8 col-md-12 mb-3 mb-xl-0">
                        <!-- Choose Delivery -->
                        <p>Documento</p>
                        <div class="row mt-2">
                        <input type="hidden" name="iddocumento_tipo" value="2">
                          <div class="col-md mb-md-0 mb-2">
                            <div class="form-check custom-option custom-option-icon position-relative checked">
                              <label class="form-check-label custom-option-content btn-type-document" for="boleta">
                                <span class="custom-option-body">
                                  <span class="custom-option-title mb-1">BOLETA</span>
                                </span>
                                <input id="boleta" class="form-check-input" type="radio" value="2" name="type_document" checked="">
                              </label>
                            </div>
                          </div>
                          <div class="col-md mb-md-0 mb-2">
                            <div class="form-check custom-option custom-option-icon position-relative">
                              <label class="form-check-label custom-option-content btn-type-document" for="factura">
                                <span class="custom-option-body">
                                  <span class="custom-option-title mb-1">FACTURA</span>
                                </span>
                                <input id="factura"  class="form-check-input" type="radio" value="1" name="type_document">
                              </label>
                            </div>
                          </div>
                          <div class="col-md">
                            <div class="form-check custom-option custom-option-icon position-relative">
                              <label class="form-check-label custom-option-content btn-type-document" for="nota_venta">
                                <span class="custom-option-body">
                                  <span class="custom-option-title mb-1">NOTA</span>
                                </span>
                                <input id="nota_venta" class="form-check-input" type="radio" value="7" name="type_document">
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-xl-4 col-md-12 mb-3 mb-xl-0">
                        <p>Serie</p>
                        <div class="bg-lighter rounded p-4 text-center">
                            <input type="hidden"name="serie_sale">
                            <h4 id="serie-sale" class="fw-medium mb-2"></h4>
                        </div>
                      </div>

                    <div class="col-12 mt-4 mb-4">
                        <div class="form-group">
                            <label class="form-label" for="dni_ruc">Cliente</label>
                            <small class="text-primary fw-bold btn-create-client" style="cursor: pointer">[+
                                Nuevo]</small>
                            <select class="select2-size-sm form-control" id="dni_ruc" name="dni_ruc">
                                <option></option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->dni_ruc . ' - ' . $client->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-12 col-sm-7 col-md-12 col-lg-7">
                      <div class="form-group"><label class="form-label">Forma de Pago</label>
                          <div class="row mb-3 shadow-payment">
                              <div class="col-6">
                                  <div class="form-group mb-1">
                                      <div class="el-select el-select--small" required="required">
                                          <div class="el-input el-input--small is-disabled el-input--suffix">
                                              <select class="form-control" id="modo_pago" name="modo_pago">
                                                  @foreach ($modo_pagos as $modo_pago)
                                                      <option value="{{ $modo_pago->id }}">{{ $modo_pago->descripcion }}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-6">
                                  <div class="form-group mb-1">
                                      <div class="text-end el-input-number el-input-number--small"  required="required">
                                          <div class="el-input el-input--small">
                                              <input type="text" autocomplete="off" max="Infinity" min="0" class="form-control" role="spinbutton" name="quantity_paying">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="row mb-3 shadow-payment">
                              <div class="col-6">
                                  <div class="form-group mb-1">
                                      <div class="el-select el-select--small" required="required">
                                          <div class="el-input el-input--small is-disabled el-input--suffix">
                                              <select class="form-control" id="modo_pago_2" name="modo_pago_2">
                                                  @foreach ($modo_pagos as $modo_pago)
                                                      <option value="{{ $modo_pago->id }}">{{ $modo_pago->descripcion }}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-6">
                                  <div class="form-group mb-1">
                                      <div class="text-end el-input-number el-input-number--small"  required="required">
                                          <div class="el-input el-input--small">
                                              <input type="text" autocomplete="off" max="Infinity" min="0" class="form-control" role="spinbutton" name="quantity_paying_2" value="0">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="row mb-3 shadow-payment">
                              <div class="col-6">
                                  <div class="form-group mb-1">
                                      <div class="el-select el-select--small" required="required">
                                          <div class="el-input el-input--small is-disabled el-input--suffix">
                                              <select class="form-control" id="modo_pago_3" name="modo_pago_3">
                                                  @foreach ($modo_pagos as $modo_pago)
                                                      <option value="{{ $modo_pago->id }}">{{ $modo_pago->descripcion }}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-6">
                                  <div class="form-group mb-1">
                                      <div class="text-end el-input-number el-input-number--small"  required="required">
                                          <div class="el-input el-input--small">
                                              <input type="text" autocomplete="off" max="Infinity" min="0" class="form-control" role="spinbutton" name="quantity_paying_3" value="0">
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-12 col-sm-5 col-md-12 col-lg-5">
                      <div class="form-group text-end h-100"><label
                              class="control-label fw-bold">&nbsp;</label>
                          <div class="card shadow-none mb-0" style="height: calc(100% - 35px);">
                              <div id="card-body-totals" class="card-body d-flex align-items-end">
                                  <div class="w-100" style="margin-bottom: 10px">
                                      <div class="row">
                                          <div class="col-6 pr-sm-0"><span
                                                  class="text-primary fw-bold">Total:</span></div>
                                          <div class="col-6 pl-sm-0"><span class="fw-bold">S/ <span id="total_pay"></span></span>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-6 pr-sm-0"><span
                                                  class="text-info fw-bold">Pagando:</span></div>
                                          <div class="col-6 pl-sm-0"><span class="fw-bold">S/ <span id="total_paying"></span></span>
                                          </div>
                                      </div>
                                      <hr class="my-1 line-total">
                                      <div class="row">
                                          <div class="col-6 pr-sm-0">
                                              <span class="fw-bold text-success wrapper_difference">Diferencia:</span>
                                          </div>
                                          <div class="col-6 pl-sm-0">
                                              <span class="fw-bold text-success wrapper_difference">S/ <span id="difference"></span></span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>

                    <div class="col-12 mt-3 text-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary btn-confirm-pay">
                            <span class="text-confirm-pay">Generar</span>
                            <span class="spinner-border spinner-border-sm me-1 d-none text-confirm-payment" role="status"
                                aria-hidden="true"></span>
                            <span class="text-confirm-payment d-none">Generando...</span>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </form>
    </div>
</div>