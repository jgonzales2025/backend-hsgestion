<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Guía de Remisión Electrónica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .header td {
            border: none;
            padding: 2px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .no-border {
            border: none !important;
        }
    </style>
</head>

<body>
    <table class="header">
        <tr>
            <td width="60%">
                <strong>EMPRESA:
                    {{ $dispatchNote->getCompany()?->getCompanyName() ?? 'EMPRESA NO ESPECIFICADA' }}</strong><br>
                RUC: {{ $dispatchNote->getCompany()?->getRuc() ?? 'NO DISPONIBLE' }}<br>
                {{ $dispatchNote->getCompany()?->getAddress() ?? 'Sin dirección registrada' }}
            </td>
            <td class="text-center" style="border: 1px solid #000; padding: 5px;">
                <strong>GUÍA DE REMISIÓN ELECTRÓNICA</strong><br>
                {{ $dispatchNote->getSerie() ?? '' }}-{{ $dispatchNote->getCorrelativo() ?? '' }}
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td><strong>FECHA EMISIÓN:</strong>
                {{ $dispatchNote->getCreatedFecha() ?? 'NO ESPECIFICADA' }}
            </td>
            <td><strong>MOTIVO:</strong> {{ $dispatchNote->getEmissionReason()?->getDescription() ?? 'VENTA' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>DESTINATARIO:</strong>
                {{ $dispatchNote->getDestinationAddressCustomer() ?? 'NO ESPECIFICADO' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="50%">
                <strong>TRANSPORTISTA</strong><br>
                <strong>N° PLACA:</strong> {{ $dispatchNote->getLicensePlate() ?? 'NO ESPECIFICADA' }}<br>

                <strong>
                    {{ strtolower($dispatchNote->getTransferType() ?? '') === 'publico' ? 'EMPRESA DE TRANSPORTE:' : 'CONDUCTOR:' }}
                </strong>
                {{ strtolower($dispatchNote->getTransferType() ?? '') === 'publico'
    ? ($dispatchNote->getTransport()?->getCompanyName() ?? 'EMPRESA NO ESPECIFICADA')
    : ($dispatchNote->getConductor()?->getName() ?? 'CONDUCTOR NO ESPECIFICADO')
}}
            </td>
            <td width="50%">
                <strong>EMPRESA TRANSPORTE</strong><br>
                <strong>RUC:</strong> {{ $dispatchNote->getTransport()?->getRuc() ?? 'NO ESPECIFICADO' }}<br>
                <strong>RAZÓN SOCIAL:</strong>
                {{ $dispatchNote->getTransport()?->getCompanyName() ?? 'NO ESPECIFICADA' }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="10%">article_id</th>
                <th width="10%">quantity</th>
                <th width="60%">description</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($dispatchArticles) && is_iterable($dispatchArticles))
                @foreach($dispatchArticles as $item)
                    <tr>

                        <td class="text-center">{{ $item['article_id'] ?? '-' }}</td>
                        <td class="text-center">{{ $item['quantity'] ?? 0 }}</td>
                        <td>{{ $item['description'] ?? 'SIN DESCRIPCIÓN' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" class="text-right"><strong>TOTAL PESO (kg):</strong></td>
                    <td class="text-right"><strong>{{ number_format($dispatchNote->getTotalWeight() ?? 0, 2) }}</strong>
                    </td>
                </tr>

            @endif

            <tr>
                <td colspan="3" class="text-right"><strong>TOTAL PESO (kg):</strong></td>
                <td class="text-right"><strong>{{ number_format($dispatchNote->getTotalWeight() ?? 0, 2) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <tr>
            <td class="no-border"><strong>OBSERVACIONES:</strong> {{ $dispatchNote->getObservations() ?? 'Ninguna' }}
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-top: 20px; font-size: 9px;">
        Documento generado el {{ date('d/m/Y H:i:s') }}
    </div>
</body>

</html>