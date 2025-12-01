<?php

namespace App\Modules\DetailPcCompatible\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class StoreByArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'article_accesory_id' => 'required|array|min:1',
            'article_accesory_id.*' => 'required|integer|exists:articles,id',
            'status' => 'nullable|boolean',
        ];
    }
}
