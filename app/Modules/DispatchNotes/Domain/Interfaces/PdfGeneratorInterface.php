<?php

namespace App\Modules\DispatchNotes\Domain\Interfaces;

use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;

interface PdfGeneratorInterface
{
    public function generate(DispatchNote $dispatchNote): string;
    public function exists(string $path): bool;
    public function getUrl(string $path): string;
}