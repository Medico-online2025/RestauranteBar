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
                        <h5 class="card-title">Filtros: </h5>
                        <form id="form-sales-seller" method="POST" class="form form-vertical"
                            action="{{ route('admin.export_sales_seller') }}" target="_blank">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-2 mb-3">
                                    <label class="form-label" for="fecha_inicial">Fecha Inicial</label>
                                    <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial"
                                        value="{{ date('Y-m-01') }}">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label class="form-label" for="fecha_final">Fecha Final</label>
                                    <input type="date" class="form-control" id="fecha_final" name="fecha_final"
                                        value="{{ date('Y-m-t') }}">
                                </div>

                                <div class="col-12 col-md-5 mb-3">
                                    <label class="form-label" for="iduser">Vendedor</label>
                                    <select class="select2-size-sm form-control" id="iduser" name="iduser">
                                        <option value="0">TODOS</option>
                                        @foreach ($sellers as $seller)
                                            <option value="{{ $seller->id }}">
                                                {{ $seller->nombres }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3 mb-3">
                                    <label class="form-label" for="document">Documento</label>
                                    <select class="select2-size-sm form-control" id="document" name="document">
                                        <option value="0">TODOS</option>
                                        @foreach ($type_documents as $type_document)
                                            <option value="{{ $type_document->id }}">
                                                {{ $type_document->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mt-3">
                                    <div class="col-12 d-flex justify-content-between">
                                        <button id="wrapper-input-reniec" class="btn btn-info btn-search" type="button"
                                            id="button-addon2">
                                            <span class="text-search">
                                                <i class="ti ti-search" style="font-size: 15px; margin-bottom: 2px;"></i>
                                                <span class="input-text-reniec"> Buscar</span>
                                            </span>

                                            <span class="spinner-border spinner-border-sm text-searching d-none"
                                                role="status" aria-hidden="true"></span>
                                            <span class="ml-25 align-middle text-searching d-none" style="font-size: 14px;">
                                                <span style="margin-left: 3px;">Buscando...</span></span>
                                        </button>

                                        <div>
                                            <button type="buttom" class="btn btn-danger btn_export_pdf" name="export_pdf"
                                                value="1">
                                                <i class="fa fa-file-pdf"></i> <span style="margin-left: 3px;">PDF</span>
                                            </button>

                                            <button type="buttom" class="btn btn-success btn_export_excel"
                                                name="export_excel" value="1">
                                                <i class="fa fa-file-excel"></i> <span style="margin-left: 3px;">Excel</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr class="mb-4 mt-5">

                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="card-title" style="font-size: 15px;">Se encontraron <span
                                        class="quantity">0</span> registros</h6>
                                <div class="table-responsive-sm mt-3">
                                    <table class="table table-sm mb-0 fs--1">
                                        <thead>
                                            <tr>
                                                <th colspan="3" class="text-center">Documento</th>
                                                <th colspan="2" class="text-center">Cliente</th>
                                                <th colspan="5" class="text-center">Soles</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Fecha</th>
                                                <th class="text-center">Documento</th>
                                                <th class="text-center">Pago</th>
                                                <th class="text-center">RUC / DNI</th>
                                                <th class="text-center">Razón Social</th>
                                                <th class="text-center">Exonerada</th>
                                                <th class="text-center">Gravada</th>
                                                <th class="text-center">Inafecta</th>
                                                <th class="text-center">IGV</th>
                                                <th class="text-center">Importe</th>
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
    @include('admin.reports.sales.sellers.js-home')
@endsection
