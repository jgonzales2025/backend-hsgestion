<?php

namespace App\Modules\ScVoucherdet\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScVoucherdetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cia' => 'required',
            'codcon' => 'required',
            'tipdoc' => 'required',
            'numdoc' => 'required',
            'glosa' => 'required',
            'impsol' => 'required',
            'impdol' => 'required',
        ];
    }
}