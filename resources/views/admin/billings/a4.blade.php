<html>
    <head>
        <style>
            *{
                box-sizing: border-box;
                font-size: 11px;
                font-family: sans-serif;
            }
            .header{
                margin-bottom: 15px;
                font-size: 14px;
            }
            .header .logo{
                /* display: inline-block; */
                height: 100px;
                width: 18%;
            }
            .header .logo img{
                width: 100%;
                height: auto
            }
            .header .data{
                /* display: inline-block; */
                width: 69%;
                text-align: center
            }

            .data-name{
                font-weight: bold;
            }
            .data-ruc{
                /* display: inline-block; */
                width: 31%;
                border: 1px solid black;
                border-radius: 5px;
            }
            .data-ruc>div{
                padding: 5px;
                text-align: center;
            } 
            .data-ruc>div.name{
                padding: 2px;
                background: #EBEBEB;
                font-weight: bold;
            }
            .user{
                display: inline-block;
                border: 1px solid black;
                border-radius: 5px;
                padding: 5px;
                margin-bottom: 15px;
                
            }
            .user>*{
                display: inline-block;
                vertical-align: top;
            }
            .user .w-15{
                width: 15%;
            }
            .user .w-50{
                width: 49%;
            }
            .user .w-20{
                width: 19%;
            }
            .dates{
                display: inline-block;
                border: 1px solid black;
                border-radius: 5px;
                padding: 5px;
                margin-bottom: 15px;
                text-align: center;
                width: 98.5%;
            }
            .dates .w-25{
                display: inline-block;
                width: 23%;
            }
            .dates .w-25>*{
                display: inline-block;
                width: 100%;
            }

            table.description{
                width: 100%;
                border: 1px solid black;
                border-radius: 5px;
                margin-bottom: 15px;
            }
            table.description .row-1{
                width: 10%
            }
            table.description .row-2{
                width: 40%
            }
            table.description tr{
                height: 18px
            }
            .price-text{
                padding: 3px;
                border: 1px solid black;
                border-radius: 5px;
                margin-bottom: 15px;
                font-weight: bold;
            }
            .all{
                width: 100%;
                margin-bottom: 10px;    
            }
            .all .observation{
                /* display: inline-block; */
                width: 60%;
                height: 50px;
                /* background: blue; */
                vertical-align: top;
            }
            .all .all-pay{
                /* display: inline-block; */
                width: 40%;
                border: 1px solid black;
                border-radius: 5px;
                margin-bottom: 15px;
                padding: 5px;
            }
            .all .all-pay .left{
                display: inline-block;
                width: 63%;
                text-align: right
            }
            .all .all-pay .right{
                display: inline-block;
                width: 35%;
                text-align: right
            }
            .all .all-pay .bold{
                font-weight: bold;
            }
            .info-aside .qr{
                margin-top: 40px;
                margin-right: 20px;
                height: 150px;
                width: 150px;
                display: inline-block;
            }
            .info-aside .qr img{
                width: 100%;
            }
            .info-aside .info{
                display: inline-block;
                width: 75%;
                text-align: center;
                vertical-align: top;
                margin-top: 18px;
            }
            .link-search {
                margin-bottom: 15px;
            }
            .info-aside .info .method{
                border-radius: 5px;
                border: 1px solid black;
                padding: 5px;
                margin-bottom: 15px;
            }
            .info-aside .info .method .w-30{
                display: inline-block;
                width: 32%;
            }
            .info-aside .info .method .w-30 b{
                display: block;
            }
            .info-aside .info .secondary{
                border-radius: 5px;
                border: 1px solid black;
                padding: 5px;
            }
            .center{
                text-align: center;
            }
            .right{
                text-align: right;
            }
            .bottom-fixed{
                margin-bottom: 0px;
            }
            .thead_table
            {
                background: #ebebeb;
            }
            .thead_table th{
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <table class="header">
            <tr>
                <td class="logo">
                    <img src="{{ public_path('assets/img/branding/logo__mytems.jpg') }}" widht="100%" height="100%">
                </td>
                <td class="data">
                    <div class="data-name">
                        {{ $business->nombre_comercial }}
                    </div>
                    <div class="data-direction">
                        {{ $business->direccion }}<br>
                        {{ $ubigeo["distrito"] }} - {{ $ubigeo["provincia"] }} - {{ $ubigeo["departamento"] }}<br>
                        Tel&eacute;fono: {{ ($business->telefono == null) ? '-' : $business->telefono }}<br>
                    </div>
                </td>
                <td class="data-ruc">
                    <div class="ruc">R.U.C. {{ $business->ruc }}</div>
                    <div class="name">{{ $type_document->descripcion }}</div>
                    <div class="number">{{ $factura->serie . '-' . $factura->correlativo }}</div>
                </td>
            </tr>
        </table>
        <div class="user">
            <b class="w-15">NOMBRE</b>
            <span class="w-50">: {{ $client->nombres }}</span>
            <b class="w-15">MONEDA</b>
            <span class="w-20">: SOLES</span>
            
            <b class="w-15">RUC</b>
            <span class="w-50">: {{ $client->dni_ruc }}</span>
            <b class="w-15">VENDEDOR</b>
            <span class="w-20">: ADMINISTRADOR</span>
            <b class="w-15">DIRECCÓN</b>
            @if ($client->direccion == "-")
                <span class="w-50">: {{ $client->direccion }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            @else
                <span class="w-50">: {{ $client->direccion }}</span>
            @endif
        </div>
        
        <table class="description">
            <thead class="thead_table">
                <th class="row-1">#</th>
                <th class="row-1">CODIGO</th>
                <th class="row-2">DESCRIPCIÓN</th>
                <th class="row-1">CANT.</th>
                <th class="row-1">UND.</th>
                <th class="row-1">P.UNIT.</th>
                <th class="row-1">TOTAL</th>
            </thead>
            <tbody>
                @foreach ($detalle as $i => $item)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td class="center">{{ ($item["codigo_interno"] == null || $item == "") ? "" : $item["codigo_interno"] }}</td>
                    <td>{{ $item["producto"] }}</td>
                    <td class="center">{{ intval($item["cantidad"]) }}</td>
                    <td class="center">{{ $item["unidad"] }}</td>
                    <td class="center">{{ $item["precio_unitario"] }}</td>
                    <td class="center">{{ $item["precio_total"] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="price-text">
            <span>SON: </span>
            <span>{{ $numero_letras }} CON 00/100 SOLES</span>
        </div>
        <table class="all">
           <tr>
                <td class="observation">
                    <b>OBSERVACIONES:</b>
                    <span>{{ $factura->observaciones }}</span>
                </td>
                <td class="all-pay">
                    <div class="item">
                        <div class="left">OP. GRAVADAS: S/.</div>
                        <div class="right">{{ number_format(($factura->exonerada + $factura->gravada + $factura->inafecta), 2, '.', '') }}</div>
                    </div>
                    <div class="item">
                        <div class="left">IGV: S/.</div>
                        <div class="right">{{ $factura->igv }}</div>
                    </div>
                    <div class="item bold">
                        <div class="left">TOTAL A PAGAR: S/.</div>
                        <div class="right">{{ $factura->total }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="info-aside">
            <div class="qr">
                <img src="{{ public_path('files/billings/qr/' . $factura->qr) }}">
            </div>
            <div class="info">
                <div class="method">
                    <div class="w-30">
                        <b>FORMA DE PAGO:</b>
                        <span>CONTADO</span>
                    </div>
                    <div class="w-30">
                        <b>FECHA DE EMISI&Oacute;N:</b>
                        <span>{{ $factura->fecha_emision }} {{ $factura->hora }}</span>
                    </div>
                    <div class="w-30">
                        <b>IMPORTE TOTAL:</b>
                        <span>{{ $factura->total }}</span>
                    </div>
                </div>
                <div class="secondary">
                    <p>Representaci&oacute;n impresa del Comprobante Electr&oacute;nico, Consulte su comprobante en:</p>
                    <a class="link-search" href="https://mytems.cloud.com/buscar">https://facturacion.mytems.cloud/buscar</a>
                </div>
                
            </div>
        </div>
    </body>
</html>