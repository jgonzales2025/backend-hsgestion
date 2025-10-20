<?php
namespace App\Modules\ReferenceCode\Infrastructure\Persistence;

use App\Modules\ReferenceCode\Domain\Entities\ReferenceCode;
use App\Modules\ReferenceCode\Domain\Interfaces\ReferenceCodeRepositoryInterface;
use App\Modules\ReferenceCode\Infrastructure\Models\EloquentReferenceCode;
use Illuminate\Support\Facades\Log;

class EloquentReferenceCodeRepository implements ReferenceCodeRepositoryInterface
{
    public function findAllReferenceCode(): array
    {
        $referenceCode = EloquentReferenceCode::all();
        if ($referenceCode->isEmpty()) {
            return [];
        }
        return $referenceCode->map(fn($referenceCode) => new ReferenceCode(
            id: $referenceCode->id,
            ref_code: $referenceCode->ref_code,
            article_id: $referenceCode->article_id,
            dateAt: $referenceCode->date_at,
            status: $referenceCode->status
        ))->toArray();
    }
  public function findById(int $articleId): array
{
    $referenceCodes = EloquentReferenceCode::where('article_id', $articleId)->get();

    if ($referenceCodes->isEmpty()) {
        return [];
    }

    return $referenceCodes->map(function ($referenceCode) {
        return new ReferenceCode(
            id: $referenceCode->id,
            ref_code: $referenceCode->ref_code,
            article_id: $referenceCode->article_id,
            dateAt: $referenceCode->date_at,
            status: $referenceCode->status
        );
    })->toArray();
}

     public function indexid(int $id): ?ReferenceCode
    {
              $referenceCode = EloquentReferenceCode::find($id);
            if (!$referenceCode) {
                return null;
            }
          return  new ReferenceCode(
            id: $referenceCode->id,
            ref_code: $referenceCode->ref_code,
            article_id: $referenceCode->article_id,
            dateAt: $referenceCode->date_at,
            status: $referenceCode->status
        );
    }
     public function update(ReferenceCode $referenceCode): void
    {

           $EloquentreferenceCode = EloquentReferenceCode::find($referenceCode->getId());
        
            if (!$EloquentreferenceCode) {
               throw new   \Exception("Error Processing Request", 1);
               
            }
           $EloquentreferenceCode->update([
            'ref_code' => $referenceCode->getRefCode(),
            'article_id' => $EloquentreferenceCode->article_id,
            'status' => $referenceCode->getStatus(),
           ]);
    }
      public function save(ReferenceCode $referenceCode): ?ReferenceCode
{
    // 1️ Crear el registro y capturar el modelo creado
    $eloquentReferenceCode = EloquentReferenceCode::create([
          'ref_code' => $referenceCode->getRefCode(), 
        'article_id' => $referenceCode->getArticleId(),
        'date_at' => $referenceCode->getDateAt() ?? now(),
        'status' => $referenceCode->getStatus(),
    ]);

    return new ReferenceCode(
        id: $eloquentReferenceCode->id,
        ref_code: $eloquentReferenceCode->ref_code,
          article_id: $eloquentReferenceCode->article_id,
        dateAt: $eloquentReferenceCode->date_at,
        status: $eloquentReferenceCode->status,
    );
}

}