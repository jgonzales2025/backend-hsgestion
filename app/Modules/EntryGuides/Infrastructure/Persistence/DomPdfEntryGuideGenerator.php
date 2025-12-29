<?php

namespace App\Modules\EntryGuides\Infrastructure\Persistence;

use App\Modules\DocumentEntryGuide\Domain\Interface\DocumentEntryGuideRepositoryInterface;
use App\Modules\EntryGuideArticle\Domain\Interface\EntryGuideArticleRepositoryInterface;
use App\Modules\EntryGuideArticle\Infrastructure\Resource\EntryGuideArticleResource;
use App\Modules\EntryGuides\Domain\Entities\EntryGuide;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuidePDF;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\EntryGuides\Infrastructure\Resource\EntryGuideResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DomPdfEntryGuideGenerator implements EntryGuidePDF
{
    public function __construct(
        private readonly EntryGuideRepositoryInterface $entryGuideRepository,
        private readonly EntryGuideArticleRepositoryInterface $entryGuideArticleRepository,
        private readonly DocumentEntryGuideRepositoryInterface $documentEntryGuideRepositoryInterface,
    ) {}

    public function generate(EntryGuide $entryGuide): string
    {
        $entryGuide = $this->entryGuideRepository->findById($entryGuide->getId());

        $articles = $this->entryGuideArticleRepository->findById($entryGuide->getId());
        $document_entry_guide = $this->documentEntryGuideRepositoryInterface->findByIdObj($entryGuide->getId());

        $pdf = Pdf::loadView('entry_guide', [
            'entryGuide' => $entryGuide,
            'company' => $entryGuide->getCompany(),
            'branch' => $entryGuide->getBranch(),
            'customer' => $entryGuide->getCustomer(),
            'articles' => $articles,
            'document_entry_guide' => $document_entry_guide,
        ]);

        $filename = 'entry_guide_' . $entryGuide->getId() . '.pdf';
        $path = 'pdf/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }
}
