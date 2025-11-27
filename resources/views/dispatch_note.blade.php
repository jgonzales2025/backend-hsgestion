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
            margin-top: 2.5cm;
            margin-bottom: 1.5cm;
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
            height: 1.5cm;
            background-color: #fff;
            text-align: center;
            line-height: 1.5cm;
            font-size: 9px;
            border-top: 1px solid #ddd;
        }

        .logo {
            max-width: 120px;
            max-height: 50px;
        }

        .company-info {
            text-align: left;
            font-size: 9px;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            color: #000;
        }

        .company-address {
            font-size: 8px;
            color: #555;
        }

        .ruc-box {
            border: 2px solid #000;
            text-align: center;
            padding: 8px;
        }

        .ruc-number {
            font-size: 11px;
            font-weight: bold;
        }

        .doc-title {
            font-size: 14px;
            font-weight: bold;
            margin: 3px 0;
            background-color: #333;
            color: #fff;
            padding: 3px;
        }

        .doc-number {
            font-size: 12px;
            font-weight: bold;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            background-color: #eee;
            padding: 4px;
            margin-top: 8px;
            margin-bottom: 3px;
            border-bottom: 1px solid #ccc;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .info-table td {
            padding: 3px;
            vertical-align: top;
            font-size: 9px;
        }

        .label {
            font-weight: bold;
            color: #444;
        }

        .products-table th {
            background-color: #333;
            color: #fff;
            padding: 5px;
            font-size: 9px;
            text-align: center;
        }

        .products-table td {
            border-bottom: 1px solid #ddd;
            padding: 5px;
            font-size: 9px;
            vertical-align: middle;
        }

        .products-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .page-number:before {
            content: "Página " counter(page);
        }
    </style>
</head>

<body>
    <header>
        <table style="width: 100%;">
            <tr>
                <td style="width: 55%; vertical-align: top;">
                    {{-- Descomentar cuando tengas el logo: <img src="{{ public_path('storage/logo/hsperu_logo.png') }}" class="logo" alt="Logo"> --}}
                    <div class="company-info" style="margin-top: 3px;">
                        <div class="company-name">GRUPO COMPUTEL S.A.C.</div>
                        <div class="company-address">AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA</div>
                    </div>
                </td>
                <td style="width: 45%; vertical-align: top;">
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
        <div class="page-number"></div>
    </footer>

    <div class="content">
        <!-- Customer Information -->
        <table class="info-table">
            <tr>
                <td style="width: 15%;"><span class="label">R.U.C.:</span></td>
                <td style="width: 35%;">20100073723</td>
                <td style="width: 20%;"><span class="label">RAZÓN SOCIAL:</span></td>
                <td style="width: 30%;">CORPORACION PERUANA DE PRODUCTOS QUIMICOS S.A.</td>
            </tr>
            <tr>
                <td><span class="label">DIRECCIÓN:</span></td>
                <td colspan="3">JR. LOS CLAVELES NRO. 265</td>
            </tr>
            <tr>
                <td><span class="label">USUARIO:</span></td>
                <td>Admin Admin</td>
                <td><span class="label">FECHA DE IMPRESIÓN:</span></td>
                <td>{{ now()->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>

        <!-- Sale Details -->
        <div class="section-title">DETALLES DE LA VENTA</div>
        <table class="info-table">
            <tr>
                <td style="width: 20%;"><span class="label">FECHA EMISIÓN:</span></td>
                <td style="width: 30%;">{{ $dispatchNote['date'] ?? '2025-11-25' }}</td>
                <td style="width: 20%;"><span class="label">FECHA VENCIMIENTO:</span></td>
                <td style="width: 30%;">{{ $dispatchNote['date_referencia'] ?? '2025-12-25' }}</td>
                <td style="width: 20%;"><span class="label">TIPO DE CAMBIO:</span></td>
                <td style="width: 30%;">3.75</td>
            </tr>
            <tr>
                <td><span class="label">FORMA PAGO:</span></td>
                <td>CONTADO</td>
                <td><span class="label">MONEDA:</span></td>
                <td>DOLARES</td>
            </tr>
            <tr>
                <td><span class="label">SUCURSAL:</span></td>
                <td colspan="5">{{ $dispatchNote['branch']['direccion'] ?? 'PRINCIPAL - AV. GARCILAZO DE LA VEGA NRO. 1348 TDA 1A-178-179 LIMA - LIMA - LIMA' }}</td>
            </tr>
        </table>

        <!-- Articles -->
        <table class="products-table" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width: 10%;">ITEM</th>
                    <th style="width: 15%;">CÓDIGO</th>
                    <th style="width: 45%;">DESCRIPCIÓN</th>
                    <th style="width: 10%;">CANT</th>
                    <th style="width: 10%;">P. UNIT</th>
                    <th style="width: 10%;">TOTAL</th>
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
                    <td style="text-align:left; padding-left: 5px;">{{ $article['name'] ?? '' }}</td>
                    <td style="text-align:center;">{{ $quantity }}</td>
                    <td style="text-align:right;">{{ number_format($price, 2) }}</td>
                    <td style="text-align:right;">{{ number_format($total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div style="width: 100%; margin-top: 10px;">
            <div style="float: right; width: 45%;">
                <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                    <tr style="background-color: #eee;">
                        <td style="text-align: right; padding: 4px; border: 1px solid #ddd;"><strong>GRAVADO:</strong></td>
                        <td style="text-align: right; padding: 4px; border: 1px solid #ddd;">$ {{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr style="background-color: #eee;">
                        <td style="text-align: right; padding: 4px; border: 1px solid #ddd;"><strong>I.G.V.:</strong></td>
                        <td style="text-align: right; padding: 4px; border: 1px solid #ddd;">$ {{ number_format($subtotal * 0.18, 2) }}</td>
                    </tr>
                    <tr style="font-weight: bold; font-size: 10px; background-color: #ddd;">
                        <td style="text-align: right; padding: 4px; border: 1px solid #000;">TOTAL:</td>
                        <td style="text-align: right; padding: 4px; border: 1px solid #000;">$ {{ number_format($subtotal * 1.18, 2) }}</td>
                    </tr>
                </table>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>

</html>