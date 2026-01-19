<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $sale->getDocumentType()->getDescription() }} {{ $sale->getSerie() }}-{{ $sale->getDocumentNumber() }}
    </title>
    <style>
        @page {
            margin: 0cm;
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
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
            background-color: #fff;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1cm;
            background-color: #fff;
            text-align: center;
            line-height: 0.5cm;
            font-size: 9px;
            border-top: 1px solid #ddd;
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
            width: 100px;
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

        .amount-in-words {
            font-style: italic;
            font-size: 10px;
            /* border-top: 1px solid #ccc; */
            padding-top: 5px;
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
                        <div class="company-name">{{ $sale->getCompany()->getCompanyName() }}</div>
                        <div class="company-address">{{ $sale->getCompany()->getAddress() }}</div>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <div class="ruc-box">
                        <div class="ruc-number">R.U.C. {{ $sale->getCompany()->getRuc() }}</div>
                        <div class="doc-title">{{ strtoupper($sale->getDocumentType()->getDescription()) }}</div>
                        <div class="doc-number">{{ $sale->getSerie() }} -
                            {{ str_pad($sale->getDocumentNumber(), 8, '0', STR_PAD_LEFT) }}
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
        <div class="section-title">DATOS DEL CLIENTE</div>
        <table class="info-table">
            @php
                $customer = $sale->getCustomer();
                $isCompany = $customer->getCustomerDocumentType()->getId() == 2;
            @endphp

            @if($isCompany)
                <tr>
                    <td class="label">R.U.C.:</td>
                    <td>{{ $customer->getDocumentNumber() }}</td>
                    <td class="label" colspan="2" style="text-align: right;">RAZÓN SOCIAL:</td>
                    <td>{{ $customer->getCompanyName() }}</td>
                </tr>
            @else
                <tr>
                    <td class="label">DNI:</td>
                    <td>{{ $customer->getDocumentNumber() }}</td>
                    <td class="label">NOMBRE:</td>
                    <td>{{ $customer->getName() }} {{ $customer->getLastname() }} {{ $customer->getSecondLastname() }}</td>
                </tr>
            @endif

            <tr>
                <td class="label">DIRECCIÓN:</td>
                <td colspan="4">
                    @php
                        $addresses = $customer->getAddresses();
                        $address = $addresses && count($addresses) > 0 ? $addresses[0]->getAddress() : '';
                    @endphp
                    {{ $address }}
                </td>
            </tr>
            <tr>
                <td class="label">USUARIO:</td>
                <td>{{ $transactionLog->getUser()->getFirstname() }} {{ $transactionLog->getUser()->getLastname() }}
                </td>
                <td class="label" colspan="3" style="text-align: right;">FECHA DE IMPRESIÓN:</td>
                <td style="white-space: nowrap;">{{ now()->format('d/m/Y H:i:s') }}</td>
            </tr>
        </table>

        <!-- Sale Information -->
        <div class="section-title" style="margin-top: 10px;">
            {{ $sale->getDocumentType()->getId() == 16 ? 'DETALLES DE LA COTIZACIÓN' : 'DETALLES DE LA VENTA' }}
        </div>
        <table class="info-table">
            <tr>
                <td class="label">FECHA EMISIÓN:</td>
                <td>{{ $sale->getDate() }}</td>
                <td class="label">FECHA VENCIMIENTO:</td>
                <td>{{ $sale->getDueDate() }}</td>
                <td class="label">TIPO DE CAMBIO:</td>
                <td>{{ $sale->getParallelRate() }}</td>
            </tr>
            <tr>
                <td class="label">FORMA PAGO:</td>
                <td>{{ $sale->getPaymentType()->getName() }}</td>
                <td class="label">MONEDA:</td>
                <td>{{ $sale->getCurrencyType()->getName() }}</td>
            </tr>
            <tr>
                <td class="label">SUCURSAL:</td>
                <td colspan="4">{{ $sale->getBranch()->getName() }} - {{ $sale->getBranch()->getAddress() }}</td>
            </tr>
            @if($sale->getObservations())
                <tr>
                    <td class="label">OBSERVACIONES:</td>
                    <td colspan="3">{{ $sale->getObservations() }}</td>
                </tr>
            @endif
            @if(in_array($sale->getDocumentType()->getId(), [7, 8]))
                <tr>
                    <td class="label">MOTIVO:</td>
                    <td colspan="5">{{ $sale->getNoteReason()?->getDescription() ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">DOC. REFERENCIA:</td>
                    <td colspan="5">{{ $sale->getReferenceSerie() }} - {{ $sale->getReferenceCorrelative() }}</td>
                </tr>
            @endif
        </table>

        <!-- Articles -->
        <table class="products-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 10%;">CÓDIGO</th>
                    <th style="width: 55%; text-align: left; padding-left: 10px;">DESCRIPCIÓN</th>
                    <th style="width: 15%;">GARANTÍA</th>
                    <th style="width: 10%;">CANT</th>
                    <th style="width: 10%;">P. UNIT</th>
                    <th style="width: 10%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($saleArticles as $article)
                    <tr>
                        <td style="text-align:center;">{{ $article->getSku() }}</td>
                        <td style="text-align:left; padding-left: 10px;">
                            {{ $article->getDescription() }}
                            @if (!empty($article->serials))
                                <br>
                                <span style="font-size: 8px; color: #555;">
                                    Series:
                                    @foreach ($article->serials as $serial)
                                        {{ $serial }}@if (!$loop->last), @endif
                                    @endforeach
                                </span>
                            @endif
                        </td>
                        <td style="text-align:center;">{{ $article->getWarranty() }}</td>
                        <td style="text-align:center;">{{ $article->getQuantity() }}</td>
                        <td style="text-align:right;">{{ number_format($article->getUnitPrice(), 2) }}</td>
                        <td style="text-align:right;">{{ number_format($article->getSubtotal(), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div style="width: 100%;">
            <div style="width: 100%; margin-bottom: 0px;">
                <div class="amount-in-words" style="border-top: 1px solid #ccc; padding-top: 5px;">
                    <span style="font-weight: bold;"></span>
                    {{ \App\Shared\Infrastructure\Helpers\NumberToWords::convert($sale->getTotal(), $sale->getCurrencyType()->getName()) }}
                </div>
            </div>
            <div style="float: left; width: 55%; padding-right: 10px;">
                <!-- Bank Accounts Information -->
                <div style="border: 1px solid #333; padding: 4px; background-color: #f9f9f9;">
                    <div class="section-title" style="margin-bottom: 3px; font-size: 7px; padding: 2px;">
                        CUENTAS BANCARIAS
                    </div>

                    <table style="width: 100%; margin-bottom: 3px;">
                        <tr>
                            <td style="width: 50%; vertical-align: top; padding-right: 5px;">
                                <div style="font-weight: bold; font-size: 6px; margin-bottom: 2px; color: #000;">
                                    CUENTAS EN SOLES:</div>
                                <div style="font-size: 6px; margin-bottom: 1px;">
                                    <span style="font-weight: bold;">Banco BBVA:</span> 001101750100099775
                                </div>
                                <div style="font-size: 6px; margin-bottom: 1px;">
                                    <span style="font-weight: bold;">Banco BCP:</span> 1917319236075
                                </div>
                                <div style="font-size: 6px; margin-bottom: 1px;">
                                    <span style="font-weight: bold;">Yape:</span> 981206097
                                </div>
                            </td>
                            <td
                                style="width: 50%; vertical-align: top; padding-left: 5px; border-left: 1px solid #ddd;">
                                <div style="font-weight: bold; font-size: 6px; margin-bottom: 2px; color: #000;">
                                    CUENTAS EN DÓLARES:</div>
                                <div style="font-size: 6px; margin-bottom: 1px;">
                                    <span style="font-weight: bold;">Banco BBVA:</span> 001101750100099783
                                </div>
                                <div style="font-size: 6px; margin-bottom: 1px;">
                                    <span style="font-weight: bold;">Banco BCP:</span> 1917320109103
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div style="border-top: 1px solid #ddd; padding-top: 3px; margin-top: 3px;">
                        <div style="font-weight: bold; font-size: 6px; margin-bottom: 2px; color: #000;">CUENTAS
                            INTERBANCARIAS (CCI):</div>

                        <table style="width: 100%; margin-bottom: 2px;">
                            <tr>
                                <td style="width: 50%; vertical-align: top; padding-right: 5px;">
                                    <div style="font-weight: bold; font-size: 5px; margin-bottom: 1px; color: #333;">
                                        Soles:
                                    </div>
                                    <div style="font-size: 5px; margin-bottom: 1px;">
                                        <span style="font-weight: bold;">BBVA:</span> 01117500010009977577
                                    </div>
                                    <div style="font-size: 5px; margin-bottom: 1px;">
                                        <span style="font-weight: bold;">BCP:</span> 00219100731923607555
                                    </div>
                                </td>
                                <td
                                    style="width: 50%; vertical-align: top; padding-left: 5px; border-left: 1px solid #ddd;">
                                    <div style="font-weight: bold; font-size: 5px; margin-bottom: 1px; color: #333;">
                                        Dólares:</div>
                                    <div style="font-size: 5px; margin-bottom: 1px;">
                                        <span style="font-weight: bold;">BBVA:</span> 01117500010009978371
                                    </div>
                                    <div style="font-size: 5px; margin-bottom: 1px;">
                                        <span style="font-weight: bold;">BCP:</span> 00219100732010910357
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <div
                            style="background-color: #fff3cd; border: 1px solid #ffc107; padding: 2px; margin-top: 2px; border-radius: 2px;">
                            <div style="font-size: 5px; color: #856404; line-height: 1.2;">
                                <strong>IMPORTANTE:</strong> Transferir sólo si es inmediato.
                                <strong>OJO:</strong>
                                Verificar antes de confirmar abono que indique <strong>CYBERHOUSE TEC
                                    SAC</strong> en su
                                aplicación, caso contrario la mercadería será entregada una vez verificado el
                                abono en
                                nuestras cuentas.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="float: right; width: 40%;">
                <table style="width: 100%; border: 1px solid #333; margin-top: 0px; margin-bottom: 8px;">
                    <thead>
                        <tr style="background-color: #333; color: #fff;">
                            <th style="padding: 6px; text-align: center; font-size: 9px;">GRAVADO</th>
                            <th style="padding: 6px; text-align: center; font-size: 9px;">I.G.V.</th>
                            <th style="padding: 6px; text-align: center; font-size: 9px;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color: #f9f9f9;">
                            <td
                                style="padding: 8px; text-align: center; font-weight: bold; border-right: 1px solid #ddd;">
                                {{ $sale->getCurrencyType()->getCommercialSymbol() }}
                                {{ number_format($sale->getSubtotal(), 2) }}
                            </td>
                            <td
                                style="padding: 8px; text-align: center; font-weight: bold; border-right: 1px solid #ddd;">
                                {{ $sale->getCurrencyType()->getCommercialSymbol() }}
                                {{ number_format($sale->getIgv(), 2) }}
                            </td>
                            <td style="padding: 8px; text-align: center; font-weight: bold; background-color: #eee;">
                                {{ $sale->getCurrencyType()->getCommercialSymbol() }}
                                {{ number_format($sale->getTotal(), 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Retention/Detraction Information -->
                @if($sale->getPorretencion() > 0)
                    <div style="border: 1px solid #000; padding: 5px; font-size: 8px;">
                        <div style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;">Información de la
                            retención</div>
                        <div><span style="font-weight: bold;">Base imponible de la retención :</span>
                            {{ $sale->getCurrencyType()->getCommercialSymbol() }} {{ number_format($sale->getTotal(), 2) }}
                        </div>
                        <div><span style="font-weight: bold;">Porcentaje de Retención:</span>
                            {{ number_format($sale->getPorretencion(), 2) }} %</div>
                        @php
                            $retentionAmount = $sale->getCurrencyType()->getId() == 1 ? $sale->getImpretens() : $sale->getImpretend();
                        @endphp
                        <div><span style="font-weight: bold;">Monto de la Retención :</span>
                            {{ $sale->getCurrencyType()->getCommercialSymbol() }} {{ number_format($retentionAmount, 2) }}
                        </div>
                    </div>
                @elseif($sale->getCoddetrac())
                    <div style="border: 1px solid #000; padding: 5px; font-size: 7px;">
                        <div style="font-weight: bold; text-decoration: underline; margin-bottom: 2px;">Información de la
                            detracción</div>
                        <div style="margin-bottom: 2px;"><span style="font-weight: bold;">Leyenda :</span> Operación sujeta
                            a Sistema de Pago de Obligaciones Tributarias con el Gobierno Central</div>
                        @php
                            $detractionDesc = $sale->getCoddetrac() == 22 ? '022 OTROS SERVICIOS GENERALES' : $sale->getCoddetrac();
                         @endphp
                        <div><span style="font-weight: bold;">Bien o Servicio :</span> {{ $detractionDesc }}</div>
                        <div><span style="font-weight: bold;">Medio de Pago :</span> 001 Depósito en cuenta.</div>
                        <div><span style="font-weight: bold;">Nro.Cta de la Nación :</span>
                            {{ $sale->getCompany()->getDetracCtaBanco() }}</div>
                        <div><span style="font-weight: bold;">Porcentaje de Detracción:</span>
                            {{ number_format($sale->getPordetrac(), 2) }} %</div>
                        <div><span style="font-weight: bold;">Monto de Detracción :</span> S/
                            {{ number_format($sale->getImpdetracs(), 2) }}
                        </div>
                    </div>
                @endif
            </div>
            <div style="clear: both;"></div>
        </div>
        <!-- QR Code Section (Bottom Left) -->
        @if(!in_array($sale->getDocumentType()->getId(), [16, 17]))
            <div style="width: 100%; page-break-inside: avoid; margin-top: 0px;">
                <div style="width: 90px; text-align: center;">
                    <div style="border: 1px solid #ddd; padding: 4px; border-radius: 5px; background-color: #f9f9f9;">
                        <div style="margin-bottom: 2px;">
                            <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code"
                                style="width: 50px; height: 50px;">
                        </div>
                        <div style="font-size: 5px; margin-top: 2px; color: #666;">Escanea para verificar</div>
                        <div style="font-size: 5px; color: #333; margin-top: 2px; line-height: 1.2;">
                            <strong>REPRESENTACIÓN FÍSICA DE
                                {{ strtoupper($sale->getDocumentType()->getDescription()) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>

</html>