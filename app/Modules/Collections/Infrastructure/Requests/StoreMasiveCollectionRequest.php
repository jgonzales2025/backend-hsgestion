<?php

namespace App\Modules\Collections\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMasiveCollectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'collections' => 'required|array|min:1',
            'collections.*.id' => 'required|integer|exists:purchase_orders,id',
            'collections.*.quantity' => 'required|integer|min:1',
        ];
    }
}