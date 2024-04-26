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
                        <h6 class="card-title">* Se visualiza la lista de los productos que est&aacute;n vencidos y pronto a vencer.</h6>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="table-responsive-sm mt-3">
                                    <table class="table table-sm mb-0 fs--1">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="7%">#</th>
                                                <th class="text-center" width="11%">C&oacute;digo</th>
                                                <th class="text-left">Descripci&oacute;n</th>
                                                <th class="text-center" width="16%">D&iacute;as para vencer</th>
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
    @include('admin.alerts.expirations.js-home')
@endsection
