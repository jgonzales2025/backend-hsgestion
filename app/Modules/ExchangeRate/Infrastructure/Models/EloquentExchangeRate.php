<?php

namespace App\Modules\ExchangeRate\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentExchangeRate extends Model
{
    protected $table = 'exchange_rates';

    protected $fillable = ['date', 'purchase_rate', 'sale_rate', 'parallel_rate', 'almacen', 'compras', 'ventas', 'cobranzas', 'pagos'];

    protected $hidden = ['created_at', 'updated_at'];
}
