<?php
namespace App\Modules\Articles\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleNotasDebito extends FormRequest {
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
         $payload = auth('api')->payload();

           $user = auth('api')->user();
        $companyId = $payload->get('company_id');

        $this->merge([
            'user_id' => $user->getAuthIdentifier(),
            'company_id' => $companyId,

        ]);
    }
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer',
            'user_id' => 'required|integer',
            'description' => 'required|string|max:100',
        ];
    }
}