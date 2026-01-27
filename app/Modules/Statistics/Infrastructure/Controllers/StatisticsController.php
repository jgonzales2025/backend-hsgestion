<?php

namespace App\Modules\Statistics\Infrastructure\Controllers;

use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Collections\Domain\Entities\Collection;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Statistics\Application\UseCases\GetArticleIdPurchaseUseCase;
use App\Modules\Statistics\Application\UseCases\GetArticleIdSoldUseCase;
use App\Modules\Statistics\Application\UseCases\GetArticlesSoldUseCase;
use App\Modules\Statistics\Application\UseCases\GetCustomerConsumedItemsUseCase;
use App\Modules\Statistics\Application\UseCases\GetCustomerConsumedItemsPaginatedUseCase;
use App\Modules\Statistics\Domain\Interfaces\StatisticsRepositoryInterface;
use App\Modules\Statistics\Infrastructure\Persistence\ArticlePurchaseExport;
use App\Modules\Statistics\Infrastructure\Persistence\CustomerConsumedItemsExport;
use App\Modules\Statistics\Infrastructure\Persistence\ExcelListaPrecio;
use App\Modules\Statistics\Infrastructure\Persistence\ListaPreciosHeaderExport;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController
{
    public function __construct(
        private readonly GetCustomerConsumedItemsUseCase $getCustomerConsumedItemsUseCase,
        private readonly GetCustomerConsumedItemsPaginatedUseCase $getCustomerConsumedItemsPaginatedUseCase,
        private readonly GetArticlesSoldUseCase $getArticlesSoldUseCase,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly GetArticleIdSoldUseCase $getArticleIdSoldUseCase,
        private readonly GetArticleIdPurchaseUseCase $getArticleIdPurchaseUseCase,
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly StatisticsRepositoryInterface $statisticsRepository
    ) {}

    public function getCustomerConsumedItemsJson(Request $request)
    {
        $request->validate([
            'company_id' => 'required|integer',
            'customer_id' => 'nullable|integer',
            'branch_id' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'category_id' => 'nullable|integer',
            'brand_id' => 'nullable|integer',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $perPage = $request->input('per_page', 10);

        $items = $this->getCustomerConsumedItemsPaginatedUseCase->execute(
            company_id: $request->input('company_id'),
            branch_id: $request->input('branch_id'),
            start_date: $request->input('start_date'),
            end_date: $request->input('end_date'),
            category_id: $request->input('category_id'),
            brand_id: $request->input('brand_id'),
            customerId: $request->input('customer_id'),
            perPage: $perPage
        );

        return response()->json([
            'data' => $items->items(),
            'current_page' => $items->currentPage(),
            'per_page' => $items->perPage(),
            'total' => $items->total(),
            'last_page' => $items->lastPage(),
            'next_page_url' => $items->nextPageUrl(),
            'prev_page_url' => $items->previousPageUrl(),
            'first_page_url' => $items->url(1),
            'last_page_url' => $items->url($items->lastPage()),
        ]);
    }

    public function getCustomerConsumedItems(Request $request)
    {
        $request->validate([
            'company_id' => 'required|integer',
            'customer_id' => 'nullable|integer',
            'branch_id' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'category_id' => 'nullable|integer',
            'brand_id' => 'nullable|integer',
        ]);

        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($request->input('company_id'));

        // Handle branch name - if no branch_id, use "TODAS LAS SUCURSALES"
        $branchName = 'TODAS LAS SUCURSALES';
        if ($request->input('branch_id') !== null) {
            $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
            $branch = $branchUseCase->execute($request->input('branch_id'));
            $branchName = $branch->getName();
        }

        $items = $this->getCustomerConsumedItemsUseCase->execute(
            company_id: $request->input('company_id'),
            branch_id: $request->input('branch_id'),
            start_date: $request->input('start_date'),
            end_date: $request->input('end_date'),
            category_id: $request->input('category_id'),
            brand_id: $request->input('brand_id'),
            customerId: $request->input('customer_id')
        );

        $export = new CustomerConsumedItemsExport(
            items: collect($items),
            companyName: $company->getCompanyName(),
            branchName: $branchName,
            startDate: $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            endDate: $request->input('end_date', now()->format('Y-m-d'))
        );

        $customerIdentifier = $request->input('customer_id') ?? 'todos';
        $fileName = 'ventas_articulos_cliente_' . $customerIdentifier . '_' . date('YmdHis') . '.xlsx';

        return Excel::download($export, $fileName);
    }

    public function getArticlesSold(Request $request)
    {
        $request->validate([
            'company_id' => 'required|integer',
            'branch_id' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'category_id' => 'nullable|integer',
            'brand_id' => 'nullable|integer',
            'article_id' => 'nullable|integer',
            'description' => 'nullable|string'
        ]);

        $articles = $this->getArticlesSoldUseCase->execute(
            company_id: $request->input('company_id'),
            branch_id: $request->input('branch_id'),
            start_date: $request->input('start_date'),
            end_date: $request->input('end_date'),
            category_id: $request->input('category_id'),
            brand_id: $request->input('brand_id'),
            article_id: $request->input('article_id'),
            description: $request->input('description')
        );

        return response()->json([
            'data' => $articles->items(),
            'current_page' => $articles->currentPage(),
            'per_page' => $articles->perPage(),
            'total' => $articles->total(),
            'last_page' => $articles->lastPage(),
            'next_page_url' => $articles->nextPageUrl(),
            'prev_page_url' => $articles->previousPageUrl(),
            'first_page_url' => $articles->url(1),
            'last_page_url' => $articles->url($articles->lastPage()),
        ]);
    }

    public function getArticleIdSold(int $id, Request $request)
    {
        $request->validate([
            'company_id' => 'required|integer',
            'branch_id' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'category_id' => 'nullable|integer',
            'brand_id' => 'nullable|integer',
        ]);

        $articles = $this->getArticleIdSoldUseCase->execute(
            company_id: $request->input('company_id'),
            article_id: $id,
            branch_id: $request->input('branch_id'),
            start_date: $request->input('start_date'),
            end_date: $request->input('end_date'),
            category_id: $request->input('category_id'),
            brand_id: $request->input('brand_id')
        );

        return response()->json([
            'data' => $articles->items(),
            'current_page' => $articles->currentPage(),
            'per_page' => $articles->perPage(),
            'total' => $articles->total(),
            'last_page' => $articles->lastPage(),
            'next_page_url' => $articles->nextPageUrl(),
            'prev_page_url' => $articles->previousPageUrl(),
            'first_page_url' => $articles->url(1),
            'last_page_url' => $articles->url($articles->lastPage()),
        ]);
    }

    public function getArticleIdPurchase(int $id, Request $request)
    {
        $request->validate([
            'company_id' => 'required|integer',
            'branch_id' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'category_id' => 'nullable|integer',
            'brand_id' => 'nullable|integer',
        ]);

        $purchases = $this->getArticleIdPurchaseUseCase->execute(
            company_id: $request->input('company_id'),
            article_id: $id,
            branch_id: $request->input('branch_id'),
            start_date: $request->input('start_date'),
            end_date: $request->input('end_date'),
            category_id: $request->input('category_id'),
            brand_id: $request->input('brand_id')
        );

        return response()->json([
            'data' => $purchases->items(),
            'current_page' => $purchases->currentPage(),
            'per_page' => $purchases->perPage(),
            'total' => $purchases->total(),
            'last_page' => $purchases->lastPage(),
            'next_page_url' => $purchases->nextPageUrl(),
            'prev_page_url' => $purchases->previousPageUrl(),
            'first_page_url' => $purchases->url(1),
            'last_page_url' => $purchases->url($purchases->lastPage()),
        ]);
    }

    public function exportArticleIdPurchase(int $id, Request $request)
    {
        $request->validate([
            'company_id' => 'required|integer',
            'branch_id' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'category_id' => 'nullable|integer',
            'brand_id' => 'nullable|integer'
        ]);

        $articleUseCase = new FindByIdArticleUseCase($this->articleRepository);
        $article = $articleUseCase->execute($id);

        $purchases = $this->getArticleIdPurchaseUseCase->execute(
            company_id: $request->input('company_id'),
            article_id: $id,
            branch_id: $request->input('branch_id'),
            start_date: $request->input('start_date'),
            end_date: $request->input('end_date'),
            category_id: $request->input('category_id'),
            brand_id: $request->input('brand_id')
        );

        $companyUseCase = new FindByIdCompanyUseCase($this->companyRepository);
        $company = $companyUseCase->execute($request->input('company_id'));

        // Handle branch name - if no branch_id, use "TODAS LAS SUCURSALES"
        $branchName = 'TODAS LAS SUCURSALES';
        if ($request->input('branch_id') !== null) {
            $branchUseCase = new FindByIdBranchUseCase($this->branchRepository);
            $branch = $branchUseCase->execute($request->input('branch_id'));
            $branchName = $branch->getName();
        }

        $export = new ArticlePurchaseExport(
            items: collect($purchases),
            companyName: $company->getCompanyName(),
            branchName: $branchName,
            startDate: $request->input('start_date', now()->startOfMonth()->format('Y-m-d')),
            endDate: $request->input('end_date', now()->format('Y-m-d')),
            articleCode: $article->getCodFab(),
            articleDescription: $article->getDescription()
        );

        $fileName = 'compras_articulo_' . $id . '_' . date('YmdHis') . '.xlsx';

        return Excel::download($export, $fileName);
    }



    public function getListaPrecios(Request $request)
    {
        $request->validate([
            'p_codma'        => 'required|integer',
            'p_codcategoria' => 'nullable|integer',
            'p_status'       => 'nullable|integer',
            'p_moneda'       => 'nullable|integer',
            'p_orden'        => 'nullable|integer',
            'company_id'     => 'required|integer' // Added company_id
        ]);

        $company = $this->companyRepository->findById($request->input('company_id'));
        $companyName = $company ? $company->getCompanyName() : '';


        $data = $this->statisticsRepository->getListaPrecio(
            $request->p_codma,
            $request->p_codcategoria,
            $request->p_status,
            $request->p_moneda,
            $request->p_orden
        );


        $fileName = 'lista_precios_' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(
            new ListaPreciosHeaderExport($data, $companyName),
            $fileName
        );
    }


    private function paginateStoredProcedure(array $items, $perPage = 10): LengthAwarePaginator
    {
        $page = request()->get('page', 1);
        $items = collect($items);

        $pagedItems = $items->slice(($page - 1) * $perPage, $perPage)->values();

        return  new LengthAwarePaginator(
            $pagedItems,
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function listarPrecios(Request $request)
    {
        $request->validate([
            'p_codma' => 'required|integer',
            'p_codcategoria' => 'nullable|integer',
            'p_status' => 'nullable|integer',
            'p_moneda' => 'nullable|integer',
            'p_orden' => 'nullable|integer'
        ]);

        $data = $this->statisticsRepository->getListaPrecio(
            p_codma: $request->input('p_codma'),
            p_codcategoria: $request->input('p_codcategoria'),
            p_status: $request->input('p_status'),
            p_moneda: $request->input('p_moneda'),
            p_orden: $request->input('p_orden')
        );

        $data = $this->paginateStoredProcedure($data, 10);

        return response()->json([
            'data'           => $data->items(),
            'current_page'   => $data->currentPage(),
            'per_page'       => $data->perPage(),
            'total'          => $data->total(),
            'last_page'      => $data->lastPage(),
            'next_page_url'  => $data->nextPageUrl(),
            'last_page_url' => $data->url($data->lastPage()),
            'first_page_url' => $data->url(1),
            'prev_page_url'  => $data->previousPageUrl(),

        ]);
    }
    public function rankingAnualCliente(Request $request){
        $request->validate([
            'company_id' => 'required|integer',
            'branch_id' => 'nullable|integer',
            'customer_id' => 'required|integer',
            'annio' => 'required|integer',
            'currency_type_id' => 'required|integer',
            'document_type_id' => 'required|integer'
        ]);

        $data = $this->statisticsRepository->rankingAnualCliente(
            $request->input('company_id'),
            $request->input('branch_id'),
            $request->input('customer_id'),
            $request->input('annio'),
            $request->input('currency_type_id'),
            $request->input('document_type_id')
        );
       $data['company_id'] = $request->input('company_id');
        $data = $this->paginateStoredProcedure($data, 10);

        return response()->json([
            'data' => $data
        ]);
    }
    public function rankingAnualClientePaginatedExcel(Request $request){
        $request->validate([
            'company_id' => 'required|integer',
            'branch_id' => 'nullable|integer',
            'customer_id' => 'required|integer',
            'annio' => 'required|integer',
            'currency_type_id' => 'required|integer',
            'document_type_id' => 'required|integer'
        ]);

        $data = $this->statisticsRepository->rankingAnualCliente(
            $request->input('company_id'),
            $request->input('branch_id'),
            $request->input('customer_id'),
            $request->input('annio'),
            $request->input('currency_type_id'),
            $request->input('document_type_id')
        );
        $data['companyName'] =  $request->input('company_id');

        $fileName = 'ranking_anual_cliente_' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(
            new ListaPreciosHeaderExport($data,  $request->input('company_id')),
            $fileName
        );
    }
}
