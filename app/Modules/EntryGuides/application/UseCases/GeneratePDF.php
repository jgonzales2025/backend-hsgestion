<?php
namespace App\Modules\EntryGuides\Application\UseCases;

use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuidePDF;

class GeneratePDF
{
    public function __construct(
        private readonly EntryGuideRepositoryInterface $entryGuideRepository,
        private readonly EntryGuidePDF $entryGuidePdfGenerator,
    ) {}

    public function execute(int $entryGuideId): string
    {
        $entryGuide = $this->entryGuideRepository->findById($entryGuideId);
        return $this->entryGuidePdfGenerator->generate($entryGuide);
    }
}
