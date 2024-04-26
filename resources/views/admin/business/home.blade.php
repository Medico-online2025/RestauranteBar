@extends('admin.template')
@section('styles')
    <style>
        body
        {overflow-x:hidden;}
    </style>
@endsection
@section('content')
    <div class="row" id="basic-table">
        <div class="col-12 mb-4">
            <div class="dt-action-buttons text-end">
                <div class="dt-buttons d-inline-flex">
                    <button class="dt-button create-new btn btn-primary btn-gen-json" tabindex="0" type="button">
                        <div class="text-gen">
                            <span> <i data-feather='share'></i> Generar Json</span>
                        </div>
                        <span class="spinner-border spinner-border-sm me-1 d-none text-load-gen" role="status" aria-hidden="true"></span>
                        <span class="text-load-gen d-none">Cargando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <section class="basic-select2">
        <div class="row">
            <!-- Congratulations Card -->
            <div class="col-12 col-md-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Datos de la Empresa</h5>
                        <form id="form-info" class="form form-vertical">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="ruc">RUC</label>
                                    <input type="text" id="ruc" class="form-control" name="ruc"
                                        value="{{ $business->ruc }}" />
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="razon_social">Raz&oacute;n Social</label>
                                    <input type="text" id="razon_social" class="form-control text-uppercase"
                                        name="razon_social" value="{{ $business->razon_social }}" />
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="direccion">Direcci&oacute;n</label>
                                    <input type="text" id="direccion" class="form-control text-uppercase"
                                        name="direccion" value="{{ $business->direccion }}" />
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="pais">Pa&iacute;s</label>
                                    <select name="pais" id="pais" class="form-control">
                                        <option value="PE">Perú</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label for="departamento">Departamento</label>
                                    <select name="departamento" id="departamento"
                                        class="select2 select2_department form-control" style="width: 100%"></select>
                                </div>

                                <div id="wrapper_province" class="col-12 col-md-4 mb-3">
                                    <label for="provincia">Provincia</label>
                                    <select name="provincia" id="provincia"
                                        class="select2 select2_province form-control"></select>
                                </div>

                                <div id="wrapper_province" class="col-12 col-md-4 mb-3">
                                    <label for="distrito">Distrito</label>
                                    <select name="distrito" id="distrito"
                                        class="select2 select2_district form-control"></select>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="url_api">URL Api</label>
                                    <input type="text" id="url_api" class="form-control" name="url_api"
                                        value="{{ $business->url_api }}" />
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="email_accounting">Contacto Contabilidad</label>
                                    <input type="text" id="email_accounting" class="form-control" name="email_accounting"
                                        value="{{ $business->email_accounting }}" />
                                </div>

                                <div class="col-12 text-end mb-2">
                                    <button type="button" class="btn btn-primary btn-save-info">
                                        <span class="text-save-info">Guardar</span>
                                        <span class="spinner-border spinner-border-sm me-1 d-none text-saving-info" role="status" aria-hidden="true"></span>
                                        <span class="text-saving-info d-none">Guardando...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Congratulations Card -->

            <!-- Medal Card -->
            <div class="col-12 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Usuario SUNAT</h5>
                        <form id="form_info_user" class="form form-vertical" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="nombre_comercial">Nombre Comercial</label>
                                    <input type="text" id="nombre_comercial" class="form-control"
                                        name="nombre_comercial" value="{{ $business->nombre_comercial }}" />
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="usuario_sunat">Usuario Secundario</label>
                                    <input type="text" id="usuario_sunat" class="form-control" name="usuario_sunat"
                                        value="{{ $business->usuario_sunat }}" />
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="clave_sunat">Clave</label>
                                    <input type="text" id="clave_sunat" class="form-control" name="clave_sunat"
                                        value="{{ $business->clave_sunat }}" />
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label for="clave_certificado">Clave Certificado</label>
                                    <input type="text" id="clave_certificado" class="form-control"
                                        name="clave_certificado" value="{{ $business->clave_certificado }}" />
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="certificado">Certificado (.pfx)</label>
                                    <input type="file" class="form-control" id="certificado" name="certificado">
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="d-block">Servidor Sunat</label>
                                    <div class="custom-control custom-radio my-50">
                                        <input type="radio" id="beta" name="servidor_sunat"
                                            class="form-check-input"
                                            {{ $business->servidor_sunat == 3 ? 'checked' : '' }} value="3">
                                        <label class="form-checked-label" for="beta">Beta</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="produccion" name="servidor_sunat"
                                            class="form-check-input"
                                            {{ $business->servidor_sunat == 1 ? 'checked' : '' }} value="1">
                                        <label class="form-checked-label" for="produccion">Producci&oacute;n</label>
                                    </div>
                                </div>

                                <div class="col-12 text-end mb-2">
                                    <button type="button" class="btn btn-primary btn-save-user">
                                        <span class="text-save-user">Guardar</span>
                                        <span class="spinner-border spinner-border-sm me-1 d-none text-saving-user" role="status" aria-hidden="true"></span>
                                        <span class="text-saving-user d-none"> Guardando...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Medal Card -->
        </div>
    </section>
@endsection
@section('scripts')
    @include('admin.business.js-home')
@endsection
