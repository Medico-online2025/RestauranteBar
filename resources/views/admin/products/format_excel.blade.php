<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista de productos</title>
</head>

<body>
    <div id="items">
        <table id="table_items">
                <thead id="thead_items" style="font-size: 12px;">
                    <tr>
                        <th style="font-weight: bold; border: 1px solid #dee2e6; text-align: center;">codigo_interno</th>
                        <th style="font-weight: bold; border: 1px solid #dee2e6; text-align: center;">codigo_barras</th>
                        <th style="font-weight: bold; border: 1px solid #dee2e6; text-align: center;">descripcion</th>
                        <th style="font-weight: bold; border: 1px solid #dee2e6; text-align: center;">marca</th>
                        <th style="font-weight: bold; border: 1px solid #dee2e6; text-align: center;">presentacion</th>
                        <th style="font-weight: bold; border: 1px solid #dee2e6; text-align: center;">precio_compra</th>
                        <th style="font-weight: bold; border: 1px solid #dee2e6; text-align: center;">precio_venta</th>
                        <th style="font-weight: bold; border: 1px solid #dee2e6; text-align: center;">stock</th>
                        <th style="font-weight: bold; border: 1px solid #dee2e6; text-align: center;">fecha_vencimiento</th>
                        <th style="font-weight: bold; border: 1px solid #dee2e6; text-align: center;">tipo_producto</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($productos as $index => $item)
                        <tr>
                            <td style="border: 1px solid #dee2e6; text-align: center;">{{ $item["codigo_interno"] }}</td>
                            <td style="border: 1px solid #dee2e6; text-align: center;">{{ $item["codigo_barras"] }}</td>
                            <td style="border: 1px solid #dee2e6; text-align: center;">{{ $item["descripcion"] }}</td>
                            <td style="border: 1px solid #dee2e6; text-align: center;">{{ $item["marca"] }}</td>
                            <td style="border: 1px solid #dee2e6; text-align: center;">{{ $item["presentacion"] }}</td>
                            <td style="border: 1px solid #dee2e6; text-align: center;">{{ $item["precio_compra"] }}</td>
                            <td style="border: 1px solid #dee2e6; text-align: center;">{{ $item["precio_venta"] }}</td>
                            <td style="border: 1px solid #dee2e6; text-align: center;">{{ $item["stock"] }}</td>
                            @if ($item["fecha_vencimiento"] != NULL)
                            <td style="border: 1px solid #dee2e6; text-align: center;">{{ date('d/m/Y', strtotime($item["fecha_vencimiento"])) }}</td>
                            @else
                            <td>{{ NULL }}</td>
                            @endif
                            <td style="border: 1px solid #dee2e6; text-align: center;">{{ ($item["opcion"] == 1) ? "producto" : "servicio" }}</td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
    </div>
</body>
</html>