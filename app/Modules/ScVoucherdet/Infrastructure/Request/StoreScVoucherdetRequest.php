<?php

namespace App\Modules\ScVoucherdet\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreScVoucherdetRequest extends FormRequest
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
            'numdoc' => 'required|string',
            'glosa' => 'required',
            'impsol' => 'required',
            'impdol' => 'required',
        ];
    }
}