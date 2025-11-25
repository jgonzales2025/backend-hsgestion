<?php
namespace App\Modules\EntryGuides\Domain\Interfaces;

use App\Modules\EntryGuides\Domain\Entities\EntryGuide;

interface EntryGuidePDF{
    public function generate(EntryGuide $entryGuide): string;
}
