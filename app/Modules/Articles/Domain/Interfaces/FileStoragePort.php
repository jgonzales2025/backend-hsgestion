<?php

namespace  App\Modules\Articles\Domain\Interfaces;

use Illuminate\Http\UploadedFile;

class FileStoragePort
{
    public function store(UploadedFile $file, string $directory): string
    {
        // Guarda el archivo y devuelve la ruta
        return $file->store($directory);
    }
    // public function getUrl(string $path): string;
    // public function delete(string $path): bool;
}