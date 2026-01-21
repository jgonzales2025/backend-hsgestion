<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $warranty->getDocumentTypeWarrantyId() == 1 ? 'GARANTÍA' : 'SOPORTE TÉCNICO' }} {{ $warranty->getSerie() }}-{{ $warranty->getCorrelative() }}</title>
    <style>
        @page {
            margin: 0cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            margin-top: 4cm;
            margin-bottom: 2cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1.5cm;
            background-color: #fff;
            padding-top: 0.5cm;
            padding-left: 1.5cm;
            padding-right: 1.5cm;
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
            font-size: 12px;
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
            margin-bottom: 3px;
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

        .details-box {
            border: 1px solid #ddd;
            padding: 8px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
            border-radius: 3px;
        }

        .details-title {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 5px;
            color: #000;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }

        .details-content {
            font-size: 9px;
            line-height: 1.4;
            color: #333;
        }

        .status-box {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }

        .status-process {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #17a2b8;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #28a745;
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
                        <div class="company-name">{{ $warranty->getCompany()->getCompanyName() }}</div>
                        <div class="company-address">{{ $warranty->getCompany()->getAddress() }}</div>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <div class="ruc-box">
                        <div class="ruc-number">R.U.C. {{ $warranty->getCompany()->getRuc() }}</div>
                        <div class="doc-title">{{ $warranty->getDocumentTypeWarrantyId() == 1 ? 'RECEP.GARANTÍA' : 'SOPORTE TÉCNICO' }}</div>
                        <div class="doc-number">{{ $warranty->getSerie() }} - {{ str_pad($warranty->getCorrelative(), 8, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <div class="page-number"></div>
    </footer>

    <div class="content">
        @if($warranty->getDocumentTypeWarrantyId() == 1)
            <!-- GARANTÍA -->

            <!-- Customer Information -->
            <div class="section-title" style="margin-top: -25px;">DATOS DEL CLIENTE</div>
            <table class="info-table">
                @php
                    $customer = $warranty->getCustomer();
                    $isCompany = $customer->getCustomerDocumentType()->getId() == 2;
                @endphp

                @if($isCompany)
                    <tr>
                        <td class="label">R.U.C.:</td>
                        <td>{{ $customer->getDocumentNumber() }}</td>
                        <td class="label" style="text-align: right;">RAZÓN SOCIAL:</td>
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
                    <td class="label">TELÉFONO:</td>
                    <td>{{ $warranty->getCustomerPhone() ?? 'N/A' }}</td>
                    <td class="label" style="text-align: right;">EMAIL:</td>
                    <td>{{ $warranty->getCustomerEmail() ?? 'N/A' }}</td>
                </tr>
            </table>

            <!-- Warranty Information -->
            <div class="section-title" style="margin-top: 10px;">INFORMACIÓN DE LA GARANTÍA</div>
            <table class="info-table">
                <tr>
                    <td class="label">FECHA EMISIÓN:</td>
                    <td>{{ $warranty->getDate() }}</td>
                    <td class="label" style="text-align: right;">ESTADO:</td>
                    <td>{{ $warranty->getWarrantyStatus()->getName() }}</td>
                </tr>
                <tr>
                    <td class="label">SUCURSAL:</td>
                    <td colspan="3">{{ $warranty->getBranch()->getName() }} - {{ $warranty->getBranch()->getAddress() }}</td>
                </tr>
                <tr>
                    <td class="label">CONTACTO:</td>
                    <td colspan="3">{{ $warranty->getContact() ?? 'N/A' }}</td>
                </tr>
            </table>

            <!-- Product Information -->
            <div class="section-title" style="margin-top: 10px;">INFORMACIÓN DEL PRODUCTO</div>
            <table class="info-table">
                <tr>
                    <td class="label">CÓDIGO:</td>
                    <td>{{ $warranty->getArticle()->getCodFab() }}</td>
                    <td class="label" style="text-align: right;">SERIE:</td>
                    <td>{{ $warranty->getSerieArt() ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">DESCRIPCIÓN:</td>
                    <td>{{ $warranty->getArticle()->getDescription() }}</td>
                </tr>
            </table>

            <!-- Reference Sale Information -->
            <div class="section-title" style="margin-top: 10px;">VENTA DE REFERENCIA</div>
            <table class="info-table">
                <tr>
                    <td class="label">DOCUMENTO:</td>
                    <td>{{ $warranty->getReferenceSale()->getDocumentType()->getDescription() }} {{ $warranty->getReferenceSale()->getSerie() }}-{{ str_pad($warranty->getReferenceSale()->getDocumentNumber(), 8, '0', STR_PAD_LEFT) }}</td>
                    <td class="label" style="text-align: right;">FECHA:</td>
                    <td>{{ $warranty->getReferenceSale()->getDate() }}</td>
                </tr>
            </table>

            <!-- Failure Description -->
            @if($warranty->getFailureDescription())
                <div class="section-title" style="margin-top: 10px;">DESCRIPCIÓN DE LA FALLA</div>
                <div class="details-box">
                    <div class="details-content">{{ $warranty->getFailureDescription() }}</div>
                </div>
            @endif

            <!-- Diagnosis -->
            @if($warranty->getDiagnosis())
                <div class="section-title" style="margin-top: 10px;">DIAGNÓSTICO</div>
                <div class="details-box">
                    <div class="details-content">{{ $warranty->getDiagnosis() }}</div>
                </div>
            @endif

            <!-- Follow Up Diagnosis -->
            @if($warranty->getFollowUpDiagnosis())
                <div class="section-title" style="margin-top: 10px;">DIAGNÓSTICO DE SEGUIMIENTO</div>
                <div class="details-box">
                    <div class="details-content">{{ $warranty->getFollowUpDiagnosis() }}</div>
                </div>
            @endif

            <!-- Follow Up Status -->
            @if($warranty->getFollowUpStatus())
                <div class="section-title" style="margin-top: 10px;">ESTADO DE SEGUIMIENTO</div>
                <div class="details-box">
                    <div class="details-content">{{ $warranty->getFollowUpStatus() }}</div>
                </div>
            @endif

            <!-- Solution -->
            @if($warranty->getSolution())
                <div class="section-title" style="margin-top: 10px;">SOLUCIÓN</div>
                <div class="details-box">
                    <div class="details-content">{{ $warranty->getSolution() }}</div>
                </div>
                @if($warranty->getSolutionDate())
                    <table class="info-table">
                        <tr>
                            <td class="label">FECHA SOLUCIÓN:</td>
                            <td>{{ $warranty->getSolutionDate() }}</td>
                        </tr>
                    </table>
                @endif
            @endif

            <!-- Delivery Information -->
            @if($warranty->getDeliveryDescription() || $warranty->getDeliverySerieArt() || $warranty->getDeliveryDate())
                <div class="section-title" style="margin-top: 10px;">INFORMACIÓN DE ENTREGA</div>
                <table class="info-table">
                    @if($warranty->getDeliveryDescription())
                        <tr>
                            <td class="label">DESCRIPCIÓN:</td>
                            <td colspan="3">{{ $warranty->getDeliveryDescription() }}</td>
                        </tr>
                    @endif
                    @if($warranty->getDeliverySerieArt())
                        <tr>
                            <td class="label">SERIE ENTREGADA:</td>
                            <td>{{ $warranty->getDeliverySerieArt() }}</td>
                            @if($warranty->getDeliveryDate())
                                <td class="label">FECHA ENTREGA:</td>
                                <td>{{ $warranty->getDeliveryDate() }}</td>
                            @endif
                        </tr>
                    @endif
                </table>
            @endif

            <!-- Credit Note Information -->
            @if($warranty->getCreditNoteSerie() || $warranty->getCreditNoteCorrelative())
                <div class="section-title" style="margin-top: 10px;">NOTA DE CRÉDITO</div>
                <table class="info-table">
                    <tr>
                        <td class="label">DOCUMENTO:</td>
                        <td>{{ $warranty->getCreditNoteSerie() }}-{{ $warranty->getCreditNoteCorrelative() }}</td>
                    </tr>
                </table>
            @endif

            <!-- Dispatch Note Information -->
            @if($warranty->getDispatchNoteSerie() || $warranty->getDispatchNoteCorrelative())
                <div class="section-title" style="margin-top: 10px;">GUÍA DE REMISIÓN</div>
                <table class="info-table">
                    <tr>
                        <td class="label">DOCUMENTO:</td>
                        <td>{{ $warranty->getDispatchNoteSerie() }}-{{ $warranty->getDispatchNoteCorrelative() }}</td>
                        @if($warranty->getDispatchNoteDate())
                            <td class="label">FECHA:</td>
                            <td>{{ $warranty->getDispatchNoteDate() }}</td>
                        @endif
                    </tr>
                </table>
            @endif

            <!-- Observations -->
            @if($warranty->getObservations())
                <div class="section-title" style="margin-top: 10px;">OBSERVACIONES</div>
                <div class="details-box">
                    <div class="details-content">{{ $warranty->getObservations() }}</div>
                </div>
            @endif

            <div style="margin-top: 15px; border-top: 1px dashed #000; padding-top: 5px; font-size: 9px;">
                <div style="margin-bottom: 5px;">HORARIO DE ATENCION DE LUNES A VIERNES DE 11:00 AM A 6:00 PM</div>
                <div style="font-weight: bold; margin-bottom: 2px;">CONDICIONES:</div>
                <div style="margin-bottom: 2px;">- NO ESTA INCLUIDO EN LA GARANTIA LOS BACKUPS COPIAS DE ARCHIVOS Y/O PROGRAMAS,
                LOS MANTENIMIENTOS GENERALES LAS REINSTALACIONES O RESTAURACIONES DE SOFTWARE Y LAS FALLAS CAUSADAS POR VIRUS,
                POR LO QUE LA EMPRESA NO SE RESPONSABILIZA POR LA PERDIDA PARCIAL O TOTAL DE LA INFORMACION ALMACENADA EN SU EQUIPO.</div>
                <div>- EL DIAGNOSTICO DE LOS PRODUCTOS SON 72 HORAS HABILES DE NUESTRO HORARIO DE ATENCION.</div>
            </div> 

        @else
            <!-- SOPORTE TÉCNICO -->

            <!-- Support Information -->
            <div class="section-title" style="margin-top: -25px;">INFORMACIÓN DEL SOPORTE</div>
            <table class="info-table">
                <tr>
                    <td class="label">FECHA EMISIÓN:</td>
                    <td>{{ $warranty->getDate() }}</td>
                </tr>
                <tr>
                    <td class="label">SUCURSAL:</td>
                    <td>{{ $warranty->getBranch()->getName() }} - {{ $warranty->getBranch()->getAddress() }}</td>
                </tr>
            </table>

            <!-- Contact Information -->
            <div class="section-title" style="margin-top: 10px;">DATOS DE CONTACTO</div>
            <table class="info-table">
                <tr>
                    <td class="label">TELÉFONO:</td>
                    <td>{{ $warranty->getCustomerPhone() }}</td>
                    <td class="label">EMAIL:</td>
                    <td>{{ $warranty->getCustomerEmail() }}</td>
                </tr>
                @if($warranty->getContact())
                    <tr>
                        <td class="label">CONTACTO:</td>
                        <td colspan="3">{{ $warranty->getContact() }}</td>
                    </tr>
                @endif
            </table>

            <!-- Failure Description -->
            <div class="section-title" style="margin-top: 10px;">DESCRIPCIÓN DE LA FALLA</div>
            <div class="details-box">
                <div class="details-content">{{ $warranty->getFailureDescription() }}</div>
            </div>

            <!-- Diagnosis -->
            <div class="section-title" style="margin-top: 10px;">DIAGNÓSTICO</div>
            <div class="details-box">
                <div class="details-content">{{ $warranty->getDiagnosis() }}</div>
            </div>

            <!-- Observations -->
            @if($warranty->getObservations())
                <div class="section-title" style="margin-top: 10px;">OBSERVACIONES</div>
                <div class="details-box">
                    <div class="details-content">{{ $warranty->getObservations() }}</div>
                </div>
            @endif
        @endif

        <table style="width: 100%; margin-top: 150px;">
                <tr>
                    <td style="width: 40%; text-align: center;">
                        <div style="border-top: 1px dashed #000; padding-top: 5px;">Cliente</div>
                    </td>
                    <td style="width: 20%;"></td>
                    <td style="width: 40%; text-align: center;">
                        <div style="border-top: 1px dashed #000; padding-top: 5px;">CYBERHOUSE TEC S.A.C.</div>
                    </td>
                </tr>
            </table>
    </div>
</body>

</html>
