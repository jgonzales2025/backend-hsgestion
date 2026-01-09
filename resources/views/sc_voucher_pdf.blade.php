<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Voucher {{ $scVoucher['nroope'] ?? '' }}</title>
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
            font-size: 10px;
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
            text-align: center;
            justify-content: center;
            align-items: center;
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

        .electronic-label {
            background-color: transparent;
            color: #000;
            font-weight: bold;
            font-size: 8px;
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
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-box">
                    <div class="electronic-label">VOUCHER ELECTRÓNICO</div>
                    <div class="invoice-type">VOUCHER</div>
                    <div class="invoice-number">{{ $scVoucher['nroope'] }}</div>
                </div>
            </div>
        </div>

        <!-- Supplier Information -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">BENEFICIARIO:</div>
                <div class="info-value">{{ $scVoucher['codigo']['name'] }}</div>
            </div>
        </div>

        <!-- Purchase Information -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">FECHA EMISIÓN:</div>
                <div class="info-value">{{ date('d/m/Y', strtotime($scVoucher['fecha'])) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">FORMA PAGO:</div>
                <div class="info-value">{{ $scVoucher['tipopago']['name'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">METODO PAGO:</div>
                <div class="info-value">{{ $scVoucher['medpag']['name'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">MONEDA:</div>
                <div class="info-value">{{ $scVoucher['tipmon']['name'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">GLOSA:</div>
                <div class="info-value">{{ $scVoucher['glosa'] ?? '' }}</div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>

                    <th style="width: 15%;">CUENTA</th>
                    <th style="width: 50%;">DESCRIPCIÓN</th>
                    <th style="width: 15%;">IMPORTE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $index => $item)
                    <tr>

                        <td class="center">{{ $item['codcon'] }}</td>
                        <td>{{ $item['glosa'] }}</td>
                        <td class="right">
                            {{ number_format(($scVoucher['tipmon']['name'] == 'DOLARES' || $scVoucher['tipmon']['id'] == 2) ? $item['impdol'] : $item['impsol'], 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="clearfix">
            <div class="totals-section">
                <div class="totals-row">
                    <div class="totals-label">TOTAL:</div>
                    <div class="totals-value bold">{{ number_format($scVoucher['total'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>