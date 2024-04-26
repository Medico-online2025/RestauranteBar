@extends('admin.template')
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="card-title mb-0">Gesti&oacute;n de Cotizaciones</h5>
                    </div>
                    <a href="{{ route('admin.create_quote') }}" class="dt-button create-new btn btn-primary waves-effect waves-light">
                        <span><i class="ti ti-plus me-sm-1"></i><span class="d-none d-sm-inline-block">Nuevo</span></span>
                    </a>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table id="table" class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="12%">Documento</th>
                                    <th width="11%">Fecha</th>
                                    <th width="10%">RUC/DNI</th>
                                    <th>Cliente</th>
                                    <th width="9%">Total</th>
                                    <th width="20%">Doc. Relacionado</th>
                                    <th width="8%">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.billings.modal-send-wpp')
    </div>
@endsection
@section('scripts')
    @include('admin.quotes.js-datatable')
    @include('admin.quotes.js-store')
@endsection