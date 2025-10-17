<?php
namespace App\Modules\VisibleArticles\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVisibleArticleRequest extends FormRequest{
    
    public function authorize():bool{
        return true;
    }
    protected function prepareForValidation():void{

    }
    public function rules():array{
        return [
            'company_id'=> 'integer',
            'branch_id'=>'integer',
            'article_id'=> 'integer',
            'user_id'=> 'integer',
            'status'=>' required|integer'
        ];
    }
}