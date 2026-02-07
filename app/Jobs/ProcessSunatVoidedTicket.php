<?php

namespace App\Jobs;

use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessSunatVoidedTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $saleId;
    protected $ticket;
    protected $ruc;
    protected $token;
    protected $baseUrl;

    /**
     * Número de intentos del job
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Tiempo máximo de ejecución (segundos)
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Segundos para esperar antes de reintentar
     *
     * @var array
     */
    public $backoff = [30, 60, 120, 240, 480];

    /**
     * Create a new job instance.
     */
    public function __construct(int $saleId, string $ticket, string $ruc, string $token, string $baseUrl)
    {
        $this->saleId = $saleId;
        $this->ticket = $ticket;
        $this->ruc = $ruc;
        $this->token = $token;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info("Procesando ticket SUNAT para anulación", [
                'sale_id' => $this->saleId,
                'ticket' => $this->ticket,
                'attempt' => $this->attempts()
            ]);

            // Consultar el estado del ticket
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->get("{$this->baseUrl}/voided/check/{$this->ticket}/{$this->ruc}");

            if ($response->successful()) {
                $result = $response->json();

                Log::info("Respuesta de ticket SUNAT recibida", [
                    'sale_id' => $this->saleId,
                    'ticket' => $this->ticket,
                    'response' => $result
                ]);

                // Actualizar la venta con la respuesta
                $sale = EloquentSale::find($this->saleId);
                if ($sale) {
                    // Aquí actualizamos el estado de la venta según la respuesta
                    if (isset($result['success']) && $result['success']) {
                        $sale->respuesta_sunat = json_encode($result);
                        $sale->estado_sunat = 'ANULADA';
                        
                        if (isset($result['fecha_respuesta'])) {
                            $sale->fecha_baja_sunat = $result['fecha_respuesta'];
                        }
                        
                        if (isset($result['hora_respuesta'])) {
                            $sale->hora_baja_sunat = $result['hora_respuesta'];
                        }
                    } else {
                        $sale->estado_sunat = 'ERROR_ANULACION';
                    }
                    $sale->save();
                }

                // Opcional: Disparar un evento para notificar al frontend
                // event(new SunatVoidedProcessed($sale, $result));

            } else {
                Log::error("Error al consultar ticket SUNAT", [
                    'sale_id' => $this->saleId,
                    'ticket' => $this->ticket,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                // Si el ticket aún está en proceso, volver a intentar
                if ($response->status() === 404 || $response->status() === 202) {
                    $this->release(60); // Reintentar en 60 segundos
                    return;
                }

                throw new \Exception("Error al consultar ticket: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("Error en ProcessSunatVoidedTicket", [
                'sale_id' => $this->saleId,
                'ticket' => $this->ticket,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Job ProcessSunatVoidedTicket falló completamente", [
            'sale_id' => $this->saleId,
            'ticket' => $this->ticket,
            'error' => $exception->getMessage()
        ]);

        // Actualizar la venta indicando que el proceso falló
        $sale = EloquentSale::find($this->saleId);
        if ($sale) {
            $sale->estado_sunat = 'FAILED_ANULACION';
            $sale->save();
        }
    }
}
