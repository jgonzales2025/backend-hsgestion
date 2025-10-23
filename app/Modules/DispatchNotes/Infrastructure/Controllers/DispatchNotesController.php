<?php

namespace App\Modules\DispatchNotes\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Infrastructure\Persistence\EloquentArticleRepository;
use App\Modules\DispatchNotes\Application\UseCases\FindAllDispatchNotesUseCase;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Resource\DispatchNoteResource;
use Illuminate\Http\JsonResponse;
 
class DispatchNotesController extends Controller{
    public function __construct(private readonly DispatchNotesRepositoryInterface $dispatchNotesRepositoryInterface){}

  public function index(): array
{
    $dispatchNoteUseCase = new FindAllDispatchNotesUseCase($this->dispatchNotesRepositoryInterface);
    $dispatchNotes = $dispatchNoteUseCase->execute();
     \Log::info('info',$dispatchNotes);

  return DispatchNoteResource::collection($dispatchNotes)->resolve();
}
    public function show(){

    }
    public function store(){

    }
}