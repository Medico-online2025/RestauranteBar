<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Compras</title>
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
            width: 230px;
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

        .th_item_bill {
            text-align: center;
        }

        .th_item_bill_right
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
                <td class="td_informacion">: {{ $business->nombre_comercial }}</td>
            </tr>

            <tr>
                <th class="th_informacion">CANTIDAD DE COMPRAS</th>
                <td class="td_informacion">: {{ $quantity }}</td>
            </tr>
        </table>
    </div>

    <div id="items">
        <table id="table_items">
            <thead id="thead_items" style="font-size: 12px;">
                <tr>
                    <th class="th_items border-solid" width="16%">Usuario</th>
                    <th class="th_items border-solid" width="14%">Fecha</th>
                    <th class="td_informacion border-solid">Descripci&oacute;n</th>
                    <th class="th_items border-solid" width="15%">Detalle</th>
                    <th class="th_items border-solid">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenses as $item)
                @php
                    $total += $item->monto;
                @endphp
                    <tr style="font-size: 12px;">
                        <td class="td_items border-solid">{{ $item->usuario }}</td>
                        <td class="td_items border-solid">{{ date('d-m-Y', strtotime($item->fecha_emision)) }}</td>
                        <td class="td_informacion border-solid">{{ $item->gasto }}</td>
                        <td class="td_items border-solid">{{ $item->detalle }}</td>
                        <td class="td_items border-solid">{{ $item->monto }}</td>
                    </tr>
                @empty
                    <tr style="font-size: 12px;">
                        <td class="td_informacion border-solid" colspan="5"></td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="4" class="text-right border-solid">Total S/ &nbsp;</td>
                    <td class="th_item_bill border-solid">{{ number_format($total, 2, ".", "") }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
