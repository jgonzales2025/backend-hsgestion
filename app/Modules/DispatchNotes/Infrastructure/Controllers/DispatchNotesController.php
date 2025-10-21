<?php

namespace App\Modules\DispatchNotes\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Infrastructure\Persistence\EloquentArticleRepository;
use App\Modules\DispatchNotes\Application\UseCases\FindAllDispatchNotesUseCase;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Resource\DispatchNoteResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class DispatchNotesController extends Controller{
    public function __construct(private readonly DispatchNotesRepositoryInterface $dispatchNotesRepositoryInterface){}

    public function index():JsonResponse{
        $dispatchNoteUseCase =new FindAllDispatchNotesUseCase($this->dispatchNotesRepositoryInterface);
        $dispatchNote = $dispatchNoteUseCase->execute();

        return response()->json(
            DispatchNoteResource::collection($dispatchNote)->resolve(),200

        );
    }
    public function show(){

    }
    public function store(){

    }
}