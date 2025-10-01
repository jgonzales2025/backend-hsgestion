<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['description', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
}
