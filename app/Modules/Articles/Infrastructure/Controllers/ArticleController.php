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
use App\Modules\Articles\Domain\Interfaces\FileStoragePort;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
  public function index(Request $request): array
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
    $data = $request->validated();

    // 🔍 Buscar el artículo existente
    $article = $this->articleRepository->findById($id);
    if (!$article) {
        return response()->json(['message' => 'Artículo no encontrado'], 404);
    }

    // 📸 Manejo de imagen con Storage
    if ($request->hasFile('image_url') && $request->file('image_url')->isValid()) {
        $image = $request->file('image_url');

        // Guardar nueva imagen en storage/app/public/articles
        $path = $image->store('articles', 'public');
        $data['image_url'] = Storage::url($path);

        // Eliminar imagen anterior si existía
        if ($article->getImageURL()) {
            $oldImagePath = str_replace('/storage', 'public', $article->getImageURL());
            if (Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
        }
    } else {
        // Mantener la URL existente si no se sube nueva imagen
        $data['image_url'] = $article->getImageURL();
    }

    //  Crear DTO y ejecutar caso de uso
    $articleDTO = new ArticleDTO($data);
    $articleUseCase = new UpdateArticleUseCase(
        $this->categoryRepository,
        $this->articleRepository,
        $this->measurementUnitRepository,
        $this->brandRepository,
        $this->userRepository,
        $this->currencyTypeRepository,
        $this->subCategoryRepository,
        $this->companyRepository
    );

    $articleUseCase->execute($id, $articleDTO);

    return response()->json(
         ["message"=>"se guardo"],200
    );
}


  public function store(StoreArticleRequest $request): JsonResponse
{
    $data = $request->validated();

    // 📸 Manejo de imagen con Storage
    if ($request->hasFile('image_url') && $request->file('image_url')->isValid()) {
        $image = $request->file('image_url');

        // Guarda en storage/app/public/articles
        $path = $image->store('articles', 'public');

        // Genera la URL pública (ejemplo: /storage/articles/imagen.png)
        $data['image_url'] = Storage::url($path);
    } else {
        $data['image_url'] = null;
    }

    //  Crear DTO y ejecutar caso de uso
    $articleDTO = new ArticleDTO($data);
    $articleUseCase = new CreateArticleUseCase(
        $this->categoryRepository,
        $this->articleRepository,
        $this->measurementUnitRepository,
        $this->brandRepository,
        $this->userRepository,
        $this->currencyTypeRepository,
        $this->subCategoryRepository,
        $this->companyRepository
    );

    $article = $articleUseCase->execute($articleDTO);

   
    return response()->json(
        (new ArticleResource($article))->resolve(),
        201
    );
}

}