@extends('admin.template')
@section('styles')
    <style>
        body
        {overflow-x:hidden;}
    </style>
@endsection
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="card-title mb-0">Gesti&oacute;n de Productos</h5>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons d-inline-flex">
                            <button class="dt-button create-new btn btn-primary waves-effect waves-light btn-create-product" style="margin-right: 3px;" tabindex="0"><span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Nuevo</span></span></button>
                            <button class="dt-button create-new btn btn-success waves-effect waves-light btn-upload ml-2" tabindex="0"><span><i class="ti ti-upload me-sm-1"></i><span class="d-none d-sm-inline-block">Cargar Excel</span></span></button>
                        </div>
                    </div>
                    
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Descripci&oacute;n</th>
                                    <th width="10%">Und.</th>
                                    <th width="15%">Precio Compra</th>
                                    <th width="13%">Precio Venta</th>
                                    <th width="10%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.products.modal-register')
        @include('admin.products.modals')
    </div>
@endsection
@section('scripts')
    @include('admin.products.js-datatable')
    @include('admin.products.js-register')
    @include('admin.products.js-store')
@endsection
