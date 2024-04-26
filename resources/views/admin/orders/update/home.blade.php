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
                                <span class="fw-bold fs-4">Actualizar Pedido</span>
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
                                    <th class="text-left" width="60%">Descripción&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th class="text-center" width="13%">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cantidad&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </th>
                                    <th class="text-center" width="14%">Precio Unitario</th>
                                    <th class="text-center" width="10%">Importe</th>
                                    <th class="text-right" width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="wrapper-tbody-pos">
                                @foreach ($detalle as $i => $product)
                                <tr id="tr__product__{{ $product['idproducto'] }}">
                                    <td class="d-none"><input type="hidden" name="idproducto" value="{{ $product['idproducto'] }}"></td>
                                    <td class="text-left">{{ $product['producto'] }}</td>
                                    <td class="text-right">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text btn-down" style="cursor: pointer;" data-id="{{ $product['idproducto'] }}" data-cantidad="{{ $product['cantidad'] }}" data-precio="{{ number_format($product['precio_unitario'], 2, '.', '') }}" data-idtable="{{ $idtable }}"><i class="ti ti-minus me-sm-1"></i></span>
                                            <input type="text" data-id="{{ $product['idproducto'] }}" class="quantity-counter text-center form-control" value="{{ intval($product['cantidad']) }}" data-idtable="{{ $idtable }}" name="input-cantidad">
                                            <span class="input-group-text btn-up" style="cursor: pointer;" data-id="{{ $product['idproducto'] }}" data-cantidad="{{ $product['cantidad'] }}" data-precio="{{ number_format($product['precio_unitario'], 2, '.', '') }}" data-idtable="{{ $idtable }}"><i class="ti ti-plus me-sm-1"></i></span>
                                        </div>
                                    </td>
                                    <td class="text-center"><input type="text" class="form-control form-control-sm text-center" value="{{ number_format($product['precio_unitario'], 2, '.', '') }}" data-cantidad="{{ $product['cantidad'] }}" data-codigo_igv="{{ $product['codigo_igv'] }}" data-impuesto="{{ $product['impuesto'] }}" data-id="{{ $product['idproducto'] }}" data-idtable="{{ $idtable }}" name="input-precio"></td>
                                    <td class="text-center">{{ number_format(($product["precio_unitario"] * $product["cantidad"]), 2, ".", "") }}</td>
                                    <td class="text-center"><span data-id="{{ $product['idproducto'] }}" data-idtable="{{ $idtable }}" class="text-danger btn-delete-product-cart" style="cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x align-middle mr-25"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span></td>
                                </tr>
                                @endforeach
                            </tbody>
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
                                        <textarea class="form-control text-uppercase" cols="8" rows="3" name="observaciones">{{ $order->observaciones }}</textarea>
                                    </div>
                                    
                                    <div id="wrapper-totals" class="col-md-6 d-flex justify-content-end mt-3">
                                        <div class="invoice-calculations">
                                            <span class="d-none span__exonerada"></span>
                                            <span class="d-none span__gravada"></span>
                                            <span class="d-none span__inafecta"></span>
                                            <div class="d-flex justify-content-between">
                                                <span class="w-px-100">OP. Gravadas:</span>
                                                <span class="fw-medium">S/ <span class="span__subtotal">{{ number_format(($order->exonerada + $order->gravada + $order->inafecta), 2) }}</span> </span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="w-px-100">IGV:</span>
                                                <span class="fw-medium">S/<span class="span__igv">{{ $order->igv }}</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <span class="w-px-100">Total:</span>
                                                <span class="fw-medium">S/<span class="span__total">{{ number_format($order->total, 2, ".", " ") }}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" aria-expanded="false">M&aacute;s opciones</button>
                                    <ul class="dropdown-menu" style="">
                                      <li><a class="dropdown-item btn-change-table" href="javascript:void(0);">Cambiar de Mesa</a></li>
                                      <li><a class="dropdown-item" href="{{ route('admin.create_order') }}">Ir a Mesas</a></li>
                                      <li><a class="dropdown-item" href="{{ route('admin.orders') }}">Ir a Pedidos</a></li>
                                    </ul>
                                </div>

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
        @include('admin.orders.update.modals')
        @include('admin.clients.modal-register')
        @include('admin.products.modal-register')
    </div>
@endsection
@section('scripts')
    @include('admin.clients.js-register')
    @include('admin.products.js-register')
    @include('admin.orders.update.js-home')
@endsection
