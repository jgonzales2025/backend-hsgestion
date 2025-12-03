<?php

namespace App\Modules\DetailPcCompatible\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDetailPcCompatibleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'article_major_id' => 'nullable|integer',
            'article_accesory_id' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ];
    }
}
