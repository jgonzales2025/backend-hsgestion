<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Compra {{ $purchase['serie'] }}-{{ $purchase['correlative'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #000;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #000;
        }

        .header-left {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
            border-right: 1px solid #000;
        }

        .header-right {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
            text-align: center;
        }

        .company-name {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .company-info {
            font-size: 8px;
            line-height: 1.4;
        }

        .invoice-box {
            border: 2px solid #000;
            padding: 8px;
            text-align: center;
        }

        .invoice-type {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .electronic-label {
            background: #000;
            color: #fff;
            padding: 3px 8px;
            font-size: 8px;
            display: inline-block;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-weight: bold;
            font-size: 11px;
            margin-top: 5px;
        }

        .info-section {
            margin-bottom: 10px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .info-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            font-size: 8px;
        }

        .info-value {
            display: table-cell;
            width: 70%;
            font-size: 8px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .items-table th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 5px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
        }

        .items-table td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 8px;
        }

        .items-table td.center {
            text-align: center;
        }

        .items-table td.right {
            text-align: right;
        }

        .totals-section {
            float: right;
            width: 200px;
            margin-top: 10px;
        }

        .totals-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .totals-label {
            display: table-cell;
            width: 60%;
            text-align: right;
            padding-right: 10px;
            font-size: 8px;
        }

        .totals-value {
            display: table-cell;
            width: 40%;
            text-align: right;
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 8px;
        }

        .totals-value.bold {
            font-weight: bold;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="company-name">GRUPO COMPUTER S.A.C.</div>
                <div class="company-info">
                    RUC: 20000000000<br>
                    Dirección de la empresa<br>
                    PROVINCIA: LIMA - LIMA<br>
                    DEPARTAMENTO: LIMA<br>
                    SUCURSAL: {{ $purchase['branch']['name'] }}
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-box">
                    <div class="electronic-label">FACTURA ELECTRÓNICA</div>
                    <div class="invoice-type">{{ $purchase['reference_document_type']['description'] ?? 'FACTURA' }}</div>
                    <div class="invoice-number">{{ $purchase['serie'] }}-{{ str_pad($purchase['correlative'], 8, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>

        <!-- Supplier Information -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">PROVEEDOR:</div>
                <div class="info-value">{{ $purchase['supplier']['name'] }}</div>
            </div>
        </div>

        <!-- Purchase Information -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">FECHA EMISIÓN:</div>
                <div class="info-value">{{ date('d/m/Y', strtotime($purchase['date'])) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">FECHA VENCIMIENTO:</div>
                <div class="info-value">{{ date('d/m/Y', strtotime($purchase['date_ven'])) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">FORMA PAGO:</div>
                <div class="info-value">{{ $purchase['paymentType']['name'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">MONEDA:</div>
                <div class="info-value">{{ $purchase['currency']['name'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">OBSERVACIÓN:</div>
                <div class="info-value">{{ $purchase['observation'] ?? '' }}</div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ITEM</th>
                    <th style="width: 10%;">CÓDIGO</th>
                    <th style="width: 35%;">DESCRIPCIÓN</th>
                    <th style="width: 8%;">CANT.</th>
                    <th style="width: 8%;">P. UNIT</th>
                    <th style="width: 8%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                $subtotal = 0;
                @endphp
                @foreach($details as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center">{{ $item['article_id'] }}</td>
                    <td>{{ $item['description'] }}</td>
                    <td class="center">{{ number_format($item['cantidad'], 2) }}</td>
                    <td class="right">{{ number_format($item['precio_costo'], 2) }}</td>
                    <td class="right">{{ number_format($item['total'] ?? ($item['cantidad'] * $item['precio_costo']), 2) }}</td>
                </tr>
                @php
                $subtotal += $item['total'] ?? ($item['cantidad'] * $item['precio_costo']);
                @endphp
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="clearfix">
            <div class="totals-section">
                @php
                $igv_rate = 0.18;
                $gravada = $subtotal / (1 + $igv_rate);
                $igv = $subtotal - $gravada;
                @endphp
                <div class="totals-row">
                    <div class="totals-label">GRAVADA:</div>
                    <div class="totals-value">S/. {{ number_format($gravada, 2) }}</div>
                </div>
                <div class="totals-row">
                    <div class="totals-label">I.G.V.:</div>
                    <div class="totals-value">S/. {{ number_format($igv, 2) }}</div>
                </div>
                <div class="totals-row">
                    <div class="totals-label">TOTAL:</div>
                    <div class="totals-value bold">S/. {{ number_format($subtotal, 2) }}</div>
                </div>
            </div>
        </div>

        @if(isset($entry_guide) && count($entry_guide) > 0)
        <!-- Entry Guides Section -->
        <div style="margin-top: 60px; clear: both;">
            <div style="font-weight: bold; font-size: 9px; margin-bottom: 5px;">GUÍAS DE INGRESO ASOCIADAS:</div>
            <div style="font-size: 8px;">
                @foreach($entry_guide as $id)
                <span style="margin-right: 10px;">{{ $id }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</body>

</html>