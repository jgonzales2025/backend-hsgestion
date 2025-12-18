<?php

namespace App\Modules\BuildPc\Infrastructure\Models;

use App\Modules\BuildDetailPc\Infrastructure\Models\EloquentBuildDetailPc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentBuildPc extends Model
{
    protected $table = 'build_pc_tabla';
    protected $fillable = [
        'id',
        'company_id',
        'name',
        'description',
        'total_price',
        'user_id',
        'status'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(EloquentBuildDetailPc::class, 'build_pc_id', 'id');
    }
}
