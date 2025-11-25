<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchArticle\Infrastructure\Resource\DispatchArticleResource;
use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;

use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Domain\Interfaces\PdfGeneratorInterface;
use App\Modules\DispatchNotes\Infrastructure\Resource\DispatchNoteResource;
use App\Modules\DispatchNotes\Infrastructure\Resource\ExcelNoteResource;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GenerarPDF 
{
    public function generatePDF(DispatchNote $purchase): string
    {
  
    }

  
}
