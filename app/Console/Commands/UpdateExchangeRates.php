<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

            $url = config('services.external_api.exchange_rate_api_url') . "/{$date}";
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.external_api.sunat_api_token'),
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

                // Actualizar registro del día anterior
                $yesterday = now()->subDay()->format('Y-m-d');
                DB::table('exchange_rates')
                    ->where('date', $yesterday)
                    ->update([
                        'ventas' => 1,
                        'cobranzas' => 1
                    ]);

                return Command::SUCCESS;
            }

            $this->warn('Error al obtener datos de la API. Intentando copiar del día anterior...');

            $lastExchangeRate = DB::table('exchange_rates')
                ->where('date', '<', $date)
                ->orderBy('date', 'desc')
                ->first();

            if ($lastExchangeRate) {
                DB::table('exchange_rates')->updateOrInsert(
                    ['date' => $date],
                    [
                        'purchase_rate' => $lastExchangeRate->purchase_rate,
                        'sale_rate' => $lastExchangeRate->sale_rate,
                        'parallel_rate' => $lastExchangeRate->parallel_rate,
                        'updated_at' => now()
                    ]
                );

                $this->info('Tipo de cambio copiado del día anterior correctamente');

                // Actualizar registro del día anterior
                $yesterday = now()->subDay()->format('Y-m-d');
                DB::table('exchange_rates')
                    ->where('date', $yesterday)
                    ->update([
                        'ventas' => 1,
                        'cobranzas' => 1
                    ]);

                return Command::SUCCESS;
            }

            $this->error('Error al obtener datos de la API y no hay datos históricos');
            return Command::FAILURE;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
