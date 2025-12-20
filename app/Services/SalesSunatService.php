<?php

namespace App\Services;

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

class SalesSunatService
{
    public function __construct(
        private DepartmentRepositoryInterface $departmentRepository,
        private ProvinceRepositoryInterface $provinceRepository,
        private DistrictRepositoryInterface $districtRepository
    ) {
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
                "tipoDoc" => $sale->getCustomer()->getCustomerDocumentType()->getId(),
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
                    "mtoBaseIgv" => $article->getSubtotal(),
                    "porcentajeIgv" => 18,
                    "igv" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "tipAfeIgv" => "10",
                    "totalImpuestos" => round($article->getSubTotal() - ($article->getSubTotal() / 1.18), 2),
                    "mtoValorVenta" => $article->getSubtotal(),
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
            "tipoDoc" => $sale->getDocumentType()->getId(),
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

        $response = Http::withToken("P1KQbNw1S3zzdD4lEoPpWs3qf7GpEPJTRIPCgHDJDY9HeLOriN7ETiIRJcQu")->post("http://192.168.18.27:8001/api/v2/factura/send", $data);

        return $response;
    }
}