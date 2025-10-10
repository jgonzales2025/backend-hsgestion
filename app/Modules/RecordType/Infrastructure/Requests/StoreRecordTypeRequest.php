<?php

namespace App\Modules\RecordType\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecordTypeRequest extends FormRequest {
    public function authorize():bool{
        return true;
    }
    public function rules():array{
        return [
            'name'=>'required|string|max:20',
            'abbreviation'=>'required|string|max:20',
            'status'=> 'required|integer',
        ];
    }
}  