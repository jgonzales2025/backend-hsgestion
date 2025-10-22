<?php

namespace App\Modules\Serie\Infrastructure\Persistence;

use App\Modules\Serie\Domain\Entities\Serie;
use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;
use App\Modules\Serie\Infrastructure\Models\EloquentSerie;
use Illuminate\Support\Facades\Log;

class EloquentSerieRepository implements SerieRepositoryInterface
{

    public function findByDocumentType(int $documentType, int $branch_id): ?Serie
    {
        $companyId = request()->get('company_id');
        Log::info('Company: ' . $companyId);
        Log::info('Branch: ' . $branch_id);
        Log::info('Document Type: ' . $documentType);
        $eloquentSeries = EloquentSerie::where('company_id', $companyId)->where('branch_id', $branch_id)->where('elec_document_type_id', $documentType)->first();
        //Log::info('Serie: ' . $eloquentSeries);
        return new Serie(
            id: $eloquentSeries->id,
            company_id: $eloquentSeries->company_id,
            serie_number: $eloquentSeries->serie_number,
            branch_id: $eloquentSeries->branch_id,
            elec_document_type_id: $eloquentSeries->elec_document_type_id,
            dir_document_type_id: $eloquentSeries->dir_document_type_id,
            status: $eloquentSeries->status,
        );
    }
}
