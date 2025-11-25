<?php

namespace App\Modules\Purchases\Domain\Interface;


interface GeneratepdfRepositoryInterface
{
    public function generate(string $html, array $options = []): string;
    public function download(string $html, string $filename, array $options = []): mixed;
}
