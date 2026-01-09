<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $purchase->getTypeDocumentId() ? $purchase->getTypeDocumentId()->getDescription() : 'FACTURA ELECTRÓNICA' }} {{ $purchase->getSerie() }}-{{ $purchase->getCorrelative() }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            margin-top: 3cm;
            margin-bottom: 2cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
            background-color: #fff;
            padding-top: 0.5cm;
            padding-left: 1.5cm;
            padding-right: 1.5cm;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #fff;
            text-align: center;
            line-height: 1.5cm;
            font-size: 9px;
            border-top: 1px solid #ddd;
            width: 100%;
        }

        .logo {
            max-width: 250px;
            max-height: 90px;
        }

        .company-info {
            text-align: left;
        }

        .company-name {
            font-size: 10px;
            font-weight: bold;
            color: #000;
        }

        .company-address {
            font-size: 9px;
            color: #555;
        }

        .ruc-box {
            border: 1px solid #000;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
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
            padding: 2px;
            text-transform: uppercase;
        }

        .doc-number {
            font-size: 14px;
            font-weight: bold;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            background-color: #eee;
            padding: 5px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ccc;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 4px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #444;
            width: 120px;
        }

        .products-table th {
            background-color: #333;
            color: #fff;
            padding: 6px;
            font-size: 9px;
            text-align: center;
        }

        .products-table td {
            border-bottom: 1px solid #ddd;
            padding: 6px;
            font-size: 9px;
            vertical-align: middle;
        }

        .products-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .page-number:before {
            content: "Página " counter(page);
        }

        .totals-table {
            width: 250px;
            float: right;
            margin-top: 10px;
        }

        .totals-table td {
            padding: 4px;
            border: 1px solid #ddd;
        }

        .totals-label {
            font-weight: bold;
            background-color: #f9f9f9;
            text-align: right;
        }

        .totals-value {
            text-align: right;
        }
    </style>
</head>

<body>
    <header>
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <div class="company-info" style="margin-top: 5px;">
                        <div class="company-name">{{ $company ? $company->getCompanyName() : 'CYBERHOUSE TEC S.A.C.' }}</div>
                        <div class="company-address">
                            {{ $purchase->getBranch() ? $purchase->getBranch()->getAddress() : ($company ? $company->getAddress() : 'N/A') }}
                        </div>
                        <div class="company-address">TELF: N/A</div>
                        <div class="company-address">CORREO: N/A</div>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <div class="ruc-box">
                        <div class="doc-title">{{ $purchase->getTypeDocumentId() ? $purchase->getTypeDocumentId()->getDescription() : 'FACTURA ELECTRÓNICA' }}</div>
                        <div class="doc-number">{{ $purchase->getReferenceSerie() ?? 'N/A' }} -
                            {{ str_pad($purchase->getReferenceCorrelative() ?? 0, 8, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <div class="page-number"></div>
    </footer>

    <div class="content" style="margin-top: 0.5cm;">
        <!-- Supplier Information -->
        <div class="section-title">DATOS DEL PROVEEDOR</div>
        <table class="info-table">
            <tr>
                <td class="label">R.U.C. / DNI:</td>
                <td>{{ $purchase->getSupplier()->getDocumentNumber() ?? 'N/A' }}</td>
                <td class="label">RAZÓN SOCIAL:</td>
                <td>{{ $purchase->getSupplier()->getCompanyName() ?? $purchase->getSupplier()->getName() ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">DIRECCIÓN:</td>
                <td colspan="3">
                    @if(!empty($purchase->getSupplier()->getAddresses()) && isset($purchase->getSupplier()->getAddresses()[0]))
                    {{ $purchase->getSupplier()->getAddresses()[0]->getAddress() }}
                    @else
                    N/A
                    @endif
                </td>
            </tr>
        </table>

        <!-- Purchase Information -->
        <div class="section-title" style="margin-top: 10px;">DATOS DE LA COMPRA</div>
        <table class="info-table">
            <tr>
                <td class="label">FECHA EMISIÓN:</td>
                <td>{{ $purchase->getDate() ?? 'N/A' }}</td>
                <td class="label">FECHA VENC.:</td>
                <td>{{ $purchase->getDateVen() ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">TIPO PAGO:</td>
                <td>{{ $purchase->getPaymentType()->getName() ?? 'N/A' }}</td>
                <td class="label">MONEDA:</td>
                <td>{{ $purchase->getCurrency()->getName() ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">OBSERVACIONES:</td>
                <td colspan="3">{{ $purchase->getObservation() ?? '-' }}</td>
            </tr>
        </table>

        <!-- Articles -->
        <table class="products-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 10%;">CANT</th>
                    <th style="width: 15%;">UNIDAD</th>
                    <th style="width: 50%; text-align: left; padding-left: 10px;">DESCRIPCIÓN</th>
                    <th style="width: 12.5%;">P. UNIT</th>
                    <th style="width: 12.5%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->getDetComprasGuiaIngreso() as $detalle)
                <tr>
                    <td style="text-align:center;">{{ $detalle->getCantidad() ?? 0 }}</td>
                    <td style="text-align:center;">UND</td>
                    <td style="text-align:left; padding-left: 10px;">
                        {{ $detalle->getDescription() ?? 'N/A' }}
                    </td>
                    <td style="text-align:right;">{{ number_format($detalle->getPrecioCosto(), 2) }}</td>
                    <td style="text-align:right;">{{ number_format($detalle->getTotal(), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals-table">
            <tr>
                <td class="totals-label">SUBTOTAL:</td>
                <td class="totals-value">{{ number_format($purchase->getSubtotal(), 2) }}</td>
            </tr>
            <tr>
                <td class="totals-label">IGV (18%):</td>
                <td class="totals-value">{{ number_format($purchase->getIgv(), 2) }}</td>
            </tr>
            <tr>
                <td class="totals-label">TOTAL:</td>
                <td class="totals-value" style="font-weight: bold;">{{ number_format($purchase->getTotal(), 2) }}</td>
            </tr>
        </table>

        <div style="clear: both;"></div>

        <div style="width: 100%; margin-top: 20px; text-align: right;">
            @if(isset($qrCode))
            <div style="display: inline-block; border: 1px solid #ddd; padding: 10px; border-radius: 5px; background-color: #f9f9f9; text-align: center;">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" style="width: 50px; height: 50px;">
                <div style="font-size: 8px; margin-top: 5px; color: #666;">Escanea para verificar</div>
            </div>
            @endif
        </div>
    </div> 
</body>

</html>