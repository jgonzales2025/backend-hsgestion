<?php

namespace App\Modules\DispatchNotes\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Infrastructure\Persistence\EloquentArticleRepository;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\DispatchNotes\application\DTOS\DispatchNoteDTO;
use App\Modules\DispatchNotes\application\UseCases\CreateDispatchNoteUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindAllDispatchNotesUseCase;
use App\Modules\DispatchNotes\Application\UseCases\FindByIdDispatchNoteUseCase;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Requests\RequestStore;
use App\Modules\DispatchNotes\Infrastructure\Resource\DispatchNoteResource;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Modules\Serie\Domain\Interfaces\SerieRepositoryInterface;
use App\Modules\Driver\Domain\Interfaces\DriverRepositoryInterface;
use App\Modules\TransportCompany\Domain\Interfaces\TransportCompanyRepositoryInterface;
use Illuminate\Http\JsonResponse;
 
class DispatchNotesController extends Controller{
    public function __construct(
  private readonly DispatchNotesRepositoryInterface $dispatchNoteRepository,
   private readonly CompanyRepositoryInterface $companyRepositoryInterface,
   private readonly BranchRepositoryInterface $branchRepository,
   private readonly SerieRepositoryInterface $serieRepositoryInterface,
   private readonly EmissionReasonRepositoryInterface $emissionReasonRepositoryInterface,
   private readonly TransportCompanyRepositoryInterface  $transportCompany,
    private readonly DocumentTypeRepositoryInterface  $documentTypeRepositoryInterface,
    private readonly DriverRepositoryInterface  $driverRepositoryInterface,
      ){}

  public function index(): array
{
    $dispatchNoteUseCase = new FindAllDispatchNotesUseCase($this->dispatchNoteRepository);
    $dispatchNotes = $dispatchNoteUseCase->execute();

  return DispatchNoteResource::collection($dispatchNotes)->resolve();
}
    public function store(RequestStore $store):JsonResponse{
          $dispatchNotesDTO = new DispatchNoteDTO($store->validated());
           $dispatchNoteUseCase = new CreateDispatchNoteUseCase($this->dispatchNoteRepository,$this->companyRepositoryInterface,$this->branchRepository,$this->serieRepositoryInterface,$this->emissionReasonRepositoryInterface,$this->transportCompany,$this->documentTypeRepositoryInterface,$this->driverRepositoryInterface);
        $dispatchNotes = $dispatchNoteUseCase->execute($dispatchNotesDTO); 

        return response()->json(
          (new DispatchNoteResource($dispatchNotes)),200
        );
    }
       public function show(int $id):JsonResponse{
           $dispatchNoteUseCase = new FindByIdDispatchNoteUseCase($this->dispatchNoteRepository);
        $dispatchNotes = $dispatchNoteUseCase->execute($id); 

        return response()->json(
          (new DispatchNoteResource($dispatchNotes)),200
        );
    }
}