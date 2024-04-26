@extends('admin.template')
@section('styles')
<style>
    body{overflow-x:hidden;}
</style>
@endsection
@section('content')
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header mt-2">
                    <h5 class="card-title">Generar Nota de Cr&eacute;dito</h5>
                </div>
                <div class="card-body">
                    <form id="form_save_credit_note" class="form form-vertical">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-3 mb-3">
                                <label class="form-label" for="idtipo_comprobante">Tipo Comprobante</label>
                                <input type="hidden" name="idfactura_anular" value="{{ $idfactura_anular }}">
                                <select class="form-control" id="idtipo_comprobante"
                                    name="idtipo_comprobante" disabled>
                                    @foreach ($type_documents as $type_document)
                                        <option value="{{ $type_document->id }}">{{ $type_document->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="serie">Serie</label>
                                <input type="text" id="serie" class="form-control text-uppercase"
                                    name="serie" value="{{ $serie->serie }}" />
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="correlativo">N&uacute;mero</label>
                                <input type="text" id="correlativo" class="form-control text-uppercase"
                                    name="correlativo" value="{{ $serie->correlativo }}" />
                            </div>

                            <div class="col-md-1"></div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="fecha_emision">Fecha de emisión</label>
                                <input type="date" id="fecha_emision" class="form-control"
                                    name="fecha_emision" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <label class="form-label" for="fecha_vencimiento">Fecha de vencimiento</label>
                                <input type="date" id="fecha_vencimiento" class="form-control"
                                    name="fecha_vencimiento" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-12 col-md-7 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="dni_ruc">Motivo / Sustentación</label>
                                    <input type="text" id="motivo" class="form-control text-uppercase" name="motivo" value="Anulacion de la operacion">
                                    <div class="invalid-feedback">El campo no debe quedar vacío</div>
                                </div>
                            </div>

                            <div class="col-md-1"></div>

                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label" for="tipo_nc">Tipo nota de crédito</label>
                                <select class="form-control" id="tipo_nc" name="tipo_nc">
                                    @foreach ($type_credit_notes as $type_credit_note)
                                        <option value="{{ $type_credit_note->id }}"> {{ $type_credit_note->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-7 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="cliente">Cliente</label>
                                    <input type="text" id="cliente" class="form-control" name="cliente" value="{{ $factura->cliente }}">
                                </div>
                            </div>

                            <div class="col-md-1"></div>

                            <div class="col-12 col-md-2 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="tipo_cambio">Tipo de cambio</label>
                                    <input type="text" id="tipo_cambio" class="form-control"
                                        name="tipo_cambio" value="0.00" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-md-2 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="modo_pago">Pago:</label>
                                    <select class="form-control" id="modo_pago" name="modo_pago">
                                        @foreach ($modo_pagos as $modo_pago)
                                            @if ($factura->modo_pago == $modo_pago->id)
                                                <option value="{{ $modo_pago->id }}" selected>{{ $modo_pago->descripcion }}</option>
                                            @else
                                                <option value="{{ $modo_pago->id }}">{{ $modo_pago->descripcion }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row invoice-add mt-3">
                            <div class="col-md-12">
                                <div class="table-responsive-sm">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th width="8%" class="text-center">#</th>
                                                <th class="">Descripción</th>
                                                <th class="text-center">Und.</th>
                                                <th class="text-center" width="13%">&nbsp;&nbsp;&nbsp;Cantidad&nbsp;&nbsp;&nbsp;</th>
                                                <th class="text-center" width="14%">Precio Unitario</th>
                                                <th class="text-center" width="10%">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_sale_notes">
                                            @foreach ($detalle as $i => $product)
                                            <tr>
                                                <td class="text-center">{{ $i + 1  }}</td>
                                                <td>{{ $product["producto"] }}</td>
                                                <td class="text-center">{{ $product["unidad"] }}</td>
                                                <td class="text-center">{{ $product["cantidad"] }}</td>
                                                <td class="text-center">{{ $product["precio_unitario"] }}</td>
                                                <td class="text-center">{{ number_format(($product["precio_unitario"] * $product["cantidad"]), 2, ".", "") }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12 d-flex justify-content-end -0 p-sm-4">
                                <div id="wrapper_totals" class="invoice-calculations">
                                    <div class="d-flex justify-content-between mb-2">
                                            <span class="w-px-100">OP. Gravadas:</span>
                                            <span class="fw-medium">S/{{ number_format(($factura['exonerada'] + $factura['gravada'] + $factura['inafecta']), 2, ".", "") }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="w-px-100">IGV:</span>
                                            <span class="fw-medium">S/{{ number_format($factura['igv'], 2, ".", "") }}</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span class="w-px-100">Total:</span>
                                            <span class="fw-medium">S/{{ number_format($factura['total'], 2, ".", "") }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-12 text-end">
                                    <a href="{{ route('admin.billings') }}" class="btn btn-secondary">Cancelar</a>
                                    <button type="button" class="btn btn-primary btn-save">
                                        <span class="text-save">Guardar </span>
                                        <span class="spinner-border spinner-border-sm text-saving d-none" role="status"
                                            aria-hidden="true"></span>
                                        <span class="ml-25 align-middle text-saving d-none">Guardando...</span>
                                    </button>
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@include('admin.credit_notes.js-create')
@endsection
