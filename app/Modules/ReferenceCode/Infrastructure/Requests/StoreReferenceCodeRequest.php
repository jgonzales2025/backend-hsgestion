<?php
namespace App\Modules\ReferenceCode\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReferenceCodeRequest extends FormRequest{
    public function authorize():bool{
        return true;
    }
      public function rules():array{
        return [
            'ref_code'=>'string|max:20',
            'status'=> 'boolean',
        ];
    }
}