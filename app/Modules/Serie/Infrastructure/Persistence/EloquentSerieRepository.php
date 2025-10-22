<?php

namespace App\Modules\Serie\Infrastructure\Persistence;

use App\Modules\Serie\Domain\Entities\Serie;
use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;
use App\Modules\Serie\Infrastructure\Models\EloquentSerie;
use Illuminate\Support\Facades\Log;

class EloquentSerieRepository implements SerieRepositoryInterface
{

    public function findByDocumentType(int $documentType): ?array
    {
        Log::info('Buscando series por tipo de documento: ' . $documentType);;
        $eloquentSeries = EloquentSerie::where('elec_document_type_id', $documentType);

        Log::info('Serires encontradas: ' . $eloquentSeries->count());

        return $eloquentSeries->map(function ($serie) {
            return new Serie(
                id: $serie->id,
                company: $serie->company->toDomain($serie->company),
                serie_number: $serie->serie_number,
                branch: $serie->branch->toDomain($serie->branch),
                elec_document_type: $serie->elecDocumentType->toDomain($serie->elecDocumentType),
                dir_document_type: $serie->dirDocumentType->toDomain($serie->dirDocumentType),
                status: $serie->status,
            );
        })->toArray();
    }
}
