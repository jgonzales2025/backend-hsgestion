<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura Electrónica</title>

    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            margin-top: 3cm;
            margin-bottom: 2.3cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2.5cm;
            background-color: #fff;
            padding-top: 0.3cm;
            padding-left: 1.5cm;
            padding-right: 1.5cm;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2.2cm;
            background-color: #fff;
            text-align: center;
            padding: 5px 10px;
        }

        .logo {
            max-width: 150px;
            max-height: 60px;
        }

        .footer-logo {
            max-width: 120px;
        }

        .company-info {
            text-align: left;
            font-size: 9px;
            margin-top: 5px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #000;
        }

        .company-address {
            font-size: 9px;
            color: #555;
        }

        .ruc-box {
            border: 1px solid #000;
            border-radius: 8px;
            text-align: center;
            padding: 10px;
        }

        .ruc-number {
            font-size: 14px;
            font-weight: bold;
        }

        .doc-title {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
            background-color: #333;
            color: #fff;
            padding: 5px;
        }

        .doc-number {
            font-size: 14px;
            font-weight: bold;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 5px;
            margin-top: 10px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ccc;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .info-table td {
            padding: 4px;
            vertical-align: top;
            font-size: 10px;
        }

        .label {
            font-weight: bold;
            color: #444;
        }

        .products-table th {
            background-color: #f0f0f0;
            color: #000;
            padding: 6px;
            font-size: 10px;
            text-align: center;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }

        .products-table td {
            padding: 6px;
            font-size: 10px;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
        }

        .totals-table td {
            padding: 5px;
        }

        .codigoQr {
            border: 1px solid #000;

        }
    </style>
</head>

<body>
    <header>
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <img src="{{ resource_path('img/loogohsperu.jpg') }}" class="logo" alt="HS Peru">
                    <div class="company-info">
                        <div class="company-name">GRUPO COMPUTEL S.A.C.</div>
                        <div class="company-address">AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA</div>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <div class="ruc-box">
                        <div class="ruc-number">R.U.C. {{ $dispatchNote['company']['ruc'] ?? '20537005514' }}</div>
                        <div class="doc-title">FACTURA ELECTRÓNICA</div>
                        <div class="doc-number">{{ $dispatchNote['serie'] }} - {{ str_pad($dispatchNote['correlativo'], 8, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <div style="position: relative; width: 100%; height: 100%;">
            @if(isset($qrCode))
            <div style="position: absolute; bottom: 5px; right: 10px;">
                <img src="data:image/png;base64,{{ $qrCode }}" style="width: 80px; height: 80px;" alt="QR Code">
            </div>
            @endif
        </div>
    </footer>

    <div class="content">
        <!-- Customer Information -->
        <div style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 5px 0; margin-bottom: 15px;">
            <table class="info-table">
                <tr>
                    <td style="width: 15%;"><span class="label">R.U.C.:</span></td>
                    <td style="width: 35%;">{{ $dispatchNote['customer']['ruc'] ?? '20100073723' }}</td>
                    <td style="width: 15%;"><span class="label">RAZÓN SOCIAL:</span></td>
                    <td style="width: 35%;">{{ $dispatchNote['customer']['name'] ?? 'CORPORACION PERUANA DE PRODUCTOS QUIMICOS S.A.' }}</td>
                </tr>
                <tr>
                    <td><span class="label">DIRECCIÓN:</span></td>
                    <td colspan="3">{{ $dispatchNote['customer']['address'] ?? 'JR. LOS CLAVELES NRO. 265' }}</td>
                </tr>
                <tr>
                    <td><span class="label">USUARIO:</span></td>
                    <td>Admin Admin</td>
                    <td style="text-align: right;"><span class="label">FECHA DE IMPRESIÓN:</span> {{ now()->format('Y-m-d H:i:s') }}</td>
                    <td></td>
                </tr>
            </table>
        </div>

        <!-- Sale Details -->
        <div class="section-title">DETALLES DE LA VENTA</div>
        <table class="info-table">
            <tr>
                <td style="width: 15%;"><span class="label">FECHA EMISIÓN:</span></td>
                <td style="width: 25%;">{{ $dispatchNote['date'] ?? '2025-11-25' }}</td>
                <td style="width: 15%;"><span class="label">FECHA VENCIMIENTO:</span></td>
                <td style="width: 25%;">{{ $dispatchNote['date_referencia'] ?? '2025-12-25' }}</td>
                <td style="width: 10%;"><span class="label">TIPO DE CAMBIO:</span></td>
                <td style="width: 10%;">3.75</td>
            </tr>
            <tr>
                <td><span class="label">FORMA PAGO:</span></td>
                <td>CONTADO</td>
                <td><span class="label">MONEDA:</span></td>
                <td>DOLARES</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td><span class="label">SUCURSAL:</span></td>
                <td colspan="5">{{ $dispatchNote['branch']['direccion'] ?? 'PRINCIPAL - AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA' }}</td>
            </tr>
        </table>

        <!-- Articles -->
        <table class="products-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 5%;">ITEM</th>
                    <th style="width: 15%;">CÓDIGO</th>
                    <th style="width: 50%; text-align: left;">DESCRIPCIÓN</th>
                    <th style="width: 10%;">CANT</th>
                    <th style="width: 10%;">P. UNIT</th>
                    <th style="width: 10%;">TOTAL</th>z
                </tr>
            </thead>
            <tbody>
                @php
                $subtotal = 0;
                $item = 1;
                @endphp
                @foreach($dispatchArticles as $article)
                @php
                $price = $article['price'] ?? 0;
                $quantity = $article['quantity'] ?? 1;
                $total = $price * $quantity;
                $subtotal += $total;
                @endphp
                <tr>
                    <td style="text-align:center;">{{ $item++ }}</td>
                    <td style="text-align:center;">{{ $article['cod_fab'] ?? '' }}</td>
                    <td>{{ $article['name'] ?? '' }}</td>
                    <td style="text-align:center;">{{ $quantity }}</td>
                    <td style="text-align:right;">{{ number_format($price, 2) }}</td>
                    <td style="text-align:right;">{{ number_format($total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div style="width: 100%; margin-top: 20px;">
            <div style="float: right; width: 40%;">
                <table class="totals-table">
                    <tr>
                        <td style="text-align: right; font-weight: bold;">GRAVADO:</td>
                        <td style="text-align: right; border: 1px solid #ccc; background-color: #f9f9f9;">$ {{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right; font-weight: bold;">I.G.V.:</td>
                        <td style="text-align: right; border: 1px solid #ccc; background-color: #f9f9f9;">$ {{ number_format($subtotal * 0.18, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right; font-weight: bold;">TOTAL:</td>
                        <td style="text-align: right; border: 1px solid #000; font-weight: bold;">$ {{ number_format($subtotal * 1.18, 2) }}</td>
                    </tr>
                </table>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>

</html>