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

        <!-- ENCABEZADO -->
        <table class="header-table">
            <tr>
                <td width="60%">
                    @if($dispatchNote->cia->logo_horizontal && file_exists(public_path('storage/cias/' . $dispatchNote->cia->logo_horizontal)))
                        <img src="{{ public_path('storage/cias/' . $dispatchNote->cia->logo_horizontal) }}"
                            class="logo"><br>
                    @endif
                    <div class="company-info">
                        <strong>{{ strtoupper($dispatchNote->cia->descripcion) }}</strong><br>
                        DIRECC: {{ $dispatchNote->cia->direccion }}<br>
                        TELF: {{ $dispatchNote->cia->telefono ?? '' }}<br>
                        CORREO: {{ $dispatchNote->cia->correo ?? '' }}
                    </div>
                </td>
                <td width="40%">
                    <div class="company-ruc">
                        <h2>R.U.C. {{ $dispatchNote->cia->ruc }}</h2>
                        <h3>GUÍA DE REMISIÓN ELECTRÓNICA</h3>
                        <h4>{{ $dispatchNote->serie }} N°{{ str_pad($dispatchNote->correlativo, 8, '0', STR_PAD_LEFT) }}
                        </h4>
                    </div>
                </td>
            </tr>
        </table>

        <!-- DATOS PRINCIPALES -->
        <table class="info-table" style="margin-top:10px;">
            <tr>
                <td><strong>EMISIÓN</strong><br>{{ $dispatchNote->fecha }}</td>
                <td><strong>TRASLADO</strong><br>{{ $dispatchNote->fecha }}
                </td>
                <td><strong>DESTINATARIO</strong><br>{{ $dispatchNote->transport_company->razon_social ?? '' }}</td>

                <td><strong>RUC</strong><br>{{ $dispatchNote->cia->ruc }}</td>
                <td><strong>MOTIVO TRASLADO</strong><br>{{ strtoupper($dispatchNote->motivo_traslado ?? 'VENTA') }}</td>
            </tr>
        </table>

        <!-- DIRECCIONES -->
        <table class="info-table">
            <tr>
                <td width="50%"><strong>DIRECCIÓN PARTIDA</strong><br>{{ $dispatchNote->cia->direccion }}</td>
                <td width="50%"><strong>DIRECCIÓN DESTINO <br></strong>
                    @if (is_null($dispatchNote->direccion_destino_cliente))
                        {{ $dispatchNote->sucursal->direccion }}
                    @elseif ($dispatchNote->direccion_destino_cliente == 0)
                        {{ $dispatchNote->customer->direccion }}
                    @elseif ($dispatchNote->direccion_destino_cliente > 0)
                        {{ $dispatchNote->address->descripcion }}
                    @endif

                </td>
            </tr>
        </table>

        <!-- TRANSPORTE -->
        <table class="info-table">
            <tr>
                <td width="50%">
                    <strong>DATOS DEL TRANSPORTISTA</strong><br>
                    <strong>N° PLACA:</strong> {{ $dispatchNote->placa ?? '' }}<br>
                    <strong>CONDUCTOR:</strong>
                    {{ $dispatchNote->conductor->nombre . ' ' . $dispatchNote->conductor->apellido_paterno . ' ' .
    $dispatchNote->conductor->apellido_materno ?? '' }}<br>
                    <strong>LIC. CONDUCIR:</strong> {{ $dispatchNote->conductor->licencia ?? '' }}
                </td>
                <td width="50%">
                    <strong>EMPRESA DE TRANSPORTE</strong><br>
                    <strong>RUC:</strong> {{ $dispatchNote->transport_company->ruc ?? ''  }}<br>
                    <strong>RAZÓN SOCIAL:</strong>{{ $dispatchNote->transport_company->razon_social ?? ''  }}<br>
                    <strong>PESO TOTAL:</strong> {{ number_format($dispatchNote->peso_total, 4) }} KGM
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
                @foreach($dispatchNote->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->unidad->siglas ?? 'UND' }}</td>
                        <td style="text-align:center;">{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->producto->descripcion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- PIE -->
        <table class="info-table" style="margin-top:6px;">
            <tr>
                <td>
                    <strong>TIPO Y N° DE COMPROBANTE DE PAGO:</strong>
                    {{ $dispatchNote->serie_referencia . '-' . $dispatchNote->correlativo_referencia ?? '' }}
                </td>
            </tr>
            <tr>
                <td><strong>OBSERVACIÓN:</strong> {{ $dispatchNote->observacion ?? '' }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>NO SE ACEPTAN CAMBIOS NI DEVOLUCIONES CON DAÑOS FÍSICOS O ACCESORIOS FALTANTES, SOLO POR FALLAS DE
                FABRICACIÓN</p>
            <p>Autorizado mediante resolución N° {{ $dispatchNote->cia->resolucion ?? '0180050002825' }}</p>
            <p>Representación impresa - Documento Electrónico</p>
            <p>Podrá ser consultada en:
                <strong>{{ $dispatchNote->cia->pagina_web ?? 'http://www.supertec.com.pe/cdpelectronico' }}</strong>
            </p>
        </div>


    </div>
</body>

</html>