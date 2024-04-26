<!DOCTYPE html>
<html lang="en">
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
        <p style="font-size: 16px; font-weight: bold; margin-bottom: 0; margin-top: 10px;">MYTEMS E.I.R.L.</p>
        <p style="font-size: 10.5px; margin-top:0; margin-bottom: 0;">JR MANCO CAPAC 452</p>
        <p style="font-size: 10.5px; margin-top:0; margin-bottom: 0;">LAMAS - SAN MARTIN</p>
        <p style="font-size: 14.5px; font-weight: bold; margin-top:0; margin-bottom: 0;">RUC: 20610316884</p>
        <p style="font-size: 14.5px; font-weight: bold; margin-top:0; margin-bottom: 0;">
            BOLETA DE VENTA ELECTRÓNICA
        </p>
        <p style="font-size: 14.5px; font-weight: bold; margin-top:0; margin-bottom: 0;">
            F001-000005
        </p>
    </div>

    <div class="informacion">
        <p style="font-size: 12px; margin-top:0; font-weight: bold; margin-bottom: 0;">Adquiriente</p>
        <p style="font-size: 11px; margin-top:0; margin-bottom: 0;">DNI . 71433073</p>
        <p style="font-size: 11px; margin-top:0; margin-bottom: 0;">KROWED NAJAR LOZANO</p>
        <p style="font-size: 11px; margin-top:0; margin-bottom: 0; text-transform: uppercase">JR SANTA INES 451</p>
        <p style="font-size: 12px; margin-top:0; font-weight: bold; margin-bottom: 0;">Fecha de Emisión:
            04/12/2021 Hora: 10:12</p>

        <p style="font-size: 12px; margin-top:0; font-weight: bold; margin-bottom: 0;">Moneda: PEN</p>
        <p style="font-size: 12px; margin-top:0; margin-bottom: 0;">Forma de Pago: CONTADO </p>
        <p style="font-size: 12px; margin-top:0; margin-bottom: 0;">Vendedor: Administrador </p>
        <p style="font-size: 12px; margin-top:0; margin-bottom: 0;">Efectivo: 20.00 Vuelto: 10.00 </p>
    </div>

    <div class="tabla_detalle">
        <table style="border-top: 1px solid #c2c2c2;" width="100%">
            <thead style="border-bottom: 1px solid #c2c2c2" style="width: 100%">
                <tr>
                    <th style="font-size: 12px;">[Cant]</th>
                    <th style="font-size: 12px; text-align: left;">Descripción</th>
                    <th style="font-size: 12px;">P/U</th>
                    <th style="font-size: 12px; text-align: right;">Total</th>
                </tr>
            </thead>

            <tbody style="border-bottom: 1px solid #c2c2c2">
                <tr style="border-bottom: 1px solid #c2c2c2">
                    <td style="font-size: 10px; text-align:center; vertical-align: top">[ {{ 2 }} ]</td>
                    <td style="font-size: 10px; text-align:left; vertical-align: top">ACICLOVIR 200 MG</td>
                    <td style="font-size: 10px; text-align:center; vertical-align: top">20.00</td>
                    <td style="font-size: 10px; text-align:right; vertical-align: top">20.00</td>
                </tr>
            </tbody>

            <tbody style="border-bottom: 1px solid #c2c2c2">
                <tr>
                    <td style="font-size: 11px; font-weight: bold; text-align: right;" colspan="2">Exonerada:</td>
                    <td style="font-size: 11px; font-weight: bold; text-align: right;" colspan="2">S/ 20.00</td>
                </tr>

                <tr>
                    <td style="font-size: 11px; font-weight: bold; text-align: right;" colspan="2">Gravada:</td>
                    <td style="font-size: 11px; font-weight: bold; text-align: right;" colspan="2">S/ 0.00</td>
                </tr>

                <tr>
                    <td style="font-size: 11px; font-weight: bold; text-align: right;" colspan="2">Inafecta:</td>
                    <td style="font-size: 11px; font-weight: bold; text-align: right;" colspan="2">S/ 0.00</td>
                </tr>

                <tr>
                    <td style="font-size: 11px; font-weight: bold; text-align: right;" colspan="2">IGV:</td>
                    <td style="font-size: 11px; font-weight: bold; text-align: right;" colspan="2">S/ 0.00</td>
                </tr>
            </tbody>

            <tbody style="border-top: 1px solid #c2c2c2; margin-bottom: 20px;">
                <tr>
                    <td style="font-size: 11px; font-weight: bold; text-align: right;" colspan="2">Importe Total:
                    </td>
                    <td style="font-size: 11px; font-weight: bold; text-align: right;" colspan="2">S/ 0.00</td>
                </tr>
            </tbody>

            <tbody style="border-top: 1px solid #c2c2c2;">
                <tr style="">
                    <td style="font-size: 11px; font-weight: bold; text-align: center;" colspan="4">
                        Son: CUARENTA Con 00/100 Soles
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    
    <div class="payments">
        <p style="font-size: 11px; margin-top:7px; font-weight: bold; margin-bottom: 0;">METODOS DE PAGO</p>
        <p style="font-size: 10px; margin-top:0; margin-bottom: 0;">EFECTIVO: 20.00</p>
        <p style="font-size: 10px; margin-top:0; margin-bottom: 0;">YAPE: 10.00</p>
        <p style="font-size: 10px; margin-top:0; margin-bottom: 0; text-transform: uppercase">PLIN: 10.00</p>
    </div>

    <div class="text-center" style="margin-top: 0px;">
        <img src="{{ public_path('files/billings/qr/qr_test.png') }}" alt="NULO" style="width: 100px; height: 100px;">
    </div>

    <div>
        <p style="font-size: 11px; text-align: justify; padding: 0px 18px;">BIENES TRANSFERIDOS EN LA AMAZON&Iacute;A REGI&Oacute;N SELVA PARA SER CONSUMIDOS EN LA MISMA.</p>
    </div>
</body>
</html>