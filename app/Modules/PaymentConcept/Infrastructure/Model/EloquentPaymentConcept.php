<?php

namespace App\Modules\PaymentConcept\Infrastructure\Model;

use App\Modules\PaymentConcept\Domain\Entities\PaymentConcept;
use Illuminate\Database\Eloquent\Model;

class EloquentPaymentConcept extends Model
{
    protected $table = 'payment_concepts';
    protected $fillable = ['description'];
    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(): PaymentConcept
    {
        return new PaymentConcept(
            id: $this->id,
            description: $this->description,
        );
    }
}
