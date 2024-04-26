@extends('admin.template')
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="card-title mb-0">Gesti&oacute;n de Notas de Cr&eacute;dito</h5>
                    </div>
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
    </div>
@endsection
@section('scripts')
    @include('admin.credit_notes.js-datatable')
    @include('admin.credit_notes.js-store')
@endsection