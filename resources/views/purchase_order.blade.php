<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Orden de Compra {{ $purchaseOrder->getSerie() }}-{{ $purchaseOrder->getCorrelative() }}</title>
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

        .totals-table {
            width: 40%;
            float: right;
            margin-top: 10px;
        }

        .totals-table td {
            padding: 5px;
            border-bottom: 1px solid #eee;
        }

        .total-row {
            font-weight: bold;
            font-size: 11px;
            background-color: #eee;
        }

        .amount-in-words {
            margin-top: 20px;
            font-style: italic;
            font-size: 10px;
            /*border-top: 1px solid #ccc;*/
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
                        <div class="company-name">{{ $company->getCompanyName() }}</div>
                        <div class="company-address">{{ $company->getAddress() }}</div>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <div class="ruc-box">
                        <div class="ruc-number">R.U.C. {{ $company->getRuc() }}</div>
                        <div class="doc-title">ORDEN DE COMPRA</div>
                        <div class="doc-number">{{ $purchaseOrder->getSerie() }} - {{ str_pad($purchaseOrder->getCorrelative(), 8, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <div class="page-number"></div>
    </footer>

    <div class="content">
        <!-- Supplier Information -->
        <div class="section-title">DATOS DEL PROVEEDOR</div>
        <table class="info-table">
            @php
            $supplier = $purchaseOrder->getSupplier();
            $isCompany = $supplier->getCustomerDocumentType()->getId() == 2;
            @endphp

            @if($isCompany)
            <tr>
                <td class="label">R.U.C.:</td>
                <td>{{ $supplier->getDocumentNumber() }}</td>
                <td class="label">RAZÓN SOCIAL:</td>
                <td>{{ $supplier->getCompanyName() }}</td>
            </tr>
            @else
            <tr>
                <td class="label">DNI:</td>
                <td>{{ $supplier->getDocumentNumber() }}</td>
                <td class="label">NOMBRE:</td>
                <td>{{ $supplier->getName() }} {{ $supplier->getLastname() }} {{ $supplier->getSecondLastname() }}</td>
            </tr>
            @endif

            <tr>
                <td class="label">DIRECCIÓN:</td>
                <td colspan="3">
                    @php
                    $addresses = $supplier->getAddresses();
                    $address = $addresses && count($addresses) > 0 ? $addresses[0]->getAddress() : '';
                    @endphp
                    {{ $address }}
                </td>
            </tr>
            <tr>
                <td class="label">CONTACTO:</td>
                <td>{{ $purchaseOrder->getContactName() }}</td>
                <td class="label">TELÉFONO:</td>
                <td>{{ $purchaseOrder->getContactPhone() }}</td>
            </tr>
            <tr>
                <td class="label">USUARIO:</td>
                <td>{{ $transactionLog?->getUser()?->getFirstname() }} {{ $transactionLog?->getUser()?->getLastname() }}</td>
                <td class="label">FECHA DE IMPRESIÓN:</td>
                <td>{{ now()->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>

        <!-- Order Information -->
        <div class="section-title" style="margin-top: 10px;">DETALLES DE LA ORDEN</div>
        <table class="info-table">
            <tr>
                <td class="label">FECHA EMISIÓN:</td>
                <td>{{ $purchaseOrder->getDate() }}</td>
                <td class="label">FECHA ENTREGA:</td>
                <td>{{ $purchaseOrder->getDeliveryDate() }}</td>
                <td class="label">TIPO DE CAMBIO:</td>
                <td>{{ $purchaseOrder->getParallelRate() }}</td>
            </tr>
            <tr>
                <td class="label">FORMA PAGO:</td>
                <td>{{ $purchaseOrder->getPaymentType()->getName() }}</td>
                <td class="label">MONEDA:</td>
                <td>{{ $purchaseOrder->getCurrencyType()->getName() }}</td>
            </tr>
            <tr>
                <td class="label">LUGAR ENTREGA:</td>
                <td colspan="4">{{ $purchaseOrder->getBranch()->getName() }} - {{ $purchaseOrder->getBranch()->getAddress() }}</td>
            </tr>
            <tr>
                <td class="label">OBSERVACIONES:</td>
                <td colspan="4">{{ $purchaseOrder->getObservations() }}</td>
            </tr>
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
                @foreach($purchaseOrderArticles as $article)
                <tr>
                    <td style="text-align:center;">{{ $article->getCodFab() }}</td>
                    <td style="text-align:left; padding-left: 10px;">{{ $article->getDescription() }}</td>
                    <td style="text-align:center;">{{ $article->getQuantity() }}</td>
                    <td style="text-align:right;">{{ number_format($article->getPurchasePrice(), 2) }}</td>
                    <td style="text-align:right;">{{ number_format($article->getSubTotal(), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div style="width: 100%;">
            <div style="float: left; width: 60%;">
                <div class="amount-in-words">
                    {{ \App\Shared\Infrastructure\Helpers\NumberToWords::convert($purchaseOrder->getTotal(), $purchaseOrder->getCurrencyType()->getName()) }}
                </div>
            </div>
            <div style="float: right; width: 40%;">
                <table class="totals-table" style="width: 100%;">
                    <tr>
                        <td style="text-align: right;"><strong>GRAVADO:</strong></td>
                        <td style="text-align: right;">{{ $purchaseOrder->getCurrencyType()->getCommercialSymbol() }} {{ number_format($purchaseOrder->getSubTotal(), 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"><strong>I.G.V.:</strong></td>
                        <td style="text-align: right;">{{ $purchaseOrder->getCurrencyType()->getCommercialSymbol() }} {{ number_format($purchaseOrder->getIgv(), 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td style="text-align: right;">TOTAL:</td>
                        <td style="text-align: right;">{{ $purchaseOrder->getCurrencyType()->getCommercialSymbol() }} {{ number_format($purchaseOrder->getTotal(), 2) }}</td>
                    </tr>
                </table>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</body>

</html>