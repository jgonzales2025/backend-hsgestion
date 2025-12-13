<?php

namespace App\Modules\ScVoucher\Domain\Interface;

use App\Modules\ScVoucher\Domain\Entities\ScVoucher;

interface PdfGeneratorInterface
{
    public function generate(ScVoucher $scVoucher): string;
}
