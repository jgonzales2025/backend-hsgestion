<?php

namespace App\Modules\DetailPcCompatible\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateDetailPcCompatibleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'article_major_id' => 'required',
            'article_accesory_id' => 'required',
            'status' => 'nullable|boolean',
        ];
    }
}
