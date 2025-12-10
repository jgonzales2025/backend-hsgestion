<?php

namespace App\Modules\Statistics\Infrastructure\Controllers;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Statistics\Application\UseCases\GetArticleIdPurchaseUseCase;
use App\Modules\Statistics\Application\UseCases\GetArticleIdSoldUseCase;
use App\Modules\Statistics\Application\UseCases\GetArticlesSoldUseCase;
use App\Modules\Statistics\Application\UseCases\GetCustomerConsumedItemsUseCase;
use App\Modules\Statistics\Infrastructure\Persistence\CustomerConsumedItemsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController
{
    public function __construct(
        private readonly GetCustomerConsumedItemsUseCase $getCustomerConsumedItemsUseCase,
        private readonly GetArticlesSoldUseCase $getArticlesSoldUseCase,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly GetArticleIdSoldUseCase $getArticleIdSoldUseCase,
        private readonly GetArticleIdPurchaseUseCase $getArticleIdPurchaseUseCase
    ) {
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
        ]);

        $articles = $this->getArticlesSoldUseCase->execute(
            company_id: $request->input('company_id'),
            branch_id: $request->input('branch_id'),
            start_date: $request->input('start_date'),
            end_date: $request->input('end_date'),
            category_id: $request->input('category_id'),
            brand_id: $request->input('brand_id')
        );

        return response()->json([
            'data' => $articles
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
            'data' => $articles
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
            'data' => $purchases
        ]);
    }
}
