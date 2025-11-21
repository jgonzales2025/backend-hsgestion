<?php

namespace App\Modules\PettyCashMotive\Infrastructure\Models;

use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;
use App\Modules\PettyCashMotive\Domain\Entities\PettyCashMotive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentPettyCashMotive extends Model{

   protected $table = 'petty_cash_motive';
   protected $fillable = [
           'company_id',
           'description',
           'receipt_type',
           'user_id', 
           'status',
   ];
         protected $hidden = ['created_at', 'updated_at'];
       public function documentType():BelongsTo
       {
           return $this->belongsTo(EloquentDocumentType::class, 'receipt_type');
       }


        public function toDomain(EloquentPettyCashMotive $pettyCashMotive): PettyCashMotive
        {
            return new PettyCashMotive(
               id: $pettyCashMotive->id,
               company_id: $pettyCashMotive->company_id,
               description: $pettyCashMotive->description,
               receipt_type: $pettyCashMotive->documentType->toDomain($pettyCashMotive->documentType),  
               user_id: $pettyCashMotive->user_id,
               status: $pettyCashMotive->status,
            );
            
        }
}