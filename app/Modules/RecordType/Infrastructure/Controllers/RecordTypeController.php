<?php
namespace App\Modules\RecordType\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\RecordType\Application\DTOs\RecordTypeDTO;
use App\Modules\RecordType\Application\UseCases\CreateRecordTypeUseCase;
use App\Modules\RecordType\Application\UseCases\FindAllRecordTypesUserCases;
use App\Modules\RecordType\Application\UseCases\FindByIdRecordTypeUseCase;
use App\Modules\RecordType\Application\UseCases\UpdateRecordTypeUseCase;
use App\Modules\RecordType\Infrastructure\Persistence\EloquentRecordTypeRepository;
use App\Modules\RecordType\Infrastructure\Requests\StoreRecordTypeRequest;
use App\Modules\RecordType\Infrastructure\Requests\UpdateRecordTypeRquest;
use App\Modules\RecordType\Infrastructure\Resources\RecordTypeResource;
use Illuminate\Http\JsonResponse;

class RecordTypeController extends Controller {
    protected $recordTypeRepository;
    public function __construct(){
        $this->recordTypeRepository = new  EloquentRecordTypeRepository();
    }

    public function index():array
    {
        $recordTypeUseCase = new FindAllRecordTypesUserCases($this->recordTypeRepository);
        $recordTypes = $recordTypeUseCase->execute();

        return RecordTypeResource::collection($recordTypes)->resolve();

    }
    public function store(StoreRecordTypeRequest $request):JsonResponse
    {
        $recordTypeDTO = new RecordTypeDTO($request -> validated());
        $recordTypeUseCase = new CreateRecordTypeUseCase($this->recordTypeRepository);
        $recordType = $recordTypeUseCase->execute($recordTypeDTO);

        return response()->json(
            (new RecordTypeResource($recordType))->resolve(),201
        );
    }
    public function show(int $id): JsonResponse {
        $recordTypeUseCase = new FindByIdRecordTypeUseCase($this->recordTypeRepository);
        $recordType = $recordTypeUseCase->execute($id);

        return response()->json(
            (new RecordTypeResource($recordType))->resolve(),200
        );
        
    }
    public function update(UpdateRecordTypeRquest $request , int $id):JsonResponse{
        $recordTypeDTO = new RecordTypeDTO(array_merge(
            $request->validated(),
            ['id' => $id]
        ));
       $recordTypeUseCase = new UpdateRecordTypeUseCase($this->recordTypeRepository);
       $recordTypeUseCase->execute($id, $recordTypeDTO);
       
       $recordType = $this->recordTypeRepository->findById($id);

       return response()->json(
        (new RecordTypeResource($recordType))->resolve(),200
       );
    }
}