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
use App\Modules\EntryItemSerial\Application\UseCases\FindBySerialUseCase;
use App\Modules\SubCategory\Domain\Interfaces\SubCategoryRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Modules\Articles\Application\UseCases\UpdateStatusArticleUseCase;
use App\Modules\EntryItemSerial\Domain\Interface\EntryItemSerialRepositoryInterface;
use App\Modules\VisibleArticles\application\UseCases\FindStatusByArticleId;
use App\Modules\VisibleArticles\Domain\Interfaces\VisibleArticleRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\DetailPcCompatible\Domain\Interface\DetailPcCompatibleRepositoryInterface;
use App\Modules\DetailPcCompatible\Infrastructure\Resource\DetailPcCompatibleResource;
use App\Modules\ReferenceCode\Domain\Interfaces\ReferenceCodeRepositoryInterface;
use App\Modules\ReferenceCode\Infrastructure\Resources\ReferenceCodeResource;

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
    private VisibleArticleRepositoryInterface $visibleArticleRepository,
    private BranchRepositoryInterface $branchRepository,
    private ReferenceCodeRepositoryInterface $referenceCodeRepository,
    private DetailPcCompatibleRepositoryInterface $detailPcCompatibleRepository
  ) {}
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
    $brand_id = $request->query("brand_id");
    $category_id = $request->query("category_id");
    $status = $request->query('status') !== null ? (int) $request->query('status') : null;

    $articleUseCase = new FindAllArticleUseCase($this->articleRepository);

    $articles = $articleUseCase->execute($name, $branchId, $brand_id, $category_id, $status);

    // Return paginated response
    return new JsonResponse([
      'data' => ArticleResource::collection($articles->items())->resolve(),
      'current_page' => $articles->currentPage(),
      'per_page' => $articles->perPage(),
      'total' => $articles->total(),
      'last_page' => $articles->lastPage(),
      'next_page_url' => $articles->nextPageUrl(),
      'prev_page_url' => $articles->previousPageUrl(),
      'first_page_url' => $articles->url(1),
      'last_page_url' => $articles->url($articles->lastPage()),
    ]);
  }
  public function show(int $id): JsonResponse
  {

    $articleUseCase = new FindByIdArticleUseCase($this->articleRepository);
    $article = $articleUseCase->execute($id);

    if (!$article) {
      return response()->json(["message" => "no se encontraron articulo"]);
    }
    $referenceCode = $this->referenceCodeRepository->findById($article->getId());
    $detailPcCompatible = $this->detailPcCompatibleRepository->findAllArticles($article->getId());

    if (!$referenceCode) {
      $referenceCode = [];
    }

    return response()->json(
      array_merge(
        (new ArticleResource($article))->resolve(),
        [
          'reference_code' => ReferenceCodeResource::collection($referenceCode)->resolve(),
          'detail_pc_compatible' => DetailPcCompatibleResource::collection($detailPcCompatible)->resolve(),
        ]
      ),
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

    // Check if the result is empty (when paginated, check if items are empty)
    if (is_object($articles) && method_exists($articles, 'isEmpty') && $articles->isEmpty()) {
      $entrySerialUseCase = new FindBySerialUseCase($this->entryItemSerialRepository);
      $entrySerial = $entrySerialUseCase->execute($description);

      if (!$entrySerial) {
        return response()->json([
          "message" => "La serie es incorrecta"
        ], 404);
      }

      $articleId = $entrySerial->getArticle()->getId();
      $serialBranchId = $entrySerial->getBranchId();

      $statusVisibleArticleUseCase = new FindStatusByArticleId($this->visibleArticleRepository);
      $statusVisibleArticle = $statusVisibleArticleUseCase->execute($articleId, $branchId);

      if ($statusVisibleArticle === null) {
        return response()->json(['message' => 'El artículo no ha sido asignado a una sucursal.'], 404);
      } else if ($statusVisibleArticle == 0) {
        return response()->json(['message' => 'El artículo no se encuentra habilitado en esta sucursal.'], 404);
      } else {
        $branch = $this->branchRepository->findById($serialBranchId);
        return response()->json([
          "message" => "El artículo no se encuentra en esta sucursal",
          'branch_id' => $branch->getId(),
          "location" => $branch->getName()
        ]);
      }
    }

    if ($articleId) {
      // When searching by specific article ID, return single item
      $firstItem = $articles->items()[0] ?? null;
      if ($firstItem) {
        return response()->json((new ArticleForSalesResource($firstItem))->resolve());
      }
      return response()->json(['message' => 'Artículo no encontrado'], 404);
    } else {
      // Return paginated response
      return new JsonResponse([
        'data' => ArticleForSalesResource::collection($articles->items())->resolve(),
        'current_page' => $articles->currentPage(),
        'per_page' => $articles->perPage(),
        'total' => $articles->total(),
        'last_page' => $articles->lastPage(),
        'next_page_url' => $articles->nextPageUrl(),
        'prev_page_url' => $articles->previousPageUrl(),
      ]);
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
  public function getIsCombo(Request $request): JsonResponse
  {
    $name = $request->query("name");

    $findbyidCombo = $this->articleRepository->findAllCombos($name);
    return response()->json(
      ArticleResource::collection($findbyidCombo)->resolve(),
      200
    );
  }
}
