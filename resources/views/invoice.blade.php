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
                <td width="60%">aa

                <td width="40%">
                    <div class="company-ruc">
                        <h2>R.U.C. {{ $dispatchNote->getCompany()->getRuc() }}</h2>
                        <h3>GUÍA DE REMISIÓN ELECTRÓNICA</h3>
                        <h4>{{ $dispatchNote->getSerie() }}
                            N°{{ str_pad($dispatchNote->getCorrelativo(), 8, '0', STR_PAD_LEFT) }}
                        </h4>
                    </div>
                </td>
            </tr>
        </table>

        <!-- DATOS PRINCIPALES -->
        <table class="info-table" style="margin-top:10px;">
            <tr>
                <td><strong>EMISIÓN</strong><br>{{ $dispatchNote->getCreatedFecha() }}</td>
                <td><strong>TRASLADO</strong><br>{{ $dispatchNote->getCreatedFecha() }}
                </td>
                <td><strong>DESTINATARIO</strong><br>{{ $dispatchNote->getTransport()->razon_social ?? '' }}</td>

                <td><strong>RUC</strong><br>{{ $dispatchNote->getCompany()?->getRuc() }}</td>
                <td><strong>MOTIVO TRASLADO</strong><br>{{ strtoupper($dispatchNote->getEmissionReason()->getDescription() ?? 'VENTA') }}</td>
            </tr>
        </table>

        <!-- DIRECCIONES -->
        <table class="info-table">
            <tr>
                <td width="50%"><strong>DIRECCIÓN PARTIDA</strong><br>{{ $dispatchNote->getCompany()->getId() }}</td>
                <td width="50%"><strong>DIRECCIÓN DESTINO <br></strong>
                    @if (is_null($dispatchNote->getdestination_branch_client()))
                        {{ $dispatchNote->getBranch()->getAddress() }}
                    @elseif ($dispatchNote->getdestination_branch_client() == 0)
                        {{ $dispatchNote->getCustomerId()->direccion ?? '' }}
                    @elseif ($dispatchNote->getdestination_branch_client() > 0)

                    @endif

                </td>
            </tr>
        </table>

        <!-- TRANSPORTE -->
        <table class="info-table">
            <tr>
                <td width="50%">
                    <strong>DATOS DEL TRANSPORTISTA</strong><br>
                    <strong>N° PLACA:</strong> {{ $dispatchNote->getLicensePlate() ?? '' }}<br>
                    <strong>CONDUCTOR:</strong>
                    {{ $dispatchNote->getConductor()->getName() . ' ' . $dispatchNote->getConductor()->getPatSurname() . ' ' .
    $dispatchNote->getConductor()->getMatSurname() ?? '' }}<br>
                    <strong>LIC. CONDUCIR:</strong> {{ $dispatchNote->getConductor()->getLicense() ?? '' }}
                </td>
                <td width="50%">
                    <strong>EMPRESA DE TRANSPORTE</strong><br>
                    <strong>RUC:</strong> {{ $dispatchNote->getTransport()->getRuc() ?? ''  }}<br>
                    <strong>RAZÓN SOCIAL:</strong>{{ $dispatchNote->getTransport()->getCompanyName() ?? ''  }}<br>
                    <strong>PESO TOTAL:</strong> {{ number_format($dispatchNote->getTotalWeight(), 4) }} KGM
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
                        <td style="text-align:center;">{{ $detalle['quantity'] ?? '' }}</td>
                        <td>{{ $detalle['name'] ?? '' }}</td>
                        <td>{{ $detalle['cod_fab'] ?? '' }}</td>
                        <td>{{ $detalle['subtotal_weight'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- PIE -->
        <table class="info-table" style="margin-top:6px;">
            <tr>
                <td>
                    <strong>TIPO Y N° DE COMPROBANTE DE PAGO:</strong>
                    {{ $dispatchNote->serie_referencia ??'' . '-' . $dispatchNote->getDocReferencia()  ?? '' }}
                </td>
            </tr>
            <tr>
                <td><strong>OBSERVACIÓN:</strong> {{ $dispatchNote->getObservations() ?? '' }}</td>
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