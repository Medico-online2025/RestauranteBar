@extends('admin.template')
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="card-title mb-0">Gesti&oacute;n de Boletas y Facturas</h5>
                    </div>
                    <a href="{{ route('admin.pos') }}" class="dt-button create-new btn btn-primary waves-effect waves-light window-open-pos" data-iduser="{{ Auth::user()['id'] }}" data-idcash="{{ Auth::user()['idcaja'] }}">
                        <span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Nuevo</span></span>
                    </a>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%" class="text-center">Documento</th>
                                    <th width="10%" class="text-center">Fecha</th>
                                    <th width="10%" class="text-left">RUC/DNI</th>
                                    <th class="text-left">Cliente</th>
                                    <th width="9%" class="text-center">Total</th>
                                    <th width="4%" class="text-center">XML</th>
                                    <th width="4%" class="text-center">CDR</th>
                                    <th width="7%" class="text-center">SUNAT</th>
                                    <th width="7%" class="text-center">Comprobante</th>
                                    <th width="10%" class="text-center">Acciones
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.billings.modals')
        @include('admin.billings.modal-send-wpp')
    </div>
@endsection
@section('scripts')
    @include('admin.billings.js-datatable')
    @include('admin.billings.js-store')
@endsection