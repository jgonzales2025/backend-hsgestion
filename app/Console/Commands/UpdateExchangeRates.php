<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza los tipos de cambio desde la API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $date = now()->format('Y-m-d');

            $url = config('app.exchange_rate_api_url') . "/{$date}";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('app.exchange_rate_api_token'),
                'Accept' => 'application/json',
            ])->get($url);

            if ($response->successful() && $response->json('success')) {
                $data = $response->json('data');

                // Obtener el parallel_rate del registro más reciente
                $previousRate = DB::table('exchange_rates')
                    ->orderBy('date', 'desc')
                    ->value('parallel_rate');

                // Usar el valor anterior o 3.55 por defecto
                $parallelRate = $previousRate ?? 3.55;

                DB::table('exchange_rates')->updateOrInsert(
                    ['date' => $data['date']],
                    [
                        'purchase_rate' => $data['buy_price'],
                        'sale_rate' => $data['sell_price'],
                        'parallel_rate' => $parallelRate,
                        'updated_at' => now()
                    ]
                );

                $this->info('Tipo de cambio actualizado correctamente');
                return Command::SUCCESS;
            }

            $this->error('Error al obtener datos de la API');
            return Command::FAILURE;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
