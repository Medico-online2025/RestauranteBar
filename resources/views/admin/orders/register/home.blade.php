@extends('admin.template')
@section('content')
    <div class="row invoice-preview" style="max-width: 1600px;">
        <!-- Invoice -->
        <div class="col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body" style="height: 83vh;">
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
        <div class="col-12 invoice-actions">
            <div class="card invoice-preview-card">
                <div class="card-body" style="padding: 1rem 1rem;">
                    <div
                        class="d-flex justify-content-end flex-xl-row flex-md-column flex-sm-row flex-column m-sm-3 m-0">
                        <div class="mb-xl-0 mb-0">
                            <div class="d-flex svg-illustration mb-2 gap-2 align-items-center"
                                style="justify-content: flex-end;">
                                <span class="fw-bold fs-4">Registrar Pedido</span>
                            </div>
                            <p class="mb-1 text-end text-primary fw-bold">{{ $table->descripcion }}</p>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div style="height: 38.5vh;">
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
                        <form id="form_gen_order" method="POST">
                            @csrf
                            <div>
                                <div class="row p-0">
                                    <div class="col-md-6 mb-md-0">
                                        <div class="d-flex align-items-center">
                                        <label for="salesperson" class="form-label me-4 fw-medium">Información Adicional:</label>
                                        </div>
                                        <input type="hidden" name="idtable" value="{{ $idtable }}">
                                        <textarea class="form-control text-uppercase" cols="8" rows="3" name="observaciones"></textarea>
                                    </div>
                                    
                                    <div id="wrapper-totals" class="col-md-6 d-flex justify-content-end mt-3"></div>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle waves-effect waves-light me-2" data-bs-toggle="dropdown" aria-expanded="false">M&aacute;s opciones</button>
                                    <ul class="dropdown-menu" style="">
                                      <li><a class="dropdown-item" href="{{ route('admin.create_order') }}">Ir a Mesas</a></li>
                                      <li><a class="dropdown-item" href="{{ route('admin.orders') }}">Ir a Pedidos</a></li>
                                    </ul>
                                </div>

                                <button class="btn btn-danger waves-effect waves-light btn-cancel-order">
                                    <span class="me-2">Cancelar venta</span>
                                </button>

                                <button class="btn btn-success waves-effect waves-light btn-process-order" style="margin-left: 5px;">
                                    <span class="me-2 text-process">Generar Pedido</span>
                                    <span class="spinner-border spinner-border-sm me-1 d-none text-processing" role="status"
                                        aria-hidden="true"></span>
                                    <span class="text-processing d-none">Espere...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
            
        </div>

        

        <!-- /Invoice Actions -->
        @include('admin.orders.register.modals')
        @include('admin.clients.modal-register')
        @include('admin.products.modal-register')
    </div>
@endsection
@section('scripts')
    @include('admin.clients.js-register')
    @include('admin.products.js-register')
    @include('admin.orders.register.js-home')
@endsection
