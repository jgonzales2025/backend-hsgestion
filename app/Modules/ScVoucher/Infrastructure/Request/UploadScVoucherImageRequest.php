<?php

namespace App\Modules\ScVoucher\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class UploadScVoucherImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'path_image' => 'required|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'path_image.required' => 'La imagen es obligatoria.',
            'path_image.image' => 'El archivo debe ser una imagen.',
            'path_image.max' => 'La imagen no debe pesar más de 2MB.',
        ];
    }
}
