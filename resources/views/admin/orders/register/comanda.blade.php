<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        html {
            margin: 0px;
            font-family: 'ticketing';
        }

        .cabecera {
            text-align: center;
        }

        .informacion {
            text-align: right;
            margin-left: 15px;
            margin-right: 15px;
        }

        .informacion_caja {
            text-align: right;
            margin-left: 15px;
            margin-right: 15px;
        }

        .tabla_detalle {
            margin-left: 15px;
            margin-right: 15px;
            margin-top: 5px;
        }

        .text-center {
            text-align: center;
        }

        .informacion_representacion {
            text-align: center;
        }

        footer {
            position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;

                /** Estilos extra personales **/
                background-color: white;
                color: black;
                text-align: center;
                line-height: 1.5cm;
            }
    </style>
</head>

<body>
    <div class="cabecera">
        <p style="font-size: 15px; font-weight: bold; margin-bottom: 0; margin-top: 10px;">TICKET DE COMANDA</p>
        <p style="font-size: 9.5px; margin-top:0; margin-bottom: 0;">{{ $business->nombre_comercial }}</p>
        <p style="font-size: 9.5px; margin-top:0; margin-bottom: 0;">{{ $ubigeo["distrito"] }} - {{ $ubigeo["departamento"] }}</p>
    </div>

    <div class="informacion">
        <p style="font-size: 10px; margin-top:10px; margin-bottom: 0;">{{ $table->descripcion }}</p>
        <p style="font-size: 10px; margin-top:0; margin-bottom: 0;">MESERO: {{ mb_strtoupper($mesero->user) }}</p>
        <p style="font-size: 11px; margin-top:0; font-weight: bold; margin-bottom: 0;">Fecha: {{ date('d-m-Y', strtotime($order->fecha)) }} Hora: {{ $order->hora }}</p>
    </div>

    <div class="tabla_detalle">
        <table style="border-top: 1px solid #c2c2c2;" width="100%">
            <thead style="border-bottom: 1px solid #c2c2c2" style="width: 100%">
                <tr>
                    <th style="font-size: 12px; text-align: center;" width="20%">[Cant]</th>
                    <th style="font-size: 12px; text-align: right;">Descripción</th>
                </tr>
            </thead>

            <tbody style="border-bottom: 1px solid #c2c2c2">
                @foreach ($detalle as $product)
                <tr style="border-bottom: 1px solid #c2c2c2">
                    <td style="font-size: 10px; text-align:center; vertical-align: top">[ {{ round($product['cantidad']) }} ]</td>
                    <td style="font-size: 10px; text-align:right; vertical-align: top">{{ $product['producto'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="informacion">
        <p style="font-size: 10px; text-align: justify">INFORMACI&Oacute;N ADICIONAL: <span style="font-weight: bold;">{{ mb_strtoupper($order->observaciones) }}</span></p>
    </div>
</body>
</html>