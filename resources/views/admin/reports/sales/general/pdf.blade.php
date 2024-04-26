<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Ventas General</title>
    <style>
        #cabecera {
            text-align: center;
            text-decoration: underline;
        }

        body {
            font-family: sans-serif;
        }

        .th_informacion {
            text-align: left;
            width: 200px;
        }

        .td_informacion {
            text-align: left;
        }

        .th_items,
        .td_items {
            text-align: center;
        }

        #table_items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        #thead_items {
            width: 100%;
        }

        .border-solid {
            border: 1px solid #dee2e6
        }

        .text-right {
            text-align: right;
        }

        .text-danger {
            color: rgb(234, 84, 85);
        }

        .pay_mode {
            margin-bottom: 0px; 
            margin-top: 0px; 
            text-align: center;
        }

        .tachado {
        text-decoration:line-through;
        }
    </style>
</head>

<body>
    <div id="cabecera">
        <h4>REPORTE DE VENTAS GENERAL</h4>
    </div>

    <div id="informacion">
        <table style="font-size: 15px;">
            <tr>
                <th class="th_informacion">RUC</th>
                <td class="td_informacion">{{ $business->ruc }}</td>
            </tr>

            <tr>
                <th class="th_informacion">EMPRESA</th>
                <td class="td_informacion">{{ $business->nombre_comercial }}</td>
            </tr>

            <tr>
                <th class="th_informacion">FECHAS</th>
                <td class="td_informacion">{{ date('d-m-Y', strtotime($fecha_inicial)) }} a {{ date('d-m-Y', strtotime($fecha_final))  }}</td>
            </tr>
        </table>
    </div>

    <div id="items">
        <table id="table_items">
            <thead id="thead_items" style="font-size: 12px;">
                <tr>
                    <th colspan="4" class="th_items border-solid">Documento</th>
                    <th colspan="2" class="th_items border-solid">Cliente</th>
                    <th colspan="5" class="th_items border-solid">Soles</th>
                </tr>
                <tr>
                    <th class="th_items border-solid">Fecha</th>
                    <th class="th_items border-solid">Documento</th>
                    <th class="th_items border-solid">Pago</th>
                    <th class="th_items border-solid">Doc. Relacionado</th>
                    <th class="th_items border-solid">RUC / DNI</th>
                    <th class="th_items border-solid">Razón Social</th>
                    <th class="th_items border-solid">Exonerada</th>
                    <th class="th_items border-solid">Gravada</th>
                    <th class="th_items border-solid">Inafecta</th>
                    <th class="th_items border-solid">IGV</th>
                    <th class="th_items border-solid">Importe</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($billings as $item)
                    <tr style="font-size: 12px;" class="{{ $item["estado_venta"] == 2 ? 'tachado' : '' }}">
                        <td class="td_items border-solid">{{ date('d-m-Y', strtotime($item["fecha_emision"])) }}</td>
                        <td class="td_items border-solid">{{ $item["serie"] ."-". $item["correlativo"] }}</td>
                        <td class="td_items border-solid">
                            @foreach ($pagos as $pago)
                                @foreach ($pago as $item_pago)
                                    @if ($item_pago->idfactura == $item["id"] && $item["idtipo_comprobante"] == $item_pago->idtipo_comprobante)
                                    <p class="pay_mode">{{ $item_pago->tipo_pago }}: {{ $item_pago->monto }}</p>
                                    @endif
                                @endforeach
                            @endforeach
                        </td>
                        <td class="td_items border-solid">
                            @foreach ($doc_relacionados as $doc_relacionado)
                                @if ($doc_relacionado != null)
                                    @if ($doc_relacionado["id"] == $item["idfactura_anular"])
                                    <p class="pay_mode">{{ $doc_relacionado->serie }}-{{ $doc_relacionado->correlativo }}</p>
                                    @endif
                                @else
                                    <p class="pay_mode"></p>
                                @endif
                            @endforeach
                        </td>
                        <td class="td_items border-solid">{{ $item["dni_ruc"] }}</td>
                        <td class="text-left border-solid">{{ $item["nombre_cliente"] }}</td>
                        <td class="td_items border-solid">{{ $item["exonerada"] }}</td>
                        <td class="td_items border-solid">{{ $item["gravada"] }}</td>
                        <td class="td_items border-solid">{{ $item["inafecta"] }}</td>
                        <td class="td_items border-solid">{{ $item["igv"] }}</td>
                        <td class="td_items border-solid">{{ $item["total"] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="10" class="text-right border-solid">Total S/ &nbsp;</td>
                    <td class="td_items border-solid">{{ number_format($total, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <td colspan="10" class="text-right border-solid text-danger">Anulado S/ &nbsp;</td>
                    <td class="td_items border-solid text-danger">{{ number_format($anulado, 2, '.', '') }}</td>
                </tr>
                <tr>
                    <td colspan="10" class="text-right border-solid">Total Neto S/ &nbsp;</td>
                    <td class="td_items border-solid">{{ number_format($total_neto, 2, '.', '') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
