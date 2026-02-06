<?php

namespace App\Services;

use App\Modules\Company\Domain\Entities\Company;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress;
use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;
use App\Modules\DispatchNotes\Infrastructure\Models\EloquentDispatchNote;
use App\Modules\Sale\Domain\Entities\Sale;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use App\Modules\SaleArticle\Domain\Entities\SaleArticle;
use App\Modules\Ubigeo\Departments\Application\UseCases\FindByIdDepartmentUseCase;
use App\Modules\Ubigeo\Departments\Domain\Interfaces\DepartmentRepositoryInterface;
use App\Modules\Ubigeo\Districts\Application\UseCases\FindByIdDistrictUseCase;
use App\Modules\Ubigeo\Districts\Domain\Interfaces\DistrictRepositoryInterface;
use App\Modules\Ubigeo\Provinces\Application\UseCases\FindByIdProvinceUseCase;
use App\Modules\Ubigeo\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SalesSunatService
{
    protected $baseUrl;
    protected $token;
    public function __construct(
        private DepartmentRepositoryInterface $departmentRepository,
        private ProvinceRepositoryInterface $provinceRepository,
        private DistrictRepositoryInterface $districtRepository,
        private CustomerRepositoryInterface $customerRepository
    ) {
        $this->baseUrl = config('services.external_api.sale_sunat_api_url');
        $this->token = config('services.external_api.sale_sunat_api_token');
    }
    public function saleGravada(Sale $sale, array $saleArticles)
    {
        $ubigeo = $sale->getCompany()->getUbigeo();
        $coddep = substr($ubigeo, 0, 2);
        $codprov = substr($ubigeo, 2, 2);
        $coddist = substr($ubigeo, 4, 2);

        $departmentUseCase = new FindByIdDepartmentUseCase($this->departmentRepository);
        $department = $departmentUseCase->execute($coddep);

        $provinceUseCase = new FindByIdProvinceUseCase($this->provinceRepository);
        $province = $provinceUseCase->execute($coddep, $codprov);

        $districtUseCase = new FindByIdDistrictUseCase($this->districtRepository);
        $district = $districtUseCase->execute($coddep, $codprov, $coddist);

        $data = [
            "client" => [
                "tipoDoc" => (string) $sale->getCustomer()->getCustomerDocumentType()->getCodSunat(),
                "numDoc" => $sale->getCustomer()->getDocumentNumber(),
                "rznSocial" => $sale->getCustomer()->getCompanyName()
            ],
            "company" => [
                "ruc" => $sale->getCompany()->getRuc(),
                "razonSocial" => $sale->getCompany()->getCompanyName(),
                "nombreComercial" => $sale->getCompany()->getCompanyName(),
                "address" => [
                    "ubigueo" => $ubigeo,
                    "departamento" => $department->getNomdep(),
                    "provincia" => $province->getNompro(),
                    "distrito" => $district->getNomdis(),
                    "urbanizacion" => '-',
                    "direccion" => $sale->getCompany()->getAddress()
                ]
            ],
            "details" => array_map(function ($article) use ($sale) {

                return [
                    "codProducto" => $article->getArticle()->getId(),
                    "unidad" => "NIU",
                    "cantidad" => $article->getQuantity(),
                    "descripcion" => $article->getArticle()->getDescription(),
                    "mtoBaseIgv" => round($article->getSubtotal() / 1.18, 2),
                    "porcentajeIgv" => 18,
                    "igv" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "tipAfeIgv" => "10",
                    "totalImpuestos" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "mtoValorVenta" => round($article->getSubtotal() / 1.18, 2),
                    "mtoValorUnitario" => round($article->getUnitPrice() / 1.18, 2),
                    "mtoPrecioUnitario" => $article->getUnitPrice()
                ];
            }, $saleArticles),
            "legends" => [
                [
                    "code" => "1000",
                    "value" => "SON CIENTO DIECIOCHO CON 00/100 SOLES"
                ]
            ],
            "ublVersion" => "2.1",
            "forma_pago_tipo" => "Contado",
            "tipoOperacion" => "0101",
            "tipoDoc" => "0" . (string) $sale->getDocumentType()->getId(),
            "serie" => $sale->getSerie(),
            "correlativo" => $sale->getDocumentNumber(),
            "fechaEmision" => $sale->getDate(),
            "tipoMoneda" => $sale->getCurrencyType()->getSunatSymbol(),
            "mtoOperGravadas" => $sale->getSubtotal(),
            "mtoIGV" => $sale->getIgv(),
            "subTotal" => $sale->getTotal(),
            "totalImpuestos" => $sale->getIgv(),
            "valorVenta" => $sale->getSubTotal(),
            "mtoImpVenta" => $sale->getTotal()
        ];
        
        $response = Http::withHeaders([
            'Authorization' => "Bearer $this->token",
            'Accept' => 'application/json'
        ])->post("{$this->baseUrl}/factura/send", $data);
        
        Log::error($response->body());

        return $response->json();
    }

    public function saleDetraccion(Sale $sale, array $saleArticles)
    {
        $ubigeo = $sale->getCompany()->getUbigeo();
        $coddep = substr($ubigeo, 0, 2);
        $codprov = substr($ubigeo, 2, 2);
        $coddist = substr($ubigeo, 4, 2);

        $departmentUseCase = new FindByIdDepartmentUseCase($this->departmentRepository);
        $department = $departmentUseCase->execute($coddep);

        $provinceUseCase = new FindByIdProvinceUseCase($this->provinceRepository);
        $province = $provinceUseCase->execute($coddep, $codprov);

        $districtUseCase = new FindByIdDistrictUseCase($this->districtRepository);
        $district = $districtUseCase->execute($coddep, $codprov, $coddist);

        $data = [
            "client" => [
                "tipoDoc" => (string) $sale->getCustomer()->getCustomerDocumentType()->getCodSunat(),
                "numDoc" => $sale->getCustomer()->getDocumentNumber(),
                "rznSocial" => $sale->getCustomer()->getCompanyName()
            ],
            "company" => [
                "ruc" => $sale->getCompany()->getRuc(),
                "razonSocial" => $sale->getCompany()->getCompanyName(),
                "nombreComercial" => $sale->getCompany()->getCompanyName(),
                "address" => [
                    "ubigueo" => $ubigeo,
                    "departamento" => $department->getNomdep(),
                    "provincia" => $province->getNompro(),
                    "distrito" => $district->getNomdis(),
                    "urbanizacion" => '-',
                    "direccion" => $sale->getCompany()->getAddress()
                ]
            ],
            "details" => array_map(function ($article) use ($sale) {

                return [
                    "codProducto" => $article->getArticle()->getId(),
                    "unidad" => "NIU",
                    "cantidad" => $article->getQuantity(),
                    "descripcion" => $article->getArticle()->getDescription(),
                    "mtoBaseIgv" => round($article->getSubtotal() / 1.18, 2),
                    "porcentajeIgv" => 18,
                    "igv" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "tipAfeIgv" => "10",
                    "totalImpuestos" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "mtoValorVenta" => round($article->getSubtotal() / 1.18, 2),
                    "mtoValorUnitario" => round($article->getUnitPrice() / 1.18, 2),
                    "mtoPrecioUnitario" => $article->getUnitPrice()
                ];
            }, $saleArticles),
            "legends" => [
                [
                    "code" => "1000",
                    "value" => "SON CIENTO DIECIOCHO CON 00/100 SOLES"
                ]
            ],
            "ublVersion" => "2.1",
            "forma_pago_tipo" => $sale->getPaymentType()->getName(),
            "tipoOperacion" => "1001",
            "tipoDoc" => "0" . (string) $sale->getDocumentType()->getId(),
            "serie" => $sale->getSerie(),
            "correlativo" => $sale->getDocumentNumber(),
            "fechaEmision" => $sale->getDate(),
            "tipoMoneda" => $sale->getCurrencyType()->getSunatSymbol(),
            "detrac_cod" => "022",
            "detrac_cta_banco" => $sale->getCompany()->getDetracCtaBanco(),
            "detrac_porc" => $sale->getPordetrac(),
            "detrac_monto" => $sale->getCurrencyType()->getSunatSymbol() === 'PEN' ? $sale->getImpdetracs() : $sale->getImpdetracd(),
            "mtoOperGravadas" => $sale->getSubtotal(),
            "mtoIGV" => $sale->getIgv(),
            "subTotal" => $sale->getTotal(),
            "totalImpuestos" => $sale->getIgv(),
            "valorVenta" => $sale->getSubTotal(),
            "mtoImpVenta" => $sale->getTotal()
        ];

        $response = Http::withToken($this->token)->post("{$this->baseUrl}/factura/send", $data);

        return $response->json();
    }

    public function saleRetencion(Sale $sale, array $saleArticles)
    {
        $ubigeo = $sale->getCompany()->getUbigeo();
        $coddep = substr($ubigeo, 0, 2);
        $codprov = substr($ubigeo, 2, 2);
        $coddist = substr($ubigeo, 4, 2);

        $departmentUseCase = new FindByIdDepartmentUseCase($this->departmentRepository);
        $department = $departmentUseCase->execute($coddep);

        $provinceUseCase = new FindByIdProvinceUseCase($this->provinceRepository);
        $province = $provinceUseCase->execute($coddep, $codprov);

        $districtUseCase = new FindByIdDistrictUseCase($this->districtRepository);
        $district = $districtUseCase->execute($coddep, $codprov, $coddist);

        $data = [
            "client" => [
                "tipoDoc" => (string) $sale->getCustomer()->getCustomerDocumentType()->getCodSunat(),
                "numDoc" => $sale->getCustomer()->getDocumentNumber(),
                "rznSocial" => $sale->getCustomer()->getCompanyName()
            ],
            "company" => [
                "ruc" => $sale->getCompany()->getRuc(),
                "razonSocial" => $sale->getCompany()->getCompanyName(),
                "nombreComercial" => $sale->getCompany()->getCompanyName(),
                "address" => [
                    "ubigueo" => $ubigeo,
                    "departamento" => $department->getNomdep(),
                    "provincia" => $province->getNompro(),
                    "distrito" => $district->getNomdis(),
                    "urbanizacion" => '-',
                    "direccion" => $sale->getCompany()->getAddress()
                ]
            ],
            "details" => array_map(function ($article) use ($sale) {

                return [
                    "codProducto" => $article->getArticle()->getId(),
                    "unidad" => "NIU",
                    "cantidad" => $article->getQuantity(),
                    "descripcion" => $article->getArticle()->getDescription(),
                    "mtoBaseIgv" => round($article->getSubtotal() / 1.18, 2),
                    "porcentajeIgv" => 18,
                    "igv" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "tipAfeIgv" => "10",
                    "totalImpuestos" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "mtoValorVenta" => round($article->getSubtotal() / 1.18, 2),
                    "mtoValorUnitario" => round($article->getUnitPrice() / 1.18, 2),
                    "mtoPrecioUnitario" => $article->getUnitPrice()
                ];
            }, $saleArticles),
            "legends" => [
                [
                    "code" => "1000",
                    "value" => "SON CIENTO DIECIOCHO CON 00/100 SOLES"
                ]
            ],
            "ublVersion" => "2.1",
            "forma_pago_tipo" => $sale->getPaymentType()->getName(),
            "tipoOperacion" => "0101",
            "tipoDoc" => "0" . (string) $sale->getDocumentType()->getId(),
            "serie" => $sale->getSerie(),
            "correlativo" => $sale->getDocumentNumber(),
            "fechaEmision" => $sale->getDate(),
            "tipoMoneda" => $sale->getCurrencyType()->getSunatSymbol(),
            "mtoIGV" => $sale->getIgv(),
            "subTotal" => $sale->getTotal(),
            "totalImpuestos" => $sale->getIgv(),
            "valorVenta" => $sale->getSubTotal(),
            "mtoImpVenta" => $sale->getTotal(),
            "mtoOperGravadas" => $sale->getSubtotal(),
            "discount" => [
                "code" => "62",
                "monto_base" => $sale->getTotal(),
                "factor" => $sale->getPorretencion() / 100,
                "monto" => $sale->getCurrencyType()->getSunatSymbol() === 'PEN' ? $sale->getImpretens() : $sale->getImpretend(),
            ]
        ];

        $response = Http::withToken($this->token)->post("{$this->baseUrl}/factura/send", $data);

        return $response->json();
    }

    public function saleInvoiceAnulacion(Sale $sale)
    {
        $ubigeo = $sale->getCompany()->getUbigeo();
        $coddep = substr($ubigeo, 0, 2);
        $codprov = substr($ubigeo, 2, 2);
        $coddist = substr($ubigeo, 4, 2);

        $departmentUseCase = new FindByIdDepartmentUseCase($this->departmentRepository);
        $department = $departmentUseCase->execute($coddep);

        $provinceUseCase = new FindByIdProvinceUseCase($this->provinceRepository);
        $province = $provinceUseCase->execute($coddep, $codprov);

        $districtUseCase = new FindByIdDistrictUseCase($this->districtRepository);
        $district = $districtUseCase->execute($coddep, $codprov, $coddist);

        $data = [
            "company" => [
                "ruc" => $sale->getCompany()->getRuc(),
                "razonSocial" => $sale->getCompany()->getCompanyName(),
                "nombreComercial" => $sale->getCompany()->getCompanyName(),
                "address" => [
                    "ubigueo" => $ubigeo,
                    "departamento" => $department->getNomdep(),
                    "provincia" => $province->getNompro(),
                    "distrito" => $district->getNomdis(),
                    "urbanizacion" => '-',
                    "direccion" => $sale->getCompany()->getAddress()
                ]
            ],
            "tip_doc" => "0" . (string) $sale->getDocumentType()->getId(),
            "serie" => $sale->getSerie(),
            "correlativo" => $sale->getDocumentNumber(),
            "motivo_baja" => "ERROR EN EL DOCUMENTO",
            "fecha_emision" => "2026-01-03"
        ];
        /* Log::info($data);
        $responseTicket = Http::withToken($this->token)->post("{$this->baseUrl}/voided/send", $data);
        Log::info($responseTicket->json());
        $ticket = $responseTicket->json('ticket'); */
        
        $response = Http::withToken($this->token)->get("{$this->baseUrl}/voided/check/2026010600384437991/{$sale->getCompany()->getRuc()}");
        //dd($response->json());
        return $response->json();
    }
    
    public function saleBol(Sale $sale, array $saleArticles)
    {
        $ubigeo = $sale->getCompany()->getUbigeo();
        $coddep = substr($ubigeo, 0, 2);
        $codprov = substr($ubigeo, 2, 2);
        $coddist = substr($ubigeo, 4, 2);

        $departmentUseCase = new FindByIdDepartmentUseCase($this->departmentRepository);
        $department = $departmentUseCase->execute($coddep);

        $provinceUseCase = new FindByIdProvinceUseCase($this->provinceRepository);
        $province = $provinceUseCase->execute($coddep, $codprov);

        $districtUseCase = new FindByIdDistrictUseCase($this->districtRepository);
        $district = $districtUseCase->execute($coddep, $codprov, $coddist);
        
        $data = [
            "client" => [
                "tipoDoc" => (string) $sale->getCustomer()->getCustomerDocumentType()->getCodSunat(),
                "numDoc" => $sale->getCustomer()->getDocumentNumber(),
                "rznSocial" => $sale->getCustomer()->getName() . ' ' . $sale->getCustomer()->getLastName() . ' ' . $sale->getCustomer()->getSecondLastName()
            ],
            "company" => [
                "ruc" => $sale->getCompany()->getRuc(),
                "razonSocial" => $sale->getCompany()->getCompanyName(),
                "nombreComercial" => $sale->getCompany()->getCompanyName(),
                "address" => [
                    "ubigueo" => $ubigeo,
                    "departamento" => $department->getNomdep(),
                    "provincia" => $province->getNompro(),
                    "distrito" => $district->getNomdis(),
                    "urbanizacion" => '-',
                    "direccion" => $sale->getCompany()->getAddress()
                ]
            ],
            "details" => array_map(function ($article) use ($sale) {

                return [
                    "codProducto" => $article->getArticle()->getId(),
                    "unidad" => "NIU",
                    "cantidad" => $article->getQuantity(),
                    "descripcion" => $article->getArticle()->getDescription(),
                    "mtoBaseIgv" => round($article->getSubtotal() / 1.18, 2),
                    "porcentajeIgv" => 18,
                    "igv" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "tipAfeIgv" => "10",
                    "totalImpuestos" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "mtoValorVenta" => round($article->getSubtotal() / 1.18, 2),
                    "mtoValorUnitario" => round($article->getUnitPrice() / 1.18, 2),
                    "mtoPrecioUnitario" => $article->getUnitPrice()
                ];
            }, $saleArticles),
            "legends" => [
                [
                    "code" => "1000",
                    "value" => "SON CIENTO DIECIOCHO CON 00/100 SOLES"
                ]
            ],
            "ublVersion" => "2.1",
            "tipoOperacion" => "0101",
            "forma_pago_tipo" => "Contado",
            "tipoDoc" => "0" . (string) $sale->getDocumentType()->getId(),
            "serie" => $sale->getSerie(),
            "correlativo" => $sale->getDocumentNumber(),
            "fechaEmision" => $sale->getDate(),
            "tipoMoneda" => $sale->getCurrencyType()->getSunatSymbol(),
            "mtoOperGravadas" => $sale->getSubtotal(),
            "mtoIGV" => $sale->getIgv(),
            "totalImpuestos" => $sale->getIgv(),
            "subTotal" => $sale->getTotal(),
            "valorVenta" => $sale->getSubTotal(),
            "mtoImpVenta" => $sale->getTotal()
        ];
        
        $response = Http::withHeaders([
            'Authorization' => "Bearer $this->token",
            'Accept' => 'application/json'
        ])->post("{$this->baseUrl}/boleta/send", $data);
        
        return $response->json();
    }
    
    public function sendCreditNote(Sale $sale, array $saleArticles)
    {
        $ubigeo = $sale->getCompany()->getUbigeo();
        $coddep = substr($ubigeo, 0, 2);
        $codprov = substr($ubigeo, 2, 2);
        $coddist = substr($ubigeo, 4, 2);

        $departmentUseCase = new FindByIdDepartmentUseCase($this->departmentRepository);
        $department = $departmentUseCase->execute($coddep);

        $provinceUseCase = new FindByIdProvinceUseCase($this->provinceRepository);
        $province = $provinceUseCase->execute($coddep, $codprov);

        $districtUseCase = new FindByIdDistrictUseCase($this->districtRepository);
        $district = $districtUseCase->execute($coddep, $codprov, $coddist);

        $data = [
            "client" => [
                "tipoDoc" => (string) $sale->getCustomer()->getCustomerDocumentType()->getCodSunat(),
                "numDoc" => $sale->getCustomer()->getDocumentNumber(),
                "rznSocial" => $sale->getCustomer()->getCompanyName()
            ],
            "company" => [
                "ruc" => $sale->getCompany()->getRuc(),
                "razonSocial" => $sale->getCompany()->getCompanyName(),
                "nombreComercial" => $sale->getCompany()->getCompanyName(),
                "address" => [
                    "ubigueo" => $ubigeo,
                    "departamento" => $department->getNomdep(),
                    "provincia" => $province->getNompro(),
                    "distrito" => $district->getNomdis(),
                    "urbanizacion" => '-',
                    "direccion" => $sale->getCompany()->getAddress()
                ]
            ],
            "details" => array_map(function ($article) use ($sale) {

                return [
                    "codProducto" => (string) $article->getArticle()->getId(),
                    "unidad" => "NIU",
                    "cantidad" => 1,
                    "descripcion" => $article->getArticle()->getDescription(),
                    "mtoBaseIgv" => round($article->getSubtotal() / 1.18, 2),
                    "porcentajeIgv" => 18,
                    "igv" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "tipAfeIgv" => "10",
                    "totalImpuestos" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "mtoValorVenta" => round($article->getSubtotal() / 1.18, 2),
                    "mtoValorUnitario" => round($article->getUnitPrice() / 1.18, 2),
                    "mtoPrecioUnitario" => $article->getUnitPrice()
                ];
            }, $saleArticles),
            "legends" => [
                [
                    "code" => "1000",
                    "value" => "SON CIENTO DIECIOCHO CON 00/100 SOLES"
                ]
            ],
            "ublVersion" => "2.1",
            "tipDocAfectado" => "01",
            "numDocAfectado" => $sale->getReferenceSerie() . '-' . $sale->getReferenceCorrelative(),
            "codMotivo" => $sale->getNoteReason()->getCodSunat(),
            "desMotivo" => $sale->getNoteReason()->getDescription(),
            "tipoOperacion" => "0101",
            "tipoDoc" => "0" . (string) $sale->getDocumentType()->getId(),
            "serie" => $sale->getSerie(),
            "correlativo" => $sale->getDocumentNumber(),
            "fechaEmision" => $sale->getDate(),
            "tipoMoneda" => $sale->getCurrencyType()->getSunatSymbol(),
            "mtoOperGravadas" => $sale->getSubtotal(),
            "mtoIGV" => $sale->getIgv(),
            "subTotal" => $sale->getTotal(),
            "totalImpuestos" => $sale->getIgv(),
            "valorVenta" => $sale->getSubTotal(),
            "mtoImpVenta" => $sale->getTotal()
        ];

        $response = Http::withHeaders([
            'Authorization' => "Bearer $this->token",
            'Accept' => 'application/json'
        ])->post("{$this->baseUrl}/notacredito/send", $data);

        return $response->json();
    }
    
    public function sendDebitNote(Sale $sale, array $saleArticles)
    {
        $ubigeo = $sale->getCompany()->getUbigeo();
        $coddep = substr($ubigeo, 0, 2);
        $codprov = substr($ubigeo, 2, 2);
        $coddist = substr($ubigeo, 4, 2);

        $departmentUseCase = new FindByIdDepartmentUseCase($this->departmentRepository);
        $department = $departmentUseCase->execute($coddep);

        $provinceUseCase = new FindByIdProvinceUseCase($this->provinceRepository);
        $province = $provinceUseCase->execute($coddep, $codprov);

        $districtUseCase = new FindByIdDistrictUseCase($this->districtRepository);
        $district = $districtUseCase->execute($coddep, $codprov, $coddist);

        $data = [
            "client" => [
                "tipoDoc" => (string) $sale->getCustomer()->getCustomerDocumentType()->getCodSunat(),
                "numDoc" => $sale->getCustomer()->getDocumentNumber(),
                "rznSocial" => $sale->getCustomer()->getCompanyName()
            ],
            "company" => [
                "ruc" => $sale->getCompany()->getRuc(),
                "razonSocial" => $sale->getCompany()->getCompanyName(),
                "nombreComercial" => $sale->getCompany()->getCompanyName(),
                "address" => [
                    "ubigueo" => $ubigeo,
                    "departamento" => $department->getNomdep(),
                    "provincia" => $province->getNompro(),
                    "distrito" => $district->getNomdis(),
                    "urbanizacion" => '-',
                    "direccion" => $sale->getCompany()->getAddress()
                ]
            ],
            "details" => array_map(function ($article) use ($sale) {

                return [
                    "codProducto" => (string) $article->getArticle()->getId(),
                    "unidad" => "NIU",
                    "cantidad" => 1,
                    "descripcion" => $article->getArticle()->getDescription(),
                    "mtoBaseIgv" => round($article->getSubtotal() / 1.18, 2),
                    "porcentajeIgv" => 18,
                    "igv" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "tipAfeIgv" => "10",
                    "totalImpuestos" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "mtoValorVenta" => round($article->getSubtotal() / 1.18, 2),
                    "mtoValorUnitario" => round($article->getUnitPrice() / 1.18, 2),
                    "mtoPrecioUnitario" => $article->getUnitPrice()
                ];
            }, $saleArticles),
            "legends" => [
                [
                    "code" => "1000",
                    "value" => "SON CIENTO DIECIOCHO CON 00/100 SOLES"
                ]
            ],
            "ublVersion" => "2.1",
            "tipDocAfectado" => "01",
            "numDocAfectado" => $sale->getReferenceSerie() . '-' . $sale->getReferenceCorrelative(),
            "codMotivo" => $sale->getNoteReason()->getCodSunat(),
            "desMotivo" => $sale->getNoteReason()->getDescription(),
            "tipoOperacion" => "0101",
            "tipoDoc" => "0" . (string) $sale->getDocumentType()->getId(),
            "serie" => $sale->getSerie(),
            "correlativo" => $sale->getDocumentNumber(),
            "fechaEmision" => $sale->getDate(),
            "tipoMoneda" => $sale->getCurrencyType()->getSunatSymbol(),
            "mtoOperGravadas" => $sale->getSubtotal(),
            "mtoIGV" => $sale->getIgv(),
            "subTotal" => $sale->getTotal(),
            "totalImpuestos" => $sale->getIgv(),
            "valorVenta" => $sale->getSubTotal(),
            "mtoImpVenta" => $sale->getTotal()
        ];

        $response = Http::withHeaders([
            'Authorization' => "Bearer $this->token",
            'Accept' => 'application/json'
        ])->post("{$this->baseUrl}/notadebito/send", $data);

        return $response->json();
    }
    
    public function sendDispatchNote(DispatchNote $dispatchNote, array $dispatchNoteArticles)
    {
        $eloquentDispatchNote = EloquentDispatchNote::find($dispatchNote->getId());
        
        $ubigeo = $dispatchNote->getCompany()->getUbigeo();
        $coddep = substr($ubigeo, 0, 2);
        $codprov = substr($ubigeo, 2, 2);
        $coddist = substr($ubigeo, 4, 2);
    
        $departmentUseCase = new FindByIdDepartmentUseCase($this->departmentRepository);
        $department = $departmentUseCase->execute($coddep);
    
        $provinceUseCase = new FindByIdProvinceUseCase($this->provinceRepository);
        $province = $provinceUseCase->execute($coddep, $codprov);
    
        $districtUseCase = new FindByIdDistrictUseCase($this->districtRepository);
        $district = $districtUseCase->execute($coddep, $codprov, $coddist);
        
        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $customer = $customerUseCase->execute($dispatchNote->getCustomerId());
        
        $customerAddress = EloquentCustomerAddress::find($dispatchNote->getdestination_branch_client());
        $departmentCustomer = strlen((string) $customerAddress->department_id) == 1 ? '0' . $customerAddress->department_id : $customerAddress->department_id;
        $provinceCustomer = strlen((string) $customerAddress->province_id) == 1 ? '0' . $customerAddress->province_id : $customerAddress->province_id;
        $districtCustomer = strlen((string) $customerAddress->district_id) == 1 ? '0' . $customerAddress->district_id : $customerAddress->district_id;
        $ubigeoCustomer = $departmentCustomer . $provinceCustomer . $districtCustomer;
    
        $data = [
            "company" => [
                "ruc" => $dispatchNote->getCompany()->getRuc(),
                "razonSocial" => $dispatchNote->getCompany()->getCompanyName(),
                "nombreComercial" => $dispatchNote->getCompany()->getCompanyName(),
                "address" => [
                    "ubigueo" => $ubigeo,
                    "departamento" => $department->getNomdep(),
                    "provincia" => $province->getNompro(),
                    "distrito" => $district->getNomdis(),
                    "urbanizacion" => '-',
                    "direccion" => $dispatchNote->getCompany()->getAddress()
                ]
            ],
            "destinatario" => [
                "tipoDoc" => (string) $customer->getCustomerDocumentType()->getCodSunat(),
                "numDoc" => $customer->getDocumentNumber(),
                "rznSocial" => $customer->getCustomerDocumentType()->getId() == 6 ? $customer->getCompanyName() : $customer->getName() . ' ' . $customer->getLastName() . ' ' . $customer->getSecondLastname()
            ],
            "envio" => [
                "codTraslado" => (string) $dispatchNote->getEmissionReason()->getId(),
                "modTraslado" => (string) $dispatchNote->getTransferType(),
                "fechTraslado" => $eloquentDispatchNote->created_at->format('Y-m-d'),
                "pesoTotal" => $dispatchNote->getTotalWeight(),
                "undPesoTotal" => "KGM",
                "llegada" => [
                    "ubigueo" => $ubigeoCustomer,
                    "direccion" => $customerAddress->address
                ],
                "partida" => [
                    "ubigueo" => $ubigeo,
                    "direccion" => $dispatchNote->getCompany()->getAddress()
                ],
                "numBultos" => 4
            ],
            "details" => array_map(function ($article) use ($dispatchNote) {
    
                return [
                    "codProducto" => (string) $article->getArticle()->getId(),
                    "unidad" => "NIU",
                    "cantidad" => $article->getQuantity(),
                    "descripcion" => $article->getArticle()->getDescription(),
                    "mtoBaseIgv" => round($article->getSubtotal() / 1.18, 2),
                    "porcentajeIgv" => 18,
                    "igv" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "tipAfeIgv" => "10",
                    "totalImpuestos" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "mtoValorVenta" => round($article->getSubtotal() / 1.18, 2),
                    "mtoValorUnitario" => round($article->getUnitPrice() / 1.18, 2),
                    "mtoPrecioUnitario" => $article->getUnitPrice()
                ];
            }, $dispatchNoteArticles)
        ];
    
        $response = Http::withHeaders([
            'Authorization' => "Bearer $this->token",
            'Accept' => 'application/json'
        ])->post("{$this->baseUrl}/notadebito/send", $data);
    
        return $response->json();
    }
}