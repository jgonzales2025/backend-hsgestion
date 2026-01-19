<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Guía de Remisión Electrónica {{ $dispatchNote['serie'] }}-{{ $dispatchNote['correlativo'] }}</title>
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
    </style>
</head>

<body>
    <header>
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <img src="{{ public_path('storage/logo/logocyberhouse.jpg') }}" class="logo" alt="Logo">
                    <div class="company-info" style="margin-top: 5px;">
                        <div class="company-name">{{ $dispatchNote['company']['name'] ?? 'N/A' }}</div>
                        <div class="company-address">
                            {{ $dispatchNote['branch']['direccion'] ?? $dispatchNote['company']['address'] ?? 'N/A' }}
                        </div>
                        <div class="company-address">TELF: {{ $dispatchNote['company']['telefono'] ?? 'N/A' }}</div>
                        <div class="company-address">CORREO: {{ $dispatchNote['company']['correo'] ?? 'N/A' }}</div>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <div class="ruc-box">
                        <div class="ruc-number">R.U.C. {{ $dispatchNote['company']['ruc'] ?? 'N/A' }}</div>
                        <div class="doc-title">GUÍA DE REMISIÓN ELECTRÓNICA</div>
                        <div class="doc-number">{{ $dispatchNote['serie'] ?? 'N/A' }} -
                            {{ str_pad($dispatchNote['correlativo'] ?? 0, 8, '0', STR_PAD_LEFT) }}
                        </div>
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
        <div class="section-title">DATOS DEL DESTINATARIO</div>
        <table class="info-table">
            <tr>
                <td class="label">R.U.C. / DNI:</td>
                <td>{{ $dispatchNote['customer']['ruc'] ?? 'N/A' }}</td>
                <td class="label">RAZÓN SOCIAL:</td>
                <td>{{ $dispatchNote['customer']['name'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">DIRECCIÓN LLEGADA:</td>
                <td colspan="3">
                    {{ !empty($dispatchNote['destination_branch_client_id']['name']) ? $dispatchNote['destination_branch_client_id']['name'] : ($dispatchNote['customer']['address'] ?? 'N/A') }}
                </td>
            </tr>
        </table>

        <!-- Shipment Information -->
        <div class="section-title" style="margin-top: 10px;">DATOS DEL TRASLADO</div>
        <table class="info-table">
            <tr>
                <td class="label">FECHA EMISIÓN:</td>
                <td>{{ $dispatchNote['date'] ?? 'N/A' }}</td>
                <td class="label">FECHA TRASLADO:</td>
                <td>{{ $dispatchNote['date'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">MOTIVO TRASLADO:</td>
                <td>{{ strtoupper($dispatchNote['emission_reason']['name'] ?? 'VENTA') }}</td>
                <td class="label">PESO TOTAL:</td>
                <td>{{ number_format($dispatchNote['total_weight'] ?? 0, 4) }} KGM</td>
            </tr>
            <tr>
                <td class="label">DIRECCIÓN PARTIDA:</td>
                <td colspan="3">
                    {{ $dispatchNote['branch']['direccion'] ?? $dispatchNote['company']['address'] ?? 'N/A' }}
                </td>
            </tr>
        </table>

        <!-- Transport Information -->
        <div class="section-title" style="margin-top: 10px;">DATOS DEL TRANSPORTE</div>
        <table class="info-table">
            <tr>
                <td class="label">CONDUCTOR:</td>
                <td>
                    {{ ($dispatchNote['conductor']['name'] ?? '') . ' ' . ($dispatchNote['conductor']['pat_surname'] ?? '') . ' ' . ($dispatchNote['conductor']['mat_surname'] ?? '') }}
                    @if(empty($dispatchNote['conductor']['name'])) N/A @endif
                </td>
                <td class="label">LICENCIA:</td>
                <td>{{ $dispatchNote['conductor']['license'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">EMPRESA TRANSP.:</td>
                <td>{{ $dispatchNote['transport']['name'] ?? 'N/A' }}</td>
                <td class="label">RUC TRANSP.:</td>
                <td>{{ $dispatchNote['transport']['ruc'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">PLACA VEHÍCULO:</td>
                <td colspan="3">{{ $dispatchNote['license_plate'] ?? 'N/A' }}</td>
            </tr>
        </table>

        <!-- Reference Document -->
        @if(!empty($dispatchNote['serie_referencia']) || !empty($dispatchNote['correlativo_referencia']))
            <div class="section-title" style="margin-top: 10px;">DOC. REFERENCIA</div>
            <table class="info-table">
                <tr>
                    <td class="label">DOCUMENTO:</td>
                    <td>{{ ($dispatchNote['serie_referencia'] ?? '') . '-' . ($dispatchNote['correlativo_referencia'] ?? '') }}
                    </td>
                    <td class="label">OBSERVACIONES:</td>
                    <td>{{ $dispatchNote['observations'] ?? '-' }}</td>
                </tr>
            </table>
        @endif

        <!-- Articles -->
        <table class="products-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 10%;">CANT</th>
                    <th style="width: 15%;">UNIDAD</th>
                    <th style="width: 75%; text-align: left; padding-left: 10px;">DESCRIPCIÓN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dispatchArticles as $detalle)
                    <tr>
                        <td style="text-align:center;">{{ $detalle['quantity'] ?? 0 }}</td>
                        <td style="text-align:center;">{{ $detalle['unidad']['siglas'] ?? 'UND' }}</td>
                        <td style="text-align:left; padding-left: 10px;">
                            {{ $detalle['name'] ?? 'N/A' }}
                            @if (!empty($detalle['serials']))
                                <br>
                                <span style="font-size: 8px; color: #555;">
                                    Series:
                                    @foreach ($detalle['serials'] as $serial)
                                        {{ $serial }}@if (!$loop->last), @endif
                                    @endforeach
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- QR Code -->
        <div style="width: 100%; margin-top: 20px; text-align: right;">
            @if(isset($qrCode))
                <div
                    style="display: inline-block; border: 1px solid #ddd; padding: 10px; border-radius: 5px; background-color: #f9f9f9; text-align: center;">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" style="width: 50px; height: 50px;">
                    <div style="font-size: 8px; margin-top: 5px; color: #666;">Escanea para verificar</div>
                </div>
            @endif
            <div
                style="display: inline-block; margin-left: 10px; vertical-align: top; font-size: 9px; color: #333; max-width: 150px; text-align: left;">
                <strong>REPRESENTACIÓN IMPRESA DE GUÍA DE REMISIÓN ELECTRÓNICA</strong>
            </div>
        </div>
    </div>
</body>

</html>