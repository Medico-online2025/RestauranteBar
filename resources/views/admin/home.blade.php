@extends('admin.template')
@section('content')
    <div class="row">
        <!-- View sales -->
        <div class="col-xl-4 mb-4 col-lg-5 col-12">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-7">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0">Felicidades {{ $seller_name }}</h5>
                            <p class="mb-2">Mejor vendedor del mes</p>
                            <h4 class="text-primary mb-1">S/{{ number_format($seller_total, 2, '.', '') }}</h4>
                            <a href="{{ route('admin.sales_seller') }}" class="btn btn-primary">Ver Resumen</a>
                        </div>
                    </div>
                    <div class="col-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}" height="130"
                                alt="view sales" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- View sales -->

        <!-- Statistics -->
        <div class="col-xl-8 mb-4 col-lg-7 col-12">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="card-title mb-0">Estad&iacute;sticas</h5>
                        <small class="text-muted">Actualizaci&oacute;n constante</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                    <i class="ti ti-chart-pie-2 ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ count($billings) + count($sale_notes) }}</h5>
                                    <small>Ventas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-info me-3 p-2">
                                    <i class="ti ti-users ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ count($clients) }}</h5>
                                    <small>Clientes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                    <i class="ti ti-shopping-cart ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ count($products) }}</h5>
                                    <small>Productos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-success me-3 p-2">
                                    <i class="ti ti-currency-dollar ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">S/{{ number_format($sum_billings + $sum_sale_notes, 2, '.', '') }}
                                    </h5>
                                    <small>Ganancias</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Statistics -->

        <div class="col-xl-4 col-12">
            <div class="row">
                <!-- Expenses -->
                <div class="col-xl-6 mb-4 col-md-3 col-6">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 class="card-title mb-0">S/{{ number_format($bills, 2, '.', '') }}</h5>
                            <small class="text-muted">Gastos</small>
                        </div>
                        <div class="card-body">
                            <div id="expensesChart"></div>
                            <div class="mt-md-2 text-center mt-lg-3 mt-3">
                                <small class="text-muted mt-3">S/{{ number_format($bills_former, 2, '.', '') }} Gastos
                                    m&aacute;s que el mes pasado.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Expenses -->

                <!-- Profit last month -->
                <div class="col-xl-6 mb-4 col-md-3 col-6">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 class="card-title mb-0">Ganancia</h5>
                            <small class="text-muted">&Uacute;ltimo mes</small>
                        </div>
                        <div class="card-body">
                            <div id="profitLastMonth"></div>
                            <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                                <h4 class="mb-0">{{ number_format($billing_profit + $sale_note_profit, 2, '.', '') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Profit last month -->

                <!-- Generated Leads -->
                <div class="col-xl-12 mb-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <div class="card-title mb-auto">
                                        <h5 class="mb-1 text-nowrap">Clientes Potenciales</h5>
                                        <small>Reporte Mensual</small>
                                    </div>
                                    <div class="chart-statistics">
                                        <h3 class="card-title mb-1">
                                            {{ count($billings) + count($sale_notes) == 0 ? 0 : count($clients) }}</h3>
                                    </div>
                                </div>
                                <div id="generatedLeadsChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Generated Leads -->
            </div>
        </div>

        <!-- Revenue Report -->
        <div class="col-12 col-xl-8 mb-4 col-lg-7">
            <div class="card">
                <div class="card-header pb-3">
                    <h5 class="m-0 me-2 card-title">Informe de Ingresos</h5>
                </div>
                <div class="card-body">
                    <div class="row row-bordered g-0">
                        <div class="col-md-8">
                            <div id="totalRevenueChart"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mt-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                        id="budgetId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <script>
                                            document.write(new Date().getFullYear());
                                        </script>
                                    </button>
                                </div>
                            </div>
                            <h3 class="text-center pt-4 mb-0">S/{{ number_format($ganancias, 2, '.', '') }}</h3>
                            <div class="px-3">
                                <div id="budgetChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Revenue Report -->

        <!-- Productos -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title m-0 me-2">Resumen de productos m&aacute;s vendidos</h5>
                </div>
                <div class="table-responsive-sm">
                    <table class="table table-borderless border-top">
                        <thead class="border-bottom">
                            <tr>
                                <th width="8%">C&oacute;digo</th>
                                <th class="text-left" width="60%">Descripci&oacute;n</th>
                                <th>Cantidad</th>
                                <th width="15%" class="text-center">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales_products as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="d-flex flex-column">
                                                <p class="mb-0 fw-medium">{{ $item["codigo"] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <p class="mb-0 fw-medium">{{ $item["producto"] }}</p>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <p class="mb-0 fw-medium">{{ intval($item["cantidad"]) }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="mb-0 fw-medium">{{ number_format($item["precio_total"], 2, '.', '') }}</p>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                              <td colspan="4">
                                <div class="el-table__empty-block">
                                  <span class="el-table__empty-text">
                                        <div class="el-empty">
                                            <div class="el-empty__image">
                                              <svg viewBox="0 0 79 86" version="1.1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <defs>
                                                        <linearGradient id="linearGradient-1-el-id-2387-15"
                                                            x1="38.8503086%" y1="0%" x2="61.1496914%"
                                                            y2="100%">
                                                            <stop stop-color="var(--el-empty-fill-color-1)"
                                                                offset="0%"></stop>
                                                            <stop stop-color="var(--el-empty-fill-color-4)"
                                                                offset="100%"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="linearGradient-2-el-id-2387-15"
                                                            x1="0%" y1="9.5%" x2="100%"
                                                            y2="90.5%">
                                                            <stop stop-color="var(--el-empty-fill-color-1)"
                                                                offset="0%"></stop>
                                                            <stop stop-color="var(--el-empty-fill-color-6)"
                                                                offset="100%"></stop>
                                                        </linearGradient>
                                                        <rect id="path-3-el-id-2387-15" x="0" y="0" width="17"
                                                            height="36"></rect>
                                                    </defs>
                                                    <g id="Illustrations" stroke="none" stroke-width="1"
                                                        fill="none" fill-rule="evenodd">
                                                        <g id="B-type"
                                                            transform="translate(-1268.000000, -535.000000)">
                                                            <g id="Group-2"
                                                                transform="translate(1268.000000, 535.000000)">
                                                                <path id="Oval-Copy-2"
                                                                    d="M39.5,86 C61.3152476,86 79,83.9106622 79,81.3333333 C79,78.7560045 57.3152476,78 35.5,78 C13.6847524,78 0,78.7560045 0,81.3333333 C0,83.9106622 17.6847524,86 39.5,86 Z"
                                                                    fill="var(--el-empty-fill-color-3)"></path>
                                                                <polygon id="Rectangle-Copy-14"
                                                                    fill="var(--el-empty-fill-color-7)"
                                                                    transform="translate(27.500000, 51.500000) scale(1, -1) translate(-27.500000, -51.500000) "
                                                                    points="13 58 53 58 42 45 2 45"></polygon>
                                                                <g id="Group-Copy"
                                                                    transform="translate(34.500000, 31.500000) scale(-1, 1) rotate(-25.000000) translate(-34.500000, -31.500000) translate(7.000000, 10.000000)">
                                                                    <polygon id="Rectangle-Copy-10"
                                                                        fill="var(--el-empty-fill-color-7)"
                                                                        transform="translate(11.500000, 5.000000) scale(1, -1) translate(-11.500000, -5.000000) "
                                                                        points="2.84078316e-14 3 18 3 23 7 5 7">
                                                                    </polygon>
                                                                    <polygon id="Rectangle-Copy-11"
                                                                        fill="var(--el-empty-fill-color-5)"
                                                                        points="-3.69149156e-15 7 38 7 38 43 -3.69149156e-15 43">
                                                                    </polygon>
                                                                    <rect id="Rectangle-Copy-12"
                                                                        fill="url(#linearGradient-1-el-id-2387-15)"
                                                                        transform="translate(46.500000, 25.000000) scale(-1, 1) translate(-46.500000, -25.000000) "
                                                                        x="38" y="7" width="17"
                                                                        height="36"></rect>
                                                                    <polygon id="Rectangle-Copy-13"
                                                                        fill="var(--el-empty-fill-color-2)"
                                                                        transform="translate(39.500000, 3.500000) scale(-1, 1) translate(-39.500000, -3.500000) "
                                                                        points="24 7 41 7 55 -3.63806207e-12 38 -3.63806207e-12">
                                                                    </polygon>
                                                                </g>
                                                                <rect id="Rectangle-Copy-15"
                                                                    fill="url(#linearGradient-2-el-id-2387-15)"
                                                                    x="13" y="45" width="40" height="36">
                                                                </rect>
                                                                <g id="Rectangle-Copy-17"
                                                                    transform="translate(53.000000, 45.000000)">
                                                                    <use id="Mask"
                                                                        fill="var(--el-empty-fill-color-8)"
                                                                        transform="translate(8.500000, 18.000000) scale(-1, 1) translate(-8.500000, -18.000000) "
                                                                        xlink:href="#path-3-el-id-2387-15"></use>
                                                                    <polygon id="Rectangle-Copy"
                                                                        fill="var(--el-empty-fill-color-9)"
                                                                        mask="url(#mask-4-el-id-2387-15)"
                                                                        transform="translate(12.000000, 9.000000) scale(-1, 1) translate(-12.000000, -9.000000) "
                                                                        points="7 0 24 0 20 18 7 16.5"></polygon>
                                                                </g>
                                                                <polygon id="Rectangle-Copy-18"
                                                                    fill="var(--el-empty-fill-color-2)"
                                                                    transform="translate(66.000000, 51.500000) scale(-1, 1) translate(-66.000000, -51.500000) "
                                                                    points="62 45 79 45 70 58 53 58"></polygon>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                              </div>
                                              <div class="el-empty__description">
                                                  <p>Sin datos</p>
                                              </div>
                                        </div>
                                    </span>
                                </div>
                              </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title m-0 me-2">Resumen de ventas por cliente</h5>
                </div>
                <div class="table-responsive-sm">
                    <table class="table table-borderless border-top">
                        <thead class="border-bottom">
                            <tr>
                                <th>RUC/DNI</th>
                                <th class="text-left" width="60%">Raz&oacute;n Social</th>
                                <th>Cantidad</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales_clients as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="d-flex flex-column">
                                                <p class="mb-0 fw-medium">{{ $item['dni_ruc'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <p class="mb-0 fw-medium">{{ $item['cliente'] }}</p>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <p class="mb-0 fw-medium">{{ $item["cantidad_ventas"] }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="mb-0 fw-medium">{{ number_format($item['total'], 2, '.', '') }}</p>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                              <td colspan="4">
                                <div class="el-table__empty-block">
                                  <span class="el-table__empty-text">
                                        <div class="el-empty">
                                            <div class="el-empty__image">
                                              <svg viewBox="0 0 79 86" version="1.1"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <defs>
                                                        <linearGradient id="linearGradient-1-el-id-2387-15"
                                                            x1="38.8503086%" y1="0%" x2="61.1496914%"
                                                            y2="100%">
                                                            <stop stop-color="var(--el-empty-fill-color-1)"
                                                                offset="0%"></stop>
                                                            <stop stop-color="var(--el-empty-fill-color-4)"
                                                                offset="100%"></stop>
                                                        </linearGradient>
                                                        <linearGradient id="linearGradient-2-el-id-2387-15"
                                                            x1="0%" y1="9.5%" x2="100%"
                                                            y2="90.5%">
                                                            <stop stop-color="var(--el-empty-fill-color-1)"
                                                                offset="0%"></stop>
                                                            <stop stop-color="var(--el-empty-fill-color-6)"
                                                                offset="100%"></stop>
                                                        </linearGradient>
                                                        <rect id="path-3-el-id-2387-15" x="0" y="0" width="17"
                                                            height="36"></rect>
                                                    </defs>
                                                    <g id="Illustrations" stroke="none" stroke-width="1"
                                                        fill="none" fill-rule="evenodd">
                                                        <g id="B-type"
                                                            transform="translate(-1268.000000, -535.000000)">
                                                            <g id="Group-2"
                                                                transform="translate(1268.000000, 535.000000)">
                                                                <path id="Oval-Copy-2"
                                                                    d="M39.5,86 C61.3152476,86 79,83.9106622 79,81.3333333 C79,78.7560045 57.3152476,78 35.5,78 C13.6847524,78 0,78.7560045 0,81.3333333 C0,83.9106622 17.6847524,86 39.5,86 Z"
                                                                    fill="var(--el-empty-fill-color-3)"></path>
                                                                <polygon id="Rectangle-Copy-14"
                                                                    fill="var(--el-empty-fill-color-7)"
                                                                    transform="translate(27.500000, 51.500000) scale(1, -1) translate(-27.500000, -51.500000) "
                                                                    points="13 58 53 58 42 45 2 45"></polygon>
                                                                <g id="Group-Copy"
                                                                    transform="translate(34.500000, 31.500000) scale(-1, 1) rotate(-25.000000) translate(-34.500000, -31.500000) translate(7.000000, 10.000000)">
                                                                    <polygon id="Rectangle-Copy-10"
                                                                        fill="var(--el-empty-fill-color-7)"
                                                                        transform="translate(11.500000, 5.000000) scale(1, -1) translate(-11.500000, -5.000000) "
                                                                        points="2.84078316e-14 3 18 3 23 7 5 7">
                                                                    </polygon>
                                                                    <polygon id="Rectangle-Copy-11"
                                                                        fill="var(--el-empty-fill-color-5)"
                                                                        points="-3.69149156e-15 7 38 7 38 43 -3.69149156e-15 43">
                                                                    </polygon>
                                                                    <rect id="Rectangle-Copy-12"
                                                                        fill="url(#linearGradient-1-el-id-2387-15)"
                                                                        transform="translate(46.500000, 25.000000) scale(-1, 1) translate(-46.500000, -25.000000) "
                                                                        x="38" y="7" width="17"
                                                                        height="36"></rect>
                                                                    <polygon id="Rectangle-Copy-13"
                                                                        fill="var(--el-empty-fill-color-2)"
                                                                        transform="translate(39.500000, 3.500000) scale(-1, 1) translate(-39.500000, -3.500000) "
                                                                        points="24 7 41 7 55 -3.63806207e-12 38 -3.63806207e-12">
                                                                    </polygon>
                                                                </g>
                                                                <rect id="Rectangle-Copy-15"
                                                                    fill="url(#linearGradient-2-el-id-2387-15)"
                                                                    x="13" y="45" width="40" height="36">
                                                                </rect>
                                                                <g id="Rectangle-Copy-17"
                                                                    transform="translate(53.000000, 45.000000)">
                                                                    <use id="Mask"
                                                                        fill="var(--el-empty-fill-color-8)"
                                                                        transform="translate(8.500000, 18.000000) scale(-1, 1) translate(-8.500000, -18.000000) "
                                                                        xlink:href="#path-3-el-id-2387-15"></use>
                                                                    <polygon id="Rectangle-Copy"
                                                                        fill="var(--el-empty-fill-color-9)"
                                                                        mask="url(#mask-4-el-id-2387-15)"
                                                                        transform="translate(12.000000, 9.000000) scale(-1, 1) translate(-12.000000, -9.000000) "
                                                                        points="7 0 24 0 20 18 7 16.5"></polygon>
                                                                </g>
                                                                <polygon id="Rectangle-Copy-18"
                                                                    fill="var(--el-empty-fill-color-2)"
                                                                    transform="translate(66.000000, 51.500000) scale(-1, 1) translate(-66.000000, -51.500000) "
                                                                    points="62 45 79 45 70 58 53 58"></polygon>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                              </div>
                                              <div class="el-empty__description">
                                                  <p>Sin datos</p>
                                              </div>
                                        </div>
                                    </span>
                                </div>
                              </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('admin.js-home-dashboard')
@endsection
