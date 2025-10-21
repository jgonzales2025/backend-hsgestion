<?php

namespace App\Modules\Articles\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Application\DTOs\ArticleDTO;
use App\Modules\Articles\Application\UseCases\CreateArticleUseCase;
use App\Modules\Articles\Application\UseCases\FindAllArticleUseCase;
use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Application\UseCases\UpdateArticleUseCase;
use App\Modules\Articles\Domain\Entities\Article;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Infrastructure\Persistence\EloquentArticleRepository;
use App\Modules\Articles\Infrastructure\Requests\StoreArticleRequest;
use App\Modules\Articles\Infrastructure\Requests\UpdateArticleRequest;
use App\Modules\Articles\Infrastructure\Resource\ArticleResource;
use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;
use App\Modules\Category\Domain\Interfaces\CategoryRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\MeasurementUnit\Domain\Interfaces\MeasurementUnitRepositoryInterface;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{


  public function __construct(
    private readonly CategoryRepositoryInterface $categoryRepository,
    private readonly ArticleRepositoryInterface $articleRepository,
    private readonly MeasurementUnitRepositoryInterface $measurementUnitRepository,
    private readonly BrandRepositoryInterface $brandRepository,
    private readonly UserRepositoryInterface $userRepository,
    private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
    private readonly SubCategoryRepositoryInterface $subCategoryRepository,
    private readonly CompanyRepositoryInterface $companyRepository,

  ) {
  }
  public function index(): array
  {
    $articleUseCase = new FindAllArticleUseCase($this->articleRepository);
    $article = $articleUseCase->execute();

    return ArticleResource::collection($article)->resolve();
  }
  public function show(int $id): JsonResponse
  {
    $articleUseCase = new FindByIdArticleUseCase($this->articleRepository);
    $article = $articleUseCase->execute($id);

    if (!$article) {
      return response()->json(["message" => "no se encontraron articulo"]);
    }

    return response()->json(
      (new ArticleResource($article))->resolve(),
      200
    );

  }
  public function update(UpdateArticleRequest $request, int $id): JsonResponse
  {
    $articleDTO = new ArticleDTO($request->validated());

    $articleUseCase = new UpdateArticleUseCase($this->categoryRepository, $this->articleRepository, $this->measurementUnitRepository, $this->brandRepository, $this->userRepository, $this->currencyTypeRepository, $this->subCategoryRepository, $this->companyRepository);
    $articleUseCase->execute($id, $articleDTO);


    return response()->json(['message' => 'se actualizo correctamente']);
  }
  public function store(StoreArticleRequest $request): JsonResponse
  {
    $data = $request->validated();

    if ($request->hasFile('image_url')) {
      $image = $request->file('image_url');

      $destinationPath = public_path('image');

      if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0777, true);
      }

      $filename = time() . '_' . $image->getClientOriginalName();

      $image->move($destinationPath, $filename);

      $publicUrl = asset('image/' . $filename);

      $data['image_url'] = $publicUrl;

      \Log::info(' Imagen guardada correctamente en: ' . $publicUrl);
    } else {

      $data['image_url'] = null;
    }


    $articleDTO = new ArticleDTO($data);
    $articleUseCase = new CreateArticleUseCase($this->categoryRepository, $this->articleRepository, $this->measurementUnitRepository, $this->brandRepository, $this->userRepository, $this->currencyTypeRepository, $this->subCategoryRepository, $this->companyRepository);
    $article = $articleUseCase->execute($articleDTO);

    return response()->json(
      (new ArticleResource($article))->resolve(),
      201
    );
  }

}