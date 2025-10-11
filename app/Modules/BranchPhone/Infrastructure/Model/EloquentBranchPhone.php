<?php

namespace App\Modules\BranchPhone\Infrastructure\Model;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentBranchPhone extends Model{
      protected $table = 'branch_phones';

    // Campos que se pueden asignar masivamente
    protected $fillable = ['branch_id', 'phone'];

    // Campos ocultos al serializar
    protected $hidden = ['created_at', 'updated_at'];

    // Relación inversa: un teléfono pertenece a una sucursal (branch)
    public function branch(): BelongsTo
    {
        return $this->belongsTo(EloquentBranch::class, 'branch_id');
    }
}