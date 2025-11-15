<?php

namespace App\Modules\DispatchArticleSerial\Domain\Interfaces;

use App\Modules\DispatchArticleSerial\Domain\Entities\DispatchArticleSerial;

interface DispatchArticleSerialRepositoryInterface
{
    public function save(DispatchArticleSerial $dispatchArticleSerial): ?DispatchArticleSerial;
}
