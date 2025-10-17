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
            refCode: $referenceCode->ref_code,
            articleId: $referenceCode->article_id,
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
            refCode: $referenceCode->ref_code,
            articleId: $referenceCode->article_id,
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
            refCode: $referenceCode->ref_code,
            articleId: $referenceCode->article_id,
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
            'refCode' => $referenceCode->getRefCode(),
            'articleId' => $referenceCode->getArticleId(),
            'dateAt' => $referenceCode->getDateAt(),
            'status' => $referenceCode->getStatus(),
           ]);
    }
      public function save(ReferenceCode $referenceCode): ?ReferenceCode
{
    // 1️⃣ Crear el registro y capturar el modelo creado
    $eloquentReferenceCode = EloquentReferenceCode::create([
          'ref_code' => $referenceCode->getRefCode(), 
        'article_id' => $referenceCode->getArticleId(),
        'date_at' => $referenceCode->getDateAt() ?? now(),
        'status' => $referenceCode->getStatus(),
    ]);

    return new ReferenceCode(
        id: $eloquentReferenceCode->id,
        refCode: $eloquentReferenceCode->ref_code,
          articleId: $eloquentReferenceCode->article_id,
        dateAt: $eloquentReferenceCode->date_at,
        status: $eloquentReferenceCode->status,
    );
}

}