<?php
namespace App\Modules\BuildDetailPc\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateBuildDetailPcRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'build_pc_id' => 'required',
            'article_id' => 'required',
            'quantity' => 'required'
        ];
    }
}
