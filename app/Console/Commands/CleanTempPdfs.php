<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTempPdfs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdfs:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina los archivos PDF temporales mayores a 24 horas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $disk = Storage::disk('public');
        $files = $disk->files('pdf'); // Asumiendo que guardas en storage/app/public/pdf
        $deleted = 0;

        foreach ($files as $file) {
            // Verifica si es un archivo PDF de tu carpeta generada
            if (str_ends_with($file, '.pdf')) {
                $lastModified = $disk->lastModified($file);
                $fileTime = Carbon::createFromTimestamp($lastModified);

                // Si tiene más de 24 horas (u otro tiempo que decidas)
                if ($fileTime->lt(Carbon::now()->subHours(24))) {
                    $disk->delete($file);
                    $deleted++;
                }
            }
        }

        $this->info("Se han eliminado $deleted archivos PDF antiguos.");
    }
}
