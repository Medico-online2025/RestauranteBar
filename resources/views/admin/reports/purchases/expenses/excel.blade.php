<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Gastos</title>
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
    </style>
</head>

<body>
    <div id="cabecera">
        <h4>REPORTE DE GASTOS</h4>
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
                <th class="th_informacion">CANTIDAD DE GASTOS</th>
                <td class="td_informacion">: {{ $quantity }}</td>
            </tr>
        </table>
    </div>

    <div id="items">
        <table id="table_items">
            <thead id="thead_items" style="font-size: 12px;">
                <tr>
                    <th class="th_item_bill border-solid" style="border: 1px solid #151515">Usuario</th>
                    <th class="th_item_bill border-solid" style="border: 1px solid #151515">Fecha</th>
                    <th class="th_item_bill border-solid" style="border: 1px solid #151515">Descripción</th>
                    <th class="th_item_bill border-solid" style="border: 1px solid #151515">Detalle</th>
                    <th class="th_item_bill_right border-solid" style="border: 1px solid #151515">Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($expenses as $index => $item)
                @php
                    $total += $item["monto"];
                @endphp
                    <tr style="font-size: 12px;">
                        <td class="th_item_bill border-solid" style="border: 1px solid #151515">{{ $item["usuario"] }}</td>
                        <td class="th_item_bill border-solid" style="border: 1px solid #151515">{{ date('d-m-Y', strtotime($item["fecha_emision"])) }}</td>
                        <td class="th_item_bill border-solid" style="border: 1px solid #151515">{{ $item["gasto"] }}</td>
                        <td class="th_item_bill border-solid" style="border: 1px solid #151515">{{ $item["detalle"] }}</td>
                        <td class="th_item_bill_right border-solid" style="border: 1px solid #151515" data-format="0.00">{{ $item["monto"] }}</td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" style="border: 1px solid #151515"></td>
                    </tr>
                    @endforelse
                    <tr>
                        <td colspan="4" class="border-solid" style="text-align: right; border: 1px solid #151515">Total S/ &nbsp;</td>
                        <td class="td_items border-solid" style="text-align: right; border: 1px solid #151515" data-format="0.00">{{ number_format($total, 2, ".", "") }}</td>
                    </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
