<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Guía de Ingreso {{ $entryGuide->getSerie() }}-{{ $entryGuide->getCorrelativo() }}</title>
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
                    <img src="{{  public_path('storage/logo/logocyberhouse.jpg') }}" class="logo" alt="Logo">
                    <div class="company-info" style="margin-top: 5px;">
                        <div class="company-name">{{ $company->getCompanyName() }}</div>
                        <div class="company-address">{{ $company->getAddress() }}</div>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <div class="ruc-box">
                        <div class="ruc-number">R.U.C. {{ $company->getRuc() }}</div>
                        <div class="doc-title">GUÍA DE INGRESO</div>
                        <div class="doc-number">{{ $entryGuide->getSerie() }} - {{ str_pad($entryGuide->getCorrelativo(), 8, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <div class="page-number"></div>
    </footer>

    <div class="content">
        <!-- Customer/Supplier Information -->
        <div class="section-title">DATOS DEL PROVEEDOR</div>
        <table class="info-table">
            @php
            $isCompany = $customer->getCustomerDocumentType()->getId() == 2;
            @endphp

            @if($isCompany)
            <tr>
                <td class="label">R.U.C.:</td>
                <td>{{ $customer->getDocumentNumber() }}</td>
                <td class="label">RAZÓN SOCIAL:</td>
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
                <td colspan="3">
                    @php
                    $addresses = $customer->getAddresses();
                    $address = $addresses && count($addresses) > 0 ? $addresses[0]->getAddress() : 'N/A';
                    @endphp
                    {{ $address }}
                </td>
            </tr>
            <tr>
                <td class="label">CONTACTO:</td>
                <td>{{ $customer->getContact() ?: 'N/A' }}</td>
                <td class="label">TELÉFONO:</td>
                <td>
                    @php
                    $phones = $customer->getPhones();
                    $phone = $phones && count($phones) > 0 ? $phones[0]->getPhone() : 'N/A';
                    @endphp
                    {{ $phone }}
                </td>
            </tr>
            <tr>
                <td class="label">USUARIO:</td>
                <td>Admin Admin</td>
                <td class="label">FECHA DE IMPRESIÓN:</td>
                <td>{{ now()->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>

        <!-- Order Information -->
        <div class="section-title" style="margin-top: 10px;">DETALLES DE LA GUÍA</div>
        <table class="info-table">
            <tr>
                <td class="label">FECHA EMISIÓN:</td>
                <td>{{ $entryGuide->getDate() }}</td>
                <td class="label">MOTIVO INGRESO:</td>
                <td colspan="3">{{ $entryGuide->getIngressReason()?->getDescription() ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">LUGAR ENTREGA:</td>
                <td colspan="5">{{ $branch->getName() }} - {{ $branch->getAddress() }}</td>
            </tr>
            <tr>
                <td class="label">Documento de deferencia:</td>
                <td colspan="5">
                      {{ $document_entry_guide?->getReferenceDocument()->getDescription() }} {{ $document_entry_guide?->getReferenceSerie() }} - {{ $document_entry_guide?->getReferenceCorrelative() }}

                </td>
            </tr>
            <tr>
                <td class="label">OBSERVACIONES:</td>
                <td colspan="5">{{ $entryGuide->getObservations() ?: 'N/A' }}</td>
            </tr>
        </table>

        <!-- Articles -->
        <table class="products-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 15%;">CÓDIGO</th>
                    <th style="width: 70%; text-align: left; padding-left: 10px;">DESCRIPCIÓN</th>
                    <th style="width: 15%;">CANT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                <tr>
                    <td style="text-align:center;">{{ $article->getArticle()->getCodFab() }}</td>
                    <td style="text-align:left; padding-left: 10px;">{{ $article->getDescription() }}</td>
                    <td style="text-align:center;">{{ $article->getQuantity() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>