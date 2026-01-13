<?php

namespace App\Modules\ArticleType\Infrastructure\Controllers;

use App\Modules\ArticleType\Domain\Interface\ArticleTypeRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ControllerArticleTypeModel
{
    public function __construct(
        private readonly ArticleTypeRepositoryInterface $articleTypeRepository
    ) {
    }
    public function index():JsonResponse
    {
      $findAll = $this->articleTypeRepository->findAll();
      return response()->json($findAll);   
    }
    public function show($id):JsonResponse
    {
        $findById = $this->articleTypeRepository->findById($id);
        return response()->json($findById);
    }   
}