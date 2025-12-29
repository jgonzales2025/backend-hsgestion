<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Guía de Remisión Electrónica</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
        }

        .container {
            width: 100%;
            padding: 10px 25px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo {
            width: 160px;
            max-height: 60px;
        }

        .company-info {
            font-size: 10px;
            line-height: 1.4;
        }

        .company-ruc {
            border: 1px solid #000;
            text-align: center;
            padding: 8px;
        }

        .company-ruc h2 {
            margin: 2px 0;
            font-size: 13px;
        }

        .company-ruc h3 {
            margin: 3px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .company-ruc h4 {
            margin: 3px 0;
            font-size: 12px;
            font-weight: bold;
        }

        /* Secciones */
        .section-title {
            background-color: #eaeaea;
            font-weight: bold;
            padding: 4px 6px;
            border: 1px solid #000;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 3px 5px;
        }

        .info-table td strong {
            color: #000;
        }

        .products-table {
            width: 100%;
            margin-top: 6px;
            font-size: 10px;
            border: 1px solid #000;
        }


        .products-table th,
        .products-table td {
            border: 1px solid #000;
            padding: 4px;
        }

        .products-table th {
            background-color: #eaeaea;
            text-align: center;
        }

        .products-table td:nth-child(1),
        .products-table td:nth-child(2),
        .products-table td:nth-child(3) {
            text-align: center;
        }

        .footer {
            font-size: 9px;
            margin-top: 10px;
            text-align: center;
        }

        .qr {
            text-align: center;
            margin-top: 8px;
        }

        .qr img {
            width: 70px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="text-align: left; margin-bottom: 5px;">
            <!-- <img src="{{ public_path('storage/image/guia_remision.jpg') }}" style="width: 150px; height: auto;"> -->
            <img src="{{ public_path('storage/logo/logocyberhouse.jpeg') }}" class="logo" alt="Logo" style="width: 150px; height: auto;">

        </div>

        <!-- ENCABEZADO -->
        <table class="header-table">
            <tr>
                <td width="60%">
                    @if(isset($dispatchNote['company']['logo_horizontal']) && $dispatchNote['company']['logo_horizontal'] && file_exists(public_path('storage/cias/' . $dispatchNote['company']['logo_horizontal'])))
                    <img src="{{ public_path('storage/cias/' . $dispatchNote['company']['logo_horizontal']) }}"
                        class="logo"><br>
                    @endif
                    <div class="company-info">
                        <strong>{{ strtoupper($dispatchNote['company']['name'] ?? 'N/A') }}</strong><br>
                        DIRECC: {{ $dispatchNote['branch']['direccion'] ?? $dispatchNote['company']['address'] ?? 'N/A' }}<br>
                        TELF: {{ $dispatchNote['company']['telefono'] ?? 'N/A' }}<br>
                        CORREO: {{ $dispatchNote['company']['correo'] ?? 'N/A' }}
                    </div>
                </td>
                <td width="40%">
                    <div class="company-ruc">
                        <h2>R.U.C. {{ $dispatchNote['company']['ruc'] ?? 'N/A' }}</h2>
                        <h3>GUÍA DE REMISIÓN ELECTRÓNICA</h3>
                        <h4>{{ $dispatchNote['serie'] ?? 'N/A' }} N°{{ str_pad($dispatchNote['correlativo'] ?? 0, 8, '0', STR_PAD_LEFT) }}
                        </h4>
                    </div>
                </td>
            </tr>
        </table>

        <!-- DATOS PRINCIPALES -->
        <table class="info-table" style="margin-top:10px;">
            <tr>
                <td><strong>EMISIÓN</strong><br>{{ $dispatchNote['date'] ?? 'N/A' }}</td>
                <td><strong>TRASLADO</strong><br>{{ $dispatchNote['date'] ?? 'N/A' }}
                </td>
                <td><strong>DESTINATARIO</strong><br>{{ $dispatchNote['customer']['name'] ?? 'N/A' }}</td>

                <td><strong>RUC</strong><br>{{ $dispatchNote['customer']['ruc'] ?? 'N/A' }}</td>
                <td><strong>MOTIVO
                        TRASLADO</strong><br>{{ strtoupper($dispatchNote['emission_reason']['name'] ?? 'VENTA') }}
                </td>
            </tr>
        </table>

        <!-- DIRECCIONES -->
        <table class="info-table">
            <tr>
                <td width="50%"><strong>DIRECCIÓN PARTIDA</strong><br>{{ $dispatchNote['branch']['direccion'] ?? $dispatchNote['company']['address'] ?? 'N/A' }}</td>
                <td width="50%"><strong>DIRECCIÓN DESTINO <br></strong>
                    {{ $dispatchNote['customer']['address'] ?? 'N/A' }}
                </td>
            </tr>
        </table>

        <!-- TRANSPORTE -->
        <table class="info-table">
            <tr>
                <td width="50%">
                    <strong>DATOS DEL TRANSPORTISTA</strong><br>
                    <strong>N° PLACA:</strong> {{ $dispatchNote['license_plate'] ?? 'N/A' }}<br>
                    <strong>CONDUCTOR:</strong>
                    {{ ($dispatchNote['conductor']['name'] ?? '') . ' ' . ($dispatchNote['conductor']['pat_surname'] ?? '') . ' ' . ($dispatchNote['conductor']['mat_surname'] ?? '') }}
                    @if(empty($dispatchNote['conductor']['name'])) N/A @endif<br>
                    <strong>LIC. CONDUCIR:</strong> {{ $dispatchNote['conductor']['license'] ?? 'N/A' }}
                </td>
                <td width="50%">
                    <strong>EMPRESA DE TRANSPORTE</strong><br>
                    <strong>RUC:</strong> {{ $dispatchNote['transport']['ruc'] ?? 'N/A'  }}<br>
                    <strong>RAZÓN SOCIAL:</strong>{{ $dispatchNote['transport']['name'] ?? 'N/A'  }}<br>
                    <strong>PESO TOTAL:</strong> {{ number_format($dispatchNote['total_weight'] ?? 0, 4) }} KGM
                </td>
            </tr>
        </table>

        <!-- DETALLES -->
        <table class="products-table">
            <thead>
                <tr>
                    <th>UND</th>
                    <th>CANT</th>
                    <th>DESCRIPCIÓN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dispatchArticles as $detalle)
                <tr>
                    <td>{{ $detalle['unidad']['siglas'] ?? 'UND' }}</td>
                    <td style="text-align:center;">{{ $detalle['quantity'] ?? 0 }}</td>
                    <td>{{ $detalle['name'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- PIE -->
        <table class="info-table" style="margin-top:6px;">
            <tr>
                <td>
                    <strong>TIPO Y N° DE COMPROBANTE DE PAGO:</strong>
                    {{ ($dispatchNote['serie_referencia'] ?? '') . '-' . ($dispatchNote['correlativo_referencia'] ?? '') }}
                    @if(empty($dispatchNote['serie_referencia']) && empty($dispatchNote['correlativo_referencia'])) N/A @endif
                </td>
            </tr>
            <tr>
                <td><strong>OBSERVACIÓN:</strong> {{ $dispatchNote['observations'] ?? 'N/A' }}</td>
            </tr>
        </table>

        <div class="footer">

            @if(isset($qrCode))
            <div class="qr">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
            </div>
            @endif
            <p>Representación impresa - Guia de Remisión Electrónica</p>
 
        </div>


    </div>
</body>

</html>