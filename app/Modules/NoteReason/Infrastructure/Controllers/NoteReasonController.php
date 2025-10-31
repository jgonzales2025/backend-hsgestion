<?php

namespace App\Modules\NoteReason\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\NoteReason\Application\UseCases\FindAllNoteReasonsUseCase;
use App\Modules\NoteReason\Domain\Interfaces\NoteReasonRepositoryInterface;
use App\Modules\NoteReason\Infrastructure\Resources\NoteReasonResource;
use Illuminate\Http\Request;

class NoteReasonController extends Controller
{
    public function __construct(private readonly NoteReasonRepositoryInterface $noteReasonRepository){}

    public function index(Request $request): array
    {
        $documentTypeId = $request->input('document_type_id');
        $noteReasonUseCases = new FindAllNoteReasonsUseCase($this->noteReasonRepository);
        $noteReasons = $noteReasonUseCases->execute($documentTypeId);

        return NoteReasonResource::collection($noteReasons)->resolve();
    }
}
