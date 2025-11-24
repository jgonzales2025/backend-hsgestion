<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { margin-bottom: 20px; }
        .title { font-size: 16px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f5f5; text-align: left; }
    </style>
    <title>Guía de Ingreso</title>
    </head>
<body>
    <div class="header">
        <div class="title">Guía de Ingreso</div>
        <div>Serie: {{ $entryGuide['serie'] ?? '' }} - Correlativo: {{ $entryGuide['correlative'] ?? '' }}</div>
        <div>Fecha: {{ $entryGuide['date'] ?? '' }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Artículo</th>
                <th>Descripción</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
        @foreach($articles as $item)
            <tr>
                <td>{{ $item['article_id'] }}</td>
                <td>{{ $item['description'] }}</td>
                <td>{{ $item['quantity'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
