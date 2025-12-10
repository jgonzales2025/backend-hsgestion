<?php

namespace App\Modules\PaymentConcept\Infrastructure\Model;

use Illuminate\Database\Eloquent\Model;

class EloquentPaymentConcept extends Model
{
    protected $table = 'payment_concepts';
    protected $fillable = ['description'];
    protected $hidden = ['created_at', 'updated_at'];
}