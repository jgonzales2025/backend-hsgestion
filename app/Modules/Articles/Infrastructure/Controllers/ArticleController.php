<?php

namespace App\Modules\Articles\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Application\DTOs\ArticleDTO;
use App\Modules\Articles\Application\DTOS\ArticleNotasDebitoDTO;
use App\Modules\Articles\Application\UseCases\CreateArticleNotasDebito;
use App\Modules\Articles\Application\UseCases\CreateArticleUseCase;
use App\Modules\Articles\Application\UseCases\ExportArticlesToExcelUseCase;
use App\Modules\Articles\Application\UseCases\FindAllArticlesNotesDebitoUseCase;
use App\Modules\Articles\Application\UseCases\FindAllArticlesPriceConvertionUseCase;
use App\Modules\Articles\Application\UseCases\FindAllArticleUseCase;
use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Application\UseCases\FindByIdNotesDebito;
use App\Modules\Articles\Application\UseCases\RequiredSerialUseCase;
use App\Modules\Articles\Application\UseCases\UpdateArticleNotasDebitoUseCase;
use App\Modules\Articles\Application\UseCases\UpdateArticleUseCase;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Infrastructure\Requests\StoreArticleNotasDebito;
use App\Modules\Articles\Infrastructure\Requests\StoreArticleRequest;
use App\Modules\Articles\Infrastructure\Requests\UpdateArticleNotasDebito;
use App\Modules\Articles\Infrastructure\Requests\UpdateArticleRequest;
use App\Modules\Articles\Infrastructure\Resource\ArticleForSalesResource;
use App\Modules\Articles\Infrastructure\Resource\ArticleNotesDebitoResource;
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
use Illuminate\Support\Facades\Storage;
use App\Modules\Articles\Application\UseCases\UpdateStatusArticleUseCase;
use App\Modules\EntryItemSerial\Application\UseCases\FindBranchBySerial;
use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;

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
    private ExportArticlesToExcelUseCase $exportUseCase,
    private EntryItemSerialRepositoryInterface $entryItemSerialRepository,

  ) {
  }
  public function export()
  {
    try {
      $filePath = $this->exportUseCase->execute();

      return response()->download(
        storage_path('app/public/' . $filePath),
        basename($filePath),
        [
          'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
      )->deleteFileAfterSend(true);

    } catch (\Exception $e) {

      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function index(Request $request): array|JsonResponse
  {
    $name = $request->query("name");
    $branchId = $request->query("branch_id");

    $articleUseCase = new FindAllArticleUseCase($this->articleRepository);

    $article = $articleUseCase->execute($name, $branchId);
    
    if (empty($article)) {
      $entryItemSerialUseCase = new FindBranchBySerial($this->entryItemSerialRepository);
      $branch = $entryItemSerialUseCase->execute($name);

      if (!$branch)
      {
        return response()->json([
          "message" => "La serie es incorrecta"
        ], 404);
      }else {
        return response()->json([
          "message" => "El artículo no se encuentra en esta sucursal",
          'branch_id' => $branch['branch_id'],
          "location" => $branch['name']
        ]);
      }
      
    }

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
  public function indexNotesDebito(Request $request): array
  {
    $description = $request->query("description");

    $articleUseCase = new FindAllArticlesNotesDebitoUseCase($this->articleRepository);

    $article = $articleUseCase->execute($description);

    return ArticleNotesDebitoResource::collection($article)->resolve();
  }
  public function showNotesDebito(int $id): JsonResponse
  {

    $articleUseCase = new FindByIdNotesDebito($this->articleRepository);
    $article = $articleUseCase->execute($id);

    if (!$article) {
      return response()->json(["message" => "no se encontraron articulo"]);
    }

    return response()->json(
      (new ArticleNotesDebitoResource($article))->resolve(),
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

    $result = $articleUseCase->execute($id, $articleDTO);

    return response()->json(
      (new ArticleResource($result))->resolve(),
      200
    );
  }
  public function updateNotesDebito(UpdateArticleNotasDebito $request, int $id): JsonResponse
  {
    $data = $request->validated();

    // 🔍 Buscar el artículo existente
    $articlebuscR = new FindByIdNotesDebito($this->articleRepository);
    $article = $articlebuscR->execute($id);

    if (!$article) {
      return response()->json(['message' => 'Artículo no encontrado'], 404);
    }

    //  Crear DTO y ejecutar caso de uso
    $articleNotasDebitoDTO = new ArticleNotasDebitoDTO($data);
    $articleUseCase = new UpdateArticleNotasDebitoUseCase($this->articleRepository);

    $result = $articleUseCase->execute($id, $articleNotasDebitoDTO);

    return response()->json(
      (new ArticleNotesDebitoResource($result))->resolve(),
      200
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

  public function indexArticlesForSales(Request $request): array|JsonResponse
  {
    $description = $request->query("description");
    $articleId = $request->query("article_id");
    $branchId = $request->query("branch_id");

    $validatedData = $request->validate([
        'date' => 'date|required'
    ]);

    $articlesUseCase = new FindAllArticlesPriceConvertionUseCase($this->articleRepository);
    $articles = $articlesUseCase->execute($validatedData['date'], $description, $articleId, $branchId);
    
    if (empty($articles)) {
      $entryItemSerialUseCase = new FindBranchBySerial($this->entryItemSerialRepository);
      $branch = $entryItemSerialUseCase->execute($description);

      if (!$branch)
      {
        return response()->json([
          "message" => "La serie es incorrecta"
        ], 404);
      }else {
        return response()->json([
          "message" => "El artículo no se encuentra en esta sucursal",
          'branch_id' => $branch['branch_id'],
          "location" => $branch['name']
        ]);
      }
      
    }

    if ($articleId)
    {
      return response()->json((new ArticleForSalesResource($articles[0]))->resolve());
    }
    else{
      return ArticleForSalesResource::collection($articles)->resolve();
    }
    
  }
  
  public function storeNotesDebito(StoreArticleNotasDebito $request): JsonResponse
  {
    $articlesNotesDebitoDTO = new ArticleNotasDebitoDTO($request->validated());
    $articlesDebito = new CreateArticleNotasDebito($this->articleRepository);
    $article = $articlesDebito->execute($articlesNotesDebitoDTO);


    return response()->json(
      (new ArticleNotesDebitoResource($article))->resolve(),
      201
    );
  }


  public function requiredSerial(int $articleId): JsonResponse
  {
    $requiredSerial = new RequiredSerialUseCase($this->articleRepository);
    $result = $requiredSerial->execute($articleId);

    return response()->json([
        'message' => $result ? 'success' : 'El artículo no requiere serial'
    ], 200);
  }

  public function updateStatus(int $articleId, Request $request): JsonResponse
  {
    $validatedData = $request->validate([
        'status' => 'required|integer|in:0,1'
    ]);

    $status = $validatedData['status'];

    $updateStatus = new UpdateStatusArticleUseCase($this->articleRepository);
    $updateStatus->execute($articleId, $status);

    return response()->json(['message' => 'Estado actualizado correctamente'], 200);
  }
    
}
