@extends('admin.pos.template')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" style="max-width: 1530px">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-md-6 col-12 mb-md-0 mb-4">
                <div class="card invoice-preview-card">
                    <div class="card-body" style="height: 85vh;">
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon-search31">
                                        <i class="ti ti-barcode"></i>
                                    </span>
                                    <input type="text" class="form-control input-search-product"
                                        placeholder="Buscar por nombre, código o código de barras"
                                        name="input-search-product" autocomplete="off">
                                    <span class="input-group-text btn-create-product" id="basic-addon11" style="cursor: pointer;"
                                        data-bs-toggle="tooltip" data-bs-original-title="Crear nuevo producto">
                                        <i class="ti ti-plus"></i>
                                    </span>
                                    <span class="input-group-text text-danger btn-clear-input" id="basic-addon11" style="cursor: pointer;"
                                        data-bs-toggle="tooltip" data-bs-original-title="Limpiar descripción">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                        width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="feather feather-x align-middle mr-25">
                                        <line x1="18" y1="6" x2="6"
                                            y2="18"></line>
                                        <line x1="6" y1="6" x2="18"
                                            y2="18"></line>
                                    </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div id="content-pos-product" class="pos mt-3 p-3 rounded overflow-auto"
                            style="height: calc(100% - 40px);">
                            <div id="wrapper-products" class="row"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Invoice -->

            

            <!-- Invoice Actions -->
            <div class="col-md-6 col-12 invoice-actions">
                <div class="card invoice-preview-card">
                    <div class="card-body" style="padding: 1rem 1rem;">
                        <div
                            class="d-flex justify-content-end flex-xl-row flex-md-column flex-sm-row flex-column m-sm-3 m-0">
                            <div class="mb-xl-0 mb-0">
                                <div class="d-flex svg-illustration mb-2 gap-2 align-items-center"
                                    style="justify-content: flex-end;">
                                    <div class="app-brand-logo demo">
                                        <img src="{{ asset('assets/img/icons/icon-login.svg') }}" alt=""
                                            class="img-fluid">
                                    </div>
                                    <span class="fw-bold fs-4">{{ $business->razon_social }}</span>
                                </div>

                                <p class="mb-1 text-end">R.U.C. {{ $business->ruc }}</p>
                                <p class="mb-1 text-end">{{ $ubigeo["distrito"] }} - {{ $ubigeo["provincia"] }} - {{ $ubigeo["departamento"] }}</p>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div style="height: 37vh;">
                        <div class="pos table-responsive-sm border-top pos overflow-auto"
                            style="height: calc(100% - 0.5rem); ">
                            <table class="table m-0" style="font-size: 12.5px;">
                                <thead>
                                    <tr>
                                        <th width="8%" class="text-center">#</th>
                                        <th class="text-left" width="60%">Descripción&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th class="text-center" width="13%">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cantidad&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </th>
                                        <th class="text-center" width="14%">Precio Unitario</th>
                                        <th class="text-center" width="10%">Importe</th>
                                        <th class="text-right" width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="wrapper-tbody-pos"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card invoice-preview-card mt-2">
                    <div class="pos table-responsive-sm border-top">
                        <div class="card-body">
                            <div>
                                <div id="wrapper-totals"></div>
                                <div class="mt-3 d-flex justify-content-end">
                                    <button class="btn btn-danger waves-effect waves-light btn-cancel-pay">
                                        <span class="me-2">Cancelar venta</span>
                                    </button>

                                    <button class="btn btn-success waves-effect waves-light btn-process-pay" style="margin-left: 5px;">
                                        <span class="me-2 text-process">Procesar Pago</span>
                                        <span class="spinner-border spinner-border-sm me-1 d-none text-processing" role="status"
                                            aria-hidden="true"></span>
                                        <span class="text-processing d-none">Espere...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                
            </div>

            

            <!-- /Invoice Actions -->
            @include('admin.pos.modals')
            @include('admin.clients.modal-register')
            @include('admin.products.modal-register')
        </div>
    </div>
@endsection
@section('scripts')
    @include('admin.clients.js-register')
    @include('admin.products.js-register')
    @include('admin.pos.js-home')
@endsection
