<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Clientes</title>
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
            width: 220px;
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
            text-align: left;
        }
    </style>
</head>

<body>
    <div id="cabecera">
        <h4>REPORTE DE CLIENTES</h4>
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
                <th class="th_informacion">CANTIDAD DE CLIENTES</th>
                <td class="td_informacion">: {{ $quantity }}</td>
            </tr>
        </table>
    </div>

    <div id="items">
        <table id="table_items">
            <thead id="thead_items" style="font-size: 12px;">
                <tr>
                    <th class="th_items border-solid" width="10%">Tipo Doc.</th>
                    <th class="th_items border-solid" width="15%">N&uacute;mero</th>
                    <th class="td_informacion border-solid">Nombres</th>
                    <th class="th_items border-solid" width="16%">Tel&oacute;ono</th>
                    <th class="th_items border-solid">Direcci&oacute;n</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $item)
                    <tr style="font-size: 12px;">
                        
                        <td class="td_items border-solid">{{ $item->documento }}</td>
                        <td class="td_items border-solid">{{ $item->dni_ruc }}</td>
                        <td class="td_informacion border-solid">{{ $item->nombres }}</td>
                        @if ($item->telefono == null)
                        <td class="td_items border-solid">-</td>
                        @else
                        <td class="td_items border-solid">{{ $item->telefono }}</td>
                        @endif
                        <td class="td_items border-solid">{{ $item->direccion }}</td>
                    </tr>
                @empty
                    <tr style="font-size: 12px;">
                        <td class="td_informacion border-solid" colspan="5"></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
