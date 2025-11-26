<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $sale->getDocumentType()->getDescription() }} {{ $sale->getSerie() }}-{{ $sale->getDocumentNumber() }}</title>
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
        }

        .logo {
            max-width: 180px;
            max-height: 60px;
        }

        .company-info {
            text-align: left;
        }

        .company-name {
            font-size: 14px;
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
            margin-top: 20px;
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
                    <img src="{{ public_path('storage/logo/logo.jpeg') }}" class="logo" alt="Logo">
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
                            {{ str_pad($sale->getDocumentNumber(), 8, '0', STR_PAD_LEFT) }}</div>
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
                $isCompany = $customer->getCustomerDocumentTypeId() == 2;
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
                <td>{{ $transactionLog->getUser()->getFirstname() }} {{ $transactionLog->getUser()->getLastname() }}</td>
                <td class="label" colspan="3" style="text-align: right;">FECHA DE IMPRESIÓN:</td>
                <td>{{ now()->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>

        <!-- Sale Information -->
        <div class="section-title" style="margin-top: 10px;">DETALLES DE LA VENTA</div>
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
        </table>

        <!-- Articles -->
        <table class="products-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 15%;">CÓDIGO</th>
                    <th style="width: 45%; text-align: left; padding-left: 10px;">DESCRIPCIÓN</th>
                    <th style="width: 10%;">CANT</th>
                    <th style="width: 10%;">P. UNIT</th>
                    <th style="width: 10%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($saleArticles as $article)
                    <tr>
                        <td style="text-align:center;">{{ $article->getSku() }}</td>
                        <td style="text-align:left; padding-left: 10px;">{{ $article->getDescription() }}</td>
                        <td style="text-align:center;">{{ $article->getQuantity() }}</td>
                        <td style="text-align:right;">{{ number_format($article->getUnitPrice(), 2) }}</td>
                        <td style="text-align:right;">{{ number_format($article->getSubtotal(), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div style="width: 100%;">
            <div style="float: left; width: 60%;">
                <div class="amount-in-words">
                    {{ \App\Shared\Infrastructure\Helpers\NumberToWords::convert($sale->getTotal(), $sale->getCurrencyType()->getName()) }}
                </div>
            </div>
            <div style="float: right; width: 40%;">
                <table style="width: 100%; border: 1px solid #333; margin-top: 10px;">
                    <thead>
                        <tr style="background-color: #333; color: #fff;">
                            <th style="padding: 6px; text-align: center; font-size: 9px;">GRAVADO</th>
                            <th style="padding: 6px; text-align: center; font-size: 9px;">I.G.V.</th>
                            <th style="padding: 6px; text-align: center; font-size: 9px;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color: #f9f9f9;">
                            <td style="padding: 8px; text-align: center; font-weight: bold; border-right: 1px solid #ddd;">
                                {{ $sale->getCurrencyType()->getCommercialSymbol() }} {{ number_format($sale->getSubtotal(), 2) }}
                            </td>
                            <td style="padding: 8px; text-align: center; font-weight: bold; border-right: 1px solid #ddd;">
                                {{ $sale->getCurrencyType()->getCommercialSymbol() }} {{ number_format($sale->getIgv(), 2) }}
                            </td>
                            <td style="padding: 8px; text-align: center; font-weight: bold; background-color: #eee;">
                                {{ $sale->getCurrencyType()->getCommercialSymbol() }} {{ number_format($sale->getTotal(), 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="clear: both;"></div>
        </div>
        
        <!-- QR Code -->
        <div style="width: 100%; margin-top: 20px; text-align: right;">
            <div style="display: inline-block; border: 1px solid #ddd; padding: 10px; border-radius: 5px; background-color: #f9f9f9; text-align: center;">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" style="width: 50px; height: 50px;">
                <div style="font-size: 8px; margin-top: 5px; color: #666;">Escanea para verificar</div>
            </div>
        </div>
    </div>
</body>

</html>