<?php
namespace App\Modules\RecordType\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecordTypeRquest extends FormRequest{
    public function authorize():bool{
        return true;
    }
   public function rules(): array
    {
    
        $recordTypeId = $this->route('id'); 

        return [
            'name' => [
                'sometimes',    
                'string',
                'max:50',
                Rule::unique('record_types', 'name')->ignore($recordTypeId)
            ],
            'abbreviation' => [
                'sometimes',
                'string',
                'max:10',
                Rule::unique('record_types', 'abbreviation')->ignore($recordTypeId)
            ],
            'status' => [
                'sometimes',
                'integer',
                'exists:statuses,id' 
            ],
        ];
    }
}