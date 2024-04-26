<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $name }}</title>
    <style>
        html {
            margin: 0px;
            font-family: 'ticketing';
        }

        .cabecera {
            text-align: center;
        }

        .informacion {
            text-align: left;
            margin-left: 15px;
            margin-right: 15px;
        }

        .payments
        {
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
        <img src="{{ public_path('assets/img/branding/logo__mytems.jpg') }}" style="margin-top: 9px;" width="35%" height="7%">
        <p style="font-size: 16px; font-weight: bold; margin-bottom: 0; margin-top: 10px;">{{ $business->nombre_comercial }}</p>
        <p style="font-size: 10px; margin-top:0; margin-bottom: 0;">{{ $business->direccion }}</p>
        <p style="font-size: 10px; margin-top:0; margin-bottom: 0;">{{ $ubigeo["distrito"] }} - {{ $ubigeo["departamento"] }}</p>
        <p style="font-size: 14px; font-weight: bold; margin-top:0; margin-bottom: 0;">RUC: {{ $business->ruc }}</p>
        <p style="font-size: 14px; font-weight: bold; margin-top:0; margin-bottom: 0;">
            {{ $tipo_comprobante->descripcion }}
        </p>
        <p style="font-size: 14px; font-weight: bold; margin-top:0; margin-bottom: 0;">
            {{ $factura->serie }}-{{ $factura->correlativo }}
        </p>
    </div>

    <div class="informacion">
        <p style="font-size: 11px; margin-top:0; font-weight: bold; margin-bottom: 0;">Adquiriente</p>
        <p style="font-size: 10px; margin-top:0; margin-bottom: 0;">{{ $tipo_documento->descripcion_documento }}. {{ $cliente->dni_ruc }}</p>
        <p style="font-size: 10px; margin-top:0; margin-bottom: 0;">{{ $cliente->nombres }}</p>
        <p style="font-size: 10px; margin-top:0; margin-bottom: 0; text-transform: uppercase">{{ $cliente->direccion }}</p>
        <p style="font-size: 11px; margin-top:0; font-weight: bold; margin-bottom: 0;">Fecha de Emisión:{{ date('d/m/Y', strtotime($factura->fecha_emision)) }} Hora: {{ $factura->hora }}</p>

        <p style="font-size: 12px; margin-top:0; font-weight: bold; margin-bottom: 0;">Moneda: {{ $moneda->codigo }}</p>
        <p style="font-size: 12px; margin-top:0; margin-bottom: 0;">Forma de Pago: {{ $modo_pago->descripcion }} </p>
        <p style="font-size: 12px; margin-top:0; margin-bottom: 0;">Vendedor: {{ $vendedor }} </p>
    </div>

    <div class="tabla_detalle">
        <table style="border-top: 1px solid #c2c2c2;" width="100%">
            <thead style="border-bottom: 1px solid #c2c2c2" style="width: 100%">
                <tr>
                    <th style="font-size: 12px;">[Cant]</th>
                    <th style="font-size: 12px; text-align: left;">Descripción</th>
                    <th style="font-size: 12px;">P/U</th>
                    <th style="font-size: 12px; text-align:right;">Importe</th>
                </tr>
            </thead>

            <tbody style="border-bottom: 1px solid #c2c2c2">
                @foreach ($detalle as $product)
                <tr style="border-bottom: 1px solid #c2c2c2">
                    <td style="font-size: 10px; text-align:center; vertical-align: top">[ {{ round($product['cantidad']) }} ]</td>
                    <td style="font-size: 10px; text-align:left; vertical-align: top">{{ $product['producto'] }}</td>
                    <td style="font-size: 10px; text-align:center; vertical-align: top">{{ $product['precio_unitario'] }}</td>
                    <td style="font-size: 10px; text-align:right; vertical-align: top">{{ $product['precio_total'] }}</td>
                </tr>
                @endforeach
            </tbody>

            <tbody style="border-bottom: 1px solid #c2c2c2">
                <tr>
                    <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="2">Exonerada:</td>
                    <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="2">S/ {{ $factura->exonerada }}</td>
                </tr>

                <tr>
                    <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="2">Gravada:</td>
                    <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="2">S/ {{ $factura->gravada }}</td>
                </tr>

                <tr>
                    <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="2">Inafecta:</td>
                    <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="2">S/ {{ $factura->inafecta }}</td>
                </tr>

                <tr>
                    <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="2">IGV:</td>
                    <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="2">S/ {{ $factura->igv }}</td>
                </tr>
            </tbody>

            <tbody style="border-top: 1px solid #c2c2c2; margin-bottom: 20px;">
                <tr>
                    <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="2">Importe Total:
                    </td>
                    <td style="font-size: 12px; font-weight: bold; text-align: right;" colspan="2">S/ {{ $factura->total }}</td>
                </tr>
            </tbody>

            <tbody style="border-top: 1px solid #c2c2c2;">
                <tr style="">
                    <td style="font-size: 12px; font-weight: bold; text-align: center;" colspan="4">
                        Son: {{ $numero_letras }} Con 00/100 Soles
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="" style="">
        <p style="font-size: 11px; text-align: justify; padding: 0px 18px;">BIENES TRANSFERIDOS EN LA AMAZON&Iacute;A REGI&Oacute;N SELVA PARA SER CONSUMIDOS EN LA MISMA.</p>
    </div>
</body>
</html>