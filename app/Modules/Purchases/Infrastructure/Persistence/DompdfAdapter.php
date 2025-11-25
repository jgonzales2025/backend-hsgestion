<?php 
namespace App\Modules\Purchases\Infrastructure\Persistence;

use App\Modules\Purchases\Domain\Interface\GeneratepdfRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class DompdfAdapter implements GeneratepdfRepositoryInterface
{
    public function generate(string $html, array $options = []): string
    {
        $pdf = Pdf::loadHTML($html);
        if (isset($options['orientation'])) {
            $pdf->setPaper('a4', $options['orientation']);
        }
        return $pdf->output();
    }
    public function download(string $html, string $filename, array $options = []): Response
    {
        $pdf = Pdf::loadHTML($html);
        if (isset($options['orientation'])) {
            $pdf->setPaper('a4', $options['orientation']);
        }
        return $pdf->download($filename);
    }
}

