<?php

namespace App\Modules\EntryItemSerial\Application\UseCases;

use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;

class FindSerialByArticleIdUseCase{

    private $entryItemSerialRepository;

    public function __construct(EntryItemSerialRepositoryInterface $entryItemSerialRepository)
    {
        $this->entryItemSerialRepository = $entryItemSerialRepository;
    }

    public function execute(int $articleId, int $branch_id, ?bool $updated, ?string $serial = null): ?array
    {
        return $this->entryItemSerialRepository->findSerialByArticleId($articleId, $branch_id, $updated, $serial);
    }
}
