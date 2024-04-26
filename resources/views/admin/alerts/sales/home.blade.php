@extends('admin.template')
@section('styles')
    <style>
        body {
            overflow-x: hidden;
        }
    </style>
@endsection
@section('content')
    <section class="basic-select2">
        <div class="row">
            <!-- Congratulations Card -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">* Se visualiza la lista de comprobantes que a&uacute;n no han sido informados a SUNAT y que est&aacute;n por vencer.</h6>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="table-responsive-sm mt-3">
                                    <table class="table table-sm mb-0 fs--1">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="7%">#</th>
                                                <th class="text-center" width="13%">Fecha Emisi&oacute;n</th>
                                                <th class="text-center" width="12%">Documento</th>
                                                <th class="text-center" width="12%">RUC/DNI</th>
                                                <th class="text-left">Raz&oacute;n Social</th>
                                                <th class="text-center" width="20%">D&iacute;as para vencer</th>
                                                <th class="text-center" width="13%">Enviar a SUNAT</th>
                                            </tr>
                                        </thead>
                                        <tbody id="wrapper_tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    @include('admin.alerts.sales.js-home')
@endsection
