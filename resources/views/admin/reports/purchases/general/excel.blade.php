<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Compras</title>
    <style>
        #cabecera
        {
            text-align: center;
            text-decoration: underline;
        }

        body
        {
            font-family: 'sans-serif';
        }

        .th_informacion
        {
            text-align: left;
            width: 200px;
        }

        .td_informacion
        {
            text-align: left;
        }

        .th_items, .td_items
        {
            text-align: center;
        }

        #table_items
        {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        #thead_items
        {
            width: 100%;
        }

        .border-solid
        {
            border: 1px solid #dee2e6
        }

        .text-right
        {
            text-align: right;
        }
    </style>
</head>
<body>
    <div id="cabecera">
        <h4>REPORTE DE COMPRAS</h4>
    </div>

    <div id="informacion">
        <table style="font-size: 15px;">
            <tr>
                <th class="th_informacion">RUC</th>
                <td class="td_informacion">: {{ $business->ruc }}</td>
            </tr>

            <tr>
                <th class="th_informacion">EMPRESA</th>
                <td style="text-align: right">: {{ $business->nombre_comercial }}</td>
            </tr>

            <tr>
                <th class="th_informacion">FECHAS</th>
                <td style="text-align: right">: {{ date('d-m-Y', strtotime($fecha_inicial)) }} a {{ date('d-m-Y', strtotime($fecha_final)) }}</td>
            </tr>
        </table>
    </div>

    <div id="items">
        <table id="table_items">
                <thead id="thead_items" style="font-size: 12px;">
                    <tr>
                        <th colspan="2" class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Documento</th>
                        <th colspan="2" class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Proveedor</th>
                        <th colspan="5" class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Soles</th>
                    </tr>

                    <tr>
                        <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Fecha</th>
                        <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Documento</th>
                        <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">RUC / DNI</th>
                        <th class="th_items border-solid" style="border: 1px solid #151515">Razón Social</th>
                        <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Exonerado</th>
                        <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Gravado</th>
                        <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Inafecta</th>
                        <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">IGV</th>
                        <th class="th_items border-solid" style="text-align: center; border: 1px solid #151515">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($buys as $index => $item)
                    @php
                        $total += $item["total"];
                    @endphp
                        <tr style="font-size: 12px;">
                            <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{ date('d-m-Y', strtotime($item["fecha_emision"])) }}</td>
                            <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{ $item["serie"] . "-" . $item["correlativo"] }}</td>
                            <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515">{{ $item["dni_ruc"] }}</td>
                            <td class="text-left border-solid" style="border: 1px solid #151515">{{ $item["proveedor"] }}</td>
                            <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515" data-format="0.00">{{ $item["exonerada"] }}</td>
                            <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515" data-format="0.00">{{ $item["gravada"] }}</td>
                            <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515" data-format="0.00">{{ $item["inafecta"] }}</td>
                            <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515" data-format="0.00">{{ $item["igv"] }}</td>
                            <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515" data-format="0.00">{{ number_format($item["total"], 2, ".", "") }}</td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="8" style="border: 1px solid #151515"></td>
                        </tr>
                        @endforelse
                        <tr>
                            <td colspan="8" class="border-solid" style="text-align: right; border: 1px solid #151515">Total S/ &nbsp;</td>
                            <td class="td_items border-solid" style="text-align: center; border: 1px solid #151515" data-format="0.00">{{ number_format($total, 2, ".", "") }}</td>
                        </tr>
                </tbody>
        </table>
    </div>
</body>
</html>