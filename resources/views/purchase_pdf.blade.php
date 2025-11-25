<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra {{ $purchase['serie'] }}-{{ $purchase['correlative'] }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
        .header { margin-bottom: 12px; }
    </style>
    </head>
<body>
    <div class="header">
        <h1>Compra</h1>
        <div>
            <strong>Serie:</strong> {{ $purchase['serie'] }}<br>
            <strong>Correlativo:</strong> {{ $purchase['correlative'] }}<br>
            <strong>Fecha:</strong> {{ $purchase['date'] }}<br>
            <strong>Proveedor:</strong> {{ $purchase['supplier']['name'] }}<br>
            <strong>Sucursal:</strong> {{ $purchase['branch']['name'] }}
        </div>
    </div>

    <h2>Detalles</h2>
    <table>
        <thead>
            <tr>
                <th>Artículo</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Sub total</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($details as $item)
            <tr>
                <td>{{ $item['article_id'] }}</td>
                <td>{{ $item['description'] }}</td>
                <td>{{ $item['cantidad'] }}</td>
                <td>{{ $item['precio_costo'] }}</td>
                <td>{{ $item['descuento'] }}</td>
                <td>{{ $item['sub_total'] }}</td>
                <td>{{ $item['total'] ?? '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2>Guías de ingreso asociadas</h2>
    <ul>
        @foreach($entry_guide as $id)
            <li>{{ $id }}</li>
        @endforeach
    </ul>
</body>
</html>
