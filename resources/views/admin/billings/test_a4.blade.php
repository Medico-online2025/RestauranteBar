<html>
    <head>
        <style>
            *{
                box-sizing: border-box;
                font-size: 12px;
                font-family: sans-serif;
            }
            .header{
                margin-bottom: 15px;
                font-size: 14px;
            }
            .header .logo{
                /* display: inline-block; */
                height: 100px;
                width: 10%;
            }
            .header .logo img{
                width: 100%;
                height: auto
            }
            .header .data{
                /* display: inline-block; */
                width: 70%;
                text-align: center
            }
            .data-ruc{
                /* display: inline-block; */
                width: 20%;
                border: 1px solid black;
                border-radius: 10px;
            }
            .data-ruc>div{
                padding: 5px;
                text-align: center;
            } 
            .data-ruc>div.name{
                background: #aaa
            }
            .user{
                display: inline-block;
                border: 1px solid black;
                border-radius: 10px;
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
                border-radius: 10px;
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
                border-radius: 10px;
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
                border-radius: 10px;
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
                border-radius: 10px;
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
            }
            .info-aside .info .method{
                border-radius: 15px;
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
                border-radius: 15px;
                border: 1px solid black;
                padding: 5px;
            }
            .center{
                text-align: center;
            }
            .right{
                text-align: right;
            }
        </style>
    </head>
    <body>
        <table class="header">
            <tr>
                <td class="logo">
                    <img src="https://img.freepik.com/vector-premium/imagen-dibujos-animados-hongo-palabra-hongo_587001-200.jpg?w=2000" widht="100%" height="100%">
                </td>
                <td class="data">
                    <div class="data-name">
                        LC KAIZEN GROUP S.A.C.
                    </div>
                    <div class="data-direction">
                        AV. JORGE BASADRE / PATRICIO MELENDEZ S/N INT 132-133 ASOC.<br>
                        MICAELA BASTIDAS<br>
                        TACNA - TACNA - TACNA - PERU<br>
                        <b>Teléfono: </b>987654321
                        <b>Correo: </b>dorisgonzalo9@gmail.com
                    </div>
                </td>
                <td class="data-ruc">
                    <div class="ruc">R.U.C. 10874569320</div>
                    <div class="name">FACTURA ELECTRÓNICA</div>
                    <div class="number">F001-00000023</div>
                </td>
            </tr>
        </table>
        <div class="user">
            <b class="w-15">NOMBRE</b>
            <span class="w-50">: AGRLICHT PERU S.A.C.</span>
            <b class="w-15">MONEDA</b>
            <span class="w-20">: SOLES</span>
            
            <b class="w-15">RUC</b>
            <span class="w-50">: 20552103816</span>
            <b class="w-15">VENDEDOR</b>
            <span class="w-20">: ADMINISTRADOR</span>
            <b class="w-15">DIRECCÓN</b>
            <span class="w-50">: PJ. JORGE BASADRE NRO 158 URB. POP LA UNIVERSAL 2DA ET</span>
        </div>
        <div class="dates">
            <div class="w-25">
                <b>Condición de Pago:</b>
                <span>CONTADO</span>
            </div>
            <div class="w-25">
                <b>Fecha de Emisión:</b>
                <span>06/08/2023</span>
            </div>
            <div class="w-25">
                <b>Fecha de Vencimiento:</b>
                <span>06/08/2023</span>
            </div>
            <div class="w-25">
                <b>Orden de Compra:</b>
                <span>-</span>
            </div>
        </div>
        <table class="description">
            <thead>
                <th class="row-1">#</th>
                <th class="row-1">CODIGO</th>
                <th class="row-2">DESCRIPCIÓN</th>
                <th class="row-1">CANT.</th>
                <th class="row-1">UND.</th>
                <th class="row-1">P.UNIT.</th>
                <th class="row-1">TOTAL</th>
            </thead>
            <tbody>
                <tr>
                    <td class="center">1</td>
                    <td>P002</td>
                    <td>ALFOMBRA 200 X 180 X 1 CM BORDE VERDE</td>
                    <td class="center">1</td>
                    <td class="center">NIU</td>
                    <td class="right">41.00</td>
                    <td class="right">41.00</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="price-text">
            <span>SON: </span>
            <span>CUARENTA Y UN CON 00/100 SOLES</span>
        </div>
        <table class="all">
           <tr>
                <td class="observation">
                    <b>OBSERVACIONES</b>
                    <span></span>
                </td>
                <td class="all-pay">
                    <div class="item">
                        <div class="left">OP. GRAVADAS: S/.</div>
                        <div class="right">34.75</div>
                    </div>
                    <div class="item">
                        <div class="left">IGV: S/.</div>
                        <div class="right">34.75</div>
                    </div>
                    <div class="item bold">
                        <div class="left">TOTAL A PAGAR: S/.</div>
                        <div class="right">34.75</div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="info-aside">
            <div class="qr">
                <img src="https://img.freepik.com/vector-premium/imagen-dibujos-animados-hongo-palabra-hongo_587001-200.jpg?w=2000">
            </div>
            <div class="info">
                <div class="method">
                    <div class="w-30">
                        <b>METODO DE PAGO:</b>
                        <span>EFECTIVO</span>
                    </div>
                    <div class="w-30">
                        <b>FECHA DE PAGO:</b>
                        <span>2023-08-06 14:21:35</span>
                    </div>
                    <div class="w-30">
                        <b>IMPORTE TOTAL:</b>
                        <span>41.00</span>
                    </div>
                </div>
                <div class="secondary">
                    <p>Representación impresa del Comprobante Electronico, Consulte su comprobante en:</p>
                    <a href="https://demo.invoicingdata.com/buscar">https://demo.invoicingdata.com/buscar</a>
                    <hr>
                    <b>Autorizado mediante Resolución de Intendencia No.034-005-0005315</b>
                </div>
                
            </div>
        </div>
    </body>
</html>